<?php 

include 'header.php';

$playerService = playerService(0,0,0);

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

echo '<h2>' . $year . ' All Players</h2>';
echo '<div align="center">';
echo '<a href="allplayers.php?pos=all">ALL</a>';
foreach($positions as $pos) {
    echo ' - <a href="allplayers.php?pos='. $pos .'">'. $pos .'</a>';
}
echo '</div><br>';

echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="allplayers.php?sort=TeamID&order=' . $sorter . '&pos='. $_GET['pos']  .'">Team</a></th>';
echo '<th><a href="allplayers.php?sort=FirstName&order=' . $sorter . '&pos='. $_GET['pos']  .'">Name</a></th>';
echo '<th><a href="allplayers.php?sort=PosSort&order=' . $sorter . '&pos='. $_GET['pos']  .'">Position</a></th>';
echo '<th><a href="allplayers.php?sort=Age&order=' . $sorter . '&pos='. $_GET['pos']  .'">Age</a></th>';
//echo '<th><a href="allplayers.php?sort=Experience&order=' . $sorter . '">Exp</a></th>';
//echo '<th><a href="allplayers.php?sort=College&order=' . $sorter . '">College</a></th>';
echo '<th><a href="allplayers.php?sort=Height&order=' . $sorter . '&pos='. $_GET['pos']  .'">Height</a></th>';
echo '<th><a href="allplayers.php?sort=Weight&order=' . $sorter . '&pos='. $_GET['pos']  .'">Weight</a></th>';
echo '<th><a href="allplayers.php?sort=Overall&order=' . $sorter . '&pos='. $_GET['pos']  .'">OVERALL</a></th>';
echo '<th><a href="allplayers.php?sort=Strength&order=' . $sorter . '&pos='. $_GET['pos']  .'">Strength</a></th>';
echo '<th><a href="allplayers.php?sort=Agility&order=' . $sorter . '&pos='. $_GET['pos']  .'">Agility</a></th>';
echo '<th><a href="allplayers.php?sort=Arm&order=' . $sorter . '&pos='. $_GET['pos']  .'">Arm</a></th>';
echo '<th><a href="allplayers.php?sort=Speed&order=' . $sorter . '&pos='. $_GET['pos']  .'">Speed</a></th>';
echo '<th><a href="allplayers.php?sort=Hands&order=' . $sorter . '&pos='. $_GET['pos']  .'">Hands</a></th>';
echo '<th><a href="allplayers.php?sort=Intelligence&order=' . $sorter . '&pos='. $_GET['pos']  .'">Intelligence</a></th>';
echo '<th><a href="allplayers.php?sort=Accuracy&order=' . $sorter . '&pos='. $_GET['pos']  .'">Accuracy</a></th>';
echo '<th><a href="allplayers.php?sort=RunBlocking&order=' . $sorter . '&pos='. $_GET['pos']  .'">Run Block</a></th>';
echo '<th><a href="allplayers.php?sort=PassBlocking&order=' . $sorter . '&pos='. $_GET['pos']  .'">Pass Block</a></th>';
echo '<th><a href="allplayers.php?sort=Tackling&order=' . $sorter . '&pos='. $_GET['pos']  .'">Tackling</a></th>';
echo '<th><a href="allplayers.php?sort=Endurance&order=' . $sorter . '&pos='. $_GET['pos']  .'">Endurance</a></th>';
echo '<th><a href="allplayers.php?sort=KickDistance&order=' . $sorter . '&pos='. $_GET['pos']  .'">Kick Dis.</a></th>';
echo '<th><a href="allplayers.php?sort=KickAccuracy&order=' . $sorter . '&pos='. $_GET['pos']  .'">Kick Acc.</a></th>';
echo '</tr>';


foreach ($playerService as $player) {
    if (($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') && $player['RetiredSeason'] == 0) {

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

echo '</table><br><br>';