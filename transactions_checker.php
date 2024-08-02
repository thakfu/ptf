<?php

include 'header.php';
$playerService = playerService($_GET['team'],0,0);
$rowcount = 0;
foreach ($playerService as $notSquad) {
    if ($notSquad['SquadTeam'] != $_GET['team'] && $notSquad['irteam'] != $_GET['team']) {
        $rowcount++; 
    }
}
usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

echo "<h1>" . idToAbbrev($_GET['team']) . " Roster Moves</h1>";
echo "<h3 id='poscat'>" . $rowcount . " players on active roster.  Limit is 53 plus 5 Practice Squad</h3>";

foreach ($positions as $pos) {
    $posCount = 0;
    echo '<div id="poscat"><b>' . $pos . '</b> <br> Minimum - ' . $rosMin[$pos] . ' || Maximum - ' . $rosMax[$pos] . '</div><br>';
    echo '<table border=1 id="'.idToAbbrev($_GET['team']).'">';
    echo '<th>Name</th>';
    echo '<th>Age</th>';
    echo '<th>Overall</th>';
    echo '<th>Speed</th>';
    echo '<th>Strength</th>';
    echo '<th>Agility</th>';
    echo '<th>Intelligence</th>';
    echo '<th>Endurance</th>';
    echo '<th>Arm</th>';
    echo '<th>Accuracy</th>';
    echo '<th>Hands</th>';
    echo '<th>Run Block</th>';
    echo '<th>Pass Block</th>';
    echo '<th>Tackling</th>';
    if ($pos == 'K' || $pos == 'P') {
        echo '<th>Kick Dist.</th>';
        echo '<th>Kick Acc.</th>';
    }
    echo '<th>RELEASE</th>';
    echo '<th>CHANGE POS.</th>';
    echo '</tr>';
    foreach ($playerService as $player) {
        if ($player['Position'] == $pos && $player['SquadTeam'] == 0) {
            $posCount++;
            echo '<tr><td>' . 
            '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>' .
            $player['Age'] . '</td><td>' .
            $player['Overall'] . '</td><td>' .
            $player['Speed'] . '</td><td>' .
            $player['Strength'] . '</td><td>' .
            $player['Agility'] . '</td><td>' .
            $player['Intelligence'] . '</td><td>' .
            $player['Endurance'] . '</td><td>'; 
            if ($pos == 'QB') {
                echo '<b>';
            }
            echo $player['Arm'] . '</td><td>';
            if ($pos == 'QB') {
                echo '<b>';
            }
            echo $player['Accuracy'] . '</td><td>';
            if ($pos == 'WR' || $pos == 'TE') {
                echo '<b>';
            }
            echo $player['Hands'] . '</td><td>';
            if (in_array($pos,array('C','T','G'))) {
                echo '<b>';
            }
            echo $player['RunBlocking'] . '</td><td>';
            if (in_array($pos,array('C','T','G'))) {
                echo '<b>';
            }
            echo $player['PassBlocking'] . '</td><td>';
            if (in_array($pos,array('DE','DT','LB','CB','SS','FS'))) {
                echo '<b>';
            }
            echo $player['Tackling'] . '</td><td>';
            if ($pos == 'K' || $pos == 'P') {
                echo '<b>' . $player['KickDistance'] . '</td><td>';
                echo '<b>' . $player['KickAccuracy'] . '</td><td>';
            }
            echo '--</td><td> ' .
            '--</td>' .
            '</tr>';
        }
        $positionCount = $posCount;
    }
    echo '</table><br>';
    if ($positionCount > $rosMax[$pos] ) {
        echo '<div id="poswarnmuch">' . $positionCount . ' Players: Too Many Players At ABOVE Position! Please RELEASE ' . ($positionCount - $rosMax[$pos]) . ' Player(s)</div><br>';
    } elseif ($positionCount < $rosMin[$pos] ) {
        echo '<div id="poswarnfew">' . $positionCount . ' Players: Too Few Players At ABOVE Position! Please SIGN ' . ($rosMin[$pos] - $positionCount) . ' Player(s)</div><br>';

    }
    echo '<hr>';
}

echo '<div id="poscat"><b>Practice Squad</b></div>';
echo '<table border=1 id="'.idToAbbrev($_GET['team']).'">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Alt Pos.</th>';
echo '<th>Overall</th>';
echo '<th>Strength</th>';
echo '<th>Agility</th>';
echo '<th>Arm</th>';
echo '<th>Speed</th>';
echo '<th>Hands</th>';
echo '<th>Intelligence</th>';
echo '<th>Accuracy</th>';
echo '<th>Run Block</th>';
echo '<th>Pass Block</th>';
echo '<th>Tackling</th>';
echo '<th>Endurance</th>';
echo '<th>Kick Dist.</th>';
echo '<th>Kick Acc.</th>';
echo '<th>RELEASE</th>';
echo '<th>CHANGE POS.</th>';
echo '</tr>';


foreach ($playerService as $player) {
    if ($player['SquadTeam'] == $_GET['team']) {
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
        '--</td><td> ' .
        '--'. '</td>' .
        '</tr>';
    }
}
echo '</table><br><br>';

?>