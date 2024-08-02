<?php 
//include '../header.php';
session_start();
require('../../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '0') {
    echo 'Admin Tools<br><br><ul>';
    echo '<li><a href="dashboard.php">DASHBOARD</a><br><br>';
    echo '<li><a href="draftadmin.php">DRAFT ADMIN TOOL</a>';
    echo '<li><a href="lists.php">DRAFT LISTS</a>';
    echo '<li><a href="../datatools">Data Tools</a>';
    echo '<li><a href="fa-endofday.php">Free Agency</a>';
    echo '<li><a href="depthcharts.php">Depth Chart Tool</a>';
    echo '<li><a href="transactionlog.php">Transaction Log</a>';
    echo '<li><a href="fareport.php">Signed Free Agent Report</a>';
    echo '<li><a href="thakfu.php">ThakFu\'s Private Stash</a>';
    echo '</ul><br><br>';
    echo 'Information held in session:<br><ul>';
    foreach ($_SESSION as $key => $sesh) {
        echo '<li>' . $key;
        echo ' - ' . $sesh;
    }
    echo '</ul>';






} else {
    echo 'You are not authorized to be here.';
}

?>