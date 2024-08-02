<?php

include 'header.php';

$playerInfo = $connection->query("SELECT FirstName, LastName, Position, AltPosition FROM ptf_players WHERE PlayerID = " . $_GET['PlayerID']);
$playerInf = $playerInfo->fetch_assoc();
$player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];

echo '<div id="release">Are you sure you want to change ' . $player . '\'s position?</br><br>';
echo 'Currently: ' . $playerInf['Position'] . '<br><br>';
echo '<form action="submit_release.php" method="POST">';
echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';

echo 'Change ' . $player . '\'s position: ';
echo '<select name="pos">
        <option value="QB">QB</option>
        <option value="RB">RB</option>
        <option value="FB">FB</option>
        <option value="WR">WR</option>
        <option value="TE">TE</option>
        <option value="G">G</option>
        <option value="T">T</option>
        <option value="C">C</option>
        <option value="DT">DT</option>
        <option value="DE">DE</option>
        <option value="LB">LB</option>
        <option value="CB">CB</option>
        <option value="FS">FS</option>
        <option value="SS">SS</option>
        <option value="P">P</option>
        <option value="K">K</option>
    </select><br><br>';
echo '<input type="submit" name="change" value="change"><br><br>';
echo '</form>';

?>