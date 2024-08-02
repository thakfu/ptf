<?php 
include 'header.php';

$user = array();
$result = $connection->query("SELECT user_id, first_name, last_name, reg_date, email, username, password, TeamID, online
			FROM ptf_users
			WHERE user_id = '" . $_SESSION["user_id"] . "'");

$user = $result->fetch_assoc();

?>

<HTML>

<head>
<title>PrimeTime Football Profile</title>
</head>

<body>
    <ul>
    <?php
        if ($_SESSION['TeamID']) {
            $team_info = array();
            $team = $connection->query("SELECT TeamID, conference, stadium, founded, state FROM ptf_teams_data WHERE TeamID = " . $_SESSION['TeamID']);
            $team_info = $team->fetch_assoc();
        }


        echo '<li>City: ' . $_SESSION['city'];
        echo '<li>State: ' . $team_info['state'];
        echo '<li>Mascot: ' . $_SESSION['mascot'];
        echo '<li>Conference: ' . $team_info['conference'];
        echo '<li>Founded: ' . $team_info['founded'];
        echo '<li>GM: ' . $user['first_name'] . ' ' . $user['last_name']. '</ul>';

        $stmt4 = $connection->query("SELECT * FROM ptf_trade_offers WHERE recTID = ". $_SESSION['TeamID']);
        $count = 0;
        $offers = array();
        while($row = $stmt4->fetch_assoc()) {
            $count++;
            array_push($offers, $row);
        }
        echo '<h4>You have '. $count .' trade offers!</h4><br>';

        foreach ($offers as $offer) {
            $teamService = teamService($offer['sentTID']);
            $team = $teamService[0];
            echo 'The ' . $team['FullName'] . ' have sent you a trade offer!  <a href="trade_approval.php">You can view it here and make a decision!</a><br><br>';
        }

        $stmt5 = $connection->query("SELECT * FROM ptf_trade_offers WHERE sentTID = ". $_SESSION['TeamID']);
        $count2 = 0;
        $offers2 = array();
        while($row = $stmt5->fetch_assoc()) {
            $count2++;
            array_push($offers2, $row);
        }
        echo '<h4>You have '. $count2 .' pending trade offers!</h4><br>';

        foreach ($offers2 as $offer) {
            $teamService = teamService($offer['recTID']);
            $team = $teamService[0];
            echo 'You have sent a trade offer to the ' . $team['FullName'] . ', and they have yet to make a decision.  <a href="trade_approval.php?cancel=true&id=' . $offer['offerID'] . '">You can view or cancel it here!</a><br><br>';
        }

    ?>

<div class="content" id="top"><h1>WELCOME <?=$user["username"]?>!</h1></div>
<div><a href='goodbye.php'>Click Here to Log Off</a><br><br></div>
<div>If this is your first time logging in you will want to change your username and password to something secure.  Also don't hack the other teams that havent logged in yet, thats a dick move! (I'm talking to you, Joe)
</div>

<h2>Update!</h2>
    <form action="update_action.php" method="POST">
    <p>
        Username: <input type="text" name="username">
    </p><p>
        Password: <input type="password" name="pass">
    </p><p>
        <input type="submit" value="Update">
    </p>
    </form>
<div>Please Log out and log back in after updating!
</div>

<?php
?>
</body>
</html>