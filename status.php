<?php

include 'header.php';

echo '<h2>Strategy Status</h2>';
echo '<p><center>Current Time: ' . date("l, F jS, Y - g:i A") . '</center></p>';
echo '<p><center>Last Sim: Week ' . $curWeek - 1 . ' - ' .  date("l, F jS, Y - g:i A",strtotime($lastSim) + 60*60*20) . '</center></p>';
echo '<p><center>Next Sim: Week ' . $curWeek . ' - ' .  date("l, F jS, Y - g:i A",strtotime($nextSim) + 60*60*20) . '</center></p>';
//echo '<p><b><center>Deadline for Play Calling and Coach Updates: ' . $offEnd . '</center></b></p>';

$users = array();
$stmt = $connection->query('SELECT u.TeamID, u.CoachID, u.last_seen, u.Last_DC, u.Last_Strat, u.coach_update, g.last_gp, t.color_1, t.color_2 FROM `ptf_users` u 
        left join `ptf_gameplan` g on u.TeamID = g.TeamID 
        left join `ptf_teams_data` t on u.TeamID = t.TeamID 
        WHERE u.TeamID <> ""
        ORDER BY u.TeamID ASC');
while($row = $stmt->fetch_assoc()) {
    array_push($users, $row);
}

echo '<table class="sortable" id="status"><tr><th>Team</th><th>Last Online</th><th>Depth Chart</th><th>Gameplan</th><th>Coach Update</th></tr>';
foreach ($users as $user) {
    if($user['Last_DC'] == $curWeek) {
        $cDC = ' - Current!';
    }
    if($user['Last_Strat'] == $curWeek) {
        $cGP = ' - Current!';
    }
    if($user['last_pc'] == $curWeek) {
        $cPC = ' - Current!';
    }
    /*if($user['last_pc'] > $offEnd) {
        $cPC = ' - Offseason is Over!';
    } */
    if($user['coach_update'] == $curWeek) {
        $cCU = ' - Current!';
    }
    /*if($user['coach_update'] > $offEnd) {
        $cCU = ' - Offseason is Over!';
    }*/

    $seen = strtotime($user['last_seen']);

    echo '<tr style="background-color:#'.$user['color_1']. ';color:#'.$user['color_2'].'"><td>' . idToAbbrev($user['TeamID']) . '</td><td>' . date("m/d/y - l gA", $seen) . '</td><td>' . 
    $user['Last_DC'] . $cDC . '</td><td>' . 
    $user['Last_Strat'] . $cGP . '</td><td>' . 
    //$user['last_pc'] . $cPC . '</td><td>' . 
    $user['coach_update'] . $cCU . '</td></tr>';
    $cDC = '';
    $cGP = '';
    $cPC = '';
    $cCU = '';
}
echo '</table><br><br>';

?>