<?php 

include 'header.php';

use App\Repositories\PlayerRepository;
use App\Services\PlayerService;

$PlayerRepository = new PlayerRepository();
$PlayerService = new PlayerService();

if ($_GET['team']) {
    $team = $_GET['team'];
    $col1 = '#';
    $sort = 'Jersey';
} else {
    $team = 0;
    $col1 = 'Team';
    $sort = 'Overall';
}
$players = $PlayerService->getAll();

/* FILTERS */
$rosterTeamId = filter_input(INPUT_GET,'team',FILTER_VALIDATE_INT) ?: 0;
$selectedTeamId = filter_input(INPUT_GET,'filter_team',FILTER_VALIDATE_INT) ?: 0;
$selectedPosition = strtoupper(trim($_GET['pos'] ?? 'all'));
$searchQuery = trim($_GET['q'] ?? '');

/* DROPDOWN DATA */
$teamOptions = [];

foreach ($players as $player) {
    if ((int) $player->ProRetire !== 0) {
        continue;
    }

    $teamOptions[(int) $player->TeamID] =
        idToAbbrev($player->TeamID);
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
                    <option value="" selected>All Teams</option>
                    <?php foreach ($teamOptions as $teamId => $abbrev): ?>
                        <option value="<?= (int) $teamId ?>">
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
            </select>
        </label>

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
    <br>
    <a href="allplayers.php">Attributes</a> - 
    <a href="allplayersper.php">Personality</a> - 
    <a href="allplayerssal.php">Salary</a></div>
    <br>


<?php
function renderHeader($column) {
    echo '<th data-column="'. htmlspecialchars($column,ENT_QUOTES,'UTF-8') . '" 
    title="' . htmlspecialchars($column,ENT_QUOTES,'UTF-8') . '">'
        . htmlspecialchars($column,ENT_QUOTES,'UTF-8')
        . '</th>';
}
?>
<table class="sortable player-table" id="playersTable">
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

    if ((int) $player->ProRetire !== 0) {
        continue;
    }

    if (
        $rosterTeamId &&
        (int) $player->TeamID !== $rosterTeamId
    ) {
        continue;
    }

    $teamAbbrev = idToAbbrev($player->TeamID);

    echo '<tr data-player-row data-player-name="'
        . htmlspecialchars(strtolower($player->FullName),ENT_QUOTES,'UTF-8') . '"'
        . ' data-team-id="' . (int) $player->TeamID . '"' . ' data-team="'
        . htmlspecialchars(strtolower($teamAbbrev),ENT_QUOTES,'UTF-8'). '"'
        . ' data-position="'. htmlspecialchars(strtoupper($player->Position),ENT_QUOTES,'UTF-8') . '">';

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
echo '</tr>';
}?>
    </tbody>
</table><br><br>

<script>
const columnDefinitions = {
    Team: {
        label: "<?= $col1 ?>",
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

    Height: {
        label: 'Hgt',
        title: 'Height'
    },

    Weight: {
        label: 'Wgt',
        title: 'Weight'
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
    }
};

const positionColumns = {
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

    RB: [
        'Agility',
        'Strength',
        'Hands',
        'Endurance',
        'RunBlocking'
    ],

    FB: [
        'Agility',
        'Strength',
        'Hands',
        'Endurance',
        'RunBlocking',
        'PassBlocking'
    ],

    WR: [
        'Agility',
        'Hands',
        'Strength',
        'Endurance',
        'PassBlocking'
    ],

    TE: [
        'Agility',
        'Hands',
        'Strength',
        'Endurance',
        'RunBlocking',
        'PassBlocking'
    ],

    K: [
        'KickDistance',
        'KickAccuracy',
        'Endurance'
    ],

    P: [
        'KickDistance',
        'KickAccuracy',
        'Endurance'
    ]
};

function buildHeaders(selectedPosition) {
    const headerRow = document.getElementById('playerTableHeader');
    //const selectedPosition = positionSelect.value.toUpperCase();
    const position = selectedPosition.toUpperCase();

    const columns = positionColumns[position] || positionColumns.ALL;

    const alwaysVisible = [
        "Team",
        "FullName",
        "Position",
        "Age",
        "Experience",
        "Overall",
        "Speed",
        "Intelligence"
    ];
    
    const headers = [
        ...new Set([
            ...alwaysVisible,
            ...columns
        ])
    ];

    headerRow.innerHTML = '';


    headers.forEach(function (columnName) {
        const column = columnDefinitions[columnName];

        if (!column) {
            console.warn('Missing column definition:', columnName);
            return;
        }

        const header = document.createElement('th');

        header.dataset.column = columnName;
        header.textContent = column.label;
        header.title = column.title;

        headerRow.appendChild(header);
    });
}

function updateVisibleCells(selectedPosition) {
    const position = selectedPosition.toUpperCase();

    const visibleColumns = positionColumns[position] || positionColumns.ALL;

    const alwaysVisible = [
        'Team',
        'FullName',
        'Position',
        'Age',
        'Experience',
        'Overall',
        'Speed',
        'Intelligence'
    ];

    const allowedColumns = [
        ...new Set([
            ...alwaysVisible,
            ...visibleColumns
        ])
    ];

    document.querySelectorAll('#playersTable tbody td[data-column]').forEach(function (cell) {
        const shouldShow = allowedColumns.includes(cell.dataset.column);
        cell.classList.toggle('column-hidden',!shouldShow);
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
                columnName === 'FullName'
                    ? 'asc'
                    : 'desc';
        }

        sortPlayerRows(columnName, currentSortDirection);
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
        table.querySelectorAll('tr[data-player-row]')
    );

    function updateUrl() {
        const url = new URL(window.location.href);

        const searchValue = searchInput.value.trim();
        const teamValue = teamSelect
            ? teamSelect.value
            : '';

        const positionValue = positionSelect.value;

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

            const matchesPosition =
                positionValue === 'ALL' ||
                position === positionValue;

            const isVisible =
                matchesSearch &&
                matchesTeam &&
                matchesPosition;

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

            const numberA = Number(valueA);
            const numberB = Number(valueB);

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
    buildHeaders(positionSelect.value);
    updateSortIndicators();
    updateVisibleCells(positionSelect.value);
    applyFilters();
});

    clearButton.addEventListener('click', function () {
        searchInput.value = '';

        if (teamSelect) {
            teamSelect.value = '';
        }

        positionSelect.value = 'all';

        buildHeaders(positionSelect.value);
        updateSortIndicators();
        updateVisibleCells(positionSelect.value);
        applyFilters();
        searchInput.focus();
    });

    buildHeaders(positionSelect.value);
    updateSortIndicators();
    updateVisibleCells(positionSelect.value);
    applyFilters();
});



</script>