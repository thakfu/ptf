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
echo '<th>Age</th>';
echo '<th title="Experience">Exp</th>';
echo '<th title="Height">Hgt</th>';
echo '<th title="Weight">Wgt</th>';
echo '<th title="OVERALL">OVR</th>';
echo '<th title="Strength">Str</th>';
echo '<th title="Agility">Agl</th>';
echo '<th title="Arm Strength">Arm</th>';
echo '<th title="Speed">Spd</th>';
echo '<th title="Hands">Hnd</th>';
echo '<th title="Intelligence">Int</th>';
echo '<th title="Throw Accuracy">Acc</th>';
echo '<th title="Run Blocking">RBlk</th>';
echo '<th title="Pass Blocking">PBlk</th>';
echo '<th title="Tackling">Tac</th>';
echo '<th title="Endurance">End</th>';
echo '<th title="Kick Distance">KDis</th>';
echo '<th title="Kick Accuracy">KAcc</th>';
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
        $player['Age'] . '</td><td>' .
        $player['Experience'] . '</td><td>' .
        floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) . '"</td><td>' .
        $player['Weight'] . '</td><td><b>' .
        $player['Overall'] . '</b></td><td>' .
        $player['Strength'] . '</td><td>' .
        $player['Agility'] . '</td><td>' .
        $player['Arm'] . '</td><td>' .
        $player['Speed'] . '</td><td>' .
        $player['Hands'] . '</td><td>' .
        $player['Intelligence'] . '</td><td>' .
        $player['Accuracy'] . '</td><td>' .
        $player['RunBlocking'] . '</td><td>' .
        $player['PassBlocking'] . '</td><td>' .
        $player['Tackling'] . '</td><td>' .
        $player['Endurance'] . '</td><td>' .
        $player['KickDistance'] . '</td><td>' .
        $player['KickAccuracy'] . '</td>' .
        '</tr>';
    }
}

echo '</table><br><br>';