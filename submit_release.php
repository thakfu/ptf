<?php
include 'header.php';
/** @var mysqli $connection */

use App\Services\ContractService;
$contractService = new ContractService();

use App\Services\PlayerService;
$playerService = new PlayerService();

$timeFrame = $year . ' Week ' . $curWeek;
$playerID = filter_input(INPUT_POST, 'PlayerID', FILTER_VALIDATE_INT);
$teamID = filter_input(INPUT_POST, 'TeamID', FILTER_VALIDATE_INT);

$playerName = trim((string) ($_POST['Player'] ?? ''));
$position = trim((string) ($_POST['Pos'] ?? ''));

$abbreviation = $teamID ? IDtoAbbrev($teamID) : '';
$transactionType = '';

function validatePlayerAndTeam(?int $playerID, ?int $teamID): array {
    $errors = [];

    if (!$playerID || $playerID < 1) {
        $errors[] = 'A valid player is required.';
    }

    if (!$teamID || $teamID < 1) {
        $errors[] = 'A valid team is required.';
    }

    return $errors;
}

function validateDiscordHook(string $playerName, string $abbreviation, string $position): array {
    $hookErrors = [];

    if ($playerName === '') {
        $hookErrors[] = 'The player name is missing.';
    }

    if ($abbreviation === '') {
        $hookErrors[] = 'The team abbreviation is missing.';
    }

    if ($position === '') {
        $hookErrors[] = 'The player position is missing.';
    }

    return $hookErrors;
}

function compactPracticeSquad(
    mysqli $connection,
    int $teamID
): ?int {
    /*
     * Lock and retrieve all occupied slots in their current order.
     */
    $remainingStatement = $connection->prepare(
        'SELECT PlayerID, TeamSlot
         FROM ptf_players_squad
         WHERE squadTeam = ?
           AND PlayerID != 0
         ORDER BY TeamSlot
         FOR UPDATE'
    );

    $remainingStatement->bind_param('i', $teamID);
    $remainingStatement->execute();

    $remainingResult = $remainingStatement->get_result();

    $remainingPlayers = $remainingResult->fetch_all(
        MYSQLI_ASSOC
    );

    /*
     * Detect a player crossing from an unprotected slot
     * into the protected top five.
     */
    $newlyProtectedPlayerID = null;

    foreach ($remainingPlayers as $index => $squadPlayer) {
        $newSlot = $index + 1;
        $oldSlot = (int) $squadPlayer['TeamSlot'];

        if ($oldSlot >= 6 && $newSlot <= 5) {
            $newlyProtectedPlayerID =
                (int) $squadPlayer['PlayerID'];

            break;
        }
    }

    /*
     * Empty the team's fixed slot rows.
     */
    $clearStatement = $connection->prepare(
        'UPDATE ptf_players_squad
         SET PlayerID = 0
         WHERE squadTeam = ?'
    );

    $clearStatement->bind_param('i', $teamID);
    $clearStatement->execute();

    /*
     * Refill the occupied slots consecutively.
     */
    $slotStatement = $connection->prepare(
        'UPDATE ptf_players_squad
         SET PlayerID = ?
         WHERE squadTeam = ?
           AND TeamSlot = ?'
    );

    foreach ($remainingPlayers as $index => $squadPlayer) {
        $newSlot = $index + 1;
        $squadPlayerID = (int) $squadPlayer['PlayerID'];

        $slotStatement->bind_param(
            'iii',
            $squadPlayerID,
            $teamID,
            $newSlot
        );

        $slotStatement->execute();

        if ($slotStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The Practice Squad could not be reordered.'
            );
        }
    }

    return $newlyProtectedPlayerID;
}
//------------------------------------------------------------ SIGN PLAYER ------------------------------------------------------------------------
if (isset($_POST['sign'])) {
    $transactionType = 'sign';

    $errors = array_merge(
        validatePlayerAndTeam($playerID, $teamID),
        validateDiscordHook($playerName, $abbreviation, $position)
    );

    if ($errors === []) {
        $transactionStarted = false;
        try {
            $connection->begin_transaction();
            $transactionStarted = true;

            $contractService->createMinimumContract($playerID, $teamID, $year);

            $rosterStatement = $connection->prepare(
                'UPDATE ptf_players
                 SET TeamID = ?, Team = ?
                 WHERE PlayerID = ?
                   AND TeamID = 0'
            );

            $rosterStatement->bind_param('isi', $teamID, $abbreviation, $playerID);

            $rosterStatement->execute();

            if ($rosterStatement->affected_rows !== 1) {
                throw new RuntimeException(
                    'The player could not be added to the roster.'
                );
            }

            $logStatement = $connection->prepare(
                'INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) 
                VALUES (?, 0, ?, ?, NOW(), ?)'
            );

            $logStatement->bind_param('iiss', $playerID, $teamID, $transactionType, $timeFrame);

            $logStatement->execute();
            if ($logStatement->affected_rows !== 1) {
                throw new RuntimeException(
                    'The transaction could not be recorded.'
                );
            }

            $connection->commit();

            echo htmlspecialchars(
                $playerName,
                ENT_QUOTES,
                'UTF-8'
            ) . ' has been signed and should now appear on your roster. '
                . 'Go on, give him a hug!';
        } catch (Throwable $exception) {
            $connection->rollback();

            error_log(
                'Free-agent signing failed: ' . $exception->getMessage()
            );

            echo 'The signing could not be completed. No changes were saved.';

            return;
        }

        try {
            transactionHook($playerName, $teamID, $position, $transactionType);
        } catch (Throwable $exception) {
            error_log(
                'Discord transaction hook failed: '
                . $exception->getMessage()
            );
        }
    } else {
        echo implode('<br>', array_map(
            static fn (string $error): string =>
                htmlspecialchars($error, ENT_QUOTES, 'UTF-8'),
            $errors
        ));
    }
//------------------------------------------------------------ RELEASE PLAYER ------------------------------------------------------------------------
} elseif (isset($_POST['release'])) {
    $transactionType = 'cut';

    $errors = array_merge(
        validatePlayerAndTeam($playerID, $teamID),
        validateDiscordHook($playerName, $abbreviation, $position)
    );

    if ($errors === []) {
        $transactionStarted = false;
        $hasDeadCap = false;
        $newlyProtectedPlayerID = null;

        try {
            $connection->begin_transaction();
            $transactionStarted = true;

            $salaryStatement = $connection->prepare(
                'SELECT
                    `' . $year . '`,
                    `' . ($year + 1) . '`,
                    `' . ($year + 2) . '`,
                    `' . ($year + 3) . '`,
                    `' . ($year + 4) . '`,
                    `' . ($year + 5) . '`
                 FROM ptf_players_salaries
                 WHERE PlayerID = ?'
            );

            $salaryStatement->bind_param('i', $playerID);
            $salaryStatement->execute();

            $salaryResult = $salaryStatement->get_result();
            $salary = $salaryResult->fetch_assoc();

            if ($salary === null) {
                throw new RuntimeException(
                    'No salary record was found for this player.'
                );
            }

            $salaryYears = [
                (int) $salary[$year],
                (int) $salary[$year + 1],
                (int) $salary[$year + 2],
                (int) $salary[$year + 3],
                (int) $salary[$year + 4],
                (int) $salary[$year + 5],
            ];

            $hasDeadCap = (
                $salaryYears[0] > 250000
                || $salaryYears[1] > 250000
                || $salaryYears[2] > 250000
            );

            if ($hasDeadCap) {
                $capStatement = $connection->prepare(
                    'UPDATE ptf_teams_data
                     SET
                        caphit = caphit + ?,
                        caphit2 = caphit2 + ?,
                        caphit3 = caphit3 + ?,
                        caphit4 = caphit4 + ?,
                        caphit5 = caphit5 + ?,
                        caphit6 = caphit6 + ?
                     WHERE TeamID = ?'
                );

                $capStatement->bind_param(
                    'iiiiiii',
                    $salaryYears[0],
                    $salaryYears[1],
                    $salaryYears[2],
                    $salaryYears[3],
                    $salaryYears[4],
                    $salaryYears[5],
                    $teamID
                );

                $capStatement->execute();

                if ($capStatement->affected_rows !== 1) {
                    throw new RuntimeException(
                        'The team dead-cap totals could not be updated.'
                    );
                }
            }

            $contractService->releaseActiveContract($playerID, $teamID);

            $rosterStatement = $connection->prepare(
                "UPDATE ptf_players
                 SET TeamID = 0, Team = 'N/A'
                 WHERE PlayerID = ?
                   AND TeamID = ?"
            );

            $rosterStatement->bind_param(
                'ii',
                $playerID,
                $teamID
            );

            $rosterStatement->execute();

            if ($rosterStatement->affected_rows !== 1) {
                throw new RuntimeException(
                    'The player could not be removed from the roster.'
                );
            }

            $squadStatement = $connection->prepare(
                'UPDATE ptf_players_squad
                SET PlayerID = 0
                WHERE PlayerID = ?
                AND squadTeam = ?'
            );

            $squadStatement->bind_param('ii', $playerID, $teamID);
            $squadStatement->execute();

            $releasedFromPracticeSquad =
                $squadStatement->affected_rows === 1;

            if ($releasedFromPracticeSquad) {
                $newlyProtectedPlayerID = compactPracticeSquad(
                    $connection,
                    $teamID
                );
            }

            $irStatement = $connection->prepare(
                'DELETE FROM ptf_players_ir
                    WHERE PlayerID = ?
                    AND squadTeam = ?'
            );

            $irStatement->bind_param('ii', $playerID, $teamID);
            $irStatement->execute();

            $logStatement = $connection->prepare(
                'INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) 
                    VALUES (?, ?, 0, ?, NOW(), ?)');

            $logStatement->bind_param('iiss', $playerID, $teamID, $transactionType, $timeFrame);

            $logStatement->execute();
            if ($logStatement->affected_rows !== 1) {
                throw new RuntimeException(
                    'The transaction could not be recorded.'
                );
    }

            $connection->commit();
            $transactionStarted = false;
        } catch (Throwable $exception) {
            if ($transactionStarted) {
                $connection->rollback();
            }

            error_log(
                'Player release failed: '
                . $exception->getMessage()
            );

            echo 'The player could not be released. '
                . 'No changes were saved.';

            return;
        }

        try {
            transactionHook(
                $playerName,
                $teamID,
                $position,
                'release'
            );

            if ($newlyProtectedPlayerID !== null) {
                $protectedPlayer = $playerService->getByID(
                    $newlyProtectedPlayerID
                );

                transactionHook(
                    $protectedPlayer->FullName,
                    $teamID,
                    $protectedPlayer->Position,
                    'protect'
                );
            }
        } catch (Throwable $exception) {
            error_log(
                'Discord release hook failed: '
                . $exception->getMessage()
            );
        }

        echo htmlspecialchars(
            $playerName,
            ENT_QUOTES,
            'UTF-8'
        ) . ' has been released and should now appear in the '
            . 'free agency pool. I hope you’re happy.';

        if ($hasDeadCap) {
            echo ' WARNING! This player’s salary is above the '
                . 'league minimum, so his salary will still count '
                . 'against your cap.';
        }
    } else {
        echo implode(
            '<br>',
            array_map(
                static fn (string $error): string =>
                    htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        'UTF-8'
                    ),
                $errors
            )
        );
    }
//------------------------------------------------------------ DEMOTE PLAYER ------------------------------------------------------------------------
} elseif (isset($_POST['demote'])) {
    $transactionType = 'squad';

    try {
        $connection->begin_transaction();

        $slotStatement = $connection->prepare(
            'SELECT TeamSlot
             FROM ptf_players_squad
             WHERE squadTeam = ?
               AND PlayerID = 0
             ORDER BY TeamSlot
             LIMIT 1
             FOR UPDATE'
        );

        $slotStatement->bind_param('i', $teamID);
        $slotStatement->execute();

        $slotResult = $slotStatement->get_result();
        $slot = $slotResult->fetch_assoc();

        if ($slot === null) {
            throw new RuntimeException(
                'Your Practice Squad is already full!'
            );
        }

        $teamSlot = (int) $slot['TeamSlot'];

        $demoteStatement = $connection->prepare(
            'UPDATE ptf_players_squad
             SET PlayerID = ?
             WHERE squadTeam = ?
               AND TeamSlot = ?
               AND PlayerID = 0'
        );

        $demoteStatement->bind_param('iii', $playerID, $teamID, $teamSlot);

        $demoteStatement->execute();

        if ($demoteStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The player could not be added to the Practice Squad.'
            );
        }

        $logStatement = $connection->prepare(
            'INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) 
                VALUES (?, ?, ?, ?, NOW(), ?)'
        );

        $logStatement->bind_param('iiiss', $playerID, $teamID, $teamID, $transactionType, $timeFrame);

        $logStatement->execute();

        if ($logStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The transaction could not be recorded.'
            );
        }

        $connection->commit();

        echo htmlspecialchars(
            $playerName, ENT_QUOTES, 'UTF-8')
            . ' has been demoted to your Practice Squad. '
            . 'He\'s gonna prove you wrong!';

        if ($teamSlot >= 6) {
            $unprotect = 'demote6';
            echo '<br><br><b>IMPORTANT: '
                . 'This player can be signed by other teams!</b>';
        } else {
            $unprotect = 'demote';

        }

        try {
            transactionHook($playerName, $teamID, $position, $unprotect);
        } catch (Throwable $exception) {
            error_log(
                'Discord transaction hook failed: '
                . $exception->getMessage()
            );
        }

    } catch (Throwable $exception) {
        $connection->rollback();
        echo $exception->getMessage();
    }
//------------------------------------------------------------ PROMOTE PLAYER ------------------------------------------------------------------------
} elseif (isset($_POST['promote'])) {
    $transactionType = 'promote';
    $activeRosterLimit = 53;

    try {
        $connection->begin_transaction();

        /*
         * Confirm that this player occupies a Practice Squad slot
         * belonging to this team.
         */
        $squadStatement = $connection->prepare(
            'SELECT TeamSlot
             FROM ptf_players_squad
             WHERE PlayerID = ?
               AND squadTeam = ?
             LIMIT 1
             FOR UPDATE'
        );

        $squadStatement->bind_param('ii', $playerID, $teamID);

        $squadStatement->execute();

        $squadResult = $squadStatement->get_result();
        $squadPlayer = $squadResult->fetch_assoc();

        if ($squadPlayer === null) {
            throw new RuntimeException(
                'This player is not on your Practice Squad.'
            );
        }

         /*
         * Count everyone assigned to the team, then subtract players
         * currently occupying Practice Squad and IR slots.
         */
        $rosterStatement = $connection->prepare(
            'SELECT
                (
                    SELECT COUNT(*)
                    FROM ptf_players
                    WHERE TeamID = ?
                ) -
                (
                    SELECT COUNT(*)
                    FROM ptf_players_squad
                    WHERE squadTeam = ?
                      AND PlayerID != 0
                ) -
                (
                    SELECT COUNT(*)
                    FROM ptf_players_ir
                    WHERE squadTeam = ?
                      AND PlayerID != 0
                ) AS ActiveCount'
        );

        $rosterStatement->bind_param('iii', $teamID, $teamID, $teamID);

        $rosterStatement->execute();

        $rosterResult = $rosterStatement->get_result();
        $rosterCount = $rosterResult->fetch_assoc();

        $activeCount = (int) $rosterCount['ActiveCount'];

        if ($activeCount >= $activeRosterLimit) {
            throw new RuntimeException(
                'Your active roster is already full!'
            );
        }

        $promoteStatement = $connection->prepare(
            'UPDATE ptf_players_squad
             SET PlayerID = 0
             WHERE PlayerID = ?
               AND squadTeam = ?'
        );

        $promoteStatement->bind_param(
            'ii', $playerID, $teamID);

        $promoteStatement->execute();

        if ($promoteStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The player could not be promoted.'
            );
        }

        $newlyProtectedPlayerID = compactPracticeSquad(
            $connection,
            $teamID
        );

        $logStatement = $connection->prepare(
            'INSERT INTO ptf_transactions (
                PlayerID,
                TeamID_Old,
                TeamID_New,
                type,
                date,
                TimeFrame
            ) VALUES (?, ?, ?, ?, NOW(), ?)'
        );

        $logStatement->bind_param('iiiss', $playerID, $teamID, $teamID, $transactionType, $timeFrame);

        $logStatement->execute();

        if ($logStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The transaction could not be recorded.'
            );
        }

        $connection->commit();

        echo htmlspecialchars(
            $playerName, ENT_QUOTES, 'UTF-8')
            . ' has been promoted from your Practice Squad. '
            . 'Let\'s go!';

        try {
            transactionHook($playerName, $teamID, $position, 'promote');

            if ($newlyProtectedPlayerID !== null) {
                /*
                * Look up the newly protected player's name here,
                * then send the corresponding Discord hook.
                */
                $proPlayer = $playerService->getByID($newlyProtectedPlayerID);
                $proPlayerName = $proPlayer->FullName;

                transactionHook($proPlayerName, $teamID, $proPlayer->Position, 'protect');
            }

        } catch (Throwable $exception) {
            error_log(
                'Discord transaction hook failed: '
                . $exception->getMessage()
            );
        }
    } catch (Throwable $exception) {
        $connection->rollback();
        echo $exception->getMessage();
    }
//------------------------------------------------------------ IR PLAYER ------------------------------------------------------------------------
} elseif (isset($_POST['IR'])) {
    $transactionType = 'ir';

    try {
        $connection->begin_transaction();

        /*
         * Confirm the player belongs to this team and has an
         * IR-eligible injury.
         */
        $playerStatement = $connection->prepare(
            'SELECT InjuryLength
             FROM ptf_players
             WHERE PlayerID = ?
               AND TeamID = ?
             LIMIT 1
             FOR UPDATE'
        );

        $playerStatement->bind_param(
            'ii',
            $playerID,
            $teamID
        );

        $playerStatement->execute();

        $playerResult = $playerStatement->get_result();
        $injuredPlayer = $playerResult->fetch_assoc();

        if ($injuredPlayer === null) {
            throw new RuntimeException(
                'This player is not on your team.'
            );
        }

        $injuryLength = (string) $injuredPlayer['InjuryLength'];

        if (stripos($injuryLength, 'Out') === false) {
            throw new RuntimeException(
                'This player is not eligible to be placed '
                . 'on Injured Reserve!'
            );
        }

        /*
         * Prevent duplicate IR entries.
         */
        $existingStatement = $connection->prepare(
            'SELECT PlayerID
             FROM ptf_players_ir
             WHERE PlayerID = ?
             LIMIT 1'
        );

        $existingStatement->bind_param('i', $playerID);
        $existingStatement->execute();

        if ($existingStatement->get_result()->fetch_assoc() !== null) {
            throw new RuntimeException(
                'This player is already on Injured Reserve.'
            );
        }

        $irStatement = $connection->prepare(
            'INSERT INTO ptf_players_ir (
                PlayerID,
                squadTeam,
                start
            ) VALUES (?, ?, ?)'
        );

        $irStatement->bind_param(
            'iii',
            $playerID,
            $teamID,
            $curWeek
        );

        $irStatement->execute();

        if ($irStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The player could not be placed on Injured Reserve.'
            );
        }

        $logStatement = $connection->prepare(
            'INSERT INTO ptf_transactions (
                PlayerID,
                TeamID_Old,
                TeamID_New,
                type,
                date,
                TimeFrame
            ) VALUES (?, ?, ?, ?, NOW(), ?)'
        );

        $logStatement->bind_param(
            'iiiss',
            $playerID,
            $teamID,
            $teamID,
            $transactionType,
            $timeFrame
        );

        $logStatement->execute();

        if ($logStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The transaction could not be recorded.'
            );
        }

        $connection->commit();

        echo htmlspecialchars(
            $playerName, ENT_QUOTES, 'UTF-8')
            . ' has been placed on Injured Reserve. '
            . 'He must remain there for at least 3 weeks.';

        try {
            transactionHook(
                $playerName,
                $teamID,
                $position,
                'IR'
            );
        } catch (Throwable $exception) {
            error_log(
                'Discord transaction hook failed: '
                . $exception->getMessage()
            );
        }
    } catch (Throwable $exception) {
        $connection->rollback();
        echo $exception->getMessage();
    }
//------------------------------------------------------------ ACTIVATE PLAYER ------------------------------------------------------------------------
} elseif (isset($_POST['activate'])) {
    $transactionType = 'activate';
    $minimumIRWeeks = 3;
    $activeRosterLimit = 53;

    try {
        $connection->begin_transaction();

        $irStatement = $connection->prepare(
            'SELECT start
             FROM ptf_players_ir
             WHERE PlayerID = ?
               AND squadTeam = ?
             LIMIT 1
             FOR UPDATE'
        );

        $irStatement->bind_param(
            'ii',
            $playerID,
            $teamID
        );

        $irStatement->execute();

        $irResult = $irStatement->get_result();
        $irPlayer = $irResult->fetch_assoc();

        if ($irPlayer === null) {
            throw new RuntimeException(
                'This player is not on your Injured Reserve.'
            );
        }

        $startWeek = (int) $irPlayer['start'];
        $weeksServed = $curWeek - $startWeek;

        if ($weeksServed < $minimumIRWeeks) {
            $weeksRemaining = $minimumIRWeeks - $weeksServed;

            throw new RuntimeException(
                'This player cannot be activated yet! He has '
                . $weeksRemaining
                . ($weeksRemaining === 1 ? ' week' : ' weeks')
                . ' remaining on IR.'
            );
        }

        /*
         * The player is still on IR while this count is taken.
         * Activating him will add one active player.
         */
        $rosterStatement = $connection->prepare(
            'SELECT
                (
                    SELECT COUNT(*)
                    FROM ptf_players
                    WHERE TeamID = ?
                ) -
                (
                    SELECT COUNT(*)
                    FROM ptf_players_squad
                    WHERE squadTeam = ?
                      AND PlayerID != 0
                ) -
                (
                    SELECT COUNT(*)
                    FROM ptf_players_ir
                    WHERE squadTeam = ?
                      AND PlayerID != 0
                ) AS ActiveCount'
        );

        $rosterStatement->bind_param(
            'iii',
            $teamID,
            $teamID,
            $teamID
        );

        $rosterStatement->execute();

        $rosterResult = $rosterStatement->get_result();
        $rosterCount = $rosterResult->fetch_assoc();

        $activeCount = (int) $rosterCount['ActiveCount'];

        if ($activeCount >= $activeRosterLimit) {
            throw new RuntimeException(
                'Your active roster is already full!'
            );
        }

        $activateStatement = $connection->prepare(
            'DELETE FROM ptf_players_ir
             WHERE PlayerID = ?
               AND squadTeam = ?'
        );

        $activateStatement->bind_param(
            'ii',
            $playerID,
            $teamID
        );

        $activateStatement->execute();

        if ($activateStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The player could not be activated.'
            );
        }

        $logStatement = $connection->prepare(
            'INSERT INTO ptf_transactions (
                PlayerID,
                TeamID_Old,
                TeamID_New,
                type,
                date,
                TimeFrame
            ) VALUES (?, ?, ?, ?, NOW(), ?)'
        );

        $logStatement->bind_param(
            'iiiss',
            $playerID,
            $teamID,
            $teamID,
            $transactionType,
            $timeFrame
        );

        $logStatement->execute();

        if ($logStatement->affected_rows !== 1) {
            throw new RuntimeException(
                'The transaction could not be recorded.'
            );
        }

        $connection->commit();

        echo htmlspecialchars(
            $playerName, ENT_QUOTES, 'UTF-8')
            . ' has been activated from Injured Reserve. '
            . 'Let\'s go!';

        try {
            transactionHook(
                $playerName,
                $teamID,
                $position,
                'activate'
            );
        } catch (Throwable $exception) {
            error_log(
                'Discord transaction hook failed: '
                . $exception->getMessage()
            );
        }
    } catch (Throwable $exception) {
        $connection->rollback();
        echo $exception->getMessage();
    }
//------------------------------------------------------------ CHANGE POSITION ------------------------------------------------------------------------
} elseif (isset($_POST['change'])) {
    $transactionType = 'change';
    $transactionStarted = false;

    try {
        $newPosition = strtoupper(
            trim((string) ($_POST['pos'] ?? ''))
        );

        $positionCheckStatement = $connection->prepare(
            'SELECT 1
            FROM ptf_pos_sort
            WHERE Position = ?
            LIMIT 1'
        );

        $positionCheckStatement->bind_param(
            's',
            $newPosition
        );

        $positionCheckStatement->execute();

        $positionCheckResult =
            $positionCheckStatement->get_result();

        if ($positionCheckResult->fetch_row() === null) {
            throw new RuntimeException(
                'The requested position is not valid.'
            );
        }

            $connection->begin_transaction();
            $transactionStarted = true;

            /*
            * Retrieve and lock the player's current position.
            */
            $playerStatement = $connection->prepare(
                'SELECT Position
                FROM ptf_players
                WHERE PlayerID = ?
                AND TeamID = ?
                LIMIT 1
                FOR UPDATE'
            );

            $playerStatement->bind_param(
                'ii',
                $playerID,
                $teamID
            );

            $playerStatement->execute();

            $playerResult = $playerStatement->get_result();
            $playerRecord = $playerResult->fetch_assoc();

            if ($playerRecord === null) {
                throw new RuntimeException(
                    'This player is not on your team.'
                );
            }

            $oldPosition = strtoupper(
                trim((string) $playerRecord['Position'])
            );

            if ($oldPosition === $newPosition) {
                throw new RuntimeException(
                    $playerName
                    . ' is already a '
                    . $newPosition
                    . '.'
                );
            }

            /*
            * Confirm that the player's extra-data record exists and
            * lock it while preserving the original position.
            */
            $extraStatement = $connection->prepare(
                'SELECT OriginalPosition
                FROM ptf_players_extra
                WHERE PlayerID = ?
                LIMIT 1
                FOR UPDATE'
            );

            $extraStatement->bind_param('i', $playerID);
            $extraStatement->execute();

            $extraResult = $extraStatement->get_result();
            $extraRecord = $extraResult->fetch_assoc();

            if ($extraRecord === null) {
                throw new RuntimeException(
                    'The player does not have an extra-data record.'
                );
            }

            $originalPosition = trim(
                (string) ($extraRecord['OriginalPosition'] ?? '')
            );

            /*
            * Only establish the original position once.
            */
            if ($originalPosition === '') {
                $originalStatement = $connection->prepare(
                    'UPDATE ptf_players_extra
                    SET OriginalPosition = ?
                    WHERE PlayerID = ?'
                );

                $originalStatement->bind_param(
                    'si',
                    $oldPosition,
                    $playerID
                );

                $originalStatement->execute();

                if ($originalStatement->affected_rows !== 1) {
                    throw new RuntimeException(
                        'The player\'s original position could not be saved.'
                    );
                }
            }

            /*
            * Update the position used by the sim.
            */
            $positionStatement = $connection->prepare(
                'UPDATE ptf_players
                SET Position = ?
                WHERE PlayerID = ?
                AND TeamID = ?'
            );

            $positionStatement->bind_param(
                'sii',
                $newPosition,
                $playerID,
                $teamID
            );

            $positionStatement->execute();

            if ($positionStatement->affected_rows !== 1) {
                throw new RuntimeException(
                    'The player\'s position could not be changed.'
                );
            }

            /*
            * Record both sides of the position change so the
            * historical log never depends on the current position.
            */
            $logStatement = $connection->prepare(
                'INSERT INTO ptf_transactions (
                    PlayerID,
                    TeamID_Old,
                    TeamID_New,
                    type,
                    date,
                    TimeFrame,
                    Position_Old,
                    Position_New
                ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)'
            );

            $logStatement->bind_param(
                'iiissss',
                $playerID,
                $teamID,
                $teamID,
                $transactionType,
                $timeFrame,
                $oldPosition,
                $newPosition
            );

            $logStatement->execute();

            if ($logStatement->affected_rows !== 1) {
                throw new RuntimeException(
                    'The transaction could not be recorded.'
                );
            }

            $connection->commit();
            $transactionStarted = false;

            echo htmlspecialchars(
                $playerName, ENT_QUOTES, 'UTF-8')
                . ' has changed his position from '
                . $oldPosition
                . ' to '
                . $newPosition
                . '. Get that man a new playbook!';

            try {
                transactionHook(
                    $playerName,
                    $teamID,
                    $newPosition,
                    'change'
                );
            } catch (Throwable $exception) {
                error_log(
                    'Discord transaction hook failed: '
                    . $exception->getMessage()
                );
            }
        } catch (Throwable $exception) {
            if ($transactionStarted) {
                $connection->rollback();
            }

            error_log(
                'Position change failed: '
                . $exception->getMessage()
            );

            echo htmlspecialchars(
                $exception->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            );
        }
    } 

function transactionHook($player, $team, $pos, $type) {
    //global $connection;
    $teamService = teamService($team);
    $teamname = $teamService[0];

    if ($type == 'sign') {
        $message = 'The ' . $teamname['FullName'] . ' have signed free agent ' . $pos . ' - ' . $player . ' to a 1 year contract for the league minimum.';
    } elseif ($type == 'release') {
        $message = 'The ' . $teamname['FullName'] . ' have released ' . $pos . ' - ' . $player . ' to the free agency pool.';
    } elseif ($type == 'change') {
        $message = 'The ' . $teamname['FullName'] . ' have changed the position of ' . $player . ' to ' . $pos . '!';
    } elseif ($type == 'promote') {
        $message = 'The ' . $teamname['FullName'] . ' have promoted ' . $pos . ' - ' . $player . ' from the practice squad to the main roster!';
    } elseif ($type == 'demote') {
        $message = 'The ' . $teamname['FullName'] . ' have demoted ' . $pos . ' - ' . $player . ' to the practice squad!';
    } elseif ($type == 'demote6') {
        $message = 'The ' . $teamname['FullName'] . ' have demoted ' . $pos . ' - ' . $player . ' to the practice squad! He is eligible to be signed by another team!';
    } elseif ($type == 'protect') {
        $message = 'As a result: ' . $pos . ' - ' . $player . ' has moved into a PROTECTED practice squad slot and is NO LONGER eligible to be signed by another team!';
    } elseif ($type == 'IR') {
        $message = 'The ' . $teamname['FullName'] . ' have placed ' . $pos . ' - ' . $player . ' on Injured Reserve!';
    } elseif ($type == 'activate') {
        $message = 'The ' . $teamname['FullName'] . ' have activated ' . $pos . ' - '. $player . ' from Injured Reserve!';
    }
    require_once 'includes/secrets.php';

    $url = getSecret('discord_transactions_webhook');

    if (!$url) {
            throw new RuntimeException('Missing Discord webhook: discord_transactions_webhook');
    }

    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'League Offices', 'content' => $message ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}

                                                                                            
?>