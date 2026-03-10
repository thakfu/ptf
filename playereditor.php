<?php
include 'header.php';

$playerService = playerService(0,$_GET['player'],0);
$player = $playerService[0];

var_dump($player);

$stmt = $connection->query('SELECT * FROM `ptf_players_data` WHERE PlayerID = ' . $_GET['player']);
$pdata = array();

$pdata = $stmt->fetch_assoc();

echo '<center><h2>' . $player['FullName'] . '</h2></center><br>'; 

echo '<form><table>';

echo '<tr><th>Nickname</th>';
echo '<td><input type="text" id="firstname" name="firstname" value="'.$pdata['Nickname'].'"></td></tr>';
echo '<tr><th>Jersey #</th>';
echo '<td><input type="number" min=0 max=99 id="lastname" name="lastname" value="'.$pdata['uniform'].'"></td></tr>';
echo '</table>';

echo '<center><h3>Status and Awards</h3><br>';
echo '<p>These setting have no affect on gameplay and are purely for fun.</p></center>';

echo '<table>';
echo '<tr><th>Status (Captain, Assistant Captain, etc)</th>';
echo '<td><input type="text" id="firstname" name="firstname" value="'.$pdata['status'].'"></td></tr>';
echo '<tr><th>Team Issued Awards</th>';
echo '<td><input type="text" id="firstname" name="firstname" value="'.$pdata['custom_awards'].'"></td></tr>';
echo '<tr><th>Helmet Stickers</th>';
echo '<td><input type="number" min=0 max=99 id="lastname" name="lastname" value="'.$pdata['stickers'].'"></td></tr>';
echo '<tr><th>Player News Article</th>';
echo '<td><textarea rows=20 cols=50 value="'.$pdata['News'].'"></textarea></td></tr>';
echo '<tr><th>Notes</th>';
echo '<td><textarea rows=20 cols=50 value="'.$pdata['notes'].'"></textarea></td></tr>';
echo '</table>';
?>