<?php

include 'header.php';
$playerService = playerService($_GET['teams'],0,2);
$rowcount = 0;
foreach ($playerService as $notSquad) {
    if ($notSquad['SquadTeam'] == NULL && $notSquad['irteam'] == NULL) {
        $rowcount++; 
    }
}
usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

$irPlayers = array();
$irp = array();
array_push($irp,0,0);
array_push($irPlayers,$irp);
$irCount = 0;
$irCheck = $connection->query("SELECT PlayerID, start as 'check' FROM `ptf_players_ir` where squadTeam = {$_GET['teams']}");
while($check = $irCheck->fetch_assoc()) {
    $irCount++;
    $left = 3 - ($curWeek - $check['check']);
    array_push($irp,$check['PlayerID'],$left);
    array_push($irPlayers,$irp);
};

$teamService = teamService($_GET['teams']);
$team = $teamService[0];

echo "<h1>" . $team['City'] . " " . $team['Mascot'] . " Roster By Position</h1>";

foreach ($positions as $pos) {
    $posCount = 0;
    echo '<div id="poscat"><b>' . $pos . '</b> <br></div><br>';
    echo '<table border=1 id="'.$team['Abbrev'].'">';
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
    echo '<th>Injury</th>';
    echo '</tr>';
    foreach ($playerService as $player) {
        if ($player['Position'] == $pos && $player['SquadTeam'] == 0 && $player['irteam'] == 0) {
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


            $irCheck = $connection->query("SELECT InjuryLength as 'check' FROM `ptf_players` where PlayerID = {$player['PlayerID']}");
            $check = $irCheck->fetch_assoc();
            if (str_contains($check['check'],"Out")) {
                echo $player['InjuryLength'] . '</td> ';
            } else {
                echo $player['InjuryLength'] . '</td> ';
            }

            echo '</tr>';
        }
        $positionCount = $posCount;
    }
    echo '</table><br>';
    echo '<hr>';
}


echo '<div id="poscat"><b>Injured Reserve</b></div>';
echo '<table border=1 id="'.$team['Abbrev'].'">';
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
echo '<th>Injury</th>';
echo '</tr>';


foreach ($playerService as $player) {
    if ($player['irteam'] == $_GET['teams']) {
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
            $player['InjuryLength'] . '</td>' .
            '</tr>';
        }
    }
echo '</table><br><br>';

echo '<hr>';


echo '<div id="poscat"><b>Practice Squad</b></div>';
echo '<table border=1 id="'.$team['Abbrev'].'">';
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
echo '</tr>';


foreach ($playerService as $player) {
    if ($player['SquadTeam'] == $_GET['teams']) {
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
        $player['KickAccuracy'] . '</td>' .
        '</tr>';
    }
}
echo '</table><br><br>';

?>