<?php

//require('../header.php');
require('../../../sql/phpmysqlconnect.php');
require('../../ptf-services/player-service.php');
//require('../../ptf-services/team-service.php');

//REMOVE BEFORE RUNNING!!!!!
//exit;
//REMOVE BEFORE RUNNING!!!!!

// VARIABLES //
$draftStart = 6242; // IMPORTANT!!!!   The Players file includes incoming draft class.  This is the PLAYERID number where draft picks BEGIN! //
$salaryCap = 105000000;

//$var = 'ext';
$var = 'fa';

if($var == 'ext') {
    $playerService = playerService(0,0,0);
} else {
    $playerService = playerService('fa',0,0);
}

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

$year = 1987;


// ARRAYS //
$positions = array('QB','RB','FB','WR','TE','G','T','C','DT','DE','LB','CB','SS','FS','P','K');

//REMOVE BEFORE RUNNING!!!!!
//exit;
//REMOVE BEFORE RUNNING!!!!!

echo '<h2>FREE AGENCY DEMANDS</h2>';

echo '<p>If these demands looks good, click the below button to send the results to the data table!  If they do not, then make changes in the code.  
     If you aren\'t Fish, you this doesn\'t apply to you.  In fact, you shouldnt be here unless you were invited!</p>';

if(isset($_POST['finalize'])){

    $passed_array = unserialize($_POST['datatable']);
    foreach ($passed_array as $row) {
        $values .=  '(' . $row[0] . ',"' . $row[1] . '",' . $row[2] . ',' . $row[3] . ',' . $row[4] . ',' . $row[5] . ',"' . $row[6] . '"),';
    }
    $values = substr($values, 0, -1);

    $keys2 = array('PlayerID','Position','year','amount','tier','previous','string');

    foreach($keys2 as $key2) {
        $update2 .= '`' . $key2 . '` = VALUES(`' . $key2 . '`),';
    }
    $upsert2 = substr($update2, 0, -1);

    echo 'INSERT INTO `ptf_fa_demands` (PlayerID, Position, year, amount, tier, previous, string) VALUES ' . $values;

    if($var == 'ext') {
        $write = $connection->query('INSERT INTO `ptf_extend_demands` (PlayerID, Position, year, amount, tier, previous, string) VALUES ' . $values . ' ON DUPLICATE KEY UPDATE ' . $upsert2 . ';');
    } else {
        $write = $connection->query('INSERT INTO `ptf_fa_demands` (PlayerID, Position, year, amount, tier, previous, string) VALUES ' . $values . ';');
    }

    echo '<p>The Data has been written to the database!  DO NOT REFRESH THE PAGE OR CLICK BACK!   <a href="http://www.thakfu.com/ptf/index.php">Click Here To Leave</a>.</p>';
}

echo '<form action="" method="POST">';


// This opens the file that contains the league averages at each position and puts them into the $salaries array.
$csvFile = fopen('../league-pay-averages.csv', 'r');
$salaries = array();
while(($data = fgetcsv($csvFile, 100, ',')) !== FALSE){
    for($i = 0; $i < count($data); $i++) {
        if ($i == 0 || $i % 2 == 0) {
            //even
            $key = $data[$i];
        } else {
            //odd
            $value = $data[$i];
            $salaries[$key] = $value;
        }
    }
}
foreach ($positionAverages as $key => $value) {
    $csvFile->fputcsv(array($key, $value));
}
$csvFile = null;
// CSV is now closed and the array takes over.

// Let's make a table for each position, for readibility.  
$writeTable = array();
foreach ($positions as $position) {
    echo '<table><tr><th>Name</th><th>Age</th><th>Prev. Team</th><th>1986 Salary</th><th>Overall</th><th>Money</th><th>TOTAL</th><th>Pos</th><th>Initial Demand</th><th>Detailed Demand</th></tr>';
    foreach ($playerService as $player) {
        if ($player['RetiredSeason'] == 0) {
            if ($player['Position'] == $position) {
                $writeRow = array();
                $previous = $player[$year - 1];

                if ($previous == 0) {
                    $previous = 250000;
                } 

                // $moneyDemand is the player's base demand trigger.
                $moneyDemand = $player['Overall'] + $player['Money'];
                    // TRIGGER POINTS //
                if ($moneyDemand >= 185 || $player['Overall'] >= 99) {
                    $demandString = 'I want to be the highest paid player at my position!';
                    $sal = $salaries[$player['Position'] . 'TOP'];
                    $demandAmt = $sal + $sal*(10/100) ;
                    $tier = 1;
                } elseif (($moneyDemand <= 185 && $moneyDemand >= 175) || $player['Overall'] >= 90) {
                    $demandString = 'I want to be in the TOP 5 highest paid players at my position!';
                    $demandAmt = $salaries[$player['Position'] . '5'];
                    $tier = 2;
                } elseif ($player['Overall'] > 65 && $player['Overall'] < 80 && $player['Money'] >= 85) {
                    $demandString = 'I want to make ATLEAST the league average for my position!';
                    $demandAmt = $salaries[$player['Position'] . 'ALL'];
                    $tier = 3;
                } else {
                    // For all players who don't fall under a trigger point, we need to figure out how much money they want.
                    $demandString = 'I have a specific financial requirement, please meet it!';
                    $tier = 0;
                    if ($player['Overall'] > 65) {
                        $sal = $salaries[$player['Position'] . 'ALL'];
                    } elseif ($player['Overall'] > 55 && $player['Overall'] <= 65) {
                        $sal = $salaries[$player['Position'] . 'ALL'] / 1.5;
                    } elseif ($player['Overall'] > 45 && $player['Overall'] <= 55) {
                        $sal = $salaries[$player['Position'] . 'ALL'] / 2;
                    } else {
                        $sal = $salaries[$player['Position'] . 'ALL'] / 4;
                    }
                    $calc = ($player['Overall'] - 65) + ($player['Money'] - 80);
                    $demandAmt = $sal + $sal*($calc/100);
                }
                if ($player['Age'] < 29) {
                    if ($previous > $demandAmt) {
                        $demandString = 'I\'m not taking a pay cut! I got plenty of good years left!';
                    }
                    $demandAmt = max($previous, $demandAmt);
                } elseif ($player['Age'] >= 29 && $player['Age'] <= 32) {
                    if ($previous > $demandAmt) {
                        $diff = $previous - $demandAmt;
                        $demandAmt = $demandAmt + ($diff / 2);
                        $demandString = 'I\'ll take a small pay cut... But I had a great salary last year!';
                    }
                }
                
                $prevTeam = $connection->query("SELECT Team, TeamID FROM `ptf_players_season_stats_1985` where Season = 1986 AND PlayerID = '" . $player['PlayerID'] . "'");
                $prev = $prevTeam->fetch_assoc();

                if($prev['TeamID'] == '') {
                    $previousTeam = 0;
                } else {
                    $previousTeam = $prev['TeamID'];
                }

                echo '<tr><td>'. $player['FirstName'] . ' ' . $player['LastName'] .'</td><td>' . $player['Age'] . '</td><td>' . $prev['Team'] . '</td><td>' . $previous . '</td><td>' . $player['Overall'] . '</td><td>' . $player['Money'] . '</td><td>' . $moneyDemand . '</td><td>' . $player['AltPosition'] . '</td><td>' . $demandString . '</td><td>' . number_format($demandAmt) . '</td>';
                echo '</tr>';
                array_push($writeRow, $player['PlayerID'],$player['Position'],1986,$demandAmt,$tier,$previousTeam,$demandString);
                array_push($writeTable,$writeRow);
            }
        }
    }
    echo '</table><br>';
}
echo '<input type="hidden" name="datatable" value="' . htmlentities(serialize($writeTable)) . '">';
echo '<input type="submit" name="finalize" value="Finalize"><br><br>';

?>