<?php
include 'header.php';

echo "<div align='center'>Week: - 
<a href='schedule.php?week=-4'>Pre 1</a> - 
<a href='schedule.php?week=-3'>Pre 2</a> - 
<a href='schedule.php?week=-2'>Pre 3</a> ||| 
<a href='schedule.php?week=1'>Bowls</a> - 
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
<a href='schedule.php?week=16'>16</a> -  
<a href='schedule.php?week=17'>17</a> - 
<a href='schedule.php?week=18'>18</a> - 
<a href='schedule.php?week=20'>WC</a> - 
<a href='schedule.php?week=21'>DP</a> - 
<a href='schedule.php?week=22'>CC</a> - 
<a href='schedule.php?week=23'>SB</a>     
</div>";

$stmt = $connection->query("SELECT *, g.GameID as GID FROM ptf_games g 
    LEFT JOIN ptf_games_data d on g.GameID = d.GameID 
    LEFT JOIN ptf_teams_data t on g.homeTeamID = t.TeamID 
    LEFT JOIN ptf_bowl_history h on h.GameID = g.GameID 
    LEFT JOIN ptf_bowl_games w on w.BowlID = h.BowlID
    LEFT JOIN ptf_broadcast b on b.bcID = w.BowlID
    WHERE Season = " . $year);
$schedule = array();
while($row = $stmt->fetch_assoc()) {
    array_push($schedule, $row);
}
usort($schedule, fn($a, $b) => $a['weekOrder'] <=> $b['weekOrder']);

if ($_GET['week'] < 1) {
    $week = 'Preseason WEEK ' .  $_GET['week'] + 5;
} elseif ($_GET['week'] == 1) {
    $week = 'BOWL WEEK';
} elseif ($_GET['week'] == 9) {
    $week = 'League Wide Bye Week!';
} elseif ($_GET['week'] == 20) {
    $week = 'Wildcard Games';
} elseif ($_GET['week'] == 21) {
    $week = 'Divisional Playoffs';
} elseif ($_GET['week'] == 22) {
    $week = 'Conference Championships';
} elseif ($_GET['week'] == 23) {
    $week = 'Super Bowl';
} else {
    $week = 'WEEK ' . $_GET['week'];
}
echo "<h1>" . $week . "</h1>";


foreach ($schedule as $game) {
    if ($week == 'BOWL WEEK') {
        $suff = '_ALT';
    } elseif ($week == 'WEEK 10') {
        $suff = '_ALT';
    } else {
        $suff = '';
    }

    if ($game['Week'] == $_GET['week']) {
        if ($game['Simmed'] == 'False') {
            $team = idToAbbrev($game['HomeTeamID']);
            $homeRec = $connection->query("SELECT Wins, Losses, Ties FROM ptf_teams WHERE TeamID = " . $game['HomeTeamID']);
            $hRec = $homeRec->fetch_assoc();
            $hRecord = $hRec['Wins'] . '-' . $hRec['Losses'] . ' ';

            $awayRec = $connection->query("SELECT Wins, Losses, Ties FROM ptf_teams WHERE TeamID = " . $game['AwayTeamID']);
            $aRec = $awayRec->fetch_assoc();
            $aRecord = $aRec['Wins'] . '-' . $aRec['Losses'] . ' ';

            echo '<style>';
            include 'css/uniforms.css';
            echo '</style>';
            echo '<table><tr><td style="background-color:Silver;">';

            if ($week == 'BOWL WEEK' || $week == 'WEEK 10') {

                echo '<table id="altuni">
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td></td><td></td><td id="t-helmet-'.$team.'"></td><td id="t-helmetlogo-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td></td><td></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-facemask-'.$team.'"></td><td id="skintone"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="skintone"></td><td id="t-facemask-'.$team.'"></td><td id="t-facemask-'.$team.'"></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-jerseystripe-'.$team.'"></td><td id="t-jerseystripe-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td></tr>
                <tr><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="t-pants-'.$team.'"></td><td></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td></tr>
                <tr><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td></td><td></td><td id="t-sockhigh-'.$team.'"></td><td id="t-sockhigh-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="t-sockhigh-'.$team.'"></td><td id="t-sockhigh-'.$team.'"></td><td></td><td></td><td id="t-socklow-'.$team.'"></td><td id="t-socklow-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="t-socklow-'.$team.'"></td><td id="t-socklow-'.$team.'"></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </table><br></td>';
                
                        } else {
                
echo ' <table id="homeuni" border="0" style="border-collapse:collapse">
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td id="h-helmet-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td id="h-helmet-'.$team.'"></td><td id="h-helmetlogo-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td id="h-helmet-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="h-jersey-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="h-facemask-'.$team.'"></td><td id="skintone"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="h-helmet-'.$team.'"></td><td id="skintone"></td><td id="h-facemask-'.$team.'"></td><td id="h-facemask-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="h-jersey-'.$team.'"></td><td id="h-jerseystripe-'.$team.'"></td><td id="h-jerseystripe-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="h-jersey-'.$team.'"></td><td id="h-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="h-pants-'.$team.'"></td><td id="h-pantsstripe-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td id="h-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="h-pants-'.$team.'"></td><td id="h-pantsstripe-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="h-pants-'.$team.'"></td><td></td><td id="skintone"></td><td id="skintone"></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="h-pants-'.$team.'"></td><td id="h-pantsstripe-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="h-pants-'.$team.'"></td><td id="h-pantsstripe-'.$team.'"></td><td></td><td id="h-pants-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td id="h-pants-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="h-pants-'.$team.'"></td><td id="h-pantsstripe-'.$team.'"></td><td></td><td></td><td id="h-sockhigh-'.$team.'"></td><td id="h-sockhigh-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="h-sockhigh-'.$team.'"></td><td id="h-sockhigh-'.$team.'"></td><td></td><td></td><td id="h-socklow-'.$team.'"></td><td id="h-socklow-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="h-socklow-'.$team.'"></td><td id="h-socklow-'.$team.'"></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>';

        }

            if ($week == 'BOWL WEEK') {
                $site = $game['Site'];
            } else {
                $site = $game['stadium'] . " in " . $game['StadCity'] . ", " . $game['StadState'];
            }
            
            echo '</td><td style="background-color:Silver;">';

            echo "<h2>You Are Looking Live....</h2>";
            echo "<center><p>at " . $site . "...";
            //echo 'home: ' . $game['HomeTeamID'] . ' - id: ' . $game['GID'];
            echo "<h3>" . $game['title'] . "</h3>";
            if ($game['network'] != NULL) {
                echo "On " . $game['network'] . ", " . $game['timeslot'] . "... with " . $game['playbyplay'] . " and " . $game['color'] . " on the call!<br><br>";
            }
            echo '<div align="center"><table id="schedule"><tr><td><img src="images/' . idToAbbrev($game['AwayTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></td><td>'.$aRecord.'</td></tr></table></div>';
            echo '<div align="center"><table id="schedule"><tr><td><img src="images/' . idToAbbrev($game['HomeTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></td><td>'.$hRecord.'</td></tr></table></div>';
            //echo '<div align="center"><img src="images/' . idToAbbrev($game['AwayTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></div>';
            //echo '<div align="center"><img src="images/' . idToAbbrev($game['HomeTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></div>';
            echo "<h3><a href='/ptf/matchup.php?id=" . $game['GID'] . "'>CLICK HERE FOR THE MATCH-UP REPORT!</a></h3>";
            echo '</td><td style="background-color:Silver;">';

            $team = idToAbbrev($game['AwayTeamID']);
            echo '<style>';
            include 'css/uniforms.css';
            echo '</style>';
            
if ($week == 'BOWL WEEK' || $week == 'WEEK 10') {

echo '<table id="altuni">
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmetlogo-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="skintone"></td><td id="t-facemask-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-helmet-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="t-facemask-'.$team.'"></td><td id="t-facemask-'.$team.'"></td><td id="skintone"></td><td id="t-helmet-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jerseystripe-'.$team.'"></td><td id="t-jerseystripe-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="t-jersey-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td id="t-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td></td><td id="t-pants-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-sockhigh-'.$team.'"></td><td id="t-sockhigh-'.$team.'"></td><td></td><td></td><td id="t-pantsstripe-'.$team.'"></td><td id="t-pants-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="t-socklow-'.$team.'"></td><td id="t-socklow-'.$team.'"></td><td></td><td></td><td id="t-sockhigh-'.$team.'"></td><td id="t-sockhigh-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td id="t-socklow-'.$team.'"></td><td id="t-socklow-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table><br></td>';

        } else {

echo '<table id="awayuni">
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-helmet-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="a-helmet-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td id="a-helmetlogo-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td id="a-helmet-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="skintone"></td><td id="a-facemask-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td id="a-helmet-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="a-facemask-'.$team.'"></td><td id="a-facemask-'.$team.'"></td><td id="skintone"></td><td id="a-helmet-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="a-jerseystripe-'.$team.'"></td><td id="a-jerseystripe-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="a-jersey-'.$team.'"></td><td id="a-jersey-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td id="a-jersey-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="skintone"></td><td id="a-pantsstripe-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td id="skintone"></td><td id="skintone"></td><td></td><td id="a-pants-'.$team.'"></td><td id="skintone"></td><td id="skintone"></td><td id="a-pants-'.$team.'"></td><td id="a-pantsstripe-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-pants-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td id="a-pantsstripe-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-pants-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td></td><td id="a-pantsstripe-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-sockhigh-'.$team.'"></td><td id="a-sockhigh-'.$team.'"></td><td></td><td></td><td id="a-pantsstripe-'.$team.'"></td><td id="a-pants-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td id="a-socklow-'.$team.'"></td><td id="a-socklow-'.$team.'"></td><td></td><td></td><td id="a-sockhigh-'.$team.'"></td><td id="a-sockhigh-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td id="a-socklow-'.$team.'"></td><td id="a-socklow-'.$team.'"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td id="shoes"></td><td id="shoes"></td><td id="shoes"></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table><br></td>';

        }            
            
            echo "</td></tr></table>";
        } else {
            echo '<div align="center"><table id="schedule"><tr><td><img src="images/' . idToAbbrev($game['AwayTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></td><td>'.$game['AwayScore'].'</td></tr></table></div>';
            echo '<div align="center"><table id="schedule"><tr><td><img src="images/' . idToAbbrev($game['HomeTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></td><td>'.$game['HomeScore'].'</td></tr></table></div>';
            echo '<center><a href="/ptf/export/Boxscores/Boxscore.html?id='.$game['GID'].'">Box Score</a> - <a href="/ptf/export/Logs/PBP.html?id='.$game['GID'].'">Play By Play</a></center>';
        }
        echo '<br><br>';
    }
}
echo '<br>';

?>