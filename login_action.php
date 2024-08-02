<?php 
include 'header.php';

if (!isset( $_SESSION['user_id'])) {
	function validate($connection, $username, $pwd) {
		$errors = array();

		if (empty($username)) { 
			array_push($errors,'Enter your username.'); 
		} else {
			 $e = mysqli_real_escape_string($connection, trim($username)); 
		}

		if (empty($pwd)) {
			array_push($errors,'Enter your password.'); 
		} else {
			$p = mysqli_real_escape_string($connection, trim($pwd)); 
		}

		if (empty($errors)) {
			$result = $connection->query("SELECT u.user_id, u.first_name, u.last_name, u.reg_date, u.email, u.TeamID, u.username, u.online, u.admin, u.Last_DC, t.City, d.market, d.state, t.Mascot, t.Abbrev  
			FROM ptf_users u 
			LEFT JOIN ptf_teams t ON u.TeamID = t.TeamID 
			LEFT JOIN ptf_teams_data d ON u.TeamID = d.TeamID 
			WHERE username = '$e'
			AND password = SHA1('$p') ");

			if (mysqli_num_rows($result) == 1) {	
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				array_push($errors,'Success');
				array_push($errors,$row);
			} else { 
				array_push($errors,'Username and/or password not found.');
			}
		}
		return $errors; 
	}
} else {
	Echo 'You are already logged in!';
}


$page_title = 'ThakFu.com';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$validate = validate($connection, $_POST['username'], $_POST['pass']);

	if ($validate[0] === 'Success') {
		session_start();

		$data = $validate[1];
		$_SESSION['user_id'] 		= $data['user_id'];	
		$_SESSION['first_name'] 	= $data['first_name'];
		$_SESSION['last_name'] 		= $data['last_name'];
		$_SESSION['reg_date'] 		= $data['reg_date'];
		$_SESSION['email'] 			= $data['email'];
		$_SESSION['username'] 		= $data['username'];
		$_SESSION['online'] 		= $data['online'];
		$_SESSION['TeamID'] 		= $data['TeamID'];
		$_SESSION['admin']			= $data['admin'];	
		$_SESSION['Last_DC']		= $data['Last_DC'];	
		$_SESSION['city']			= $data['City'];
		$_SESSION['state']			= $data['state'];
		$_SESSION['market']			= $data['market'];
		$_SESSION['mascot']			= $data['Mascot'];	
		$_SESSION['abbreviation']	= $data['Abbrev'];
		//$_SESSION['color_1']		= $data['color_1'];	
		//$_SESSION['color_2']		= $data['color_2'];	

		$query = $connection->query("UPDATE ptf_users SET online='1' WHERE username='$data[username]'");

		echo '<div id="login"><h2>Hello!</h2>
		<p>You are now logged in.</p>
		<p><a href="goodbye.php">Logout</a></p></div>';

		?>
		<script type="text/javascript">
			setTimeout(function() { 
				window.location.href = "http://www.thakfu.com/ptf/index.php";
			},2000);
		</script>
		<?php
	} else {
		echo '<div id="login">There was a problem:<br><br>';
		foreach ($validate as $error) {
			echo '<span id="error">' . $error . '</span><br><br>';
		}
		echo '<a href="login.php">Go Back and Try Again!</a><br><br>';
		echo '- or -<br><br> <a href="register.php">Create an Account!</a></div>';
    }

mysqli_close($connection);

}

?>