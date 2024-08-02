<?php
include 'header.php';

echo "<div align='center'>Week: - 
<a href='schedule.php?week=-4'>Pre 1</a> - 
<a href='schedule.php?week=-3'>Pre 2</a> - 
<a href='schedule.php?week=-2'>Pre 3</a> ||| 
<a href='schedule.php?week=1'>1</a> - 
<a href='schedule.php?week=2'>2</a> - 
<a href='schedule.php?week=3'>3</a> - 
<a href='schedule.php?week=4'>4</a> - 
<a href='schedule.php?week=5'>5</a> - 
<a href='schedule.php?week=6'>6</a> - 
<a href='schedule.php?week=7'>7</a> - 
<a href='schedule.php?week=8'>8</a> - 
<a href='schedule.php?week=9'>9</a> - 
<a href='schedule.php?week=10'>10</a> - 
<a href='schedule.php?week=11'>11</a> - 
<a href='schedule.php?week=12'>12</a> - 
<a href='schedule.php?week=13'>13</a> - 
<a href='schedule.php?week=14'>14</a> - 
<a href='schedule.php?week=15'>15</a> - 
<a href='schedule.php?week=16'>16</a> |||  
<a href='schedule.php?week=17'>DP</a> - 
<a href='schedule.php?week=18'>CC</a> - 
<a href='schedule.php?week=19'>SB</a> - 
</div>";

$stmt = $connection->query("SELECT *, g.GameID as GID FROM ptf_games g 
    LEFT JOIN ptf_games_data d on g.GameID = d.GameID 
    LEFT JOIN ptf_broadcast b on b.bcID = d.bcID 
    LEFT JOIN ptf_teams_data t on d.homeID = t.TeamID 
    WHERE Season = " . $year);
$schedule = array();
while($row = $stmt->fetch_assoc()) {
    array_push($schedule, $row);
}
usort($schedule, fn($a, $b) => $a['weekOrder'] <=> $b['weekOrder']);

if ($_GET['week'] < 1) {
    $week = 'Preseason WEEK ' .  $_GET['week'] + 5;
} elseif ($_GET['week'] == 17) {
    $week = 'Divisional Playoffs';
} elseif ($_GET['week'] == 18) {
    $week = 'Conference Championships';
} elseif ($_GET['week'] == 19) {
    $week = 'Super Bowl';
} else {
    $week = 'WEEK ' . $_GET['week'];
}
echo "<h1>" . $week . "</h1>";
foreach ($schedule as $game) {
    if ($game['Week'] == $_GET['week']) {
        if ($game['Simmed'] == 'False') {
            echo "<h2>You Are Looking Live....</h2>";
            echo "<center><p>at " . $game['stadium'] . " in " . $game['StadCity'] . ", " . $game['StadState'] . "...";
            //echo 'home: ' . $game['HomeTeamID'] . ' - id: ' . $game['GID'];
            echo "<h3>" . $game['title'] . "</h3>";
            echo '<div align="center"><img src="images/' . idToAbbrev($game['AwayTeamID']) . '_cbs.png" id="cbsBanner"></div>';
            echo '<div align="center"><img src="images/' . idToAbbrev($game['HomeTeamID']) . '_cbs.png" id="cbsBanner"></div>';
            echo $game['timeslot'] . " on " . $game['network'] . ". " . $game['playbyplay'] . " and " . $game['color'] . " on the call!</center>";
        } else {
            echo '<div align="center"><table id="schedule"><tr><td><img src="images/' . idToAbbrev($game['AwayTeamID']) . '_cbs.png" id="cbsBanner"></td><td>'.$game['AwayScore'].'</td></tr></table></div>';
            echo '<div align="center"><table id="schedule"><tr><td><img src="images/' . idToAbbrev($game['HomeTeamID']) . '_cbs.png" id="cbsBanner"></td><td>'.$game['HomeScore'].'</td></tr></table></div>';
            echo '<center><a href="/ptf/export/Boxscores/Boxscore.html?id='.$game['GID'].'">Box Score</a> - <a href="/ptf/export/Logs/PBP.html?id='.$game['GID'].'">Play By Play</a></center>';
        }
        echo '<br><br>';
    }
}
echo '<br>';

?>