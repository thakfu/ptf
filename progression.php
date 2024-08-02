<?php
echo '<h2>' .  $player['FullName'] . ' Career Progression</h2>';

$playerService = progressionService($_GET['player']);

echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>Year</th>';
echo '<th>Type</th>';
echo '<th>Strength</th>';
echo '<th>Agility</th>';
echo '<th>Speed</th>';
echo '<th>Hands</th>';
echo '<th>Intelligence</th>';
echo '<th>Accuracy</th>';
echo '<th>Arm</th>';
echo '<th>Run Block</th>';
echo '<th>Pass Block</th>';
echo '<th>Tackling</th>';
echo '<th>Endurance</th>';
echo '<th>Kick Dist.</th>';
echo '<th>Kick Acc.</th>';
echo '</tr>';

foreach ($playerService as $players) {
    if(intval($players['Season']) != (intval($player['DraftSeason']) - 1)) {
        if ($players['Type'] == '') {
            $type = 'Pre-Camp';
        } else {
            $type = $players['Type'];
        }
        echo '<tr><td>' .
            $players['Season'] . '</td><td>' .
            $type . '</td><td>' .
            $players['Strength'] . '</td><td>' .
            $players['Agility'] . '</td><td>' .
            $players['Speed'] . '</td><td>' .
            $players['Hands'] . '</td><td>' .
            $players['Intelligence'] . '</td><td>' .
            $players['Accuracy'] . '</td><td>' .
            $players['Arm'] . '</td><td>' .
            $players['RunBlocking'] . '</td><td>' .
            $players['PassBlocking'] . '</td><td>' .
            $players['Tackling'] . '</td><td>' .
            $players['Endurance'] . '</td><td>' .
            $players['KickDistance'] . '</td><td>' .
            $players['KickAccuracy'] . '</td>' .
            '</tr>';
    }
}

$playerService = playerService(0,$_GET['player'],0);
foreach ($playerService as $player) {
    echo '<tr><th>' .
    '</th><th>' .
    'CURRENT</th><th>' .
    $player['Strength'] . '</th><th>' .
    $player['Agility'] . '</th><th>' .
    $player['Speed'] . '</th><th>' .
    $player['Hands'] . '</th><th>' .
    $player['Intelligence'] . '</th><th>' .
    $player['Accuracy'] . '</th><th>' .
    $player['Arm'] . '</th><th>' .
    $player['RunBlocking'] . '</th><th>' .
    $player['PassBlocking'] . '</th><th>' .
    $player['Tackling'] . '</th><th>' .
    $player['Endurance'] . '</th><th>' .
    $player['KickDistance'] . '</th><th>' .
    $player['KickAccuracy'] . '</th>' .
    '</tr>';
}
echo '</table><br><br>';



?>