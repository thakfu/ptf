<?php

include 'header.php';
$playerService = playerService($_SESSION['TeamID'],0,2);
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
$irCheck = $connection->query("SELECT PlayerID, start as 'check' FROM `ptf_players_ir` where squadTeam = {$_SESSION['TeamID']}");
while($check = $irCheck->fetch_assoc()) {
    $irCount++;
    $left = 3 - ($curWeek - $check['check']);
    array_push($irp,$check['PlayerID'],$left);
    array_push($irPlayers,$irp);
};

echo "<h1>" . $_SESSION['city'] . " " . $_SESSION['mascot'] . " Roster Moves</h1>";
echo "<h3 id='poscat'>" . $rowcount . " players on active roster.  Limit is 53 plus 5 Practice Squad</h3>";

foreach ($positions as $pos) {
    $posCount = 0;
    echo '<div id="poscat"><b>' . $pos . '</b> <br> Minimum - ' . $rosMin[$pos] . ' || Maximum - ' . $rosMax[$pos] . '</div><br>';
    echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
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
    echo '<th>Injury</th>';
    echo '<th>CHANGE POS.</th>';
    echo '<th>EXTEND</th>';
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


            if ($_SESSION['admin'] != '2') {
                if ($waivers == 0) {
                    echo '';
                } else {
                    echo '<a href="release.php?PlayerID=' . $player['PlayerID'] . '">RELEASE / DEMOTE</a>';
                }
            } else {
                echo '<a href="release.php?PlayerID=' . $player['PlayerID'] . '">RELEASE / DEMOTE</a>';
            }
            echo '</td><td> ';


            $irCheck = $connection->query("SELECT InjuryLength as 'check' FROM `ptf_players` where PlayerID = {$player['PlayerID']}");
            $check = $irCheck->fetch_assoc();
            if (str_contains($check['check'],"Out")) {
                echo $player['InjuryLength'] . ' <a href="release.php?PlayerID=' . $player['PlayerID'] . '">PLACE ON IR</a>' . '</td><td> ';
            } else {
                echo $player['InjuryLength'] . '</td><td> ';
            }

            if ($_SESSION['admin'] != '2') {
                if ($positionchanges == 0) {
                    echo '';
                } else {
                    echo '<a href="change.php?PlayerID=' . $player['PlayerID'] . '">CHANGE</a>' ;
                }
            } else {
                echo '<a href="change.php?PlayerID=' . $player['PlayerID'] . '">CHANGE</a>' ;
            }

            echo '</td><td>';
            if ($_SESSION['admin'] != '2') {
                if ($extensions == 0) {
                    echo '';
                } else {
                    if ($player[$year + 1] == 0) {
                        echo '<a href="extend.php?player=' . $player['PlayerID'] . '">EXTEND</a>';
                    }
                }
            } else {
                if ($player[$year + 1] == 0) {
                    echo '<a href="extend.php?player=' . $player['PlayerID'] . '">EXTEND</a>';
                }
            }

            echo '</tr>';
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


echo '<div id="poscat"><b>Injured Reserve</b></div>';
echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
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
echo '<th>Weeks Left</th>';
echo '<th>ACTIVATE</th>';
echo '</tr>';


foreach ($playerService as $player) {
    if ($player['irteam'] == $_SESSION['TeamID']) {
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
            $player['InjuryLength'] . '</td><td>' .
            $left . '</td><td>' .
            '<a href="release.php?PlayerID=' . $player['PlayerID'] . '">ACTIVATE</a>' . '</td> ' .
            '</tr>';
        }
    }
echo '</table><br><br>';

echo '<hr>';


echo '<div id="poscat"><b>Practice Squad</b></div>';
echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
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
    if ($player['SquadTeam'] == $_SESSION['TeamID']) {
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
        '<a href="release.php?PlayerID=' . $player['PlayerID'] . '">RELEASE / PROMOTE</a>' . '</td><td> ' .
        'CHANGE'. '</td>' .
        '</tr>';
    }
}
echo '</table><br><br>';

?>