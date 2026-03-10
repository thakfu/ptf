<?php
include 'header.php';


$stmt = $connection->query("SELECT *, g.GameID as GID FROM ptf_games g 
    LEFT JOIN ptf_games_data d on g.GameID = d.GameID 
    LEFT JOIN ptf_broadcast b on b.bcID = d.bcID 
    LEFT JOIN ptf_teams_data t on d.homeID = t.TeamID 
    WHERE g.Season = " . $year . " and (g.HomeTeamID = " . $_GET['team'] ." or g.AwayTeamID = " . $_GET['team'] . ")");
$schedule = array();
while($row = $stmt->fetch_assoc()) {
    array_push($schedule, $row);
}
usort($schedule, fn($a, $b) => $a['Week'] <=> $b['Week']);


echo '<br><div align="center"><img src="images/' . idToAbbrev($_GET['team']) . '_cbs.png" id="cbsBanner"></div>';
echo '<h1>' . $year . ' Schedule</h1>
<br><br><table>
<tr><th>Week</th><th>Opponent</th><th>Result</th><th>Score</th><th>Opp</th><th>Record</th><th>Box Score</th><th>Play By Play</th></tr>';

$wins = 0;
$losses = 0;
$week = -4;
foreach ($schedule as $game) {
    $prevWeek = $week;
    /*if ($game['Week'] > 10) {
        $week = $game['Week'] - 1;
    } else {
        $week = $game['Week'];
    } */
   $week = $game['Week'];

    if ($game['HomeTeamID'] == $_GET['team']) {
        $opp = $game['AwayTeamID'];
        $score = $game['HomeScore'];
        $oscore = $game['AwayScore'];
        $pre = 'vs. ';
        $color = 'FFFFFF';
    } else {
        $opp = $game['HomeTeamID'];
        $oscore = $game['HomeScore'];
        $score = $game['AwayScore'];
        $pre = '@ ';
        $color = 'CCCCCC';
    }

    if ($game['OPOGPlayerID'] != 0) {
        if ($oscore > $score) {
            $result = 'Loss';
            $oscore = '<b>' . $oscore . '</b>';
            if ($game['Week'] >= 1 && $game['OPOGPlayerID'] != 0) {
                $losses++;
            }
        } else {
            $result = '<b>Win</b>';
            $score = '<b>' . $score . '</b>';
            if ($game['Week'] >= 1 && $game['OPOGPlayerID'] != 0) {
                $wins++;
            }
        }
    } else {
        $result = $score = $oscore = '- - -';
        $wins = $losses = '-';

    }

    if ($week != $prevWeek + 1 && $prevWeek > 0) {
        echo '<tr><td>' . $week - 1 . '</td><td align="left"><b>BYE</b></td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td></tr>';

    }
    $gameLine = $pre . idToName($opp);

    echo '<tr><td>' . $week . '</td><td align="left" bgcolor="' . $color . '"><b>' . $gameLine .'</b></td><td>' . $result .'</td><td>' . $score .'</td><td>' . $oscore .'</td><td>' . $wins . ' - ' . $losses . '</td><td><a href="/ptf/export/Boxscores/Boxscore.html?id='.$game['GID'].'">Box Score</a></td><td><a href="/ptf/export/Logs/PBP.html?id='.$game['GID'].'">Play By Play</a></td></tr>';
}

echo '</table><br><br>';

?>