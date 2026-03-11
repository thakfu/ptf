<?php 
include 'header.php';
?>

<HTML>

<head>
<title>PrimeTime Football Login</title>
</head>

<body>

<div class="content" id="top"><h1>Welcome to PTF!</h1></div>

<?php 

    if (!isset($_SESSION['user_id'])) {
        echo '<div id="login"><h2>Login</h2>
            <form action="login_action.php" method="POST">
            <p>
                Username: <input type="text" name="username">
            </p><p>
                Password: <input type="password" name="pass">
            </p><p>
                <input type="submit" value="Login">
            </p>
            </form></div>';
    } else {
        echo '<div id="login"><h2>Hello!</h2>
            <p>You are now logged in.</p>
            <p><a href="goodbye.php">Logout</a></p></div>';
    }
?>
</body>
</html>