<?php

include 'adminheader.php';
if ($_SESSION['admin'] < 1) {
    echo 'You are not authorized to be here.';
} else {

    echo '<table border = "1"><tr>';
    for($x = 1;$x < 19;$x++) {
        echo '<td><h2>'. idToAbbrev($x) .' Latest List</h2>';
        $roster = $connection->query("SELECT * FROM ptf_draft_list WHERE TeamID = " . $x . " ORDER BY TIME DESC");
        $list = $roster->fetch_assoc();
        echo '<ul align="left">';
        echo '<li align="left">' . $list['Choice13'];
        echo '<li align="left">' . $list['Choice14'];
        echo '<li align="left">' . $list['Choice15'];
        echo '<li align="left">' . $list['Choice16'];
        echo '<li align="left">' . $list['Choice17'];
        echo '<li align="left">' . $list['Choice18'];
        echo '<li align="left">' . $list['Choice19'];
        echo '<li align="left">' . $list['Choice20'];
        echo '</ul></td>';
        if ($x == 5 || $x == 10 || $x == 15) {
            echo '</tr>';
        }
    }
    echo '</tr></table>';

}
?>