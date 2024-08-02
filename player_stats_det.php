<?php

include 'header.php';
$statsService = statsService($_GET['team'],0,'season');
$teamService = teamService($_GET['team']);
$team = $teamService[0];
$abrv = idToAbbrev($_GET['team']);

if ($_GET['sort'] == NULL) {
    $_GET['sort'] = 'PosSort';
}

if ($_GET['year'] == NULL) {
    $_GET['year'] = $year;
}

if ($_GET['order'] == 'desc') {
    $sorter = 'asc';
    usort($statsService, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
} else {
    $sorter = 'desc';
    usort($statsService, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
}

echo '<div align="center"><img src="images/' . $abrv . '_cbs.png" id="cbsBanner"></div>';
echo "<h1>" . $_GET['year'] . " Player Stats</h1>";
echo '<div align="center">Season Stats: ';
foreach ($pastSeasons as $ps) {
    echo ' <a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $ps . '">' . $ps . '</a> - ';
}
echo ' <a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $year . '">' . $year . '</a> - ';
echo ' <a href="player_stats_career.php?team=' . $_GET['team'] . '">All-Time</a>';
echo '<br>';
echo ' <a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $year . '">Summarized Stats Found Here</a>';
echo '</div>';

echo '<div align="center"><h3>Passing</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=GS&order=' . $sorter . '">Starts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Plays&order=' . $sorter . '">Plays</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassAtt&order=' . $sorter . '">Attempts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassCmp&order=' . $sorter . '">Completions</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassYds&order=' . $sorter . '">Yards</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassTD&order=' . $sorter . '">TDs</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassInt&order=' . $sorter . '">INTs</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassRating&order=' . $sorter . '">Rating</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassPct=' . $sorter . '">Pass Pct</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassingYardsPerAttempt&order=' . $sorter . '">Yds / Att</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassingYardsPerCompletion&order=' . $sorter . '">Yds / Cmp</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassingYdsPerGame&order=' . $sorter . '">Yds / G</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassingAttemptsPerGame&order=' . $sorter . '">Att / G</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=WasSacked&order=' . $sorter . '">Sacked</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Pass20&order=' . $sorter . '">20+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Pass40&order=' . $sorter . '">40+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassLong&order=' . $sorter . '">Long</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $played = 0;
    $playedPos = array('QB');
    if (in_array($player['Position'],$playedPos)) {
        $played = $player['Plays'];
    }
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && ($player['PassAtt'] > 0 || $played > 0)) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['PassAtt'] . '</td><td>' .
        $player['PassCmp'] . '</td><td>' .
        $player['PassYds'] . '</td><td>' .
        $player['PassTD'] . '</td><td>' .
        $player['PassInt'] . '</td><td>' .
        $player['PassRating'] . '</td><td>' .
        $player['PassPct'] . '</td><td>' .
        $player['PassingYardsPerAttempt'] . '</td><td>' .
        $player['PassingYardsPerCompletion'] . '</td><td>' .
        $player['PassingYdsPerGame'] . '</td><td>' .
        $player['PassingAttemptsPerGame'] . '</td><td>' .
        $player['WasSacked'] . '</td><td>' .
        $player['Pass20'] . '</td><td>' .
        $player['Pass40'] . '</td><td>' .
        $player['PassLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';

echo '<div align="center"><h3>Rushing</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=GS&order=' . $sorter . '">Starts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Plays&order=' . $sorter . '">Plays</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushAtt&order=' . $sorter . '">Attempts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushYds&order=' . $sorter . '">Yards</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushTD&order=' . $sorter . '">TDs</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Fumbles&order=' . $sorter . '">Fumbles</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushAvg&order=' . $sorter . '">Rush Avg</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushingYdsPerGame&order=' . $sorter . '">Yds / G</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushingAttPerGame&order=' . $sorter . '">Att / G</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Rush20&order=' . $sorter . '">20+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Rush40&order=' . $sorter . '">40+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RunLong&order=' . $sorter . '">Long</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $played = 0;
    $playedPos = array('RB','FB');
    if (in_array($player['Position'],$playedPos)) {
        $played = $player['Plays'];
    }
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && ($player['RushAtt'] > 0 || $played > 0)) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['RushAtt'] . '</td><td>' .
        $player['RushYds'] . '</td><td>' .
        $player['RushTD'] . '</td><td>' .
        $player['Fumbles'] . '</td><td>' .
        $player['RushAvg'] . '</td><td>' .
        $player['RushingYdsPerGame'] . '</td><td>' .
        $player['RushingAttPerGame'] . '</td><td>' .
        $player['Rush20'] . '</td><td>' .
        $player['Rush40'] . '</td><td>' .
        $player['RunLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';

echo '<div align="center"><h3>Receiving</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=GS&order=' . $sorter . '">Starts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Plays&order=' . $sorter . '">Plays</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Tar&order=' . $sorter . '">Targets</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Catches&order=' . $sorter . '">Catches</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RecYds&order=' . $sorter . '">Yards</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RecTD&order=' . $sorter . '">TDs</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Fumbles&order=' . $sorter . '">Fumbles</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=DroppedPasses&order=' . $sorter . '">Drops</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RecAvg&order=' . $sorter . '">Rec Avg</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=ReceivingYdsPerGame&order=' . $sorter . '">Yds / G</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=CatchesPerGame&order=' . $sorter . '">Catch / G</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Rec20&order=' . $sorter . '">20+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Rec40&order=' . $sorter . '">40+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RecLong&order=' . $sorter . '">Long</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $played = 0;
    $playedPos = array('WR','TE');
    if (in_array($player['Position'],$playedPos)) {
        $played = $player['Plays'];
    }
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && ($player['Catches'] > 0 || $played > 0)) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['Tar'] . '</td><td>' .
        $player['Catches'] . '</td><td>' .
        $player['RecYds'] . '</td><td>' .
        $player['RecTD'] . '</td><td>' .
        $player['Fumbles'] . '</td><td>' .
        $player['DroppedPasses'] . '</td><td>' .
        $player['RecAvg'] . '</td><td>' .
        $player['ReceivingYdsPerGame'] . '</td><td>' .
        $player['CatchesPerGame'] . '</td><td>' .
        $player['Rec20'] . '</td><td>' .
        $player['Rec40'] . '</td><td>' .
        $player['RecLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';


echo '<div align="center"><h3>Blocking</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=GS&order=' . $sorter . '">Starts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Plays&order=' . $sorter . '">Plays</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Pancakes&order=' . $sorter . '">Pancakes</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=SacksAllowed&order=' . $sorter . '">Sacks Allowed</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=MissedBlocks&order=' . $sorter . '">Missed Blks</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FumblesRecovered&order=' . $sorter . '">Fumbles Rec.</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $played = 0;
    $playedPos = array('C','G','T','TE','FB');
    if (in_array($player['Position'],$playedPos)) {
        $played = $player['Plays'];
    }
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && (($player['MissedBlocks'] + $player['Pancakes']) > 0 || $played > 0)) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['Pancakes'] . '</td><td>' .
        $player['SacksAllowed'] . '</td><td>' .
        $player['MissedBlocks'] . '</td><td>' .
        $player['FumblesRecovered'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';


echo '<div align="center"><h3>Defense</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=GS&order=' . $sorter . '">Starts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Plays&order=' . $sorter . '">Plays</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Tackles&order=' . $sorter . '">Tackles</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=TFL&order=' . $sorter . '">TFL</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=MissedTackles&order=' . $sorter . '">Missed Tac.</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Sacks&order=' . $sorter . '">Sacks</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Hurries&order=' . $sorter . '">Hurries</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Knockdowns&order=' . $sorter . '">Knockdowns</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Tar&order=' . $sorter . '">Targeted</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Int&order=' . $sorter . '">Ints</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=IntReturnYds&order=' . $sorter . '">Int Yds</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassesDefended&order=' . $sorter . '">Passes Def</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=ForcedFumbles&order=' . $sorter . '">Force Fum</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FumblesRecovered&order=' . $sorter . '">Fum Recovered</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FumReturnYds&order=' . $sorter . '">Fum Yds</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=DefensiveTD&order=' . $sorter . '">Def TDs</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Safeties&order=' . $sorter . '">Safeties</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $defender = $player['Tackles'] + $player['Int'] + $player['Hurries'] + $player['Knockdowns'] + $player['PassesDefensed'];
    $played = 0;
    $playedPos = array('DE','DT','LB','CB','FS','SS','DB');
    if (in_array($player['Position'],$playedPos)) {
        $played = $player['Plays'];
    }
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && ($defender > 0 || $played > 0)) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['Tackles'] . '</td><td>' .
        $player['TFL'] . '</td><td>' .
        $player['MissedTackles'] . '</td><td>' .
        $player['Sacks'] . '</td><td>' .
        $player['Hurries'] . '</td><td>' .
        $player['Knockdowns'] . '</td><td>' .
        $player['Tar'] . '</td><td>' .
        $player['Int'] . '</td><td>' .
        $player['IntReturnYds'] . '</td><td>' .
        $player['PassesDefensed'] . '</td><td>' .
        $player['ForcedFumbles'] . '</td><td>' .
        $player['FumblesRecovered'] . '</td><td>' .
        $player['FumReturnYds'] . '</td><td>' .
        $player['DefensiveTD'] . '</td><td>' .
        $player['Safeties'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';

echo '<div align="center"><h3>Kicking</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=XPA&order=' . $sorter . '">XPA</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=XPM&order=' . $sorter . '">XPM</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=XPPct&order=' . $sorter . '">XP Pct</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FGA&order=' . $sorter . '">FGA</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FGM&order=' . $sorter . '">FGA</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FGPct&order=' . $sorter . '">FG Pct</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FGA_50&order=' . $sorter . '">FGA 50+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FGM_50&order=' . $sorter . '">FGM 50+</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FGLong&order=' . $sorter . '">FG Long</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=KickingPoints&order=' . $sorter . '">Points</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Punts&order=' . $sorter . '">Punts</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntYds&order=' . $sorter . '">PuntYds</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntsInside20&order=' . $sorter . '">PuntsInside20</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntLong&order=' . $sorter . '">PuntLong</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntAvg&order=' . $sorter . '">PuntAvg</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $special = $player['FGA'] + $player['XPA'] + $player['Punts'];
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && $special > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['XPA'] . '</td><td>' .
        $player['XPM'] . '</td><td>' .
        $player['XPPct'] . '</td><td>' .
        $player['FGA'] . '</td><td>' .
        $player['FGM'] . '</td><td>' .
        $player['FGPct'] . '</td><td>' .
        $player['FGA_50'] . '</td><td>' .
        $player['FGM_50'] . '</td><td>' .
        $player['FGLong'] . '</td><td>' .
        $player['KickingPoints'] . '</td><td>' .
        $player['Punts'] . '</td><td>' .
        $player['PuntYds'] . '</td><td>' .
        $player['PuntsInside20'] . '</td><td>' .
        $player['PuntLong'] . '</td><td>' .
        $player['PuntAvg'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';


echo '<div align="center"><h3>Returns</h3></div>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=G&order=' . $sorter . '">Games</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=KickoffReturns&order=' . $sorter . '">Kick Returns</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=KickoffReturnYds&order=' . $sorter . '">Kick Ret Yds</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=KickoffReturnTD&order=' . $sorter . '">Kick Ret TD</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=KickReturnAvg&order=' . $sorter . '">Kick Ret Avg</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=KRLong&order=' . $sorter . '">KR Long</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntReturns&order=' . $sorter . '">Punt Returns</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntReturns&order=' . $sorter . '">Punt Ret Yds</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntReturns&order=' . $sorter . '">Punt Ret TD</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PuntReturns&order=' . $sorter . '">Punt Ret Avg</a></th>';
echo '<th><a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PRLong&order=' . $sorter . '">PR Long</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    $special = $player['KickoffReturns'] + $player['PuntReturns'];
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && $special > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['G'] . '</td><td>' . 
        $player['KickoffReturns'] . '</td><td>' .
        $player['KickoffReturnYds'] . '</td><td>' .
        $player['KickoffReturnTD'] . '</td><td>' .
        $player['KickReturnAvg'] . '</td><td>' .
        $player['KRLong'] . '</td><td>' .
        $player['PuntReturns'] . '</td><td>' .
        $player['PuntReturnYds'] . '</td><td>' .
        $player['PuntReturnTD'] . '</td><td>' .
        $player['PuntReturnAvg'] . '</td><td>' .
        $player['PRLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table><br><br>';

?>