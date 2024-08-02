<?php 

include 'header.php';

include 'nav.php'; 

$teamService = teamService($_GET['team']);
$team = $teamService[0];

$playerService = playerService($_GET['team'],0,2);

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
$top = 0;
$topArray = array();
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
foreach ($topArray as $ta) {
    echo '<img width="100px" title="' . $ta . '" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div><br>';

echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Jersey&order=' . $sorter . '">Jersey</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Age&order=' . $sorter . '">Age</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Experience&order=' . $sorter . '">Exp</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=College&order=' . $sorter . '">College</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Height&order=' . $sorter . '">Height</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Weight&order=' . $sorter . '">Weight</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Overall&order=' . $sorter . '">OVERALL</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Overall&order=' . $sorter . '">Injury</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Overall&order=' . $sorter . '">Traits</a></th>';
/*echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Strength&order=' . $sorter . '">Strength</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Agility&order=' . $sorter . '">Agility</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Arm&order=' . $sorter . '">Arm</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Speed&order=' . $sorter . '">Speed</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Hands&order=' . $sorter . '">Hands</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Intelligence&order=' . $sorter . '">Intelligence</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Accuracy&order=' . $sorter . '">Accuracy</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=RunBlocking&order=' . $sorter . '">Run Block</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=PassBlocking&order=' . $sorter . '">Pass Block</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Tackling&order=' . $sorter . '">Tackling</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=Endurance&order=' . $sorter . '">Endurance</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=KickDistance&order=' . $sorter . '">Kick Dis.</a></th>';
echo '<th><a href="team.php?team=' . $_GET['team'] . '&sort=KickAccuracy&order=' . $sorter . '">Kick Acc.</a></th>';*/
echo '</tr>';


foreach ($playerService as $player) {
    echo '<tr><td>'; 
    if ($player['SquadTeam'] == $_GET['team']) {
        echo '**' . $player['Jersey'];
    } elseif ($player['irteam'] == $_GET['team']) {
        echo '##' . $player['Jersey'];
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
    echo '</td><td>' .
    $player['College'] . '</td><td>' .  
    floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) . '"</td><td>' .
    $player['Weight'] . '</td><td>' .
    $player['Overall'] . '</td><td>' .
    $player['InjuryLength'] . '</td><td>' .
    $player['trait1'] . '</td>' .
    /*$player['Strength'] . '</td><td>' .
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
    $player['KickAccuracy'] . '</td>' .*/
    '</tr>';
}

echo '</table><br><br>';
echo '<table><tr style="background-color:white"><td valign="top">';
echo '<h3 align="center">Owned Draft Picks</h3>';
echo '<table id="'.$team['Abbrev'].'">';
$picksuser = $connection->query("SELECT draftID, team, year, round, owner FROM ptf_draft_picks WHERE year >= " . $year . " AND playerID = 0 AND owner = " . $team['TeamID']);
while($row = $picksuser->fetch_assoc()) {
    echo '<tr><td>' . $row['year'] . '</td><td>Round ' .  $row['round'] . '</td><td><img src="images/' . idToAbbrev($row['team']) . '_115.png" id="draftHelmet"></td></tr>'; 
}
echo '</table><br>';
echo '</td><td valign="top">';
echo '<h3 align="center">Team Information</h3>';
echo '<ul align="left">' . 
    '<li><b>Owner:</b> ' . $team['first_name'] . ' ' . $team['last_name'] . 
    '<li><b>Member Since:</b> ' . $team['reg_date'] . 
    '<li><b>Stadium:</b> ' . $team['stadium'] . 
    '<li><b>Conference:</b> ' . $team['Conference'] . 
    '<li><b>Coach:</b> ' . ' ' . 
    '<li><b>Total Salary:</b> $' . number_format($team['TeamSalary']) . 
    '</ul><br><br>';
echo '<i><b>Last Online:</b> ' . $team['last_seen'] . '</i><br>';


echo '</td></tr><table></div><br>';

include 'gridtest.php'; 


?>