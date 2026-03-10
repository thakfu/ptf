<?php 

include 'header.php';

//include 'nav.php'; 

$teamService = teamService($_GET['team']);
$team = $teamService[0];

$playerService = playerService($_GET['team'],0,2);

$top = 0;
$topArray = array();

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

foreach ($playerService as $player) {
    if ($top < 10) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}

echo '<div class="tmPage">';
include 'teamhead.php';

echo '</table><br>';


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

/*if ($_GET['team'] == 4) {
    echo '<table><tr><td><img src="rb-trophy.png" title="Super Bowl - ' . $ch . '" width="75"></td></tr><tr><td>Super Bowl I - 1985</td></tr></table>';
} elseif ($_GET['team'] == 14) {
    echo '<table><tr><td><img src="rb-trophy.png" title="Super Bowl - ' . $ch . '" width="75"></td></tr><tr><td>Super Bowl II - 1986</td></tr></table>';
} */
echo '<h2>' . $year . ' Roster</h2>';
echo '<div align="center">';
if(!$isMob) {
    foreach ($topArray as $ta) {
        echo '<img width="100px" title="' . $ta . '" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
    }
}

echo '</div><br>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<th>#</th>';
echo '<th>Name</th>';
echo '<th>Pos.</th>';
echo '<th>Age</th>';
echo '<th>Exp</th>';
//echo '<th>College</th>';
//echo '<th>Height</th>';
//echo '<th>Weight</th>';
echo '<th>OVR</th>';
echo '<th>Grade</th>';
echo '<th>Injury</th>';
echo '<th>Traits</th>';
echo '<th>Personality</th>';
echo '<th>Contract</th>';
echo '</tr>';

foreach ($playerService as $player) {

    $salary = $player[$year] + $player[$year + 1] + $player[$year + 2]  + $player[$year + 3]  + $player[$year + 4]  + $player[$year + 5];
    if ($player[$year + 1] == 0) {
        $left = 1;
    } elseif ($player[$year + 2] == 0) {
        $left = 2;
    } elseif ($player[$year + 3] == 0) {
        $left = 3;
    } elseif ($player[$year + 4] == 0) {
        $left = 4;
    } elseif ($player[$year + 5] == 0) {
        $left = 5;
    } else {
        $left = 6;
    }
    $salAvg = number_format($salary / $left);

    echo '<tr><td>'; 
    if ($player['SquadTeam'] == $_GET['team'] && $player['TeamSlot'] < 6) {
        echo '<b>**</b>' . $player['Jersey'];
    } elseif ($player['SquadTeam'] == $_GET['team'] && $player['TeamSlot'] >= 6) {
        echo '<b>$$</b>' . $player['Jersey'];
    }elseif ($player['irteam'] == $_GET['team']) {
        echo '<b>##</b>' . $player['Jersey'];
    }else {
        echo $player['Jersey'];
    }
    echo '</td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
    $player['Position'] . '</td><td>' .
    $player['Age'] . '</td><td>'; 
    if ($player['Experience'] == 0) {
        echo '<b>R</b>';
    } else {
        echo $player['Experience'];
    }

    if ($_GET['team'] == 33) {
        $pers = $connection->query("SELECT grade, personality FROM ptf_players_extra WHERE playerID = " . $player['PlayerID']);
        while($row = $pers->fetch_assoc()) {
            $grade = $row['grade'];
            $person = $row['personality'];
        }
    } else {
        $grade = $person = '';
    }
    $t2 = !$player['trait2'] ? '' : ' - ';
    $t3 = !$player['trait3'] ? '' : ' - ';
    $t4 = !$player['trait4'] ? '' : ' - ';
    $f1 = !$player['flag'] ? '' : ' - ';
    echo '</td><td>' .
    //$player['College'] . '</td><td>' .  
    //floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) . '"</td><td>' .
    //$player['Weight'] . '</td><td><b>' .
    '<b>' . $player['Overall'] . '</b><td>' . $grade . '</td>' .'</td><td>' .
    $player['InjuryLength'] . '</td><td>' .
    $player['trait1'] . $t2 . $player['trait2'] . $t3 . $player['trait3'] .  $t4 . $player['trait4'] .  $f1 . $player['flag'] .  '</td>' . '<td>' . $person . '</td>' .
    '<td>' . $left . ' yrs x $' . $salAvg . '</td>' .
    '</tr>';
}

echo '</table><br><i>## Injured Reserve -- ** Practice Squad (5) -- $$ Unprotected Practice Squad (3)</i><br><br>';
echo '<table><tr style="background-color:white"><td valign="top">';
echo '<h3 align="center">Owned Draft Picks</h3>';
echo '<table id="'.$team['Abbrev'].'">';
$picksuser = $connection->query("SELECT draftID, team, year, round, owner FROM ptf_draft_picks WHERE year >= " . $year . " AND playerID = 0 AND owner = " . $team['TeamID'] . " ORDER BY year ASC, round ASC");
while($row = $picksuser->fetch_assoc()) {
    echo '<tr><td>' . $row['year'] . '</td><td>Round ' .  $row['round'] . '</td><td><img src="images/' . idToAbbrev($row['team']) . '_115.png" id="draftHelmet"></td></tr>'; 
}
echo '</table><br>';
echo '</td><td valign="top">';
echo '<h3 align="center">Team Information</h3>';
echo '<ul style="list-style-type: none;">' . 
    '<li><b>Owner:</b> ' . $team['first_name'] . ' ' . $team['last_name'] . 
    '<li><b>Member Since:</b> ' . $team['reg_date'] . 
    '<li><b>Stadium:</b> ' . $team['stadium'] . 
    '<li><b>Conference:</b> ' . $team['Conference'] . 
    '<li><b>Coach:</b> ' . ' ' . 
    '<li><b>Total Salary:</b> $' . number_format($team['TeamSalary']) . 
    '</ul><br><br>';
echo '<i><b>Last Online:</b> ' . $team['last_seen'] . '</i><br>';


//echo '</td><td>';
if(!$isMob) {
    include 'gridtest.php'; 
}

echo '</td></tr><table></div><br>';


?>