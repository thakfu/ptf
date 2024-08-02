<?php
include 'header.php';
/*

ConsecutiveCompletions
ConsecutiveFGMade
ConsecutiveGamesPlayed
ConsecutivePassesNoInt
GamesWInt
GamesWith100YardsReceiving
GamesWith100YardsRushing
GamesWith300Yards
GamesWNoInt
GamesWPassTD
GamesWRecTD
GamesWRushTD
GamesWSack

*/
echo '<h2>League Records</h2>';

echo '<table id="'.$curteam.'"><tr valign="top"><td>';
echo '<h3 align="center">Records</h3>';
echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>Range</th>';
echo '<th>Type</th>';
echo '<th>Value</th>';
echo '<th>Player</th>';
echo '<th>Team</th>';
echo '<th>When</th>';
echo '</tr>';
$recordService = statsService(0,0,'leaguerecords');
foreach ($recordService as $record) {

    if ($record['RecordType'] == 0) {
        $range ='Single Game';
    } elseif ($record['RecordType'] == 2) {
        $range ='Playoff Game';
    } elseif ($record['RecordType'] == 1) {
        $range ='Season';
    } elseif ($record['RecordType'] == 3) {
        $range ='Career';
    } 

    if ($record['RecordType'] != 1 && $record['VsTeamID'] != 0) {
        if ($record['RecordType'] == 2) {
            $week = '';
        } else {
            $week = ' Week ' . $record['Week'];
        }
        $when = $record['Season'] . $week . ' vs ' . idToAbbrev($record['VsTeamID']);
    } else {
        if ($record['RecordType'] == 1 && $record['Season'] != 0) {
            $when = $record['Season'];
        } else {
            $when = ' ';
        }
    }

    switch ($record['RecordID']) {
        case 0: $type = 'Passing Attempts'; break;
        case 1: $type = 'Passing Completions'; break;
        case 2: $type = 'Completion Percent'; break;
        case 3: $type = 'Pass Interceptions'; break;
        case 4: $type = 'Passing Yards'; break;
        case 5: $type = 'Passing TDs'; break;
        case 6: $type = 'Passer Rating'; break;
        case 7: $type = 'Longest Pass'; break;
        case 8: $type = 'Rushing Attempts'; break;
        case 9: $type = 'Rushing Yards'; break;
        case 10: $type = 'Rushing TDs'; break;
        case 11: $type = 'Longest Run'; break;
        case 12: $type = 'Catches'; break;
        case 13: $type = 'Receiving Yards'; break;
        case 14: $type = 'Receiving TDs'; break;
        case 15: $type = 'Longest Reception'; break;
        case 16: $type = 'Fumbles'; break;
        case 17: $type = 'Fumbles Lost'; break;
        case 18: $type = 'Interceptions'; break;
        case 19: $type = 'Interception Yards'; break;
        case 20: $type = 'Tackles'; break;
        case 21: $type = 'Sacks'; break;
        case 22: $type = 'Forced Fumbles'; break;
        case 23: $type = 'Fumbles Recovered'; break;
        case 24: $type = 'Fumble Return Yards'; break;
        case 25: $type = 'Defensive TDs'; break;
        case 26: $type = 'Punt Return Yards'; break;
        case 27: $type = 'Punt Return TDs'; break;
        case 28: $type = 'Longest Punt Return'; break;
        case 29: $type = 'Kick Return Yards'; break;
        case 30: $type = 'Kick Return TDs'; break;
        case 31: $type = 'Longest Kick Return'; break;
        case 32: $type = 'Field Goals Attempted'; break;
        case 33: $type = 'Field Goals Made'; break;
        case 34: $type = 'Longest Field Goal'; break;
        case 35: $type = 'Extra Points Attempted'; break;
        case 36: $type = 'Extra Points Made'; break;
        case 37: $type = 'Field Goal Percent'; break;
        case 38: $type = 'Extra Point Percent'; break;
        case 39: $type = 'Punt Average'; break;
        case 40: $type = 'Punt Yards'; break;
        case 41: $type = 'Longest Punt'; break;
        case 42: $type = 'Tackles For Loss'; break;
        case 43: $type = 'Pancakes'; break;
        case 44: $type = 'Sacks Allowed'; break;
    }


    echo '<tr><td>' .
    $range . '</td><td>' .
    $type . '</td><td>' .
    $record['RecordValue'] . '</td><td>' .
    $record['FullName'] . '</td><td class="career" id="'.idToAbbrev($record['TeamID']).'">' . 
    idToAbbrev($record['TeamID'])  . '</td><td>' . 
    $when . '</td>' .
    '</tr>';
}
echo '</table><br>';
echo '</td>';

exit;

echo '<h3 align="center">Streaks</h3>';
echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>Streak Type</th>';
echo '<th>Current</th>';
echo '<th>All Time Best</th>';
echo '</tr>';


$streakService = statsService(0,$_GET['player'],'streaks');
foreach ($streakService as $record) {

    if ($record['RecordType'] != 1 && $record['VsTeamID'] != 0) {
        if ($record['RecordType'] == 2) {
            $week = '';
        } else {
            $week = ' Week ' . $record['Week'];
        }
        $when = $record['Season'] . $week . ' vs ' . idToAbbrev($record['VsTeamID']);
    } else {
        if ($record['RecordType'] == 1 && $record['Season'] != 0) {
            $when = $record['Season'];
        } else {
            $when = ' ';
        }
    }


    switch ($record['StreakID']) {
        case 'ConsecutiveCompletions': $type = 'Consecutive Passing Completions'; break;
        case 'ConsecutiveFGMade': $type = 'Consecutive Field Goals Made'; break;
        case 'ConsecutiveGamesPlayed': $type = 'Consecutive Games Played'; break;
        case 'ConsecutivePassesNoInt': $type = 'Consecutive Passes Without An Interception'; break;
        case 'GamesWInt': $type = 'Consecutive Games With An Interception'; break;
        case 'GamesWith100YardsReceiving': $type = 'Consecutive Games With 100 Yards Receiving'; break;
        case 'GamesWith100YardsRushing': $type = 'Consecutive Games With 100 Yards Rushing'; break;
        case 'GamesWith300Yards': $type = 'Consecutive Games With 300 Yards Passing'; break;
        case 'GamesWNoInt': $type = 'Consecutive Games Without An Interception'; break;
        case 'GamesWPassTD': $type = 'Consecutive Games With a Passing TD'; break;
        case 'GamesWRecTD': $type = 'Consecutive Games With a Receiving TD'; break;
        case 'GamesWRushTD': $type = 'Consecutive Games With a Rushing TD'; break;
        case 'GamesWSack': $type = 'Consecutive Games With a Sack'; break;
    }


    echo '<tr><td>' .
    $type. '</td><td>' .
    $record['CurrentStreak']. '</td><td>' .
    $record['MaxStreak'] . '</td>' .
    '</tr>';
}
echo '</table>';

echo '</td></tr></table><br>';

?>