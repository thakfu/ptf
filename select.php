<?php

include 'header.php';

$year = 1987;
$stmt2= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.owner = t.TeamID WHERE d.year = ' . $year . ' and d.current = "1" ORDER BY d.round ASC, d.pick ASC');
$draftID = array();
while($row = $stmt2->fetch_assoc()) {
    array_push($draftID, $row);
}

$playerInfo = $connection->query("SELECT FirstName, LastName, Position, AltPosition FROM ptf_players WHERE PlayerID = " . $_GET['PlayerID']);
$playerInf = $playerInfo->fetch_assoc();
$player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];

echo '<div id="release">Are you sure you want to draft ' . $player . '?  </br><br>';
echo '<form action="submit_release.php" method="POST">';
echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
echo '<input type="hidden" id="pos" name="pos" value="' . $playerInf['Position'] . '">';
echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
echo '<input type="hidden" id="draftID" name="draftID" value="' . $draftID[0]['draftID'] . '">';
echo '<input type="hidden" id="round" name="round" value="' . $draftID[0]['round'] . '">';
echo '<input type="hidden" id="pick" name="pick" value="' . $draftID[0]['pick'] . '">';
echo '<input type="hidden" id="Abbreviation" name="Abbreviation" value="' . $_SESSION['abbreviation'] . '">';

echo 'Draft ' . $player . ': ';

echo '<input type="submit" name="draft" value="draft"><br><br>';
echo '</form>';

?>