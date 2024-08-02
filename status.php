<?php

include 'header.php';

echo '<h2>Strategy Status</h2>';
echo '<p><center>Current Time: ' . date("Y-m-d H:i:s") . '</center></p>';
echo '<p><center>Last Sim: ' . $lastSim . '</center></p>';
echo '<p><center>Next Sim: ' . $nextSim . '</center></p>';
echo '<p><b><center>Deadline for Play Calling and Coach Updates: ' . $offEnd . '</center></b></p>';

$users = array();
$stmt = $connection->query('SELECT u.TeamID, u.CoachID, u.last_seen, u.Last_DC, u.coach_update, g.last_gp, p.last_pc, t.color_1, t.color_2 FROM `ptf_users` u 
        left join `ptf_gameplan` g on u.TeamID = g.TeamID 
        left join `ptf_playcalling` p on u.TeamID = p.TeamID 
        left join `ptf_teams_data` t on u.TeamID = t.TeamID 
        WHERE u.TeamID <> ""
        ORDER BY u.TeamID ASC');
while($row = $stmt->fetch_assoc()) {
    array_push($users, $row);
}

echo '<table class="sortable" id="status"><tr><th>Team</th><th>Last Online</th><th>Depth Chart</th><th>Gameplan</th><th>Play Calling</th><th>Coach Update</th></tr>';
foreach ($users as $user) {
    if($user['Last_DC'] > $lastSim) {
        $cDC = ' - Current!';
    }
    if($user['last_gp'] > $lastSim) {
        $cGP = ' - Current!';
    }
    if($user['last_pc'] > $lastSim) {
        $cPC = ' - Current!';
    }
    /*if($user['last_pc'] > $offEnd) {
        $cPC = ' - Offseason is Over!';
    } */
    if($user['coach_update'] > $lastSim) {
        $cCU = ' - Current!';
    }
    /*if($user['coach_update'] > $offEnd) {
        $cCU = ' - Offseason is Over!';
    }*/
    if($user['TeamID'] == '99') {
        $cCU = 'HAS NOT SUBMITTED A COACH!!';
    } elseif($user['coach_update'] == NULL) {
        $cCU = 'Submitted';
    }
    echo '<tr style="background-color:#'.$user['color_1']. ';color:#'.$user['color_2'].'"><td>' . idToAbbrev($user['TeamID']) . '</td><td>' . $user['last_seen'] . '</td><td>' . 
    $user['Last_DC'] . $cDC . '</td><td>' . 
    $user['last_gp'] . $cGP . '</td><td>' . 
    $user['last_pc'] . $cPC . '</td><td>' . 
    $user['coach_update'] . $cCU . '</td></tr>';
    $cDC = '';
    $cGP = '';
    $cPC = '';
    $cCU = '';
}
echo '</table><br><br>';

?>