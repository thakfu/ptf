<?php 
//include '../header.php';
session_start();
require('../../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '2') {
    echo 'LOL... Yeah right';
} else {
    echo '<ul>';
    echo "<li>Session Save Path: " . ini_get( 'session.save_path');
    echo '<li>Session ID:  ' . session_id();
    echo '</ul>';
    echo phpinfo();
}