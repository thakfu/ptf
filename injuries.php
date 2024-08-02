<?php

include 'header.php';
$playerService = playerService(0,0,0);

usort($playerService, fn($a, $b) => $a['PosSort'] <=> $b['PosSort']);

echo '<h2>Injury Report</h2>';
for ($x = 1; $x <= 18; $x++ ) {
    echo'<table id="'.idToAbbrev($x).'"><tr><th colspan = 4>' . idToAbbrev($x) . ' Injuries</th></tr>';
    foreach ($playerService as $player) {
        if ($player['InjuryLength'] != '') {
            if ($player['TeamID'] == $x) {
                echo '<tr><td>' . $player['FullName'] . '</td><td>' . $player['Position'] . '</td><td>' . $player['Injury'] . '</td><td>' . $player['InjuryLength'] . '</td></tr>';
            }
        }
    }
    echo '</table><br><br>';
}

?>