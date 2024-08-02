<?php

include 'header.php';

$playerInfo = $connection->query("SELECT FirstName, LastName, Position, AltPosition FROM ptf_players WHERE PlayerID = " . $_GET['PlayerID']);
$playerInf = $playerInfo->fetch_assoc();
$player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];

echo '<div id="release">Are you sure you want to sign ' . $player . '\' to a contract?  He will be signed for the league minimum.</br><br>';
echo '<form action="submit_release.php" method="POST">';
echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
echo '<input type="hidden" id="Pos" name="Pos" value="' . $playerInf['Position'] . '">';
echo '<input type="hidden" id="Abbreviation" name="Abbreviation" value="' . $_SESSION['abbreviation'] . '">';

echo 'Sign ' . $player . ': ';

echo '<input type="submit" name="sign" value="sign"><br><br>';
echo '</form>';

?>