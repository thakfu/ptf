<?php

include 'header.php';

if ($waivers == 0) {
    echo 'Open Signing is not currently active';
    exit;
}


$playerService = playerService('fa',0,0);

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

if ($_GET['sort'] == NULL) {
    $_GET['sort'] = 'PosSort';
}

if ($_GET['year'] == NULL) {
    $_GET['year'] = $year;
}

if ($_GET['order'] == 'desc') {
    $sorter = 'asc';
    usort($playerService, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
} else {
    $sorter = 'desc';
    usort($playerService, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
}

echo "<h1>Free Agents</h1>";

$override = 0;
if ($freeagency == 1 || $override == 1) {
    echo 'Free Agency is active!   You can\'t sign free agents outright until it ends!';
} else {
    foreach ($positions as $pos) {
        echo '<div id="poscat"><b>' . $pos . '</div><br>';
        echo '<table class="sortable" border=1 id="'.$_SESSION['abbreviation'].'">';
        echo '<th>Name</a></th>';
        echo '<th>Position</a></th>';
        echo '<th>Alt Pos.</a></th>';
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
        echo '<th>SIGN PLAYER</th>';
        echo '</tr>';
        foreach ($playerService as $player) {
            if ($player['Position'] == $pos && $player['RetiredSeason'] == 0) {
                echo '<tr><td>' . 
                '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>' .
                $player['Position'] . '</td><td>' .
                $player['AltPosition'] . '</td><td>' .
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
                $player['KickAccuracy'] . '</td><td>' .
                '<a href="sign.php?PlayerID=' . $player['PlayerID'] . '">SIGN</a>' . 
                '</td> ' .
                '</tr>';
            }
        }
        echo '</table><br>';
    }
}

?>