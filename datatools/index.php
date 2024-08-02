<?php
session_start();

if ($_SESSION['admin'] !== '2') {
    echo 'LOL... Yeah right';
} else {

    echo "<h1>DATA TOOLS</h1>
    <p>Click on the link below to process the various data tools.  Be sure what you are doing before clicking ANYTHING on this page!!!!</p>
    <br>
    <h2>Season Tools</h2>
    <ul>
        <li><a href='dataimport.php'>DATA IMPORT</a> - This imports all data from dataexports to the database! Run this after every sim!!!
    </ul>
    <h2>Offseason Tools</h2>
    <ul>
        <li><a href='freeagentdemands.php'>FREE AGENT DEMANDS</a> - Sets player demands for free agency.  Run this at the beginning of FA ONLY ONCE!!
        <li><a href='finalizefasignings.php'>FINALIZE FA SIGNINGS</a> - Imports all free agency results to the database.  Run this after every night of free agency!!
    </ul>
    <h2>Misc Tools</h2>
    <ul>
        <li><a href='insertsalary.php'>INSERT SALARY</a> - DEPRICATED!!!  Assigned salaries to every player in a previous test league.  DONT USE!!!
    </ul>";

}