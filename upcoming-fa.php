<?php 

include 'header.php';

$playerService = playerService('upfa',0,0);

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
$top = 0;
$topArray = array();
foreach ($playerService as $player) {
    if ($top < 10) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
if ($_GET['pos'] == NULL) {
    if ($_GET['sort'] == 'all');
}

if ($_GET['sort'] == NULL) {
    $_GET['sort'] = 'PosSort';
    $_GET['order'] = 'asc';
}

if ($_GET['order'] == 'asc') {
    $sorter = 'desc';
    usort($playerService, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
} else {
    $sorter = 'asc';
    usort($playerService, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
}

echo '<h2>' . $year + 1 . ' Free Agents</h2>';
echo '<div align="center">';
echo '<a href="upcoming-fa.php?pos=all">ALL</a>';
foreach($positions as $pos) {
    echo ' - <a href="upcoming-fa.php?pos='. $pos .'">'. $pos .'</a>';
}
echo '</div><br>';

echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<th>Team</th>';
echo '<th>Name</a></th>';
echo '<th>Position</a></th>';
echo '<th>Age</a></th>';
//echo '<th><a href="upcoming-fa.php?sort=Experience&order=' . $sorter . '">Exp</a></th>';
//echo '<th><a href="upcoming-fa.php?sort=College&order=' . $sorter . '">College</a></th>';
echo '<th>Height</a></th>';
echo '<th>Weight</a></th>';
echo '<th>OVERALL</a></th>';
echo '<th>Strength</a></th>';
echo '<th>Agility</a></th>';
echo '<th>Arm</a></th>';
echo '<th>Speed</a></th>';
echo '<th>Hands</a></th>';
echo '<th>Intelligence</a></th>';
echo '<th>Accuracy</a></th>';
echo '<th>Run Block</a></th>';
echo '<th>Pass Block</a></th>';
echo '<th>Tackling</a></th>';
echo '<th>Endurance</a></th>';
echo '<th>Kick Dis.</a></th>';
echo '<th>Kick Acc.</a></th>';
echo '</tr>';


foreach ($playerService as $player) {
    if ($player['RetiredSeason'] == 0) {
        if ($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') {
            echo '<tr><td>'; 
            echo idToAbbrev($player['TeamID']);
            echo '</td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
            $player['Position'] . '</td><td>' .
            $player['Age'] . '</td><td>' .
            floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) . '"</td><td>' .
            $player['Weight'] . '</td><td>' .
            $player['Overall'] . '</td><td>' .
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
}

echo '</table><br><br>';