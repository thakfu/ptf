<?php
echo '<h2>' .  $player['FullName'] . ' Career Progression</h2>';

$playerService2 = progressionService($_GET['player']);

echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>Year</th>';
echo '<th>Type</th>';
echo '<th>OVERALL</th>';
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



foreach ($playerService2 as $players) {

    $greatest = max($players['QB'],$players['RB'],$players['FB'],$players['WR'],$players['TE'],$players['C'],$players['G'],$players['T'],$players['DT'],$players['DE'],$players['LB'],$players['CB'],$players['SS'],$players['FS'],$players['K'],$players['P']);
    if ($players['Position'] == 'QB') {
        $overall =  round((($players['Strength'] * 3) + ($players['Agility'] * 10) + ($players['Arm'] * 30) + ($players['Intelligence'] * 30) + ($players['Accuracy'] * 40) + ($players['Tackling'] * 0) + ($players['Speed'] * 10) + ($players['Hands']* 0) + ($players['PassBlocking'] * 0) + ($players['RunBlocking'] * 0) + ($players['KickDistance'] * 0) + ($players['KickAccuracy'] * 0) + ($players['Endurance'] * 1) + ($greatest * 10)) / 125);
    } elseif ($players['Position'] == 'RB') {
        $overall =  round((($players['Strength'] * 20) + ($players['Agility'] * 25) + ($players['Arm'] * 0) + ($players['Intelligence'] * 10) + ($players['Accuracy'] * 0) + ($players['Tackling'] * 0) + ($players['Speed'] * 50) + ($players['Hands']* 20) + ($players['PassBlocking'] * 3) + ($players['RunBlocking'] * 3) + ($players['KickDistance'] * 0) + ($players['KickAccuracy'] * 0) + ($players['Endurance'] * 1) + ($greatest * 10)) / 125);
    }
    /*
    '<td>' . round((($str * 30) + ($agl * 5) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 10) + ($hnd * 20) + ($pbl * 48) + ($rbl * 48) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 15) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 45) + ($hnd * 30) + ($pbl * 3) + ($rbl * 3) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 30) + ($agl * 20) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 25) + ($hnd * 30) + ($pbl * 15) + ($rbl * 15) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 0) + ($spd * 15) + ($hnd * 0) + ($pbl * 30) + ($rbl * 45) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 0) + ($spd * 15) + ($hnd * 0) + ($pbl * 45) + ($rbl * 30) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 0) + ($spd * 15) + ($hnd * 0) + ($pbl * 37) + ($rbl * 37) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 50) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 45) + ($spd * 10) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 30) + ($agl * 15) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 40) + ($spd * 35) + ($hnd * 15) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 35) + ($agl * 15) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 35) + ($spd * 30) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 25) + ($arm * 0) + ($int * 20) + ($acc * 0) + ($tck * 30) + ($spd * 40) + ($hnd * 25) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 25) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 25) + ($spd * 35) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 25) + ($spd * 40) + ($hnd * 25) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 5) + ($arm * 0) + ($int * 25) + ($acc * 0) + ($tck * 0) + ($spd * 10) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 35) + ($kac * 40) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 5) + ($arm * 0) + ($int * 25) + ($acc * 0) + ($tck * 0) + ($spd * 10) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 40) + ($kac * 35) + ($end * 1) + ($greatest * 10)) / 125) . '</td>' .
    '</tr>*/



   // if(intval($players['Season']) != (intval($player['DraftSeason']) - 1)) {
        if ($players['Type'] == '') {
            $type = 'Pre-Camp';
        } else {
            $type = $players['Type'];
        }
        echo '<tr><td>' .
            $players['Season'] . '</td><td>' .
            $type . '</td><td>' .
            $overall . '</td><td>' .
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
//}

$playerService = playerService(0,$_GET['player'],0);
foreach ($playerService as $player) {
    echo '<tr><th>' .
    '</th><th>' .
    'CURRENT</th><th>' .
    $player['Overall'] . '</th><th>' .
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