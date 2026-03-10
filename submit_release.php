<?php

include 'header.php';

$time = "'1991 Week " . $curWeek . "'";

if (isset($_POST['demote'])) {
    $demoteCheck = $connection->query("SELECT count(squadTeam) as 'check' FROM `ptf_players_squad` where PlayerID != 0 and squadTeam = {$_POST['TeamID']}");
    $check = $demoteCheck->fetch_assoc();

    if ($check['check'] >= 8) {
        echo 'Your Practice Squad is already full!';
    } else {
        $slot = $check['check'] + 1;
        echo $_POST['Player'] . ' has been demoted to your Practice Squad.  He\'s gonna prove you wrong!';
        $roster = $connection->query("UPDATE ptf_players_squad SET PlayerID = {$_POST['PlayerID']} WHERE squadTeam = {$_POST['TeamID']} and TeamSlot = {$slot}");
        //$log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'squad', NOW(), $time)");
        if ($slot >= 6) { 
            echo '<br><br><b>IMPORTANT: This player can be signed by other teams!</b>';
        }
    }
    //transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'demote');
} elseif (isset($_POST['promote'])) {
    $promoteCheck1 = $connection->query("SELECT count(squadTeam) as 'check' FROM `ptf_players_squad` where squadTeam = {$_POST['TeamID']}");
    $check1 = $promoteCheck1->fetch_assoc();
    $promoteCheck2 = $connection->query("SELECT count(squadTeam) as 'check' FROM `ptf_players_ir` where squadTeam = {$_POST['TeamID']}");
    $check2 = $promoteCheck2->fetch_assoc();
    $promoteCheck3 = $connection->query("SELECT count(TeamID) as 'check' FROM `ptf_players` where TeamID = {$_POST['TeamID']}");
    $check3 = $promoteCheck3->fetch_assoc();
    $count = $check3['check'] - $check2['check'] - $check1['check'];


    echo $_POST['Player'] . ' has been promoted from your Practice Squad.  Let\'s go!';
    $squad = $connection->query("UPDATE ptf_players_squad SET PlayerID = 0 WHERE PlayerID = " . $_POST['PlayerID']);
    //$log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'promote', NOW(), $time)");
    //transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'promote');
} elseif (isset($_POST['IR'])) {
    $irCheck = $connection->query("SELECT InjuryLength as 'check' FROM `ptf_players` where PlayerID = {$_POST['PlayerID']}");
    $check = $irCheck->fetch_assoc();

    if (!str_contains($check['check'],"Out")) {
        echo 'This player is not eligible to be placed on Injured Reserve!';
    } else {
        echo $_POST['Player'] . ' has been placed on Injured Reserve.  He must remain there for atleast 3 weeks.';
        $roster = $connection->query("INSERT INTO ptf_players_ir (PlayerID, squadTeam, start) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$curWeek})");
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'ir', NOW(), $time)");
    } 
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'IR');
} elseif (isset($_POST['activate'])) {
    $irCheck = $connection->query("SELECT start as 'check' FROM `ptf_players_ir` where PlayerID = {$_POST['PlayerID']}");
    $check = $irCheck->fetch_assoc();
    $left = $curWeek - $check['check'];
    if ($left < 3) {
        echo 'This player cannot be activated yet!  He has ' . 3 - $left  . ' weeks remaining on IR.';
    } else {
        echo $_POST['Player'] . ' has been activated from your Injured Reserve.  Let\'s go!';
        $squad = $connection->query("DELETE FROM ptf_players_ir WHERE PlayerID = " . $_POST['PlayerID']);
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'activate', NOW(), $time)");
    }
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'activate');
}elseif (isset($_POST['release'])) {
    echo $_POST['Player'] . ' has been released and should now appear in the free agency pool.  I hope your happy.';

    $salcheck = $connection->query("SELECT `" . $year . "`, `" . $year + 1 . "`, `" . $year + 2 . "`, `" . $year + 3 . "`, `" . $year + 4 . "`, `" . $year + 5 . "` FROM ptf_players_salaries WHERE PlayerId = " . $_POST['PlayerID']);
    $check = $salcheck->fetch_assoc();

    if ($check[$year] > 300000 || $check[$year + 1] > 300000 || $check[$year + 2] > 300000) {
        echo ' WARNING!   This player\'s salary is above the league minimum, therefore, his salary will STILL count against your cap! '; 
        $sal = $connection->query("UPDATE ptf_teams_data SET 
            caphit = caphit + " . $check[$year] . ", 
            caphit2 = caphit2 + " . $check[$year + 1] . ", 
            caphit3 = caphit3 + " . $check[$year + 2] . ", 
            caphit4 = caphit4 + " . $check[$year + 3] . ", 
            caphit5 = caphit5 + " . $check[$year + 4] . ", 
            caphit6 = caphit6 + " . $check[$year + 5] . "
            WHERE TeamID = " . $_POST['TeamID']);
    }

    $roster = $connection->query("UPDATE ptf_players SET TeamID = '0', Team = 'N/A' WHERE PlayerID = " . $_POST['PlayerID']);

    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']}, 0, 'cut', NOW(), $time)");

    $squad = $connection->query("DELETE FROM ptf_players_squad WHERE PlayerID = " . $_POST['PlayerID']);

    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'release');
} elseif (isset($_POST['change'])) {
    echo $_POST['Player'] . ' has changed his position to ' . $_POST['pos'] . '.  Get that man a new playbook!';

    $roster = $connection->query("UPDATE ptf_players SET Position = '{$_POST['pos']}' WHERE PlayerID = " . $_POST['PlayerID']);

    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'change', NOW(), {$time})");
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['pos'], 'change');
} elseif (isset($_POST['sign'])) {
    echo $_POST['Player'] . ' has been signed and should now appear on your roster.  Go on, give him a hug!';

    $roster = $connection->query("UPDATE ptf_players SET TeamID = '{$_POST['TeamID']}', Team = '{$_POST['Abbreviation']}' WHERE PlayerID = " . $_POST['PlayerID']);
    $roster = $connection->query("UPDATE ptf_players_salaries SET `" . $year . "` = '250000' WHERE PlayerID = " . $_POST['PlayerID']);

    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'sign', NOW(), {$time})");
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'sign');
} elseif (isset($_POST['revoke'])) {
    echo $_POST['Player'] . '\'s offer has been revoked.  Try again... or don\'t.  I don\'t care.';

    $squad = $connection->query("DELETE FROM ptf_fa_offers WHERE PlayerID = " . $_POST['PlayerID'] . " AND TeamID = " . $_POST['TeamID']);
} elseif (isset($_POST['extend'])) {

    $playerService = playerService(0,$_POST['PlayerID'],0);
    $player = $playerService[0];

    //echo '<pre>'; var_dump($_POST);    
    if (($_POST['year1'] && $_POST['year1'] < $_POST['ogyo']) 
        || ($_POST['year2'] && $_POST['year2'] < $_POST['ogyo']) 
        || ($_POST['year3'] && $_POST['year3'] < $_POST['ogyo']) 
        || ($_POST['year4'] && $_POST['year4'] < $_POST['ogyo']) 
        || ($_POST['year5'] && $_POST['year5'] < $_POST['ogyo']) 
        || ($_POST['year6'] && $_POST['year6'] < $_POST['ogyo'])) {
        echo $player['FullName'] . ' considers your offer an insult!  There will be no further extension negotiations and he will go to free agency in the offseason!  He will also be angry should you try to franchise tag him!';
        $strikes = $connection->query("UPDATE ptf_extend_demands SET strikes = 5 WHERE PlayerID = " . $_POST['PlayerID']);
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_SESSION['TeamID']}, 'extbreak', NOW(), $time)");
        transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'extbreak');
        exit;
    }
    
    if ($_POST['year1'] > ($salaryCap - $totalnext)) {
        echo 'WARNING!!! Your offer puts you over the cap by $' . number_format($_POST['year1'] - ($salaryCap - $totalnext)) . ' next season!  You must be under ' . $salaryCap . ' before the beginning of the next season!';
    }

    $diff1 = $diff2 = $diff3 = $diff4 = $diff5 = $diff6 = 0;
    $target1 = $target2 = $target3 = $target4 = $target5 = $target6 = 0;


    $chance = 0;
    $below1 = $_POST['y1min'] - $_POST['year1'];
    $below2 = $_POST['y2min'] - $_POST['year2'];
    $below3 = $_POST['y3min'] - $_POST['year3'];
    $below4 = $_POST['y4min'] - $_POST['year4'];
    $below5 = $_POST['y5min'] - $_POST['year5'];
    $below6 = $_POST['y6min'] - $_POST['year6'];

    $target1 = ($_POST['y1min'] - $_POST['ogyo']) / 50000;
    if ($_POST['year1'] > $_POST['y1min']) {
        $diff1 = ($_POST['y1min'] - $_POST['ogyo']) / 50000;
    } else {
        $diff1 = ($_POST['year1'] - $_POST['ogyo']) / 50000;
    }

    if ($_POST['year2']) {
        $target2 = ($_POST['y2min'] - $_POST['ogyo']) / 50000;
        if ($_POST['year2'] > $_POST['y2min']) {
            $diff2 = ($_POST['y2min'] - $_POST['ogyo']) / 50000;
        } else {
            $diff2 = ($_POST['year2'] - $_POST['ogyo']) / 50000;
        }
    }

    if ($_POST['year3']) {
        $target3 = ($_POST['y3min'] - $_POST['ogyo']) / 50000;
        if ($_POST['year3'] > $_POST['y3min']) {
            $diff3 = ($_POST['y3min'] - $_POST['ogyo']) / 50000;
        } else {
            $diff3 = ($_POST['year3'] - $_POST['ogyo']) / 50000;
        }
    }

    if ($_POST['year4']) {
        $target4 = ($_POST['y4min'] - $_POST['ogyo']) / 50000;
        if ($_POST['year4'] > $_POST['y4min']) {
            $diff4 = ($_POST['y4min'] - $_POST['ogyo']) / 50000;
        } else {
            $diff4 = ($_POST['year4'] - $_POST['ogyo']) / 50000;
        }
    }

    if ($_POST['year5']) {
        $target5 = ($_POST['y5min'] - $_POST['ogyo']) / 50000;
        if ($_POST['year5'] > $_POST['y5min']) {
            $diff5 = ($_POST['y5min'] - $_POST['ogyo']) / 50000;
        } else {
            $diff5 = ($_POST['year5'] - $_POST['ogyo']) / 50000;
        }
    }

    if ($_POST['year6']) {
        $target6 = ($_POST['y6min'] - $_POST['ogyo']) / 50000;
        if ($_POST['year6'] > $_POST['y6min']) {
            $diff6 = ($_POST['y6min'] - $_POST['ogyo']) / 50000;
        } else {
            $diff6 = ($_POST['year6'] - $_POST['ogyo']) / 50000;
        }
    }

    $key = 'y' . $_POST['extat']  . 'min';
    echo 'The threshold is the amount at which the player will automatically accept.  In this player\'s case it\'s ' . number_format($_POST[$key]) . '. <br><br>';

    if ($_POST['extat'] >= 1) {
        if ($_POST['year1'] < $_POST['y1min']) {
            echo 'Year 1 offer is below the threshold by $' . number_format($below1) . '.<br>';
            $chance = 1;
        } else {
            echo 'Year 1 offer is at or above the threshold!<br>';
        }
    }
    if ($_POST['extat'] >= 2) {
        if ($_POST['year2'] < $_POST['y2min'] && $_POST['extat'] >= 2) {
            echo 'Year 2 offer is below the threshold by $' . number_format($below2) . '.<br>';
            $chance = 1;
        } else {
            echo 'Year 2 offer is at or above the threshold!<br>';
        }
    }
    if ($_POST['extat'] >= 3) {
        if ($_POST['year3'] < $_POST['y3min'] && $_POST['extat'] >= 3) {
            echo 'Year 3 offer is below the threshold by $' . number_format($below3) . '.<br>';
            $chance = 1;
        } else {
            echo 'Year 3 offer is at or above the threshold!<br>';
        }
    }
    if ($_POST['extat'] >= 4) {
        if ($_POST['year4'] < $_POST['y4min'] && $_POST['extat'] >= 4) {
            echo 'Year 4 offer is below the threshold by $' . number_format($below4) . '.<br>';
            $chance = 1;
        } else {
            echo 'Year 4 offer is at or above the threshold!<br>';
        }
    }
    if ($_POST['extat'] >= 5) {
        if ($_POST['year5'] < $_POST['y5min'] && $_POST['extat'] >= 5) {
            echo 'Year 5 offer is below the threshold by $' . number_format($below5) . '.<br>';
            $chance = 1;
        } else {
            echo 'Year 5 offer is at or above the threshold!<br>';
        }
}
    if ($_POST['extat'] >= 6) {
        if ($_POST['year6'] < $_POST['y6min'] && $_POST['extat'] >= 6) {
            echo 'Year 6 offer is below the threshold by $' . number_format($below6) . '.<br>';
            $chance = 1;
        } else {
            echo 'Year 6 offer is at or above the threshold!<br>';
        }
    }
    //echo $diff1 . ' - ' . $diff2 . ' - ' . $diff3 . ' - ' . $diff4 . ' - ' . $diff5 . ' - ' . $diff6;

    if ($chance == 1) {
        $player['FullName'] . ' is considering your offer.... <br><br>';
        $targ = $target1 + $target2 + $target3 + $target4 + $target5 + $target6;
        $sum = $diff1 + $diff2 + $diff3 + $diff4 + $diff5 + $diff6;
        $finalTarget = ceil($targ / $_POST['extat']); 
        $diff = ceil($sum / $_POST['extat']); 
        $rand = rand(0,($finalTarget));
        $checksum = $rand + $diff;
        echo '<b>Offer score is: ' . $checksum . '.</b> ' .  $finalTarget . ' is needed for a successful offer.<br><br>';
        if ($checksum <= 1) {
            echo $player['FullName'] . ' considers your offer an insult!  There will be no further extension negotiations and he will go to free agency in the offseason!';
            echo '<br><br><a href="transactions.php">Go Back to Transactions</a>'; 
            $strikes = $connection->query("UPDATE ptf_extend_demands SET strikes = 3 WHERE PlayerID = " . $_POST['PlayerID']);
            $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_SESSION['TeamID']}, 'extbreak', NOW(), $time)");
            transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'extbreak');
            exit;
        } elseif ($checksum < $finalTarget) {
            $tries = $_POST['try'] . ' = ';
            $strikes = $tries + 1;
            $try = 3 - $strikes;
            if ($try == 0) {
                echo $player['FullName'] . ' does not accept your offer.  You are out of attempts.  He will go to free agency in the offseason!';
                echo '<br><br><a href="transactions.php">Go Back to Transactions</a>'; 
                $strikes = $connection->query("UPDATE ptf_extend_demands SET strikes = 3 WHERE PlayerID = " . $_POST['PlayerID']);
                $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_SESSION['TeamID']}, 'extbreak', NOW(), $time)");
                transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'extbreak');
                exit;
            } else {
                echo $player['FullName'] . ' does not accept your offer.  You can try again ' . $try . ' more times!';
                echo '<br><br><a href="transactions.php">Go Back to Transactions</a>'; 
                $strikes = $connection->query("UPDATE ptf_extend_demands SET strikes = " . $strikes . " WHERE PlayerID = " . $_POST['PlayerID']);
                $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_SESSION['TeamID']}, 'extdecline', NOW(), $time)");
                transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'extdecline');
                exit;
            }

        }
    }

    if ($_POST['extat'] == 6) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3'] . ', `' . $year + 4 . '` = ' . $_POST['year4'] . ', `' . $year + 5 . '` = ' . $_POST['year5'] . ', `' . $year + 6 . '` = ' . $_POST['year6']; 
        if (!$_POST['year6'] || !$_POST['year5'] || !$_POST['year4'] || !$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 5) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3'] . ', `' . $year + 4 . '` = ' . $_POST['year4'] . ', `' . $year + 5 . '` = ' . $_POST['year5']; 
        if (!$_POST['year5'] || !$_POST['year4'] || !$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 4) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3'] . ', `' . $year + 4 . '` = ' . $_POST['year4']; 
        if (!$_POST['year4'] || !$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 3) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3']; 
        if (!$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 2) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2']; 
        if (!$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 1) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1']; 
    }

    $sum = $_POST['year6'] + $_POST['year5'] + $_POST['year4'] + $_POST['year3'] + $_POST['year2'] + $_POST['year1'];

    $extension = $connection->query("UPDATE ptf_teams_data SET Extensions = Extensions - 1 WHERE TeamID = " . $_SESSION['TeamID']);
    $roster = $connection->query("UPDATE ptf_players_salaries SET " . $string . " WHERE PlayerID = " . $_POST['PlayerID']);
    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_SESSION['TeamID']}, 'extend', NOW(), $time)");
    transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'extend');
    echo $player['FullName'] . ' has accepted your offer of ' . $_POST['extat'] . ' years at a total of ' . number_format($sum) . '.';


} elseif (isset($_POST['offer'])) {

    if ($_POST['year1'] > (300000000 - $_SESSION['currentCap'])) {
        echo 'Your offer puts you over the cap by $' . number_format($_POST['year1'] - (300000000 - $_SESSION['currentCap'])) . ' and is invalid!  Go back and try again!';
        exit;
    }

    $year1 = intval(str_replace(",","",$_POST['year1']));
    $year2 = intval(str_replace(",","",$_POST['year2']));
    $year3 = intval(str_replace(",","",$_POST['year3']));
    $year4 = intval(str_replace(",","",$_POST['year4']));
    $year5 = intval(str_replace(",","",$_POST['year5']));
    $year6 = intval(str_replace(",","",$_POST['year6']));
    $year7 = intval(str_replace(",","",$_POST['year7']));
    $demand = intval(str_replace(",","",$_POST['Demand']));
    $sum = $year1+$year2+$year3+$year4+$year5+$year6+$year7;
    $years = 1;
    if ($_POST['year2'] > 0) $years++;
    if ($_POST['year3'] > 0) $years++;
    if ($_POST['year4'] > 0) $years++;
    if ($_POST['year5'] > 0) $years++;
    if ($_POST['year6'] > 0) $years++;
    if ($_POST['year7'] > 0) $years++;

    if ($_POST['PlayerID'] >= 8358) {
        $result = 2;
    } elseif ($sum / $years >= $demand) {
        $result = 1;
    } else {
        $result = 0;
    }

    if ($result == 2) {
        echo 'Undrafted Free Agent ' . $_POST['Player'] . ' has been offered the rookie minimum contract.  Good luck.';
    } elseif ($result == 1) {
        echo $_POST['Player'] . ' has been offered a contract and will make his decision shortly.  Hope you didnt cheap out on him.';
    } else {
        echo $_POST['Player'] . ' has received your contract offer, but it might not meet his demand.  We shall see I guess.  Good luck!';
    }

    echo ' ' . $years . ' years at a total of ' . number_format($sum) . '.';

    $offer = $connection->query("INSERT INTO ptf_fa_offers (playerID, teamID, year, amount1, amount2, amount3, amount4, amount5, amount6, total, demand, result) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},1989,{$year1},{$year2},{$year3},{$year4},{$year5},{$year6},{$sum},{$demand},{$result})");
    offerHook($_POST['Player'], $_SESSION['TeamID'], $years, number_format($sum));

} elseif (isset($_POST['tag'])) {
    echo $_POST['Player'] . ' has been franchise tagged! He may or may not be happy, but he for sure is getting paid!';
    $franchise = intval(str_replace(",","",$_POST['franchise']));

    $tagToggle =  $connection->query("INSERT INTO ptf_players_extra (PlayerID, taggedBy" . $_POST['taggedBy'] . ") VALUES (" . $_POST['PlayerID'] . ", " . $_POST['TeamID'] . ")  ON DUPLICATE KEY UPDATE taggedBy1 = " . $_POST['TeamID']);

    $roster = $connection->query("UPDATE ptf_players_salaries SET `" . $year . "` = '" . $franchise . "', `" . $year + 1 . "` = '" . $franchise . "' WHERE PlayerID = " . $_POST['PlayerID']);
    $roster2 = $connection->query("UPDATE ptf_players SET TeamID = " . $_POST['TeamID'] . ", Team = '" . idToAbbrev($_POST['TeamID']) . "' WHERE PlayerID = " . $_POST['PlayerID']);
    $tagcount = $connection->query("UPDATE ptf_teams_data SET FranchiseTag = 0 WHERE TeamID = " .  $_POST['TeamID']);
    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'tag', NOW(), '1991 Franchise Tag')");
    
    $playerService = playerService(0,$_POST['PlayerID'],0);
    $player = $playerService[0];
    transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'tag');

} elseif (isset($_POST['draft'])) {
    $nextpick = $_POST['pick'] + 1;
    if ($nextpick == 21) {
        $nextpick = 1;
        $nextround = $_POST['round'] + 1;
    } else {
        $nextround = $_POST['round'];
    }

    echo $_POST['Player'] . ' has been drafted!  What a special day!';

    $stmt2= $connection->query('SELECT playerID FROM ptf_draft_picks  WHERE draftID = ' . $_POST['draftID']);
    $check = $stmt2->fetch_assoc();

    if($check['playerID'] == 0) {
        $roster = $connection->query("UPDATE ptf_draft_picks SET playerID = '{$_POST['PlayerID']}' WHERE draftID = " . $_POST['draftID']);

        $currentpick = $connection->query("UPDATE ptf_draft_picks SET current = '0' WHERE draftID = " . $_POST['draftID']);

        $curTime = date("Y-m-d H:i:s", time());
        $next = $connection->query("UPDATE ptf_draft_picks SET current = '1', `time` = '".$curTime."' WHERE round = '" . $nextround . "' AND pick = '" . $nextpick . "'");

        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date, TimeFrame) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'draft', NOW(), '1991 Draft')");

        $roster = $connection->query("UPDATE ptf_players SET TeamID = " . $_POST['TeamID'] . ", Team = '" . idToAbbrev($_POST['TeamID']) . "' WHERE PlayerID = " . $_POST['PlayerID']);

        draftHook($_POST['Player'], $_POST['TeamID'], $_POST['pick'], $_POST['round'], $_POST['pos']);
    }

}

if (isset($_POST['offer']) || isset($_POST['revoke'])) {
    echo '<br><br><a href="freeagency.php?abbrev=' . $_SESSION['abbreviation'] . '">Go Back to Free Agency</a>'; 
} else {
    echo '<br><br><a href="transactions.php">Go Back to Transactions</a>'; 
}

function transactionHook($player, $team, $pos, $type) {
    //global $connection;
    $teamService = teamService($team);
    $teamname = $teamService[0];

    if ($type == 'sign') {
        $message = 'The ' . $teamname['FullName'] . ' have signed free agent ' . $pos . ' - ' . $player . ' to a 1 year contract for the league minimum.';
    } elseif ($type == 'release') {
        $message = 'The ' . $teamname['FullName'] . ' have released ' . $pos . ' - ' . $player . ' to the free agency pool.';
    } elseif ($type == 'extend') {
        $message = 'The ' . $teamname['FullName'] . ' have agreed to a contract extension with ' . $player . '!';
    } elseif ($type == 'extdecline') {
        $message = 'The ' . $teamname['FullName'] . ' have had an extension declined by ' . $player . '!';
    } elseif ($type == 'extbreak') {
        $message = 'The ' . $teamname['FullName'] . ' have had an extension declined by ' . $player . ', and future talks are broken off!';
    } elseif ($type == 'tag') {
        $message = 'The ' . $teamname['FullName'] . ' have franchise tagged ' . $player . '!';
    } elseif ($type == 'change') {
        $message = 'The ' . $teamname['FullName'] . ' have changed the position of ' . $player . ' to ' . $pos . '!';
    } elseif ($type == 'promote') {
        $message = 'The ' . $teamname['FullName'] . ' have promoted ' . $player . ' from the practice squad to the main roster!';
    } elseif ($type == 'demote') {
        $message = 'The ' . $teamname['FullName'] . ' have demoted ' . $player . ' to the practice squad!';
    } elseif ($type == 'IR') {
        $message = 'The ' . $teamname['FullName'] . ' have placed ' . $player . ' on Injured Reserve!';
    } elseif ($type == 'activate') {
        $message = 'The ' . $teamname['FullName'] . ' have activated ' . $player . ' from Injured Reserve!';
    }

    $url = 'https://discord.com/api/webhooks/1331306239623434312/e4KJkCcCF_MadaS_AWyhvGMbPlhCs-f5dLlDxKXvWwU1BqG2pWngZKpqfMNCY3I9n3Rl';
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'League Offices', 'content' => $message ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}

function offerHook($player, $team, $years, $sum) {

    //global $connection;
    $teamService = teamService($team);
    $teamname = $teamService[0];

    $message = 'The ' . $teamname['FullName'] . ' have made a free agency offer!';
    $privatemessage = 'The ' . $teamname['FullName'] . ' have offered ' . $player . ' : ' . $years . ' years, $' . $sum . '.';

    $url = 'https://discordapp.com/api/webhooks/1317257489871278091/4tG3jEXwyyxlGStrd6lhQCGFF0i37jyszErRWCHkd5UW5zd2vw8LlYaooqc-PHDTwCd3';
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'Free Agent Rumors', 'content' => $message ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);


    $url = 'https://discordapp.com/api/webhooks/1317258182971756614/5m9SbqGASgpbpSlWaYQIOpU9sCbw571OFZd3mRTgn16_H6L0vYdRjiAwZEhprnKYSxxs';
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'Free Agent Rumors', 'content' => $privatemessage ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}


function draftHook($player, $team, $pick, $round, $pos) {
    global $connection;
    $teamService = teamService($team);
    $teamname = $teamService[0];
    $nextup = $pick + 1;
    if ($nextup == 21) {
        $nextup = 1;
        $round = $round + 1;
    }

    if ($nextup == 1) {
        $roundtag = $round - 1;
    } else {
        $roundtag = $round;
    }

    $stmt2= $connection->query('SELECT t.DiscordTag, t.DiscordUser FROM ptf_draft_picks d JOIN ptf_teams_data t ON d.owner = t.TeamID WHERE d.year = "1991" and d.round = '. $round .' and d.pick = ' . $nextup);
    $nextteam = array();
    while($row = $stmt2->fetch_assoc()) {
        array_push($nextteam, $row);
    }
    $discordtag = $nextteam[0]['DiscordTag'];
    $discordUser = $nextteam[0]['DiscordUser'];

    $message = 'With the number ' . $pick . ' pick of round ' . $roundtag . ', the ' . $teamname['FullName'] . ' select ' . $pos . ' - ' . $player . '.  ' . $discordtag . ' - ' . $discordUser . ' is on the clock!';

    //$url = 'https://discord.com/api/webhooks/1208652742453624872/N9WcLuNn98u-hDWya1l8tuQRcZTBs7hluZzAG6YEzRQ4iQdwdFUOGwIND5hyomrFWplK';   - 1986
    //$url = 'https://discord.com/api/webhooks/1248126643432853505/8Qgtz_9lrOlZVTIq_7EDvRNIS7dg6ipVdSntay5FT-BMuGG1TtBwDRvVN6MXEQ2tnFeW';   - 1987
    //$url = 'https://discord.com/api/webhooks/1305272197438378046/V8GTP3eWZh49F0kB6Iixq9qx1IH7S-ug2Zio227jAUMD7pPSRwQSs3ldjurTbG0P3aah';   - 1988
    $url = 'https://discord.com/api/webhooks/1380025021098889336/AaLw55CHmi8LkH6mRCRk_3TZZLZS4MKvbDUzNzv7j9tyu--cC0VdZCAWD8FqVT4c7ovX';
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'League Offices', 'content' => $message ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}
                                                                                            
?>