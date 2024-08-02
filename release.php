<?php

include 'header.php';

$playerInfo = $connection->query("SELECT p.FirstName, p.LastName, p.Position, s.squadTeam, i.squadTeam as 'irTeam' FROM ptf_players p LEFT JOIN ptf_players_ir i ON p.PlayerID = i.PlayerID LEFT JOIN ptf_players_squad s ON p.PlayerID = s.PlayerID WHERE p.PlayerID = " . $_GET['PlayerID']);
$playerInf = $playerInfo->fetch_assoc();
$player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];

echo '<div id="release">Are you sure you want to release ' . $player . '?</br><br>';
echo '<form action="submit_release.php" method="POST">';
echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
echo '<input type="hidden" id="Pos" name="Pos" value="' . $playerInf['Position'] . '">';
echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';

$irCheck1 = $connection->query("SELECT InjuryLength as 'check' FROM `ptf_players` where PlayerID = {$_GET['PlayerID']}");
$check1 = $irCheck1->fetch_assoc();
$irCheck2 = $connection->query("SELECT start as 'check' FROM `ptf_players_ir` where PlayerID = {$_GET['PlayerID']}");
$check2 = $irCheck2->fetch_assoc();
if (intval($check2['check']) <= intval($curWeek - 3)) {
    if ($playerInf['irTeam'] == $_SESSION['TeamID']) {
        echo 'Activate ' . $player . ' from injured reserve: ';
        echo '<input type="submit" name="activate" value="activate"><br><br><br><br>';
    }
}

if (str_contains($check1['check'],'Out')) {
     if (!$check2['check']) {
        echo 'Place ' . $player . ' on injured reserve: ';
        echo '<input type="submit" name="IR" value="IR"><br><br><br><br>';
    }
}

if ($playerInf['squadTeam'] == $_SESSION['TeamID']) {
    echo 'Promote ' . $player . ' from the practice squad: ';
    echo '<input type="submit" name="promote" value="promote"><br><br><br><br>';
} else {
    echo 'Demote ' . $player . ' to the practice squad: ';
    echo '<input type="submit" name="demote" value="demote"><br><br><br><br>';
}

echo 'Release ' . $player . ' from your roster: ';
echo '<input type="submit" name="release" value="release"><br><br>';
echo '</form>';

?>