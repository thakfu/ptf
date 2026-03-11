<?php 

include 'header.php';

$link = $_GET['team'];

$teams = array();
$stmt = $connection->query("SELECT * FROM ptf_teams t
LEFT JOIN ptf_teams_data d ON t.TeamID = d.TeamID 
LEFT JOIN ptf_users u ON t.TeamID = u.TeamID 
WHERE t.Abbrev = '" . $link . "'");
while($row = $stmt->fetch_assoc()) {
    array_push($teams, $row);
}

echo '<ul>';
foreach ($teams as $team) {
    foreach ($team as $key => $value) {
        echo '<li>' . $key . ' - ' . $value;
    }
}
echo '</ul>';





?>