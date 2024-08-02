<?php

include 'adminheader.php';
if ($_SESSION['admin'] < 1) {
    echo 'You are not authorized to be here.';
} else {

$players = playerService(0, 0, 1);
if ($_GET['sort'] == NULL) {
    usort($players, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
}


if ($_GET['order'] == 'asc') {
    $sorter = 'desc';
    usort($players, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
} else {
    $sorter = 'asc';
    usort($players, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
}

$stmt2= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.team = t.TeamID WHERE d.year = "1987" and d.current = "1" ORDER BY d.round ASC, d.pick ASC');
$picks = array();
while($row = $stmt2->fetch_assoc()) {
    array_push($picks, $row);
}

$stmt3= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.team = t.TeamID WHERE d.year = "1987" ORDER BY d.round ASC, d.pick ASC');
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


function picklink($picks, $id, $team) {
    if ($team != 0) {
        return '<a href="select.php?PlayerID=' . $id . '&team='. $team .'">SELECT PLAYER FOR ' . idToAbbrev($team) .'</a> ';
    }
}



echo 'Draft For: ';
for ($x = 1; $x < 21; $x++) {
    echo '<a href="draftadmin.php?team='.$x.'">'.idToAbbrev($x).'</a> - ';
}

echo "<h1>Dispersal Draft Pool</h1>";


    echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
    echo '<th><a href="draftadmin.php?table=y&sort=FirstName&order=' . $sorter . '">Name</a></th>';
    echo '<th><a href="draftadmin.php?table=y&sort=Position&order=' . $sorter . '">Position</a></th>';
    echo '<th><a href="draftadmin.php?table=y&sort=Age&order=' . $sorter . '">Age</a></th>';
    echo '<th><a href="draftadmin.php?table=y&sort=Overall&order=' . $sorter . '">Overall</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Strength&order=' . $sorter . '">Strength</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Agility&order=' . $sorter . '">Agility</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Arm&order=' . $sorter . '">Arm</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Speed&order=' . $sorter . '">Speed</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Hands&order=' . $sorter . '">Hands</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Intelligence&order=' . $sorter . '">Intelligence</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Accuracy&order=' . $sorter . '">Accuracy</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=RunBlocking&order=' . $sorter . '">Run Block</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=PassBlocking&order=' . $sorter . '">Pass Block</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Tackling&order=' . $sorter . '">Tackling</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=Endurance&order=' . $sorter . '">Endurance</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=KickDistance&order=' . $sorter . '">Kick Dist.</a></th>';
    echo '<th><a href="draftadmin.php?table=p&sort=KickAccuracy&order=' . $sorter . '">Kick Acc.</a></th>';
    echo '<th>DRAFT PLAYER</th>';
    echo '</tr>';
    foreach ($players as $player) {
        if(!in_array($player['PlayerID'],$picked)) {
            $picklink = picklink($picks, $player['PlayerID'], $_GET['team']);

                echo '<tr><td>' . 
                '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>' .
                $player['Position'] . '</td><td>' .
                $player['Age'] . '</td><td>' .
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
                $picklink . '</td></tr>';
            
        }
    }
    echo '</table><br>';

}
?>