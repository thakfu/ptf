<?php

require('../../../sql/phpmysqlconnect.php');


//REMOVE BEFORE RUNNING!!!!!
//exit;
//REMOVE BEFORE RUNNING!!!!!

$stmt = $connection->query('SELECT round, pick, playerID FROM `ptf_draft_picks` WHERE playerID != 0 and year = "1987" and round = 7');
$players = array();
while($row = $stmt->fetch_assoc()) {
    array_push($players, $row);
}

foreach ($players as $player) {
    
    switch ($player['round']) {
        case 1:
            if (in_array($player['pick'],[1,2,3,4,5])) {
                $sal = 3000000;
            } elseif (in_array($player['pick'],[6,7,8,9,10])) {
                $sal = 2500000;
            } elseif (in_array($player['pick'],[11,12,13,14,15])) {
                $sal = 2000000;
            } elseif (in_array($player['pick'],[16,17,18,19,20])) {
                $sal = 1750000;
            }
            $yrs = 4;
            break;
        case 2:
            if (in_array($player['pick'],[1,2,3,4,5])) {
                $sal = 1500000;
            } elseif (in_array($player['pick'],[6,7,8,9,10])) {
                $sal = 1250000;
            } elseif (in_array($player['pick'],[11,12,13,14,15])) {
                $sal = 1000000;
            } elseif (in_array($player['pick'],[16,17,18,19,20])) {
                $sal = 750000;
            }
            $yrs = 3;
            break;
        case 3:
            $yrs = 3;
            $sal = 750000;
            break;
        case 4:
            $yrs = 3;
            $sal = 500000;
            break;
        case 5:
        case 6:
        case 7:
            $yrs = 2;
            $sal = 250000;
            break;
    }

    switch ($yrs) {
        case 1:
            $sal1 = $sal; 
            $sal2 = 0;
            $sal3 = 0;
            $sal4 = 0;
            break;
        case 2:
            $sal1 = $sal; 
            $sal2 = $sal; 
            $sal3 = 0;
            $sal4 = 0;
            break;
        case 3:
            $sal1 = $sal; 
            $sal2 = $sal; 
            $sal3 = $sal; 
            $sal4 = 0;
            break;
        case 4:
            $sal1 = $sal; 
            $sal2 = $sal; 
            $sal3 = $sal; 
            $sal4 = $sal; 
            break;
        }

    //$insert = $connection->query    
    //("UPDATE `ptf_players_salaries` SET contract_length = {$yrs} WHERE {$player['PlayerID']} = player_id");
    echo     "INSERT INTO `ptf_players_salaries` 
    (PlayerID,
    `1987`,
    `1988`,
    `1989`,
    `1990`)
    VALUES 
    ({$player['playerID']},
    {$sal1},
    {$sal2},
    {$sal3},
    {$sal4})" . '<br>';
    $insert = $connection->query("INSERT INTO `ptf_players_salaries` (PlayerID,`1987`,`1988`,`1989`,`1990`,`1991`,`1992`,`1993`,`1994`,`1995`,`1996`,`1997`,`1985`,`1986`) VALUES 
            ({$player['playerID']},{$sal1},{$sal2},{$sal3},{$sal4},0,0,0,0,0,0,0,0,0)"); 
    }

?>