<?php

include 'header.php';

if ($_SESSION['admin'] != '2') {
    if ($waivers == 0) {
        echo 'Open Signing is not currently active';
        exit;
    }
} else {
    echo 'ADMIN ACCESS';
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
        echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Name</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Position</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Alt Pos.</a></th>';
        echo '<th><a href="freeagents.php?sort=Overall&order=' . $sorter . '">Overall</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Strength</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Agility</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Arm</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Speed</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Hands</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Intelligence</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Accuracy</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Run Block</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Pass Block</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Tackling</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Endurance</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Kick Dist.</a></th>';
        echo '<th><a href="freeagents.php?sort=FirstName&order=' . $sorter . '">Kick Acc.</a></th>';
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