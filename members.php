<?php

include 'header.php';

$members = array();

$stmt = $connection->query('SELECT u.user_id,u.first_name,u.last_name,u.username,u.online,u.last_seen,u.TeamID,t.City,t.Mascot FROM `ptf_users` u LEFT JOIN `ptf_teams` t ON u.TeamID = t.TeamID ORDER BY u.user_id ASC');
while($row = $stmt->fetch_assoc()) {
    array_push($members, $row);
}



echo '<h3>Currently Online</h3>';
echo '<table><tr><th>Name</th><th>username</th><th>Team</th></tr>';
foreach ($members as $member) {
    if ($member['TeamID'] != NULL && $member['online'] == 1){
        echo '<tr><td>' . $member['first_name'] . ' ' . $member['last_name'] . '</td><td>' . $member['username'] . '</td><td>' . $member['City'] . ' ' . $member['Mascot'] . '</td></tr>';
    } 
}
echo '</table>';

echo '<h3>Currently Offline</h3>';
echo '<table><tr><th>Name</th><th>username</th><th>Team</th></tr>';
foreach ($members as $member) {
    if ($member['TeamID'] != 0 && $member['online'] == 0){
        echo '<tr><td>' . $member['first_name'] . ' ' . $member['last_name'] . '</td><td>' . $member['username'] . '</td><td>' . $member['City'] . ' ' . $member['Mascot'] . '</td></tr>';
    } 
}
echo '</table>';

echo '<h3>Non-GM Members</h3>';
echo '<table><tr><th>Name</th><th>username</th><th>Online</th></tr>';
foreach ($members as $member) {
    if ($member['TeamID'] == 0) {
        echo '<tr><td>' . $member['first_name'] . ' ' . $member['last_name'] . '</td><td>' . $member['username'] . '</td>';
        if ($member['online'] == 0) {
            echo '<td>Offline</td></tr>';
        } else {
            echo '<td>ONLINE</td></tr>';
        }

    } 
}
echo '</table>';

?>