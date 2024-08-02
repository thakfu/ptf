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
    echo ' <a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $ps . '">' . $ps . '</a> - ';
}
echo ' <a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $year . '">' . $year . '</a> - ';
echo ' <a href="player_stats_career.php?team=' . $_GET['team'] . '">All-Time</a>';
echo '<br>';
echo ' <a href="player_stats_det.php?team=' . $_GET['team'] . '&year=' . $year . '">Detailed Stats Found Here</a>';
echo '</div>';

echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Jersey&order=' . $sorter . '">Jersey</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=InjuryLength&order=' . $sorter . '">Injury</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassAtt&order=' . $sorter . '">Pass Att.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassCmp&order=' . $sorter . '">Pass Cmp.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassYds&order=' . $sorter . '">Pass Yds.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassTD&order=' . $sorter . '">Pass TDs</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=PassInt&order=' . $sorter . '">Pass INTs</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushAtt&order=' . $sorter . '">Rush Att.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushYds&order=' . $sorter . '">Rush Yds.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RushTD&order=' . $sorter . '">Rush TDs</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Fumbles&order=' . $sorter . '">Fumbles</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Catches&order=' . $sorter . '">Rec.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RecYds&order=' . $sorter . '">Rec Yds.</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=RecTD&order=' . $sorter . '">Rec TDs</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Tar&order=' . $sorter . '">Targ</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Tackles&order=' . $sorter . '">Tackles</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Sacks&order=' . $sorter . '">Sacks</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=Int&order=' . $sorter . '">Ints</a></th>';
echo '<th><a href="player_stats.php?team=' . $_GET['team'] . '&year=' . $_GET['year'] . '&sort=DefensiveTD&order=' . $sorter . '">Def. TDs</a></th>';
echo '</tr>';

foreach ($statsService as $player) {
    if ($player['Season'] == $_GET['year'] && $player['TeamID'] == $_GET['team'] && $player['G'] > 0) {

        echo '<tr><td>'; 
        echo $player['Jersey'];
        echo '</td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['InjuryLength'] . '</td><td>' .
        $player['PassAtt'] . '</td><td>' . 
        $player['PassCmp'] . '</td><td>' .
        $player['PassYds'] . '</td><td>' .
        $player['PassTD'] . '</td><td>' .
        $player['PassInt'] . '</td><td>' .
        $player['RushAtt'] . '</td><td>' .
        $player['RushYds'] . '</td><td>' .
        $player['RushTD'] . '</td><td>' .
        $player['Fumbles'] . '</td><td>' .
        $player['Catches'] . '</td><td>' .
        $player['RecYds'] . '</td><td>' .
        $player['RecTD'] . '</td><td>' .
        $player['Tar'] . '</td><td>' .
        $player['Tackles'] . '</td><td>' .
        $player['Sacks'] . '</td><td>' .
        $player['Int'] . '</td><td>' .
        $player['DefensiveTD'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';

?>