<?php 

include 'header.php';

use App\Services\PlayerService;

$PlayerService = new PlayerService();

$players = $PlayerService->getAll();
use App\Services\ContractService;
echo '<pre>';

try {
    $contractService = new ContractService();

    print_r(
        $contractService->getTeamCapTotal(3, 1991)
    );

    print_r(
        $contractService->getTeamCompliance(3, 1991)
    );
} catch (\Throwable $error) {
    echo $error->getMessage();
    echo "\n\n";
    echo $error->getTraceAsString();
}

echo '</pre>';

/* FILTERS */
$rosterTeamId = filter_input(INPUT_GET,'team',FILTER_VALIDATE_INT) ?: 0;
$selectedTeamId = filter_input(INPUT_GET,'filter_team',FILTER_VALIDATE_INT) ?: 0;
$selectedPosition = strtoupper(trim($_GET['pos'] ?? 'all'));
$allowedGroups = [
    'attributes',
    'personality',
    'salary',
    'skills',
    'traits',
    'biography',
    'extradata'
];

$groupLabels = [
    'attributes'  => 'Attributes',
    'personality' => 'Personality',
    'salary'      => 'Salary',
    'skills'      => 'Skills',
    'traits'      => 'Traits',
    'biography'   => 'Biography',
    'extradata'   => 'Extra Data',
];

$selectedGroup = strtolower(
    trim($_GET['group'] ?? 'attributes')
);

if (!in_array($selectedGroup, $allowedGroups, true)) {
    $selectedGroup = 'attributes';
}
$searchQuery = trim($_GET['q'] ?? '');

$allowedStatuses = [
    'current',
    'active',
    'freeagents',
    'draft',
    'injured',
    'retired',
    'all'
];

$selectedStatus = strtolower(
    trim($_GET['status'] ?? 'current')
);

if (!in_array($selectedStatus, $allowedStatuses, true)) {
    $selectedStatus = 'current';
}

/* DROPDOWN DATA */
$teamOptions = [];

foreach ($players as $player) {
    $teamId = (int) $player->TeamID;

    if (
        (int) $player->ProRetire !== 0 ||
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
            action="allplayers.php"
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
        <?= htmlspecialchars($groupLabels[$selectedGroup], ENT_QUOTES,'UTF-8') ?>
    </h3>
<a
    href="allplayers.php?group=attributes"
    class="playerGroupLink<?= $selectedGroup === 'attributes' ? ' active' : '' ?>"
    data-group="attributes"
>
        Attributes
    </a>
    -
    <a
        href="allplayers.php?group=personality"
        class="playerGroupLink<?= $selectedGroup === 'personality' ? ' active' : '' ?>"
        data-group="personality"
    >
        Personality
    </a>
    -
    <a
        href="allplayers.php?group=salary"
        class="playerGroupLink<?= $selectedGroup === 'salary' ? ' active' : '' ?>"
        data-group="salary"
    >
        Salary
    </a>
    -
    <a
        href="allplayers.php?group=skills"
        class="playerGroupLink<?= $selectedGroup === 'skills' ? ' active' : '' ?>"
        data-group="skills"
    >
        Skills
    </a>
    -
    <a
        href="allplayers.php?group=traits"
        class="playerGroupLink<?= $selectedGroup === 'traits' ? ' active' : '' ?>"
        data-group="traits"
    >
        Traits
    </a>
    -
    <a
        href="allplayers.php?group=biography"
        class="playerGroupLink<?= $selectedGroup === 'biography' ? ' active' : '' ?>"
        data-group="biography"
    >
        Biography
    </a>
    -
    <a
        href="allplayers.php?group=extradata"
        class="playerGroupLink<?= $selectedGroup === 'extradata' ? ' active' : '' ?>"
        data-group="extradata"
    >
        Extra Data
    </a>
    <br><br>


<?php
function renderHeader($column) {
    echo '<th data-column="'. htmlspecialchars($column,ENT_QUOTES,'UTF-8') . '" 
    title="' . htmlspecialchars($column,ENT_QUOTES,'UTF-8') . '">'
        . htmlspecialchars($column,ENT_QUOTES,'UTF-8')
        . '</th>';
}
?>
<table class="player-table player-table-loading" id="playersTable">
    <thead>
        <tr id="playerTableHeader">
            <!-- Javascript handles this! -->
        </tr>
    </thead>
    <tbody>

<?php 
usort($players, fn($a, $b) => $b->Overall <=> $a->Overall);

foreach ($players as $player) {
    $teamAbbrev = idToAbbrev($player->TeamID);
    $primaryColor = '#' . idConvert('primeColor', $player->TeamID);
    $secondaryColor = '#' . idConvert('secondaryColor', $player->TeamID);

    if (
        $rosterTeamId &&
        (int) $player->TeamID !== $rosterTeamId
    ) {
        continue;
    }

    $teamAbbrev = idToAbbrev($player->TeamID);

    $teamId = (int)$player->TeamID;

    $isRetired =
        (int)$player->ProRetire !== 0;

    $isDraftClass =
        (int)$player->DraftSeason === ((int)$year + 1);

    $isInjured =
        trim((string)($player->Injury ?? '')) !== '';

    $isActive =
        $teamId !== 0 &&
        !$isRetired &&
        !$isDraftClass;

    $isFreeAgent =
        $teamId === 0 &&
        !$isRetired &&
        !$isDraftClass;

    echo '<tr data-player-row data-player-name="'
        . htmlspecialchars(strtolower($player->FullName),ENT_QUOTES,'UTF-8') . '"'
        . ' data-team-id="' . (int) $player->TeamID . '"' . ' data-team="'
        . htmlspecialchars(strtolower($teamAbbrev),ENT_QUOTES,'UTF-8'). '"'
        . ' data-position="'. htmlspecialchars(strtoupper($player->Position),ENT_QUOTES,'UTF-8') . '"'
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
        <a href="/ptf/player.php?player=' . (int)$player->PlayerID . '">'
            . htmlspecialchars($player->FullName,ENT_QUOTES,'UTF-8') .
        '</a>
    </td>';

echo '<td data-column="Position">' . htmlspecialchars($player->Position,ENT_QUOTES,'UTF-8') . '</td>';
echo '<td data-column="Age">' . (int)$player->Age . '</td>';
echo '<td data-column="Experience">' . (int)$player->Experience . '</td>';
echo '<td data-column="Overall"><b>' . (int)$player->Overall . '</b></td>';
echo '<td data-column="Intelligence">' . (int)$player->Intelligence . '</td>';
echo '<td data-column="Strength">' . (int)$player->Strength . '</td>';
echo '<td data-column="Agility">' . (int)$player->Agility . '</td>';
echo '<td data-column="Arm">' . (int)$player->Arm . '</td>';
echo '<td data-column="Speed">' . (int)$player->Speed . '</td>';
echo '<td data-column="Hands">' . (int)$player->Hands . '</td>';
echo '<td data-column="Accuracy">' . (int)$player->Accuracy . '</td>';
echo '<td data-column="RunBlocking">' . (int)$player->RunBlocking . '</td>';
echo '<td data-column="PassBlocking">' . (int)$player->PassBlocking . '</td>';
echo '<td data-column="Tackling">' . (int)$player->Tackling . '</td>';
echo '<td data-column="Endurance">' . (int)$player->Endurance . '</td>';
echo '<td data-column="KickDistance">' . (int)$player->KickDistance . '</td>';
echo '<td data-column="KickAccuracy">' . (int)$player->KickAccuracy . '</td>';
echo '<td data-column="Leadership">' . (int)$player->Leadership . '</td>';
echo '<td data-column="WorkEthic">' . (int)$player->WorkEthic . '</td>';
echo '<td data-column="Competitiveness">' . (int)$player->Competitiveness . '</td>';
echo '<td data-column="TeamPlayer">' . (int)$player->TeamPlayer . '</td>';
echo '<td data-column="Sportsmanship">' . (int)$player->Sportsmanship . '</td>';
echo '<td data-column="SocialDisposition">' . (int)$player->SocialDisposition . '</td>';
echo '<td data-column="Money">' . (int)$player->Money . '</td>';
echo '<td data-column="Security">' . (int)$player->Security . '</td>';
echo '<td data-column="Loyalty">' . (int)$player->Loyalty . '</td>';
echo '<td data-column="Winning">' . (int)$player->Winning . '</td>';
echo '<td data-column="PlayingTime">' . (int)$player->PlayingTime . '</td>';
echo '<td data-column="CloseToHome">' . (int)$player->CloseToHome . '</td>';
echo '<td data-column="MarketSize">' . (int)$player->MarketSize . '</td>';
echo '<td data-column="Morale">' . (int)$player->Morale . '</td>';
echo '<td data-column="QB">' . (int)$player->QB . '</td>';
echo '<td data-column="RB">' . (int)$player->RB . '</td>';
echo '<td data-column="FB">' . (int)$player->FB . '</td>';
echo '<td data-column="WR">' . (int)$player->WR . '</td>';
echo '<td data-column="TE">' . (int)$player->TE . '</td>';
echo '<td data-column="C">' . (int)$player->C . '</td>';
echo '<td data-column="G">' . (int)$player->G . '</td>';
echo '<td data-column="T">' . (int)$player->T . '</td>';
echo '<td data-column="DT">' . (int)$player->DT . '</td>';
echo '<td data-column="DE">' . (int)$player->DE . '</td>';
echo '<td data-column="LB">' . (int)$player->LB . '</td>';
echo '<td data-column="CB">' . (int)$player->CB . '</td>';
echo '<td data-column="FS">' . (int)$player->FS . '</td>';
echo '<td data-column="SS">' . (int)$player->SS . '</td>';
echo '<td data-column="K">' . (int)$player->K . '</td>';
echo '<td data-column="P">' . (int)$player->P . '</td>';
echo '<td data-column="AltPosition">' . htmlspecialchars((string)($player->AltPosition ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="Jersey">' . (int)$player->Jersey . '</td>';
echo '<td data-column="Height">' . floor($player->Height / 12) . '\'' . ($player->Height % 12) . '</td>';
echo '<td data-column="Weight">' . (int)$player->Weight . '</td>';
echo '<td data-column="College">' . htmlspecialchars((string)($player->College ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="DraftedBy">' . htmlspecialchars(idToAbbrev((int)($player->DraftedBy ?? 0)), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="DraftSeason">' . (int)$player->DraftSeason . '</td>';
echo '<td data-column="DraftRound">' . (int)$player->DraftRound . '</td>';
echo '<td data-column="DraftPick">' . (int)$player->DraftPick . '</td>';
echo '<td data-column="Nickname">' . htmlspecialchars((string)($player->Nickname ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="stickers">' . (int)$player->stickers . '</td>';
echo '<td data-column="status">' . htmlspecialchars((string)($player->status ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="grade">' . htmlspecialchars((string)($player->grade ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="personality">' . htmlspecialchars((string)($player->personality ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="flag">' . htmlspecialchars((string)($player->flag ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="trait1">' . htmlspecialchars((string)($player->trait1 ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="trait2">' . htmlspecialchars((string)($player->trait2 ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="trait3">' . htmlspecialchars((string)($player->trait3 ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
echo '<td data-column="trait4">' . htmlspecialchars((string)($player->trait4 ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
foreach (range($year, $year+5) as $years) {
    echo '<td data-column="Salary' . $years . '">' . number_format((int)$player->{(string)$years}) . '</td>';
}
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

    Overall: {
        label: 'OVR',
        title: 'Overall'
    },

    Strength: {
        label: 'Str',
        title: 'Strength'
    },

    Agility: {
        label: 'Agl',
        title: 'Agility'
    },

    Arm: {
        label: 'Arm',
        title: 'Arm Strength'
    },

    Speed: {
        label: 'Spd',
        title: 'Speed'
    },

    Hands: {
        label: 'Hnd',
        title: 'Hands'
    },

    Intelligence: {
        label: 'Int',
        title: 'Intelligence'
    },

    Accuracy: {
        label: 'Acc',
        title: 'Throw Accuracy'
    },

    RunBlocking: {
        label: 'RBlk',
        title: 'Run Blocking'
    },

    PassBlocking: {
        label: 'PBlk',
        title: 'Pass Blocking'
    },

    Tackling: {
        label: 'Tac',
        title: 'Tackling'
    },

    Endurance: {
        label: 'End',
        title: 'Endurance'
    },

    KickDistance: {
        label: 'KDis',
        title: 'Kick Distance'
    },

    KickAccuracy: {
        label: 'KAcc',
        title: 'Kick Accuracy'
    },
    Leadership: {
    label: 'Lead',
    title: 'Leadership'
    },

    WorkEthic: {
        label: 'Work',
        title: 'Work Ethic'
    },

    Competitiveness: {
        label: 'Comp',
        title: 'Competitiveness'
    },

    TeamPlayer: {
        label: 'TeamP',
        title: 'Team Player'
    },

    Sportsmanship: {
        label: 'Sport',
        title: 'Sportsmanship'
    },

    SocialDisposition: {
        label: 'Social',
        title: 'Social Disposition'
    },

    Money: {
        label: 'Money',
        title: 'Money'
    },

    Security: {
        label: 'Sec',
        title: 'Security'
    },

    Loyalty: {
        label: 'Loy',
        title: 'Loyalty'
    },

    Winning: {
        label: 'Win',
        title: 'Winning'
    },

    PlayingTime: {
        label: 'PT',
        title: 'Playing Time'
    },

    CloseToHome: {
        label: 'Home',
        title: 'Close to Home'
    },

    MarketSize: {
        label: 'Market',
        title: 'Market Size'
    },

    Morale: {
        label: 'Morale',
        title: 'Morale'
    },

        Jersey: {
        label: 'Jersey',
        title: 'Jersey Number'
    },

    Height: {
        label: 'Hgt',
        title: 'Height'
    },

    Weight: {
        label: 'Wgt',
        title: 'Weight'
    },

    College: {
        label: 'College',
        title: 'College'
    },

    DraftedBy: {
        label: 'Drafted By',
        title: 'Drafted By'
    },

    DraftSeason: {
        label: 'Draft Season',
        title: 'Draft Season'
    },

    DraftRound: {
        label: 'Draft Round',
        title: 'Draft Round'
    },

    DraftPick: {
        label: 'Draft Pick',
        title: 'Draft Pick'
    },  

    AltPosition: {
        label: 'Alt Pos',
        title: 'Alternate Position'
    },

    QB: {
        label: 'QB',
        title: 'Quarterback'
    },  

    RB: {
        label: 'RB',
        title: 'Running Back'
    },

    FB: {
        label: 'FB',
        title: 'Fullback'
    },

    WR: {
        label: 'WR',
        title: 'Wide Receiver'
    },

    TE: {
        label: 'TE',
        title: 'Tight End'
    },

    C: {
        label: 'C',
        title: 'Center'
    },

    G: {
        label: 'G',
        title: 'Guard'
    },

    T: {
        label: 'T',
        title: 'Tackle'
    },

    DT: {
        label: 'DT',
        title: 'Defensive Tackle'
    },

    DE: {
        label: 'DE',
        title: 'Defensive End'
    },

    LB: {
        label: 'LB',
        title: 'Linebacker'
    },

    CB: {
        label: 'CB',
        title: 'Cornerback'
    },

    FS: {
        label: 'FS',
        title: 'Free Safety'
    },

    SS: {
        label: 'SS',
        title: 'Strong Safety'
    },

    K: {
        label: 'K',
        title: 'Kicker'
    },

    P: {
        label: 'P',
        title: 'Punter'
    },

    Nickname: {
        label: 'Nickname',
        title: 'Nickname'
    },

    stickers: {
        label: 'Stickers',
        title: 'Helmet Stickers'
    },

    status: {
        label: 'Status',
        title: 'Custom Status'
    },

    grade: {
        label: 'Grade',
        title: 'Sim Grade'
    },

    personality: {
        label: 'Personality',
        title: 'Personality Profile'
    },

    flag: {
        label: 'Flags',
        title: 'Running QB Flag'
    },
    trait1: {
        label: 'Trait 1',
        title: 'Trait 1'
    },
    trait2: {
        label: 'Trait 2',
        title: 'Trait 2'
    },
    trait3: {
        label: 'Trait 3',
        title: 'Trait 3'
    },
    trait4: {
        label: 'Trait 4',
        title: 'Trait 4'
    },


};
const curYear = <?= json_encode((int)$year) ?>;
const lastYear = curYear + 5;
for (let year = curYear; year <= lastYear; year++) {
    columnDefinitions[`Salary${year}`] = {
        label: String(year),
        title: `${year} Salary`
    };
}

const lockedColumns = [
    'Team',
    'FullName',
    'Position'
];

const baseColumns = [
    'Team',
    'FullName',
    'Position',
    'Age',
    'Experience',
    'Overall',
    'Speed',
    'Intelligence'
];

const offensiveLine = [
    'Agility',
    'Hands',
    'Strength',
    'Endurance',
    'RunBlocking',
    'PassBlocking'
];

const defensiveLine = [
    'Agility',
    'Strength',
    'Tackling',
    'Endurance'
];

const secondary = [
    'Agility',
    'Strength',
    'Tackling',
    'Endurance',
    'Hands'
];

const skill = [
    'Agility',
    'Hands',
    'Strength',
    'Endurance',
    'RunBlocking',
    'PassBlocking'
];

const kickers = [
    'KickDistance',
    'KickAccuracy',
    'Endurance'
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

    ALL: [
        'Strength',
        'Agility',
        'Arm',
        'Hands',
        'Accuracy',
        'RunBlocking',
        'PassBlocking',
        'Tackling',
        'Endurance',
        'KickDistance',
        'KickAccuracy'
    ],

    QB: [
        'Arm',
        'Accuracy',
        'Agility',
        'Strength',
        'Endurance'
    ],

    SK: [
        'Agility',
        'Hands',
        'Strength',
        'Endurance',
        'RunBlocking',
        'PassBlocking'
    ],
    OL:  [
        'Agility',
        'Hands',
        'Strength',
        'Endurance',
        'RunBlocking',
        'PassBlocking'
    ],

    DL: [
        'Agility',
        'Strength',
        'Tackling',
        'Endurance'
    ],

    DB: [
        'Agility',
        'Strength',
        'Tackling',
        'Endurance',
        'Hands'
    ],
};

const columnOrder = [
    'Team',
    'FullName',
    'Position',
    'Age',
    'Experience',
    'Overall',
    'Intelligence',
    'Strength',
    'Agility',
    'Arm',
    'Speed',
    'Hands',
    'Accuracy',
    'RunBlocking',
    'PassBlocking',
    'Tackling',
    'Endurance',
    'KickDistance',
    'KickAccuracy'
];

const dataGroups = {
    attributes: [
        'Age',
        'Experience',
        'Overall',
        'Intelligence',
        'Strength',
        'Agility',
        'Arm',
        'Speed',
        'Hands',
        'Accuracy',
        'RunBlocking',
        'PassBlocking',
        'Tackling',
        'Endurance',
        'KickDistance',
        'KickAccuracy'
    ],

    personality: [
        'Leadership',
        'WorkEthic',
        'Competitiveness',
        'TeamPlayer',
        'Sportsmanship',
        'SocialDisposition',
        'Money',
        'Security',
        'Loyalty',
        'Winning',
        'PlayingTime',
        'CloseToHome',
        'MarketSize',
        'Morale'
    ],

    skills: [
        'QB',
        'RB',
        'FB',
        'WR',
        'TE',
        'G',
        'T',
        'C',
        'DT',
        'DE',
        'LB',
        'CB',
        'SS',
        'FS',
        'K',
        'P'   
    ],

    biography: [
        'AltPosition',
        'Jersey',
        'Height',
        'Weight',
        'College',
        'DraftedBy',
        'DraftSeason',
        'DraftRound',
        'DraftPick'
    ],

    extradata: [
        'Nickname',
        'stickers',
        'status',
        'grade',
        'personality'
    ],

    traits: [
        'flag',
        'trait1',
        'trait2',
        'trait3',
        'trait4'
    ],

    salary: Array.from(
        { length: 6 },
        function (_, offset) {
            return `Salary${curYear + offset}`;
        }
    )
};

columnOrder.push(
    ...dataGroups.personality,
    ...dataGroups.salary,
    ...dataGroups.skills,
    ...dataGroups.traits,
    ...dataGroups.biography,
    ...dataGroups.extradata
);

let selectedColumns = new Set();
let currentDataGroup = <?= json_encode($selectedGroup) ?>;

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
    document
        .querySelectorAll(
            '#playersTable tbody td[data-column]'
        )
        .forEach(function (cell) {
            const shouldShow =
                selectedColumns.has(
                    cell.dataset.column
                );

            cell.classList.toggle(
                'column-hidden',
                !shouldShow
            );
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('playerFilters');
    const table = document.getElementById('playersTable');

    if (!form || !table) {
        return;
    }

    let currentSortColumn = 'Overall';
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

    function updateUrl() {
        const url = new URL(window.location.href);

        const searchValue = searchInput.value.trim();
        const teamValue = teamSelect
            ? teamSelect.value
            : '';

        const positionValue = positionSelect.value;
        const statusValue = statusSelect.value;

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
                position === positionValue ||
                groupedPositions?.includes(position);
                
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

    clearButton.addEventListener('click', function () {
        searchInput.value = '';

        if (teamSelect) {
            teamSelect.value = '';
        }

        positionSelect.value = 'all';

        statusSelect.value = 'current';

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

    const availableColumns = new Set([
        ...lockedColumns,
        ...dataGroups[currentDataGroup]
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
    if (currentDataGroup !== 'attributes') {
        return [
            ...lockedColumns,
            ...dataGroups[currentDataGroup]
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
    return columnOrder.filter(function (columnName) {
        return selectedColumns.has(columnName);
    });
}

function resetSelectedColumns(selectedPosition) {
    selectedColumns = new Set(
        getDefaultColumns(selectedPosition)
    );
}



</script>