<?php
include 'adminheader.php';
session_start();
require('../../sql/phpmysqlconnect.php');

 $stmt = $connection->query(
    'SELECT g.Season, g.GameID, g.Simmed, g.HomeTeamID, g.AwayTeamID, g.Week, g.GameType, g.HomeScore, g.AwayScore, d.weekOrder, h.BowlID, d.bcID, d.HomeID, h.Year, h.WinnerID, h.LoserID, h.Notes, b.ShortName, b.Name, b.Site, b.selectionOrder, c.network, c.timeslot, c.playbyplay, c.color, c.title, c.notes as bcnotes   from ptf_games g 
        LEFT JOIN ptf_games_data d on g.GameID = d.GameID  
        LEFT JOIN ptf_bowl_history h on g.GameID = h.GameID 
        LEFT JOIN ptf_bowl_games b on h.BowlID = b.BowlID 
        LEFT JOIN ptf_broadcast c on d.bcID = c.bcID 
        WHERE g.Season = 1991 OR g.GameType = "Playoffs" OR h.BowlID > 0 OR g.Week = 1
        ORDER BY g.GameID ASC'
);

$games = array();
while($row = $stmt->fetch_assoc()) {
    array_push($games,$row);
}

echo '<br><br><table border = 1>';
echo '<tr><th>Year</th><th>Week</th><th>GameID</th><th>Type</th><th>Complete</th><th>Home</th><th>Away</th><th>Final Score</th><th>Winner</th><th>Order</th><th>Home Site</th><th>Network</th>
        <th>Timeslot</th><th>PBP</th><th>Color</th><th>Title</th><th>Short Name</th><th>Full Name</th><th>Site</th></tr>';
foreach ($games as $g) {
    //var_dump($g);
    echo '<tr><td>' . $g['Season'] . '</td>';
    echo '<td>' . $g['Week'] . '</td>';
    echo '<td>' . $g['GameID'] . '</td>';
    echo '<td>' . $g['GameType'] . '</td>';
    echo '<td>' . $g['Simmed'] . '</td>';
    echo '<td>' . idToName($g['HomeTeamID']). '</td>';
    echo '<td>' . idToName($g['AwayTeamID']). '</td>';
    echo '<td>' . $g['HomeScore'] . ' - ' . $g['AwayScore'] . '</td>';
    echo '<td>' . $g['WinnerID'] . '</td>';
    echo '<td>' . $g['weekOrder'] . '</td>';
    echo '<td>' . $g['HomeID']. '</td>';
    echo '<td>' . $g['network'] . '</td>';
    echo '<td>' . $g['timeslot'] . '</td>';
    echo '<td>' . $g['playbyplay'] . '</td>';
    echo '<td>' . $g['color'] . '</td>';
    echo '<td>' . $g['title'] . '</td>';
    echo '<td>' . $g['ShortName'] . '</td>';
    echo '<td>' . $g['Name'] . '</td>';
    echo '<td>' . $g['Site'] . '</td>';
    echo '</tr>';
}
echo '<br><br></table>';


?>