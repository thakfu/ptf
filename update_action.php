<?php 
session_start();

require('../../sql/phpmysqlconnect.php');

$page_title = 'ThakFu.com';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//$stmt = $connection->query('SELECT PlayerID, FirstName, LastName, Position, Overall FROM `ptf_players` WHERE TeamID = ' . $_SESSION['team'] . ' ORDER BY Overall DESC');
	$stmt = $connection->query('UPDATE `ptf_users` SET username = "' . $_POST['username'] . '", password = "' . SHA1($_POST['pass']) . '" WHERE user_id= ' . $_SESSION['user_id']);

	echo '<div>Please Log out and log back in after updating!</div>';
	echo '<p><a href="goodbye.php">Logout</a></p>';
}

?>