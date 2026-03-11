<?php

require('../../sql/phpmysqlconnect.php');

$id = $_GET['id'];

$stmt = $connection->prepare("
    SELECT * 
    FROM ptf_teams t 
    JOIN ptf_teams_data d ON t.TeamID = d.TeamID
    WHERE t.TeamID = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_assoc());

?>
