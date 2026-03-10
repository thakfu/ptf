<?php

include 'header.php';

$year = $year + 1;

$playerService = playerService(0,0,1);

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

echo "<h1>" . $year . " Draft Outlook</h1>";
echo '<div align="center">';
echo '<a href="draftpreview.php?pos=all">ALL</a>';
foreach($positions as $pos) {
    echo ' - <a href="draftpreview.php?pos='. $pos .'">'. $pos .'</a>';
}
echo '</div><br>';
echo '<table class="sortable" border=1 id="'.$_SESSION['abbreviation'].'">';
echo '<th>Rank</th>';
echo '<th>Name</a></th>';
echo '<th>Position</a></th>';
echo '<th>Age</a></th>';
echo '<th>Overall</a></th>';
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
echo '<th>Kick Dist.</a></th>';
echo '<th>Kick Acc.</a></th>';
echo '<th>Traits.</a></th>';
echo '</tr>';
$count = 0;

foreach ($playerService as $player) {
    $count++;

    if ($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') {

        echo '<tr><td>' . $count . '</td><td>' . 
        '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['Age'] . '</td><td><b>' .
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
        $player['KickAccuracy'] . '</td><td>' . 
        $player['trait1'] . '</td></tr>';
    
    }
}
echo '</table><br>';


?>