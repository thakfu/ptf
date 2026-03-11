<?php

$nextWeek = $curWeek;
$curWeek = $curWeek - 1;

$stmt = $connection->query('SELECT th.Mascot as Home, ta.Mascot as Away, g.AwayScore, g.HomeScore, g.Week FROM ptf_games g LEFT JOIN ptf_teams th ON g.HomeTeamID = th.TeamID LEFT JOIN ptf_teams ta ON g.AwayTeamID = ta.TeamID WHERE g.Season = ' . $year . ' AND g.Week IN (' . $curWeek . ', "' . $nextWeek . '") ');
$picks = array();
while($row = $stmt->fetch_assoc()) {
    array_push($picks, $row);
}

$games = array(
    '<b>' . $year . ' WEEK '. $curWeek . ':</b>'
);
$games2 = array(
    '<b>' . $year . ' WEEK '. $nextWeek . ':</b>'
);

foreach ($picks as $pick) {
    if ($pick['Week'] == $curWeek) {
        //$row = $pick['Home'] .' vs. ' . $pick['Away'];
        $row = $pick['Home'] . ' - ' . $pick['HomeScore'] . str_repeat('&nbsp;', 6) . $pick['Away'] . ' - ' . $pick['AwayScore'];
        array_push($games,$row);
    } elseif ($pick['Week'] == $nextWeek) {
        $row2 = $pick['Home'] .' vs. ' . $pick['Away'];
        array_push($games2,$row2);
    }
}


echo '<div id="footer">  <marquee behavior="scroll" direction="left"
onmouseover="this.stop();"
onmouseout="this.start();">';

foreach ($games as $game) {
    echo $game . str_repeat('&nbsp;', 30);
}
foreach ($games2 as $game2) {
    echo $game2 . str_repeat('&nbsp;', 30);
}
echo '</marquee></div>';
echo '</body></html>';

?>
