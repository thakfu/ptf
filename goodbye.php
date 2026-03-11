<?php 
include 'header.php';
?>

<html>

<head>
<title>Logout</title>
</head>

<body>

<div class="content" id="top"><h1>Logout</h1></div>

<?php

$page_title = 'ThakFu.com';

$query = $connection->query("UPDATE ptf_users SET online='0' WHERE username='$_SESSION[username]'");

$_SESSION = array() ;

session_destroy();


echo '<h2>Good Bye!</h2>
<p>You are now logged out.</p>
<p><a href="login.php">Login</a></p>';

mysqli_close( $connection );
?>

</body>
</html>