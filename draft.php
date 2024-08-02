<?php

include 'header.php';

$year = '1988';


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

$stmt2= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.owner = t.TeamID WHERE d.year = ' . $year . ' and d.current = "1" ORDER BY d.round ASC, d.pick ASC');
$picks = array();
while($row = $stmt2->fetch_assoc()) {
    array_push($picks, $row);
}

$stmt3= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.team = t.TeamID WHERE d.year = ' . $year . ' ORDER BY d.round ASC, d.pick ASC');
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

//var_dump($picked);

function picklink($picks, $id) {
    foreach ($picks as $pick) {
        if ($pick['Abbrev'] == $_SESSION['abbreviation'] ) {
            return '<a href="select.php?PlayerID=' . $id . '">SELECT PLAYER</a> ';
        } else {
            return 'Not Your Pick ';
        }
    }
}

echo "<h1>" . $year . " Draft Pool</h1>";

foreach ($positions as $pos) {
    echo '<div id="poscat' . $pos . '" align="center"><b>' . $pos . '</div><br>';
    echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
    echo '<th><a href="draft.php?table=y&sort=FirstName&order=' . $sorter . '#poscat' . $pos . '">Name</a></th>';
    echo '<th><a href="draft.php?table=y&sort=Position&order=' . $sorter . '#poscat' . $pos . '">Position</a></th>';
    echo '<th><a href="draft.php?table=y&sort=Age&order=' . $sorter . '#poscat' . $pos . '">Age</a></th>';
    echo '<th><a href="draft.php?table=y&sort=Overall&order=desc#poscat' . $pos . '">Overall</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Speed&order=desc#poscat' . $pos . '">Speed</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Strength&order=desc#poscat' . $pos . '">Strength</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Agility&order=desc#poscat' . $pos . '">Agility</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Intelligence&order=desc#poscat' . $pos . '">Intelligence</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Endurance&order=desc#poscat' . $pos . '">Endurance</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Arm&order=desc#poscat' . $pos . '">Arm</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Accuracy&order=desc#poscat' . $pos . '">Accuracy</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Hands&order=desc#poscat' . $pos . '">Hands</a></th>';
    echo '<th><a href="draft.php?table=p&sort=RunBlocking&order=desc#poscat' . $pos . '">Run Block</a></th>';
    echo '<th><a href="draft.php?table=p&sort=PassBlocking&order=desc#poscat' . $pos . '">Pass Block</a></th>';
    echo '<th><a href="draft.php?table=p&sort=Tackling&order=desc#poscat' . $pos . '">Tackling</a></th>';
    if ($pos == 'K' || $pos == 'P') {
        echo '<th><a href="draft.php?table=p&sort=KickDistance&order=desc#poscat' . $pos . '">Kick Dist.</a></th>';
        echo '<th><a href="draft.php?table=p&sort=KickAccuracy&order=desc#poscat' . $pos . '">Kick Acc.</a></th>';
    }
    echo '<th><a href="draft.php?table=p&sort=trait1&order=desc#poscat' . $pos . '">Traits</a></th>';
    echo '<th>DRAFT PLAYER</th>';
    echo '</tr>';
    foreach ($players as $player) {
        if ($player['PlayerID'] >= $draftStart) {
        if(!in_array($player['PlayerID'],$picked)) {
            $picklink = picklink($picks, $player['PlayerID']);
            if ($player['Position'] == $pos) {
                echo '<tr><td>' . 
                '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>' .
                $player['Position'] . '</td><td>' .
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
                echo $player['trait1'] . '</td><td>';
                echo $picklink . '</td></tr>';
            }
        }
    }
}
    echo '</table><br>';
}

?>