<?php

declare(strict_types=1);


include 'header.php';

use App\Services\StatsService;

$StatsService = new StatsService();

$firstSeason = 1985;
$currentSeason = (int)$year;

$availableSeasons = range(
    $firstSeason,
    $currentSeason
);

/* FILTERS */
$rosterTeamId = filter_input(INPUT_GET,'team',FILTER_VALIDATE_INT) ?: 0;
$selectedTeamId = filter_input(INPUT_GET,'filter_team',FILTER_VALIDATE_INT) ?: 0;
$selectedPosition = strtoupper(trim($_GET['pos'] ?? 'all'));
$allowedGroups = array_map(
    'strval',
    $availableSeasons
);

$selectedGroup = (string)(
    $_GET['group'] ?? $currentSeason
);

if (!in_array($selectedGroup, $allowedGroups, true)) {
    $selectedGroup = (string)$currentSeason;
}

$selYear = (int)$selectedGroup;


$stats = $StatsService->getAllSeason($selYear);




$searchQuery = trim($_GET['q'] ?? '');
$isCurrentSeason = $selYear === $currentSeason;

if ($isCurrentSeason) {
    $allowedStatuses = [
        'current',
        'active',
        'freeagents',
        'draft',
        'injured',
        'retired',
        'all'
    ];
    $defaultStatus = 'current';
} else {
        $allowedStatuses = [
        'active',
        'retired',
        'all'
    ];
    $defaultStatus = 'all';
}


$defaultStatus = $isCurrentSeason
    ? 'current'
    : 'all';

$selectedStatus = strtolower(
    trim($_GET['status'] ?? $defaultStatus)
);

if (!in_array($selectedStatus, $allowedStatuses, true)) {
    $selectedStatus = $defaultStatus;

}

$selectedView = strtolower(
    trim($_GET['view'] ?? 'stats')
);

$allowedViews = [
    'stats',
    'awards',
    'team'
];

if (!in_array($selectedView, $allowedViews, true)) {
    $selectedView = 'stats';
}

/* DROPDOWN DATA */
$teamOptions = [];

foreach ($stats as $stat) {
    if ((int)$stat->DraftSeason > $selYear) {
        continue;
    }

    $teamId = (int) $stat->TeamID;

    if (
        (int) $stat->ProRetire !== 0 ||
        $teamId === 0
    ) {
        continue;
    }

    $teamOptions[$teamId] =
        idToAbbrev($teamId);
}

asort($teamOptions, SORT_NATURAL | SORT_FLAG_CASE); ?>

<h2>All Players</h2>
<div align="center">
    <div class="player-directory-toolbar">
        <form 
            class="player-filter-bar"
            id="playerFilters"
            method="get"
            action="player_stats.php"
        >
        <?php if ($rosterTeamId): ?>
            <input
                type="hidden"
                name="team"
                value="<?= (int) $rosterTeamId ?>"
            >
        <?php endif; ?>

            <label class="player-filter-control player-search-control">
                <span class="player-filter-icon" aria-hidden="true">
                    ⌕
                </span>

                <span class="sr-only">Search players</span>

            <input
                type="search"
                name="q"
                id="playerSearch"
                placeholder="Search players..."
                value="<?= htmlspecialchars($searchQuery,ENT_QUOTES,'UTF-8') ?>"
                autocomplete="off"
            >

        </label>

        <?php if (!$rosterTeamId): ?>
            <label class="player-filter-control player-select-control">
                <span class="sr-only">Filter by team</span>
                <select name="filter_team" id="teamFilter">
                    <option value=""
                    <?= $selectedTeamId === 0 ? 'selected' : '' ?>>
                        All Teams
                    </option>
                    <?php foreach ($teamOptions as $teamId => $abbrev): ?>
                        <option value="<?= (int) $teamId ?>"
                            <?= $selectedTeamId === (int) $teamId ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($abbrev,ENT_QUOTES,'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

        <?php endif; ?>

        <label class="player-filter-control player-select-control">
            <span class="sr-only">Filter by position</span>
            <select name="pos" id="positionFilter">
                <option value="all">All Positions</option>
                <?php foreach ($positions as $position): ?>
                    <option
                        value="<?= htmlspecialchars($position,ENT_QUOTES,'UTF-8') ?>"
                        <?= $selectedPosition === strtoupper($position) ? 'selected': '' ?>
                    >
                        <?= htmlspecialchars($position,ENT_QUOTES,'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
                <optgroup label="Position Groups">
                    <option
                        value="ST"
                        <?= $selectedPosition === 'ST' ? 'selected' : '' ?>
                    >
                        Special Teams
                    </option>
                    <option
                        value="SK"
                        <?= $selectedPosition === 'SK' ? 'selected' : '' ?>
                    >
                        All Skill Positions
                    </option>
                    <option
                        value="OL"
                        <?= $selectedPosition === 'OL' ? 'selected' : '' ?>
                    >
                        All Offensive Line
                    </option>

                    <option
                        value="DL"
                        <?= $selectedPosition === 'DL' ? 'selected' : '' ?>
                    >
                        All Defensive Front
                    </option>

                    <option
                        value="DB"
                        <?= $selectedPosition === 'DB' ? 'selected' : '' ?>
                    >
                        All Secondary
                    </option>
                </optgroup>
            </select>
        </label>
    <label class="player-filter-control player-select-control">
    <span class="sr-only">Filter by player status</span>

    <select name="status" id="statusFilter">
    
    <?php if ($isCurrentSeason): ?>
        <option
            value="current"
            <?= $selectedStatus === 'current' ? 'selected' : '' ?>
        >
            Current Players
        </option>

        <option
            value="active"
            <?= $selectedStatus === 'active' ? 'selected' : '' ?>
        >
            Active Rosters
        </option>

        <option
            value="freeagents"
            <?= $selectedStatus === 'freeagents' ? 'selected' : '' ?>
        >
            Free Agents
        </option>

        <option
            value="draft"
            <?= $selectedStatus === 'draft' ? 'selected' : '' ?>
        >
            Draft Class
        </option>

        <option
            value="injured"
            <?= $selectedStatus === 'injured' ? 'selected' : '' ?>
        >
            Injured
        </option>

        <option
            value="retired"
            <?= $selectedStatus === 'retired' ? 'selected' : '' ?>
        >
            Retired
        </option>

    <?php endif; ?>

    <?php if (!$isCurrentSeason): ?>
        <option
            value="active"
            <?= $selectedStatus === 'active' ? 'selected' : '' ?>
        >
            Active Rosters
        </option>
        <option
            value="retired"
            <?= $selectedStatus === 'retired' ? 'selected' : '' ?>
        >
            Retired
        </option>
    <?php endif; ?>

    <option
        value="all"
        <?= $selectedStatus === 'all' ? 'selected' : '' ?>
    >
        All Players
    </option>
    </select>
</label>

        <div class="player-column-picker">
    <button
        type="button"
        class="player-filter-clear player-column-button"
        id="playerColumnButton"
        aria-expanded="false"
        aria-controls="playerColumnMenu"
    >
        Columns
    </button>

    <div
        class="player-column-menu"
        id="playerColumnMenu"
        hidden
    >
        <div class="player-column-menu-header">
            Select Columns
        </div>

        <div id="playerColumnOptions"></div>

        <div class="player-column-actions">
    <button
        type="button"
        class="player-column-clear"
        id="clearPlayerColumns"
    >
        Clear All
    </button>

    <button
        type="button"
        class="player-column-reset"
        id="resetPlayerColumns"
    >
        Reset Defaults
    </button>
</div>
    </div>
</div>

        <button
            type="button"
            class="player-filter-clear"
            id="clearPlayerFilters"
        >
            Clear
        </button>

        <div class="player-filter-count">
            <strong id="visiblePlayerCount">0</strong>
            <span>players</span>
        </div>
    </form>
</div>
    <h3 id="playerGroup">    
        <?= (int)$selectedGroup ?>
    </h3>
<?php foreach ($availableSeasons as $index => $season): ?>
    <?php if ($index > 0): ?>
        -
    <?php endif; ?>

    <a
        href="player_stats.php?group=<?= $season ?>"
        class="playerGroupLink<?= (int)$selectedGroup === $season
            ? ' active'
            : '' ?>"
        data-group="<?= $season ?>"
    >
        <?= $season ?>
    </a>
<?php endforeach; ?>
    <br>
<select name="view" id="viewFilter">
    <option
        value="stats"
        <?= $selectedView === 'stats' ? 'selected' : '' ?>
    >
        Position Stats
    </option>

    <option
        value="awards"
        <?= $selectedView === 'awards' ? 'selected' : '' ?>
    >
        Awards
    </option>

    <option
        value="team"
        <?= $selectedView === 'team' ? 'selected' : '' ?>
    >
        Team and Playoff
    </option>
</select>
<br><br>



<table class="player-table player-table-loading" id="playersTable">
    <thead>
        <tr id="playerTableHeader">
            <!-- Javascript handles this! -->
        </tr>
    </thead>
    <tbody>

<?php 
usort($stats, fn($a, $b) => $b->Plays <=> $a->Plays);

foreach ($stats as $stat) {

    if ((int)$stat->DraftSeason > $selYear) {
        continue;
    }

    $teamId = (int)$stat->TeamID;
    $teamAbbrev = idToAbbrev($stat->TeamID);
    $primaryColor = '#' . idConvert('primeColor', $stat->TeamID);
    $secondaryColor = '#' . idConvert('secondaryColor', $stat->TeamID);

    if (
        $rosterTeamId &&
        (int) $stat->TeamID !== $rosterTeamId
    ) {
        continue;
    }

    $isRetired =
        (int)$stat->ProRetire !== 0;

    $isDraftClass =
        (int)$stat->DraftSeason === ((int)$year + 1);

    $isInjured =
        trim((string)($stat->Injury ?? '')) !== '';

    $isActive =
        $teamId !== 0 &&
        !$isRetired &&
        !$isDraftClass;

    $isFreeAgent =
        $teamId === 0 &&
        !$isRetired &&
        !$isDraftClass;

    $isSpecialTeams =
        (int)$stat->PuntReturns >= 1 ||
        (int)$stat->KickoffReturns >= 1 ||
        (int)$stat->BlockedPunt >= 1 ||
        (int)$stat->BlockedFG >= 1 ||
        (int)$stat->BlockedPAT >= 1;


    $ageReferenceYear = $isRetired
        ? (int)$stat->ProRetire
        : (int)$year;

    $historicalAge = max(
        0,
        (int)$stat->Age -
        ($ageReferenceYear - $selYear) +
        ($isRetired ? 1 : 0)
    );

    if ((int)$stat->ProRetire > 0) {
        $experienceReferenceYear =
            (int)$stat->ProRetire;
    } else {
        $experienceReferenceYear =
            (int)$year;
    }

    $historicalExperience = max(
        0,
        (int)$stat->Experience -
        ($experienceReferenceYear - $selYear)
    );

    echo '<tr data-player-row data-player-name="'
        . htmlspecialchars(strtolower($stat->FullName),ENT_QUOTES,'UTF-8') . '"'
        . ' data-team-id="' . (int) $stat->TeamID . '"' . ' data-team="'
        . htmlspecialchars(strtolower($teamAbbrev),ENT_QUOTES,'UTF-8'). '"'
        . ' data-position="'. htmlspecialchars(strtoupper($stat->Position),ENT_QUOTES,'UTF-8') . '"'
        . ' data-special-teams="' . (int)$isSpecialTeams . '"'
        . ' data-active="' . (int)$isActive . '"'
        . ' data-free-agent="' . (int)$isFreeAgent . '"'
        . ' data-draft-class="' . (int)$isDraftClass . '"'
        . ' data-injured="' . (int)$isInjured . '"'
        . ' data-retired="' . (int)$isRetired . '">';

    echo '<td class="team-cell" data-column="Team">';

    echo '<span class="team-badge" style="'
        . '--team-primary: ' . htmlspecialchars($primaryColor,ENT_QUOTES,'UTF-8')
        . '; --team-secondary: ' . htmlspecialchars($secondaryColor,ENT_QUOTES,'UTF-8') . ';">';

    echo htmlspecialchars($teamAbbrev,ENT_QUOTES,'UTF-8');

    echo '</span>';
    echo '</td>';
echo '<td data-column="FullName">
        <a href="/ptf/player.php?player=' . (int)$stat->PlayerID . '">'
            . htmlspecialchars($stat->FullName,ENT_QUOTES,'UTF-8') .
        '</a>
    </td>';

echo '<td data-column="Position">' . htmlspecialchars($stat->Position,ENT_QUOTES,'UTF-8') . '</td>';
echo '<td data-column="Age">' . $historicalAge . '</td>';
echo '<td data-column="Experience">' . $historicalExperience . '</td>';
echo '<td data-column="G">' . (int)$stat->G . '</td>';
echo '<td data-column="PassAtt"><b>' . (int)$stat->PassAtt . '</b></td>';
echo '<td data-column="PassCmp"><b>' . (int)$stat->PassCmp . '</b></td>';
echo '<td data-column="PassYds"><b>' . (int)$stat->PassYds . '</b></td>';
echo '<td data-column="PassTD"><b>' . (int)$stat->PassTD . '</b></td>';
echo '<td data-column="PassInt"><b>' . (int)$stat->PassInt . '</b></td>';
echo '<td data-column="RushAtt"><b>' . (int)$stat->RushAtt . '</b></td>';
echo '<td data-column="RushYds"><b>' . (int)$stat->RushYds . '</b></td>';
echo '<td data-column="RushTD"><b>' . (int)$stat->RushTD . '</b></td>';
echo '<td data-column="Fumbles"><b>' . (int)$stat->Fumbles . '</b></td>';
echo '<td data-column="Catches"><b>' . (int)$stat->Catches . '</b></td>';
echo '<td data-column="RecYds"><b>' . (int)$stat->RecYds . '</b></td>';
echo '<td data-column="RecTD"><b>' . (int)$stat->RecTD . '</b></td>';
echo '<td data-column="Tar"><b>' . (int)$stat->Tar . '</b></td>';
echo '<td data-column="Tackles"><b>' . (int)$stat->Tackles . '</b></td>';
echo '<td data-column="Sacks"><b>' . (int)$stat->Sacks . '</b></td>';
echo '<td data-column="Int"><b>' . (int)$stat->Int . '</b></td>';
echo '<td data-column="DefensiveTD"><b>' . (int)$stat->DefensiveTD . '</b></td>';
echo '<td data-column="GS">' . (int)$stat->GS . '</td>';
echo '<td data-column="Championships">' . (int)$stat->Championships . '</td>';
echo '<td data-column="ChampionshipYears">' . htmlspecialchars((string)$stat->ChampionshipYears) . '</td>';
echo '<td data-column="MadePlayoffs">' . (int)$stat->MadePlayoffs . '</td>';
echo '<td data-column="Wins">' . (int)$stat->Wins . '</td>';
echo '<td data-column="Losses">' . (int)$stat->Losses . '</td>';
echo '<td data-column="Ties">' . (int)$stat->Ties . '</td>';
echo '<td data-column="WinPct">' . (float)$stat->WinPct . '</td>';
echo '<td data-column="Round1Wins">' . (int)$stat->Round1Wins . '</td>';
echo '<td data-column="Round1Losses">' . (int)$stat->Round1Losses . '</td>';
echo '<td data-column="Rounds1WinPct">' . (float)$stat->Rounds1WinPct . '</td>';
echo '<td data-column="Round2Wins">' . (int)$stat->Round2Wins . '</td>';
echo '<td data-column="Round2Losses">' . (int)$stat->Round2Losses . '</td>';
echo '<td data-column="Round2WinPct">' . (float)$stat->Round2WinPct . '</td>';
echo '<td data-column="Round3Wins">' . (int)$stat->Round3Wins . '</td>';
echo '<td data-column="Round3Losses">' . (int)$stat->Round3Losses . '</td>';
echo '<td data-column="Round3WinPct">' . (float)$stat->Round3WinPct . '</td>';
echo '<td data-column="ChampionshipWins">' . (int)$stat->ChampionshipWins . '</td>';
echo '<td data-column="ChampionshipLosses">' . (int)$stat->ChampionshipLosses . '</td>';
echo '<td data-column="ChampionshipWinPct">' . (float)$stat->ChampionshipWinPct . '</td>';
echo '<td data-column="ELORating">' . (float)$stat->ELORating . '</td>';
echo '<td data-column="Plays">' . (int)$stat->Plays . '</td>';
echo '<td data-column="PassYdsAgainst">' . (int)$stat->PassYdsAgainst . '</td>';
echo '<td data-column="TotalYdsAgainst">' . (int)$stat->TotalYdsAgainst . '</td>';
echo '<td data-column="TotalYds">' . (int)$stat->TotalYds . '</td>';
echo '<td data-column="RushYdsAgainst">' . (int)$stat->RushYdsAgainst . '</td>';
echo '<td data-column="PassRating">' . (float)$stat->PassRating . '</td>';
echo '<td data-column="PassingAttemptsPerGame">' . (float)$stat->PassingAttemptsPerGame . '</td>';
echo '<td data-column="PassingYardsPerAttempt">' . (float)$stat->PassingYardsPerAttempt . '</td>';
echo '<td data-column="PassingYardsPerCompletion">' . (float)$stat->PassingYardsPerCompletion . '</td>';
echo '<td data-column="SackPct">' . (float)$stat->SackPct . '</td>';
echo '<td data-column="PassingYdsPerGame">' . (float)$stat->PassingYdsPerGame . '</td>';
echo '<td data-column="RunLong">' . (int)$stat->RunLong . '</td>';
echo '<td data-column="PassLong">' . (int)$stat->PassLong . '</td>';
echo '<td data-column="RecLong">' . (int)$stat->RecLong . '</td>';
echo '<td data-column="PuntLong">' . (int)$stat->PuntLong . '</td>';
echo '<td data-column="KRLong">' . (int)$stat->KRLong . '</td>';
echo '<td data-column="PRLong">' . (int)$stat->PRLong . '</td>';
echo '<td data-column="FGLong">' . (int)$stat->FGLong . '</td>';
echo '<td data-column="IntReturnYds">' . (int)$stat->IntReturnYds . '</td>';
echo '<td data-column="FumblesLost">' . (int)$stat->FumblesLost . '</td>';
echo '<td data-column="FumReturnYds">' . (int)$stat->FumReturnYds . '</td>';
echo '<td data-column="Pancakes">' . (int)$stat->Pancakes . '</td>';
echo '<td data-column="MissedTackles">' . (int)$stat->MissedTackles . '</td>';
echo '<td data-column="DroppedPasses">' . (int)$stat->DroppedPasses . '</td>';
echo '<td data-column="PassesDefensed">' . (int)$stat->PassesDefensed . '</td>';
echo '<td data-column="SacksAllowed">' . (int)$stat->SacksAllowed . '</td>';
echo '<td data-column="MissedBlocks">' . (int)$stat->MissedBlocks . '</td>';
echo '<td data-column="WasSacked">' . (int)$stat->WasSacked . '</td>';
echo '<td data-column="SackedYards">' . (int)$stat->SackedYards . '</td>';
echo '<td data-column="TFL">' . (int)$stat->TFL . '</td>';
echo '<td data-column="Hurries">' . (int)$stat->Hurries . '</td>';
echo '<td data-column="Knockdowns">' . (int)$stat->Knockdowns . '</td>';
echo '<td data-column="Safeties">' . (int)$stat->Safeties . '</td>';
echo '<td data-column="ForcedFumbles">' . (int)$stat->ForcedFumbles . '</td>';
echo '<td data-column="FumblesRecovered">' . (int)$stat->FumblesRecovered . '</td>';
echo '<td data-column="BlockedFG">' . (int)$stat->BlockedFG . '</td>';
echo '<td data-column="BlockedPAT">' . (int)$stat->BlockedPAT . '</td>';
echo '<td data-column="BlockedPunt">' . (int)$stat->BlockedPunt . '</td>';
echo '<td data-column="Penalties">' . (int)$stat->Penalties . '</td>';
echo '<td data-column="PenaltyYds">' . (int)$stat->PenaltyYds . '</td>';
echo '<td data-column="Punts">' . (int)$stat->Punts . '</td>';
echo '<td data-column="PuntYds">' . (int)$stat->PuntYds . '</td>';
echo '<td data-column="PuntsInside20">' . (int)$stat->PuntsInside20 . '</td>';
echo '<td data-column="PuntReturns">' . (int)$stat->PuntReturns . '</td>';
echo '<td data-column="PuntReturnYds">' . (int)$stat->PuntReturnYds . '</td>';
echo '<td data-column="PuntReturnTD">' . (int)$stat->PuntReturnTD . '</td>';
echo '<td data-column="KickoffReturns">' . (int)$stat->KickoffReturns . '</td>';
echo '<td data-column="KickoffReturnYds">' . (int)$stat->KickoffReturnYds . '</td>';
echo '<td data-column="KickoffReturnTD">' . (int)$stat->KickoffReturnTD . '</td>';
echo '<td data-column="FGA_U20">' . (int)$stat->FGA_U20 . '</td>';
echo '<td data-column="FGA_2029">' . (int)$stat->FGA_2029 . '</td>';
echo '<td data-column="FGA_3039">' . (int)$stat->FGA_3039 . '</td>';
echo '<td data-column="FGA_4049">' . (int)$stat->FGA_4049 . '</td>';
echo '<td data-column="FGA_50">' . (int)$stat->FGA_50 . '</td>';
echo '<td data-column="FGM_U20">' . (int)$stat->FGM_U20 . '</td>';
echo '<td data-column="FGM_2029">' . (int)$stat->FGM_2029 . '</td>';
echo '<td data-column="FGM_3039">' . (int)$stat->FGM_3039 . '</td>';
echo '<td data-column="FGM_4049">' . (int)$stat->FGM_4049 . '</td>';
echo '<td data-column="FGM_50">' . (int)$stat->FGM_50 . '</td>';
echo '<td data-column="FGA">' . (int)$stat->FGA . '</td>';
echo '<td data-column="FGM">' . (int)$stat->FGM . '</td>';
echo '<td data-column="XPA">' . (int)$stat->XPA . '</td>';
echo '<td data-column="XPM">' . (int)$stat->XPM . '</td>';
echo '<td data-column="POG">' . (int)$stat->POG . '</td>';
echo '<td data-column="POW">' . (int)$stat->POW . '</td>';
echo '<td data-column="POY">' . (int)$stat->POY . '</td>';
echo '<td data-column="POYYears">' . htmlspecialchars((string)$stat->POYYears) . '</td>';
echo '<td data-column="ProBowl">' . (int)$stat->ProBowl . '</td>';
echo '<td data-column="ProBowlYears">' . htmlspecialchars((string)$stat->ProBowlYears) . '</td>';
echo '<td data-column="MVP">' . (int)$stat->MVP . '</td>';
echo '<td data-column="MVPYears">' . htmlspecialchars((string)$stat->MVPYears) . '</td>';
echo '<td data-column="PlayoffMVP">' . (int)$stat->PlayoffMVP . '</td>';
echo '<td data-column="PlayoffMVPYears">' . htmlspecialchars((string)$stat->PlayoffMVPYears) . '</td>';
echo '<td data-column="ROY">' . (int)$stat->ROY . '</td>';
echo '<td data-column="ROYYears">' . htmlspecialchars((string)$stat->ROYYears) . '</td>';
echo '<td data-column="RushAvg">' . (float)$stat->RushAvg . '</td>';
echo '<td data-column="PassAvg">' . (float)$stat->PassAvg . '</td>';
echo '<td data-column="PassPct">' . (float)$stat->PassPct . '</td>';
echo '<td data-column="RecAvg">' . (float)$stat->RecAvg . '</td>';
echo '<td data-column="FGPct">' . (float)$stat->FGPct . '</td>';
echo '<td data-column="XPPct">' . (float)$stat->XPPct . '</td>';
echo '<td data-column="PuntAvg">' . (float)$stat->PuntAvg . '</td>';
echo '<td data-column="PuntReturnAvg">' . (float)$stat->PuntReturnAvg . '</td>';
echo '<td data-column="KickReturnAvg">' . (float)$stat->KickReturnAvg . '</td>';
echo '<td data-column="RushingAttPerGame">' . (float)$stat->RushingAttPerGame . '</td>';
echo '<td data-column="RushingYdsPerGame">' . (float)$stat->RushingYdsPerGame . '</td>';
echo '<td data-column="RushFDPct">' . (float)$stat->RushFDPct . '</td>';
echo '<td data-column="CatchesPerGame">' . (float)$stat->CatchesPerGame . '</td>';
echo '<td data-column="ReceivingYdsPerGame">' . (float)$stat->ReceivingYdsPerGame . '</td>';
echo '<td data-column="RecFDPct">' . (float)$stat->RecFDPct . '</td>';
echo '<td data-column="KickingPoints">' . (int)$stat->KickingPoints . '</td>';
echo '<td data-column="Points">' . (int)$stat->Points . '</td>';
echo '<td data-column="PointsFor">' . (int)$stat->PointsFor . '</td>';
echo '<td data-column="PointsAgainst">' . (int)$stat->PointsAgainst . '</td>';
echo '<td data-column="Rush20">' . (int)$stat->Rush20 . '</td>';
echo '<td data-column="Rush40">' . (int)$stat->Rush40 . '</td>';
echo '<td data-column="RushFD">' . (int)$stat->RushFD . '</td>';
echo '<td data-column="Pass20">' . (int)$stat->Pass20 . '</td>';
echo '<td data-column="Pass40">' . (int)$stat->Pass40 . '</td>';
echo '<td data-column="PassFD">' . (int)$stat->PassFD . '</td>';
echo '<td data-column="Rec20">' . (int)$stat->Rec20 . '</td>';
echo '<td data-column="Rec40">' . (int)$stat->Rec40 . '</td>';
echo '<td data-column="RecFD">' . (int)$stat->RecFD . '</td>';
echo '<td data-column="CtA">' . (int)$stat->CtA . '</td>';
echo '<td data-column="DTar">' . (int)$stat->DTar . '</td>';

echo '</tr>';
}?>
    </tbody>
</table><br><br>

<script>
const columnDefinitions = {
    Team: {
        label: "Team",
        title: "Team"
    },

    FullName: {
        label: "Name",
        title: "Player Name"
    },

    Position: {
        label: "Pos",
        title: "Position"
    },
    
    Age: {
        label: 'Age',
        title: 'Age'
    },

    Experience: {
        label: 'Exp',
        title: 'Experience'
    },

    G: {
        label: 'G',
        title: 'Games'
    },

    PassAtt: {
        label: 'Att',
        title: 'Pass Attempts'
    },

    PassCmp: {
        label: 'Cmp',
        title: 'Pass Completions'
    },

    PassYds: {
        label: 'PassYds',
        title: 'Passing Yards'
    },

    PassTD: {
        label: 'PassTD',
        title: 'Passing Touchdowns'
    },

    PassInt: {
        label: 'PassInt',
        title: 'Passing Interceptions'
    },

    RushAtt: {
        label: 'RushAtt',
        title: 'Rushing Attempts'
    },

    RushYds: {
        label: 'RushYds',
        title: 'Rushing Yards'
    },

    RushTD: {
        label: 'RushTD',
        title: 'Rushing Touchdowns'
    },

    Fumbles: {
        label: 'Fum',
        title: 'Fumbles'
    },

    Catches: {
        label: 'Cat',
        title: 'Catches'
    },

    RecYds: {
        label: 'RecYds',
        title: 'Receiving Yards'
    },

    RecTD: {
        label: 'RecTD',
        title: 'Receiving Touchdowns'
    },

    Tar: {
        label: 'Tar',
        title: 'Targets'
    },
    Tackles: {
        label: 'Tack',
        title: 'Tackles'
    },

    Sacks: {
        label: 'Sck',
        title: 'Sacks'
    },

    Int: {
        label: 'Int',
        title: 'Interceptions'
    },

    DefensiveTD: {
        label: 'DTD',
        title: 'Defensive Touchdowns'
    },

    GS: {
    label: 'GS',
    title: 'Games Started'

    },
    Championships: {
        label: 'Champ',
        title: 'Championships'
    },
    ChampionshipYears: {
        label: 'Champ Yrs',
        title: 'Championship Years'
    },
    MadePlayoffs: {
        label: 'Playoffs',
        title: 'Playoff Appearances'
    },
    Wins: {
        label: 'W',
        title: 'Wins'
    },
    Losses: {
        label: 'L',
        title: 'Losses'
    },
    Ties: {
        label: 'T',
        title: 'Ties'
    },
    WinPct: {
        label: 'Win%',
        title: 'Winning Percentage'
    },
    Round1Wins: {
        label: 'R1 W',
        title: 'Round 1 Wins'
    },
    Round1Losses: {
        label: 'R1 L',
        title: 'Round 1 Losses'
    },
    Rounds1WinPct: {
        label: 'R1 Win%',
        title: 'Round 1 Winning Percentage'
    },
    Round2Wins: {
        label: 'R2 W',
        title: 'Round 2 Wins'
    },
    Round2Losses: {
        label: 'R2 L',
        title: 'Round 2 Losses'
    },
    Round2WinPct: {
        label: 'R2 Win%',
        title: 'Round 2 Winning Percentage'
    },
    Round3Wins: {
        label: 'R3 W',
        title: 'Round 3 Wins'
    },
    Round3Losses: {
        label: 'R3 L',
        title: 'Round 3 Losses'
    },
    Round3WinPct: {
        label: 'R3 Win%',
        title: 'Round 3 Winning Percentage'
    },
    ChampionshipWins: {
        label: 'Champ W',
        title: 'Championship Game Wins'
    },
    ChampionshipLosses: {
        label: 'Champ L',
        title: 'Championship Game Losses'
    },
    ChampionshipWinPct: {
        label: 'Champ Win%',
        title: 'Championship Game Winning Percentage'
    },
    ELORating: {
        label: 'ELO',
        title: 'ELO Rating'
    },
    Plays: {
        label: 'Plays',
        title: 'Total Plays'
    },
    PassYdsAgainst: {
        label: 'Pass Yds A',
        title: 'Passing Yards Allowed'
    },
    TotalYdsAgainst: {
        label: 'Tot Yds A',
        title: 'Total Yards Allowed'
    },
    TotalYds: {
        label: 'Total Yds',
        title: 'Total Yards'
    },
    RushYdsAgainst: {
        label: 'Rush Yds A',
        title: 'Rushing Yards Allowed'
    },
    PassRating: {
        label: 'Rating',
        title: 'Passer Rating'
    },
    PassingAttemptsPerGame: {
        label: 'Pass Att/G',
        title: 'Passing Attempts Per Game'
    },
    PassingYardsPerAttempt: {
        label: 'Pass Y/A',
        title: 'Passing Yards Per Attempt'
    },
    PassingYardsPerCompletion: {
        label: 'Pass Y/C',
        title: 'Passing Yards Per Completion'
    },
    SackPct: {
        label: 'Sack%',
        title: 'Sack Percentage'
    },
    PassingYdsPerGame: {
        label: 'Pass Yds/G',
        title: 'Passing Yards Per Game'
    },
    RunLong: {
        label: 'Rush Long',
        title: 'Longest Rush'
    },
    PassLong: {
        label: 'Pass Long',
        title: 'Longest Pass'
    },
    RecLong: {
        label: 'Rec Long',
        title: 'Longest Reception'
    },
    PuntLong: {
        label: 'Punt Long',
        title: 'Longest Punt'
    },
    KRLong: {
        label: 'KR Long',
        title: 'Longest Kickoff Return'
    },
    PRLong: {
        label: 'PR Long',
        title: 'Longest Punt Return'
    },
    FGLong: {
        label: 'FG Long',
        title: 'Longest Field Goal'
    },
    IntReturnYds: {
        label: 'INT Ret Yds',
        title: 'Interception Return Yards'
    },
    FumblesLost: {
        label: 'Fum Lost',
        title: 'Fumbles Lost'
    },
    FumReturnYds: {
        label: 'Fum Ret Yds',
        title: 'Fumble Return Yards'
    },
    Pancakes: {
        label: 'Pancakes',
        title: 'Pancake Blocks'
    },
    MissedTackles: {
        label: 'Miss Tack',
        title: 'Missed Tackles'
    },
    DroppedPasses: {
        label: 'Drops',
        title: 'Dropped Passes'
    },
    PassesDefensed: {
        label: 'PD',
        title: 'Passes Defensed'
    },
    SacksAllowed: {
        label: 'Sacks All',
        title: 'Sacks Allowed'
    },
    MissedBlocks: {
        label: 'Miss Blocks',
        title: 'Missed Blocks'
    },
    WasSacked: {
        label: 'Sacked',
        title: 'Times Sacked'
    },
    SackedYards: {
        label: 'Sack Yds',
        title: 'Yards Lost to Sacks'
    },
    TFL: {
        label: 'TFL',
        title: 'Tackles for Loss'
    },
    Hurries: {
        label: 'Hurries',
        title: 'Quarterback Hurries'
    },
    Knockdowns: {
        label: 'Knockdowns',
        title: 'Quarterback Knockdowns'
    },
    Safeties: {
        label: 'Safeties',
        title: 'Safeties'
    },
    ForcedFumbles: {
        label: 'FF',
        title: 'Forced Fumbles'
    },
    FumblesRecovered: {
        label: 'FR',
        title: 'Fumbles Recovered'
    },
    BlockedFG: {
        label: 'Blk FG',
        title: 'Blocked Field Goals'
    },
    BlockedPAT: {
        label: 'Blk PAT',
        title: 'Blocked Extra Points'
    },
    BlockedPunt: {
        label: 'Blk Punt',
        title: 'Blocked Punts'
    },
    Penalties: {
        label: 'Pen',
        title: 'Penalties'
    },
    PenaltyYds: {
        label: 'Pen Yds',
        title: 'Penalty Yards'
    },
    Punts: {
        label: 'Punts',
        title: 'Punts'
    },
    PuntYds: {
        label: 'Punt Yds',
        title: 'Punting Yards'
    },
    PuntsInside20: {
        label: 'In 20',
        title: 'Punts Inside the 20'
    },
    PuntReturns: {
        label: 'PR',
        title: 'Punt Returns'
    },
    PuntReturnYds: {
        label: 'PR Yds',
        title: 'Punt Return Yards'
    },
    PuntReturnTD: {
        label: 'PR TD',
        title: 'Punt Return Touchdowns'
    },
    KickoffReturns: {
        label: 'KR',
        title: 'Kickoff Returns'
    },
    KickoffReturnYds: {
        label: 'KR Yds',
        title: 'Kickoff Return Yards'
    },
    KickoffReturnTD: {
        label: 'KR TD',
        title: 'Kickoff Return Touchdowns'
    },
    FGA_U20: {
        label: 'FGA <20',
        title: 'Field Goal Attempts Under 20 Yards'
    },
    FGA_2029: {
        label: 'FGA 20-29',
        title: 'Field Goal Attempts From 20 to 29 Yards'
    },
    FGA_3039: {
        label: 'FGA 30-39',
        title: 'Field Goal Attempts From 30 to 39 Yards'
    },
    FGA_4049: {
        label: 'FGA 40-49',
        title: 'Field Goal Attempts From 40 to 49 Yards'
    },
    FGA_50: {
        label: 'FGA 50+',
        title: 'Field Goal Attempts From 50 or More Yards'
    },
    FGM_U20: {
        label: 'FGM <20',
        title: 'Field Goals Made Under 20 Yards'
    },
    FGM_2029: {
        label: 'FGM 20-29',
        title: 'Field Goals Made From 20 to 29 Yards'
    },
    FGM_3039: {
        label: 'FGM 30-39',
        title: 'Field Goals Made From 30 to 39 Yards'
    },
    FGM_4049: {
        label: 'FGM 40-49',
        title: 'Field Goals Made From 40 to 49 Yards'
    },
    FGM_50: {
        label: 'FGM 50+',
        title: 'Field Goals Made From 50 or More Yards'
    },
    FGA: {
        label: 'FGA',
        title: 'Field Goal Attempts'
    },
    FGM: {
        label: 'FGM',
        title: 'Field Goals Made'
    },
    XPA: {
        label: 'XPA',
        title: 'Extra Point Attempts'
    },
    XPM: {
        label: 'XPM',
        title: 'Extra Points Made'
    },
    POG: {
        label: 'POG',
        title: 'Player of the Game Awards'
    },
    POW: {
        label: 'POW',
        title: 'Player of the Week Awards'
    },
    POY: {
        label: 'POY',
        title: 'Player of the Year Awards'
    },
    POYYears: {
        label: 'POY Yrs',
        title: 'Player of the Year Award Years'
    },
    ProBowl: {
        label: 'Pro Bowl',
        title: 'Pro Bowl Selections'
    },
    ProBowlYears: {
        label: 'PB Yrs',
        title: 'Pro Bowl Selection Years'
    },
    MVP: {
        label: 'MVP',
        title: 'Most Valuable Player Awards'
    },
    MVPYears: {
        label: 'MVP Yrs',
        title: 'Most Valuable Player Award Years'
    },
    PlayoffMVP: {
        label: 'Playoff MVP',
        title: 'Playoff Most Valuable Player Awards'
    },
    PlayoffMVPYears: {
        label: 'PMVP Yrs',
        title: 'Playoff Most Valuable Player Award Years'
    },
    ROY: {
        label: 'ROY',
        title: 'Rookie of the Year Awards'
    },
    ROYYears: {
        label: 'ROY Yrs',
        title: 'Rookie of the Year Award Years'
    },
    RushAvg: {
        label: 'Rush Avg',
        title: 'Rushing Yards Per Attempt'
    },
    PassAvg: {
        label: 'Pass Avg',
        title: 'Passing Yards Per Attempt'
    },
    PassPct: {
        label: 'Cmp%',
        title: 'Pass Completion Percentage'
    },
    RecAvg: {
        label: 'Rec Avg',
        title: 'Receiving Yards Per Reception'
    },
    FGPct: {
        label: 'FG%',
        title: 'Field Goal Percentage'
    },
    XPPct: {
        label: 'XP%',
        title: 'Extra Point Percentage'
    },
    PuntAvg: {
        label: 'Punt Avg',
        title: 'Punting Average'
    },
    PuntReturnAvg: {
        label: 'PR Avg',
        title: 'Punt Return Average'
    },
    KickReturnAvg: {
        label: 'KR Avg',
        title: 'Kickoff Return Average'
    },
    RushingAttPerGame: {
        label: 'Rush Att/G',
        title: 'Rushing Attempts Per Game'
    },
    RushingYdsPerGame: {
        label: 'Rush Yds/G',
        title: 'Rushing Yards Per Game'
    },
    RushFDPct: {
        label: 'Rush FD%',
        title: 'Rushing First Down Percentage'
    },
    CatchesPerGame: {
        label: 'Rec/G',
        title: 'Receptions Per Game'
    },
    ReceivingYdsPerGame: {
        label: 'Rec Yds/G',
        title: 'Receiving Yards Per Game'
    },
    RecFDPct: {
        label: 'Rec FD%',
        title: 'Receiving First Down Percentage'
    },
    KickingPoints: {
        label: 'Kick Pts',
        title: 'Kicking Points'
    },
    Points: {
        label: 'Pts',
        title: 'Points Scored'
    },
    PointsFor: {
        label: 'PF',
        title: 'Points For'
    },
    PointsAgainst: {
        label: 'PA',
        title: 'Points Against'
    },
    Rush20: {
        label: 'Rush 20+',
        title: 'Rushing Plays of 20 or More Yards'
    },
    Rush40: {
        label: 'Rush 40+',
        title: 'Rushing Plays of 40 or More Yards'
    },
    RushFD: {
        label: 'Rush FD',
        title: 'Rushing First Downs'
    },
    Pass20: {
        label: 'Pass 20+',
        title: 'Passing Plays of 20 or More Yards'
    },
    Pass40: {
        label: 'Pass 40+',
        title: 'Passing Plays of 40 or More Yards'
    },
    PassFD: {
        label: 'Pass FD',
        title: 'Passing First Downs'
    },
    Rec20: {
        label: 'Rec 20+',
        title: 'Receptions of 20 or More Yards'
    },
    Rec40: {
        label: 'Rec 40+',
        title: 'Receptions of 40 or More Yards'
    },
    RecFD: {
        label: 'Rec FD',
        title: 'Receiving First Downs'
    },

    CtA: {
        label: 'CtA',
        title: 'Catches Allowed'
    },
    DTar: {
        label: 'D Tar',
        title: 'Defensive Targets'
    }




};
let currentView =
    <?= json_encode($selectedView) ?>;

const viewColumns = {
    awards: [
        'G',
        'GS',
        'POG',
        'POW',
        'POY',
        'POYYears',
        'ProBowl',
        'ProBowlYears',
        'MVP',
        'MVPYears',
        'PlayoffMVP',
        'PlayoffMVPYears',
        'ROY',
        'ROYYears',
        'Championships',
        'ChampionshipYears'
    ],

    team: [
        'G',
        'GS',
        'Wins',
        'Losses',
        'Ties',
        'WinPct',
        'MadePlayoffs',
        'Round1Wins',
        'Round1Losses',
        'Rounds1WinPct',
        'Round2Wins',
        'Round2Losses',
        'Round2WinPct',
        'Round3Wins',
        'Round3Losses',
        'Round3WinPct',
        'ChampionshipWins',
        'ChampionshipLosses',
        'ChampionshipWinPct',
        'ELORating'
    ]
};


const defaultStatus = <?= json_encode($defaultStatus) ?>;
const curYear = <?= json_encode((int)$year) ?>;
const lastYear = curYear + 5;
for (let year = curYear; year <= lastYear; year++) {
    columnDefinitions[`1987${year}`] = {
        label: String(year),
        title: `${year} 1987`
    };
}

const lockedColumns = [
    'Team',
    'FullName',
    'Position',
];

const baseColumns = [
    'Team',
    'FullName',
    'Position',
    'Age',
    'Experience',
    'G',
    'GS',
    'Plays'
];

const offensiveLine = [
        'Pancakes',
        'SacksAllowed',
        'MissedBlocks',
        'Penalties',
        'PenaltyYds'
];

const defensiveLine = [
        'Tackles',
        'Sacks',
        'Int',
        'DefensiveTD',
        'TFL',
        'Hurries',
        'Knockdowns',
        'Safeties',
        'ForcedFumbles',
        'FumblesRecovered',
        'FumReturnYds',
        'MissedTackles',
        'PassesDefensed',
        'IntReturnYds',
        'CtA',
        'DTar'
];

const secondary = [
        'Tackles',
        'Sacks',
        'Int',
        'DefensiveTD',
        'TFL',
        'Hurries',
        'Knockdowns',
        'Safeties',
        'ForcedFumbles',
        'FumblesRecovered',
        'FumReturnYds',
        'MissedTackles',
        'PassesDefensed',
        'IntReturnYds',
        'CtA',
        'DTar'
];

const skill = [
        'RushAtt',
        'RushYds',
        'RushTD',
        'RushAvg',
        'RushingAttPerGame',
        'RushingYdsPerGame',
        'RushFDPct',
        'Rush20',
        'Rush40',
        'RushFD',
        'RunLong',
        'Catches',
        'RecYds',
        'RecTD',
        'Tar',
        'RecAvg',
        'CatchesPerGame',
        'ReceivingYdsPerGame',
        'RecFDPct',
        'Rec20',
        'Rec40',
        'RecFD',
        'RecLong',
        'DroppedPasses',
        'Fumbles',
        'FumblesLost'
];

const kickers = [
    'Punts',
    'PuntYds',
    'PuntAvg',
    'PuntsInside20',
    'PuntLong',
    'FGA',
    'FGM',
    'FGPct',
    'XPA',
    'XPM',
    'XPPct',
    'FGLong',
    'KickingPoints',
    'FGA_U20',
    'FGM_U20',
    'FGA_2029',
    'FGM_2029',
    'FGA_3039',
    'FGM_3039',
    'FGA_4049',
    'FGM_4049',
    'FGA_50',
    'FGM_50'

];


const specialTeams = [
    'PuntReturns',
    'PuntReturnYds',
    'PuntReturnAvg',
    'PuntReturnTD',
    'PRLong',
    'KickoffReturns',
    'KickoffReturnYds',
    'KickReturnAvg',
    'KickoffReturnTD',
    'KRLong',
    'BlockedPunt',
    'BlockedFG',
    'BlockedPAT',
    'Tackles',
    'ForcedFumbles',
    'FumblesRecovered'
];

const positionGroups = {
    OL: ['C', 'G', 'T'],
    DL: ['DE', 'DT', 'LB'],
    DB: ['CB', 'FS', 'SS'],
    SK: ['RB', 'WR', 'FB', 'TE']
};

const positionColumns = {
    C: offensiveLine,
    G: offensiveLine,
    T: offensiveLine,

    DE: defensiveLine,
    DT: defensiveLine,
    LB: defensiveLine,   

    CB: secondary,
    FS: secondary,
    SS: secondary,

    TE: skill,
    FB: skill,
    WR: skill,
    RB: skill,

    K: kickers,
    P: kickers,

    ST: specialTeams,

    ALL: [
        'Age',
        'Experience',
        'G',
        'GS',
        'PassAtt',
        'PassCmp',
        'PassYds',
        'PassTD',
        'PassInt',
        'RushAtt',
        'RushYds',
        'RushTD',
        'Fumbles',
        'Catches',
        'RecYds',
        'RecTD',
        'Tar',
        'Tackles',
        'Sacks',
        'Int',
        'DefensiveTD'
    ],

    QB: [
        'PassAtt',
        'PassCmp',
        'PassYds',
        'PassTD',
        'PassInt',
        'PassRating',
        'PassingAttemptsPerGame',
        'PassingYardsPerAttempt',
        'PassingYardsPerCompletion',
        'SackPct',
        'PassingYdsPerGame',
        'PassAvg',
        'PassPct',
        'Pass20',
        'Pass40',
        'PassFD',
        'WasSacked',
        'SackedYards',
        'PassLong'
    ],

    SK: [
        'RushAtt',
        'RushYds',
        'RushTD',
        'RushAvg',
        'RushingAttPerGame',
        'RushingYdsPerGame',
        'RushFDPct',
        'Rush20',
        'Rush40',
        'RushFD',
        'RunLong',
        'Catches',
        'RecYds',
        'RecTD',
        'Tar',
        'RecAvg',
        'CatchesPerGame',
        'ReceivingYdsPerGame',
        'RecFDPct',
        'Rec20',
        'Rec40',
        'RecFD',
        'RecLong',
        'DroppedPasses',
        'Fumbles',
        'FumblesLost'
    ],
    OL:  [
        'Pancakes',
        'SacksAllowed',
        'MissedBlocks',
        'Penalties',
        'PenaltyYds'
    ],

    DL: [
        'Tackles',
        'Sacks',
        'Int',
        'DefensiveTD',
        'TFL',
        'Hurries',
        'Knockdowns',
        'Safeties',
        'ForcedFumbles',
        'FumblesRecovered',
        'FumReturnYds',
        'MissedTackles',
        'PassesDefensed',
        'IntReturnYds',
        'CtA',
        'DTar'
    ],

    DB: [
        'Tackles',
        'Sacks',
        'Int',
        'DefensiveTD',
        'TFL',
        'Hurries',
        'Knockdowns',
        'Safeties',
        'ForcedFumbles',
        'FumblesRecovered',
        'FumReturnYds',
        'MissedTackles',
        'PassesDefensed',
        'IntReturnYds',
        'CtA',
        'DTar'
    ],
};

const columnOrder = [
    'Team',
    'FullName',
    'Position',
    'Age',
    'Experience',
    'G',
    'GS',
    'Plays',
    'PassAtt',
    'PassCmp',
    'PassYds',
    'PassTD',
    'PassInt',
    'RushAtt',
    'RushYds',
    'RushTD',
    'RushAvg',
    'Fumbles',
    'Catches',
    'RecYds',
    'RecTD',
    'Tar',
    'Tackles',
    'Sacks',
    'Int',
    'DefensiveTD',
    'PassRating',
    'PassingAttemptsPerGame',
    'PassingYardsPerAttempt',
    'PassingYardsPerCompletion',
    'SackPct',
    'PassingYdsPerGame',
    'PassAvg',
    'PassPct',
    'Pass20',
    'Pass40',
    'PassFD',
    'WasSacked',
    'SackedYards',
    'PassLong',
    'RushingAttPerGame',
    'RushingYdsPerGame',
    'RushFDPct',
    'Rush20',
    'Rush40',
    'RushFD',
    'RunLong',
    'RecAvg',
    'CatchesPerGame',
    'ReceivingYdsPerGame',
    'RecFDPct',
    'Rec20',
    'Rec40',
    'RecFD',
    'RecLong',
    'DroppedPasses',
    'FumblesLost',
    'Pancakes',
    'SacksAllowed',
    'MissedBlocks',
    'Penalties',
    'PenaltyYds',
    'TFL',
    'Hurries',
    'Knockdowns',
    'Safeties',
    'ForcedFumbles',
    'FumblesRecovered',
    'FumReturnYds',
    'MissedTackles',
    'PassesDefensed',
    'IntReturnYds',
    'CtA',
    'DTar',
    'Punts',
    'PuntYds',
    'PuntAvg',
    'PuntsInside20',
    'PuntLong',
    'FGA',
    'FGM',
    'FGPct',
    'XPA',
    'XPM',
    'XPPct',
    'FGLong',
    'KickingPoints',
    'FGA_U20',
    'FGM_U20',
    'FGA_2029',
    'FGM_2029',
    'FGA_3039',
    'FGM_3039',
    'FGA_4049',
    'FGM_4049',
    'FGA_50',
    'FGM_50',
    'PuntReturns',
    'PuntReturnYds',
    'PuntReturnAvg',
    'PuntReturnTD',
    'PRLong',
    'KickoffReturns',
    'KickoffReturnYds',
    'KickReturnAvg',
    'KickoffReturnTD',
    'KRLong',
    'BlockedPunt',
    'BlockedFG',
    'BlockedPAT',
    'Wins',
    'Losses',
    'Ties',
    'WinPct',
    'MadePlayoffs',
    'Round1Wins',
    'Round1Losses',
    'Rounds1WinPct',
    'Round2Wins',
    'Round2Losses',
    'Round2WinPct',
    'Round3Wins',
    'Round3Losses',
    'Round3WinPct',
    'ChampionshipWins',
    'ChampionshipLosses',
    'ChampionshipWinPct',
    'ELORating',
    'POG',
    'POW',
    'POY',
    'POYYears',
    'ProBowl',
    'ProBowlYears',
    'MVP',
    'MVPYears',
    'PlayoffMVP',
    'PlayoffMVPYears',
    'ROY',
    'ROYYears',
    'Championships',
    'ChampionshipYears'

];

let selectedColumns = new Set();
const availableSeasons =
    <?= json_encode($availableSeasons) ?>;

const currentDataGroup =
    <?= json_encode($selectedGroup) ?>;

function buildHeaders() {
    const headerRow =
        document.getElementById('playerTableHeader');

    headerRow.innerHTML = '';

    getSelectedColumns().forEach(function (columnName) {
        const column = columnDefinitions[columnName];

        if (!column) {
            console.warn(
                'Missing column definition:',
                columnName
            );

            return;
        }

        const header =
            document.createElement('th');

        header.dataset.column = columnName;
        header.textContent = column.label;
        header.title = column.title;

        headerRow.appendChild(header);
    });
}

function updateVisibleCells() {
    const visibleColumns = getSelectedColumns();

    document
        .querySelectorAll('#playersTable tbody tr')
        .forEach(function (row) {
            const cells = new Map();

            row
                .querySelectorAll('td[data-column]')
                .forEach(function (cell) {
                    cells.set(
                        cell.dataset.column,
                        cell
                    );

                    cell.classList.add(
                        'column-hidden'
                    );
                });

            visibleColumns.forEach(
                function (columnName) {
                    const cell =
                        cells.get(columnName);

                    if (!cell) {
                        console.warn(
                            'Missing table cell:',
                            columnName
                        );

                        return;
                    }

                    cell.classList.remove(
                        'column-hidden'
                    );

                    /*
                     * Appending an existing cell
                     * moves it into the correct order.
                     */
                    row.appendChild(cell);
                }
            );
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('playerFilters');
    const table = document.getElementById('playersTable');

    if (!form || !table) {
        return;
    }

    let currentSortColumn = 'Plays';
    let currentSortDirection = 'desc';

    function updateRowStriping() {
    const visibleRows = rows.filter(function (row) {
        return !row.classList.contains('player-row-filtered');
    });

    rows.forEach(function (row) {
        row.classList.remove('row-odd', 'row-even');
    });

    visibleRows.forEach(function (row, index) {
        row.classList.add(
            index % 2 === 0
                ? 'row-odd'
                : 'row-even'
        );
    });
}

    table.addEventListener('click', function (event) {
        const header = event.target.closest('th[data-column]');

        if (!header) {
            return;
        }

        const columnName = header.dataset.column;

        if (!columnName) {
            return;
        }

        if (currentSortColumn === columnName) {
            currentSortDirection =
                currentSortDirection === 'asc'
                    ? 'desc'
                    : 'asc';
        } else {
            currentSortColumn = columnName;
            currentSortDirection =
            ['FullName', 'Team', 'Position'].includes(columnName)
                ? 'asc'
                : 'desc';
        }

        sortPlayerRows(currentSortColumn,currentSortDirection);

        updateSortIndicators();
        updateRowStriping();
    });

    function updateSortIndicators() {
        table.querySelectorAll('th[data-column]').forEach(function (header) {
        header.classList.remove('sort-asc', 'sort-desc');

        if (header.dataset.column === currentSortColumn) {
            header.classList.add(
                currentSortDirection === 'asc'
                    ? 'sort-asc'
                    : 'sort-desc'
                );
            }
        });
    }

    const searchInput =
        document.getElementById('playerSearch');

    const teamSelect =
        document.getElementById('teamFilter');

    const positionSelect =
        document.getElementById('positionFilter');

    const clearButton =
        document.getElementById('clearPlayerFilters');

    const countElement =
        document.getElementById('visiblePlayerCount');

    const rows = Array.from(
        table.querySelectorAll('tr[data-player-row]'));

    const groupLinks = 
        document.querySelectorAll('.playerGroupLink[data-group]');

    const columnButton =
        document.getElementById('playerColumnButton');

    const columnMenu =
        document.getElementById('playerColumnMenu');

    const columnOptions =
        document.getElementById('playerColumnOptions');

    const resetColumnsButton =
        document.getElementById('resetPlayerColumns');

    const clearColumnsButton =
        document.getElementById('clearPlayerColumns');

    const statusSelect =
        document.getElementById('statusFilter');

    const viewSelect =
        document.getElementById('viewFilter');

    function updateUrl() {
        const url = new URL(window.location.href);

        const searchValue = searchInput.value.trim();
        const teamValue = teamSelect
            ? teamSelect.value
            : '';

        const positionValue = positionSelect.value;
        const statusValue = statusSelect.value;
        const viewValue = viewSelect.value;

        if (viewValue && viewValue !== 'stats') {
            url.searchParams.set('view', viewValue);
        } else {
            url.searchParams.delete('view');
        }

        if (searchValue) {
            url.searchParams.set('q', searchValue);
        } else {
            url.searchParams.delete('q');
        }

        if (teamValue) {
            url.searchParams.set(
                'filter_team',
                teamValue
            );
        } else {
            url.searchParams.delete('filter_team');
        }

        if (
            positionValue &&
            positionValue !== 'all'
        ) {
            url.searchParams.set(
                'pos',
                positionValue
            );
        } else {
            url.searchParams.delete('pos');
        }

        if (
            statusValue &&
            statusValue !== 'current'
        ) {
            url.searchParams.set(
                'status',
                statusValue
            );
        } else {
            url.searchParams.delete('status');
        }

        window.history.replaceState(
            {},
            '',
            url.toString()
        );
    }

    function applyFilters() {
        const searchValue =
            searchInput.value
                .trim()
                .toLowerCase();

        const teamValue = teamSelect
            ? teamSelect.value
            : '';

        const positionValue =
            positionSelect.value.toUpperCase();

        const statusValue = statusSelect.value;

        let visibleCount = 0;

        rows.forEach(function (row) {
            const playerName =
                row.dataset.playerName || '';

            const teamId =
                row.dataset.teamId || '';

            const position =
                row.dataset.position || '';

            const matchesSearch =
                !searchValue ||
                playerName.includes(searchValue);

            const matchesTeam =
                !teamValue ||
                teamId === teamValue;

            const groupedPositions =
                positionGroups[positionValue] || [];

            const matchesPosition =
                positionValue === 'ALL' ||
                (
                    positionValue === 'ST' &&
                    row.dataset.specialTeams === '1'
                ) ||
                position === positionValue ||
                groupedPositions.includes(position);
                
            const statusMatches = {
                current:
                    row.dataset.active === '1' ||
                    row.dataset.freeAgent === '1',

                active:
                    row.dataset.active === '1',

                freeagents:
                    row.dataset.freeAgent === '1',

                draft:
                    row.dataset.draftClass === '1',

                injured:
                    row.dataset.injured === '1',

                retired:
                    row.dataset.retired === '1',

                all: true
            };

            const matchesStatus =
                statusMatches[statusValue] ?? false;

            const isVisible =
                matchesSearch &&
                matchesTeam &&
                matchesPosition &&
                matchesStatus;

            row.classList.toggle(
                'player-row-filtered',
                !isVisible
            );

            if (isVisible) {
                visibleCount++;
            }
        });

        countElement.textContent = visibleCount;

        updateRowStriping();
        updateUrl();
    }

    function sortPlayerRows(columnName, direction) {
        const tbody = table.querySelector('tbody') || table;

        const sortedRows = [...rows].sort(function (rowA, rowB) {
            const cellA = rowA.querySelector(
                `td[data-column="${columnName}"]`
            );

            const cellB = rowB.querySelector(
                `td[data-column="${columnName}"]`
            );

            const valueA = cellA
                ? cellA.textContent.trim()
                : '';

            const valueB = cellB
                ? cellB.textContent.trim()
                : '';

            const isBlankA = valueA === '';
            const isBlankB = valueB === '';

            if (isBlankA && isBlankB) {
                return 0;
            }

            if (isBlankA) {
                return 1;
            }

            if (isBlankB) {
                return -1;
            }

            const numericValueA = valueA.replace(/[$,\s]/g, '');
            const numericValueB = valueB.replace(/[$,\s]/g, '');

            const numberA = Number(numericValueA);
            const numberB = Number(numericValueB);

            let comparison;

            if (
                valueA !== '' &&
                valueB !== '' &&
                !Number.isNaN(numberA) &&
                !Number.isNaN(numberB)
            ) {
                comparison = numberA - numberB;
            } else {
                comparison = valueA.localeCompare(
                    valueB,
                    undefined,
                    {
                        numeric: true,
                        sensitivity: 'base'
                    }
                );
            }

            return direction === 'asc'
                ? comparison
                : -comparison;
        });

        sortedRows.forEach(function (row) {
            tbody.appendChild(row);
        });
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        applyFilters();
    });

    searchInput.addEventListener(
        'input',
        applyFilters
    );

    if (teamSelect) {
        teamSelect.addEventListener(
            'change',
            applyFilters
        );
    }

    positionSelect.addEventListener('change', function () {
        resetSelectedColumns(positionSelect.value);

        buildHeaders();
        updateVisibleCells();
        updateSortIndicators();
        buildColumnOptions();
        applyFilters();
    });

    statusSelect.addEventListener(
        'change',
        applyFilters
    );

    groupLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const url = new URL(window.location.href);

            url.searchParams.set(
                'group',
                link.dataset.group
            );

            window.location.href = url.toString();
        });
    });

    viewSelect.addEventListener('change', function () {
        currentView = viewSelect.value;

        resetSelectedColumns(
            positionSelect.value
        );

        buildHeaders();
        updateVisibleCells();
        updateSortIndicators();
        buildColumnOptions();
        applyFilters();

        updateUrl();
    });

    clearButton.addEventListener('click', function () {
        searchInput.value = '';

        if (teamSelect) {
            teamSelect.value = '';
        }

        positionSelect.value = 'all';

        statusSelect.value = defaultStatus;

        resetSelectedColumns(
            positionSelect.value
        );

        buildHeaders();
        updateVisibleCells();
        updateSortIndicators();
        buildColumnOptions();
        applyFilters();

        searchInput.focus();
    });

function buildColumnOptions() {
    columnOptions.innerHTML = '';

    let selectableColumns;

    if (currentView === 'stats') {
        const position =
            positionSelect.value.toUpperCase();

        selectableColumns =
            positionColumns[position] ||
            positionColumns.ALL;
    } else {
        selectableColumns =
            viewColumns[currentView] || [];
    }

    const availableColumns = new Set([
        ...baseColumns,
        ...selectableColumns,
    ]);

    Object.entries(columnDefinitions)
        .filter(function ([columnName]) {
            return availableColumns.has(columnName);
        })
        .forEach(function ([columnName, definition]) {
            const label =
                document.createElement('label');

            label.className =
                'player-column-option';

            const checkbox =
                document.createElement('input');

            checkbox.type = 'checkbox';
            checkbox.value = columnName;
            checkbox.checked =
                selectedColumns.has(columnName);

            const isLocked =
                lockedColumns.includes(columnName);

            checkbox.disabled = isLocked;

            checkbox.addEventListener(
                'change',
                function () {
                    if (checkbox.checked) {
                        selectedColumns.add(columnName);
                    } else {
                        selectedColumns.delete(columnName);
                    }

                    lockedColumns.forEach(
                        function (lockedColumn) {
                            selectedColumns.add(
                                lockedColumn
                            );
                        }
                    );

                    if (
                        !selectedColumns.has(
                            currentSortColumn
                        )
                    ) {
                        currentSortColumn = 'FullName';
                        currentSortDirection = 'asc';

                        sortPlayerRows(
                            currentSortColumn,
                            currentSortDirection
                        );
                    }

                    buildHeaders();
                    updateVisibleCells();
                    updateSortIndicators();
                    buildColumnOptions();
                }
            );

            const text =
                document.createElement('span');

            text.textContent =
                definition.title;

            label.appendChild(checkbox);
            label.appendChild(text);

            columnOptions.appendChild(label);
        });
}

columnButton.addEventListener(
    'click',
    function () {
        const isOpen = !columnMenu.hidden;

        columnMenu.hidden = isOpen;

        columnButton.setAttribute(
            'aria-expanded',
            String(!isOpen)
        );
    }
);

resetColumnsButton.addEventListener(
    'click',
    function () {
        resetSelectedColumns(
            positionSelect.value
        );

        buildHeaders();
        updateVisibleCells();
        updateSortIndicators();
        buildColumnOptions();
    }
);

clearColumnsButton.addEventListener(
    'click',
    function () {
        selectedColumns = new Set(lockedColumns);

        if (
            !selectedColumns.has(
                currentSortColumn
            )
        ) {
            currentSortColumn = 'FullName';
            currentSortDirection = 'asc';

            sortPlayerRows(
                currentSortColumn,
                currentSortDirection
            );
        }

        buildHeaders();
        updateVisibleCells();
        updateSortIndicators();
        buildColumnOptions();
        updateRowStriping();
    }
);

document.addEventListener(
    'click',
    function (event) {
        if (
            !event.target.closest(
                '.player-column-picker'
            )
        ) {
            columnMenu.hidden = true;

            columnButton.setAttribute(
                'aria-expanded',
                'false'
            );
        }
    }
);

    resetSelectedColumns(
    positionSelect.value
);

    buildHeaders();
    updateVisibleCells();
    updateSortIndicators();
    buildColumnOptions();
    applyFilters();
    table.classList.remove('player-table-loading');
});

function getDefaultColumns(selectedPosition) {
    if (currentView !== 'stats') {
        return [
            ...new Set([
                ...baseColumns,
                ...viewColumns[currentView]
            ])
        ];
    }

    const position = selectedPosition.toUpperCase();

    const positionSpecificColumns =
        positionColumns[position] ||
        positionColumns.ALL;

    return [
        ...new Set([
            ...baseColumns,
            ...positionSpecificColumns
        ])
    ];
}

function getSelectedColumns() {
    return [...new Set(columnOrder)].filter(function (columnName) {
        return selectedColumns.has(columnName);
    });
}

function resetSelectedColumns(selectedPosition) {
    selectedColumns = new Set(
        getDefaultColumns(selectedPosition)
    );
}



</script>

