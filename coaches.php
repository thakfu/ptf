<?php

include 'header.php';


$stmt = $connection->query('SELECT * FROM ptf_coaches c LEFT JOIN ptf_teams t on t.TeamID = c.TeamID ORDER BY c.TeamID');
$coaches = array();

while($row = $stmt->fetch_assoc()) {
    array_push($coaches, $row);
}

echo '<table><tr><th>Coach</th><th>Position</th><th>Team</th><th>Age</th><th>Off Scheme</th><th>Pass Pref</th><th>Target Pref</th><th>Run Pref</th>
    <th>TE Role</th><th>RB Role</th><th>WR Pref</th><th>FB Role</th><th>QB Pref</th></tr>';
foreach($coaches as $coach) {
    echo '<tr><td>' . $coach['FirstName'] . ' ' . $coach['LastName'] . '</td><td>' . $coach['Job'] . '</td><td>' . idToAbbrev($coach['TeamID']);
    echo '</td><td>' . $coach['Age'] . '</td><td>' . $coach['OffScheme'] . '</td><td>' . $coach['PassPref'] . '</td><td>' . $coach['PassTargetPref'] . '</td><td>' . $coach['RunPref'] . 
    '</td><td>' . $coach['TERole'] . '</td><td>' . $coach['RBRole'] . '</td><td>' . $coach['WRPref'] . '</td><td>' . $coach['FBRole'] . '</td><td>' . $coach['QBPref'] . '</td></tr>'; 

}
echo '</ul>';

