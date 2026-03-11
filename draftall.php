<?php

include 'header.php';
//require('../ptf-services/team-service.php');
//require('../ptf-services/player-service.php');

if ($draftactive == 0) {
    echo '<center><h3>The Draft has concluded.  See you next season!<?h3></center>';
} else {

    $playerService = playerService(0,0,1);

    usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

    $year = 1991;

    $stmt2= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.owner = t.TeamID WHERE d.year =  ' . $year . '  and d.current = "1" ORDER BY d.round ASC, d.pick ASC');
    $picks = array();
    while($row = $stmt2->fetch_assoc()) {
        array_push($picks, $row);
    }

    $stmt3= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.team = t.TeamID WHERE d.year =  ' . $year . ' ORDER BY d.round ASC, d.pick ASC');
    $picksmade = array();
    while($row = $stmt3->fetch_assoc()) {
        array_push($picksmade, $row);
    }
    $picked = array();
    foreach($picksmade as $pick) {
        if ($pick['playerID'] != 0) {
            array_push($picked, $pick['playerID']);
        }
    }

    function picklink($picks, $id) {
        foreach ($picks as $pick) {
            if ($pick['Abbrev'] == $_SESSION['abbreviation'] ) {
                return '<a href="select.php?PlayerID=' . $id . '">SELECT PLAYER</a> ';
            } else {
                return 'Not Your Pick ';
            }
        }
    }

    echo "<h1>" . $year . " Draft Outlook</h1>";
    echo '<div align="center">';
    echo '<a href="draftall.php?pos=all">ALL</a>';
    foreach($positions as $pos) {
        echo ' - <a href="draftall.php?pos='. $pos .'">'. $pos .'</a>';
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
    echo '<th>DRAFT PLAYER</th>';
    echo '</tr>';
    $count = 0;
    foreach ($playerService as $player) {
        $count++;

        if ($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') {
            if(!in_array($player['PlayerID'],$picked)) {
                $picklink = picklink($picks, $player['PlayerID']);
            } else {
                $picklink = 'SELECTED';
            }


                if(in_array($player['PlayerID'],$picked)) {
                    echo '<tr style="background-color:#bbbbbb"><td>' . $count . '</td><td><strike>';
                } else {
                    echo '<tr><td>' . $count . '</td><td>';
                }
                echo '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>';
                if(in_array($player['PlayerID'],$picked)) {
                    echo '</strike>';
                } 

                echo
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
                $player['trait1'] . '</td><td>' . 
                $picklink . '</td></tr>';
        
        }
    }
    echo '</table><br>';
}


?>