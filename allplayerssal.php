<?php 

include 'header.php';

if ($_GET['team']) {
    $team = $_GET['team'];
    $col1 = '#';
    $sort = 'Jersey';
} else {
    $team = 0;
    $col1 = 'Team';
    $sort = 'Overall';
}

$playerService = newPlayerService($team,0,'pro');

if ($_GET['team']) {
    usort($playerService, fn($a, $b) => $a['Jersey'] <=> $b['Jersey']);
} else {
    usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
}

if ($_GET['pos'] == NULL) {
    $_GET['pos'] = 'all';
}

if (!$_GET['team']) {
    echo '<h2>All Players</h2>';
    echo '<div align="center">';
    echo '<a href="allplayers.php?pos=all">ALL</a>';
    foreach($positions as $pos) {
        echo ' - <a href="allplayers.php?pos='. $pos .'">'. $pos .'</a>';
    }
    echo '<br><br>';
    echo '<a href="allplayers.php">Attributes</a> - <a href="allplayersper.php">Personality</a> - <a href="allplayerssal.php">Salary</a></div><br>';
} else {
    echo '<h2>' . idToName($team) . ' Roster</h2>';
    echo '<div align="center">';
    echo '<a href="allplayers.php?team=' . $team . '">Attributes</a> - <a href="allplayersper.php?team=' . $team . '">Personality</a> - <a href="allplayerssal.php?team=' . $team . '">Salary</a></div><br>';
}

echo '<table class="sortable" border=1 id="'.idToAbbrev($team).'"><tr style="background-color:#FFFFFF">';
echo '<th>' . $col1 . '</th>';
echo '<th>Name</th>';
echo '<th title="Position">Pos</th>';
echo '<th title="1991">1991</th>';
echo '<th title="1992">1992</th>';
echo '<th title="1993">1993</th>';
echo '<th title="1994">1994</th>';
echo '<th title="1995">1995</th>';
echo '<th title="1996">1996</th>';
echo '<th title="Total">Total</th>';
echo '<th title="Drafted">Drafted</th>';
if ($team != 0) {
    echo '<th title="Status">Status</th>';
}
echo '<th title="Franchise">Franchise</th>';
echo '</tr>';


foreach ($playerService as $player) {

    if (($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') && $player['ProRetire'] == 0) {

        if ($_GET['team']) {
            echo '<tr><td class="career"><b>'; 
            echo $player['Jersey'];
        } else {
            echo '<tr><td class="career" id="'.idToAbbrev($player['TeamID']).'"><b>';
            echo idToAbbrev($player['TeamID']); 
        }
        echo '</b></td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['1991'] . '</td><td>' .
        $player['1992'] . '</td><td>' .
        $player['1993'] . '</td><td>' .
        $player['1994'] . '</td><td>' .
        $player['1995'] . '</td><td>' .
        $player['1996'] . '</td><td>' .
        $player['1991'] +
        $player['1992'] +
        $player['1993'] +
        $player['1994'] +
        $player['1995'] +
        $player['1996'] . '</td><td>' . 
        "Rd " . $player['DraftRound'] . " pk " . $player['DraftPick'] . " - " . $player['DraftSeason'];

        if ($team != 0) {
            echo '</td><td>';
            if ($player['squadTeam'] == $_GET['team']) {
                echo 'PS';
            } elseif ($player['irTeam'] == $_GET['team']) {
                echo 'IR';
            }
        }

        echo '</td><td>' .
        '</td>' .
        '</tr>';
    }
}

echo '</table><br><br>';