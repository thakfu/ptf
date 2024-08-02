<?php

include 'header.php';

$stmt = $connection->query('SELECT * FROM ptf_draft_picks d LEFT JOIN ptf_teams t ON d.owner = t.TeamID LEFT JOIN ptf_players y ON y.PlayerID = d.playerID WHERE d.year = "1988" ORDER BY d.round ASC, d.pick ASC');
$picks = array();
while($row = $stmt->fetch_assoc()) {
    array_push($picks, $row);
}

$stmt2 = $connection->query('SELECT y.FirstName, y.LastName FROM ptf_players y RIGHT JOIN ptf_draft_picks d ON y.PlayerID = d.playerID WHERE d.playerID != "0"');
$players = array();
while($row = $stmt2->fetch_assoc()) {
    array_push($players, $row);
}
echo '<center><a href="http://www.thakfu.com/ptf/draft.php?table=y&sort=Overall&order=desc">The Draft Pool can be found HERE!</a></center><br>';

for ($x = 1; $x <= 7; $x++) {
    echo '<h2>Round ' . $x . '</h2>';
    echo '<table><tr><th>Round</th><th>Pick</th><th>Team</th><th>Selection</th></tr>';
    foreach ($picks as $pick) {
        if ($pick['owner'] != $pick['team']) {
            $string = " (from " . idToAbbrev($pick['team']) . ")";
        } else {
            $string = "";
        }

        if ($pick['round'] == $x) {    
            echo '<tr><td>' . $pick['round'] . '</td><td>' . $pick['pick'] . '</td><td>' .  $pick['City'] . ' ' . $pick['Mascot'] . $string . '</td><td>' . $pick['FirstName'] . ' ' . $pick['LastName'] . '</td></tr>' ;
        }
    }
    echo '</table>';
}


?>