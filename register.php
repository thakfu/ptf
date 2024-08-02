<?php 
include 'header.php';
?>

<HTML>

<head>
<title>PrimeTime Football Login</title>
</head>

<body>

<div class="content" id="top"><h2>PrimeTime Football Register</div>

<?php

$page_title = 'Register';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$errors = array() ; 

	if ( empty( $_POST[ 'first_name' ] ) ) {
        $errors[] = 'Enter your first name.' ; 
    } else {
        $fn = mysqli_real_escape_string( $connection, trim( $_POST[ 'first_name' ] ) ) ; 
	}

	if ( empty( $_POST[ 'last_name' ] ) ) {
        $errors[] = 'Enter your last name.' ; 
    } else { 
        $ln = mysqli_real_escape_string( $connection, trim( $_POST[ 'last_name' ] ) ) ; 
	}

	if ( empty( $_POST[ 'email' ] ) ) {
        $errors[] = 'Enter your email address.' ; 
    } else {
        $e = mysqli_real_escape_string( $connection, trim( $_POST[ 'email' ] ) ) ; 
	}

	if ( empty( $_POST[ 'username' ] ) ) {
        $errors[] = 'Pick a user name.' ; 
    } else {
        $u = mysqli_real_escape_string( $connection, trim( $_POST[ 'username' ] ) ) ; 
	}

	if ( !empty( $_POST[ 'pass1' ] ) ) {
	    if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] ) {
            $errors[] = 'Passwords do not match.' ; 
        } else {
            $p = mysqli_real_escape_string( $connection, trim( $_POST[ 'pass1' ] ) ) ; 
        }
	} else {
        $errors[] = 'Enter your password.' ; 
    }
	
	if ( empty( $errors ) ) {
        $result = $connection->query("SELECT user_id FROM ptf_users WHERE email='$e' ");

	    if (mysqli_num_rows($result) != 0) {
            $errors[] = 'Email address already registered. <a href="login.php">Login</a>' ; 
        }

        $insert = $connection->query("INSERT INTO ptf_users (first_name, last_name, email, username, password, online, reg_date, admin, CoachID, CoachSet,TeamID)
                                      VALUES ('$fn', '$ln', '$e', '$u', SHA('$p'), 0, NOW(), 0,0,0,0 )");

        if ($insert) {
            echo '<h2>Registered!</h2>
                <p>You are now registered.</p>
                <p><a href="login.php">Login</a></p>' ;
        } else {
            echo 'hmmm, you should tell ThakFu something went wrong.';
        }
        
        mysqli_close( $connection );
        exit();
	} else {
	    echo '<h1>Error!</h1><p id="err_msg">The following error(s) occured.<br>' ;
	    foreach ( $errors as $msg ) {
	        echo " - $msg<br>" ;
	    }   
	    echo 'Please try again.</p>' ;
	    mysqli_close( $connection );
	}
}

?>

<h2>Register</h2>
<form action="register.php" method="POST">
<p>
    First Name: <input type="text" name="first_name" value="<?php if ( isset( $_POST[ 'first_name' ] ) ) echo $_POST[ 'first_name' ]; ?>" >
    Last Name: <input type="text" name="last_name" value="<?php if ( isset( $_POST[ 'last_name' ] ) ) echo $_POST[ 'last_name' ]; ?>" >
</p>
<p>
    Email Address: <input type="text" name="email" value="<?php if ( isset( $_POST[ 'email' ] ) ) echo $_POST[ 'email' ]; ?>" >
</p>
<p>
    User Name: <input type="text" name="username" value="<?php if ( isset( $_POST[ 'username' ] ) ) echo $_POST[ 'username' ]; ?>" >
</p>
<p>
    Password: <input type="password" name="pass1" value="<?php if ( isset( $_POST[ 'pass1' ] ) ) echo $_POST[ 'pass1' ]; ?>" >
    Confirm Password: <input type="password" name="pass2" value="<?php if ( isset( $_POST[ 'pass2' ] ) ) echo $_POST[ 'pass2' ]; ?>" >
</p>
<p>
    <input type="submit" value="Register">
</p>
</form>

</body>
</html>