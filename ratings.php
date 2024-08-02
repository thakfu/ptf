<?php
echo '<h2>' .  $player['FullName'] . ' ' . $year . ' Ratings & Salary </h2>';

echo '<h3 align="center">Attributes</h3>';
echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>Overall</th>';
echo '<th>Strength</th>';
echo '<th>Agility</th>';
echo '<th>Speed</th>';
echo '<th>Hands</th>';
echo '<th>Intelligence</th>';
echo '<th>Arm</th>';
echo '<th>Accuracy</th>';
echo '<th>Run Block</th>';
echo '<th>Pass Block</th>';
echo '<th>Tackling</th>';
echo '<th>Endurance</th>';
echo '<th>Kick Dist.</th>';
echo '<th>Kick Acc.</th>';
echo '</tr>';

$playerService = playerService(0,$_GET['player'],0);
foreach ($playerService as $player) {
    echo '<tr><td>' .
    $player['Overall'] . '</td><td>' .
    $player['Strength'] . '</td><td>' .
    $player['Agility'] . '</td><td>' .
    $player['Speed'] . '</td><td>' .
    $player['Hands'] . '</td><td>' .
    $player['Intelligence'] . '</td><td>' .
    $player['Arm'] . '</td><td>' .
    $player['Accuracy'] . '</td><td>' .
    $player['RunBlocking'] . '</td><td>' .
    $player['PassBlocking'] . '</td><td>' .
    $player['Tackling'] . '</td><td>' .
    $player['Endurance'] . '</td><td>' .
    $player['KickDistance'] . '</td><td>' .
    $player['KickAccuracy'] . '</td>' .
    '</tr></table>';
}

echo '<h3 align="center">Personality</h3>';
echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>Leadership</th>';
echo '<th>Work Ethic</th>';
echo '<th>Competitive</th>';
echo '<th>Team Player</th>';
echo '<th>Sportsmanship</th>';
echo '<th>Social Dispo.</th>';
echo '<th>Money</th>';
echo '<th>Security</th>';
echo '<th>Loyalty</th>';
echo '<th>Winning</th>';
echo '<th>Playing Time</th>';
echo '<th>Close Home</th>';
echo '<th>Market</th>';
echo '<th>Morale</th>';
echo '</tr>';



$playerService = playerService(0,$_GET['player'],0);
//30-50
if ($player['Security'] <= 35) {
    $sec = '1-2 Yrs';
} elseif ($player['Security'] <= 40 && $player['Security'] > 35) {
    $sec = '1-4 Yrs';
} elseif ($player['Security'] <= 45 && $player['Security'] > 40) {
    $sec = '3-6 Yrs';
} elseif ($player['Security'] >= 46) {
    $sec = '5-6 Yrs';
}
//30-50
if ($player['Loyalty'] <= 32) {
    $loy = 'D';
} elseif ($player['Loyalty'] <= 39 && $player['Loyalty'] > 32) {
    $loy = 'C';
} elseif ($player['Loyalty'] <= 47 && $player['Loyalty'] > 39) {
    $loy = 'B';
} elseif ($player['Loyalty'] >= 48) {
    $loy = 'A';
}
//30-70
if ($player['Winning'] <= 40) {
    $win = 'D';
} elseif ($player['Winning'] <= 50 && $player['Winning'] > 40) {
    $win = 'C';
} elseif ($player['Winning'] <= 60 && $player['Winning'] > 50) {
    $win = 'B';
} elseif ($player['Winning'] >= 61) {
    $win = 'A';
}
//70-100
if ($player['PlayingTime'] <= 75) {
    $pt = 'D';
} elseif ($player['PlayingTime'] <= 85 && $player['PlayingTime'] > 75) {
    $pt = 'C';
} elseif ($player['PlayingTime'] <= 92 && $player['PlayingTime'] > 85) {
    $pt = 'B';
} elseif ($player['PlayingTime'] >= 93) {
    $pt = 'A';
}
//30-50
if ($player['CloseToHome'] <= 32) {
    $cth = 'D';
} elseif ($player['CloseToHome'] <= 39 && $player['CloseToHome'] > 32) {
    $cth = 'C';
} elseif ($player['CloseToHome'] <= 47 && $player['CloseToHome'] > 39) {
    $cth = 'B';
} elseif ($player['CloseToHome'] >= 48) {
    $cth = 'A';
}
//30-70
if ($player['MarketSize'] <= 40) {
    $mar = 'Small';
} elseif ($player['MarketSize'] <= 50 && $player['MarketSize'] > 40) {
    $mar = 'Sm-Mid';
} elseif ($player['MarketSize'] <= 60 && $player['MarketSize'] > 50) {
    $mar = 'Lg-Mid';
} elseif ($player['MarketSize'] >= 61) {
    $mar = 'Large';
}

foreach ($playerService as $player) {
    echo '<tr><td>' .
    $player['Leadership'] . '</td><td>' .
    $player['WorkEthic'] . '</td><td>' .
    $player['Competitiveness'] . '</td><td>' .
    $player['TeamPlayer'] . '</td><td>' .
    $player['Sportsmanship'] . '</td><td>' .
    $player['SocialDisposition'] . '</td><td>' .
    $player['Money'] . '</td><td>' .
    $sec . '</td><td>' .
    $loy . '</td><td>' .
    $win . '</td><td>' .
    $pt . '</td><td>' .
    $cth . '</td><td>' .
    $mar . '</td><td>' .
    $player['Morale'] . '</td>' .
    '</tr></table>';
}

echo '<h3 align="center">Position Skills</h3>';
echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>QB</th>';
echo '<th>RB</th>';
echo '<th>FB</th>';
echo '<th>WR</th>';
echo '<th>TE</th>';
echo '<th>G</th>';
echo '<th>T</th>';
echo '<th>C</th>';
echo '<th>DT</th>';
echo '<th>DE</th>';
echo '<th>LB</th>';
echo '<th>CB</th>';
echo '<th>SS</th>';
echo '<th>FS</th>';
echo '<th>K</th>';
echo '<th>P</th>';
echo '</tr>';

$playerService = playerService(0,$_GET['player'],0);
foreach ($playerService as $player) {

    $ovr = $player['Overall'];
    $str = $player['Strength']; 
    $agl = $player['Agility'];
    $spd = $player['Speed']; 
    $hnd = $player['Hands']; 
    $int = $player['Intelligence'];
    $arm = $player['Arm']; 
    $acc = $player['Accuracy']; 
    $rbl = $player['RunBlocking']; 
    $pbl = $player['PassBlocking']; 
    $tck = $player['Tackling'];
    $end = $player['Endurance']; 
    $kds = $player['KickDistance']; 
    $kac = $player['KickAccuracy'];

    echo '<tr><td>' .
    $player['QB'] . '</td><td>' .
    $player['RB'] . '</td><td>' .
    $player['FB'] . '</td><td>' .
    $player['WR'] . '</td><td>' .
    $player['TE'] . '</td><td>' .
    $player['G'] . '</td><td>' .
    $player['T'] . '</td><td>' .
    $player['C'] . '</td><td>' .
    $player['DT'] . '</td><td>' .
    $player['DE'] . '</td><td>' .
    $player['LB'] . '</td><td>' .
    $player['CB'] . '</td><td>' .
    $player['SS'] . '</td><td>' .
    $player['FS'] . '</td><td>' .
    $player['K'] . '</td><td>' .
    $player['P'] . '</td></tr></table>';
    /*'</tr><tr><th colspan=16>**ESTIMATED** OVERALL POSITION RATING</th></tr><tr>' . 
    '<td>' . round((($str * 3) + ($agl * 10) + ($arm * 30) + ($int * 30) + ($acc * 40) + ($tck * 0) + ($spd * 10) + ($hnd * 0) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['QB'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 50) + ($hnd * 20) + ($pbl * 3) + ($rbl * 3) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['RB'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 30) + ($agl * 5) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 10) + ($hnd * 20) + ($pbl * 48) + ($rbl * 48) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['FB'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 15) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 45) + ($hnd * 30) + ($pbl * 3) + ($rbl * 3) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['WR'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 30) + ($agl * 20) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 0) + ($spd * 25) + ($hnd * 30) + ($pbl * 15) + ($rbl * 15) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['TE'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 0) + ($spd * 15) + ($hnd * 0) + ($pbl * 30) + ($rbl * 45) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['G'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 0) + ($spd * 15) + ($hnd * 0) + ($pbl * 45) + ($rbl * 30) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['T'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 20) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 0) + ($spd * 15) + ($hnd * 0) + ($pbl * 37) + ($rbl * 37) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['C'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 50) + ($agl * 5) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 45) + ($spd * 10) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['DT'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 50) + ($agl * 15) + ($arm * 0) + ($int * 15) + ($acc * 0) + ($tck * 40) + ($spd * 35) + ($hnd * 15) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['DE'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 35) + ($agl * 15) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 35) + ($spd * 30) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['LB'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 25) + ($arm * 0) + ($int * 20) + ($acc * 0) + ($tck * 30) + ($spd * 40) + ($hnd * 25) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['CB'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 25) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 25) + ($spd * 35) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['SS'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 25) + ($arm * 0) + ($int * 10) + ($acc * 0) + ($tck * 25) + ($spd * 40) + ($hnd * 25) + ($pbl * 0) + ($rbl * 0) + ($kds * 0) + ($kac * 0) + ($end * 1) + ($player['FS'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 5) + ($arm * 0) + ($int * 25) + ($acc * 0) + ($tck * 0) + ($spd * 10) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 35) + ($kac * 40) + ($end * 1) + ($player['K'] * 10)) / 125) . '</td>' .
    '<td>' . round((($str * 10) + ($agl * 5) + ($arm * 0) + ($int * 25) + ($acc * 0) + ($tck * 0) + ($spd * 10) + ($hnd * 10) + ($pbl * 0) + ($rbl * 0) + ($kds * 40) + ($kac * 35) + ($end * 1) + ($player['P'] * 10)) / 125) . '</td>' .
    '</tr></table>';*/
}


echo '<h3 align="center">Contract</h3>';
echo '<table class="roster" border=1 id="'.$curteam.'">';
echo '<tr>';
echo '<th>TOTAL</th>';
echo '<th>' . $year . '</th>';
echo '<th>' . $year + 1 . '</th>';
echo '<th>' . $year + 2 . '</th>';
echo '<th>' . $year + 3 . '</th>';
echo '<th>' . $year + 4 . '</th>';
echo '<th>' . $year + 5 . '</th>';
echo '<th>' . $year + 6 . '</th>';
echo '</tr>';

$playerService = playerService(0,$_GET['player'],0);
$total = $player[$year] + $player[$year + 1] + $player[$year + 2] + $player[$year + 3] + $player[$year + 4] + $player[$year + 5] + $player[$year + 6];
foreach ($playerService as $player) {
    echo '<tr><td>$' .
    number_format($total) . '</td><td>$' .
    number_format($player[$year]) . '</td><td>$' .
    number_format($player[$year + 1]) . '</td><td>$' .
    number_format($player[$year + 2]) . '</td><td>$' .
    number_format($player[$year + 3]) . '</td><td>$' .
    number_format($player[$year + 4]) . '</td><td>$' .
    number_format($player[$year + 5]) . '</td><td>$' .
    number_format($player[$year + 6]) . '</td>' .
    '</tr></table>';
}


echo '<br>';
?>