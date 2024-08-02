<?php
require('../../../sql/phpmysqlconnect.php');
$leaguestmt = $connection->query("SELECT * FROM ptf_league ");
$league = array();
while($row = $leaguestmt->fetch_assoc()) {
    array_push($league, $row);
}

$year = $league[5]['value'];
$day = $league[20]['value'];

//REMOVE BEFORE RUNNING!!!!!
//exit;
//REMOVE BEFORE RUNNING!!!!!


$offers = array();
$offer = $connection->query("SELECT * FROM ptf_fa_offers_accepted a WHERE a.result = 1 ORDER BY a.PlayerID, a.final DESC");
while($row = $offer->fetch_assoc()) {
    array_push($offers,$row);
}

$tot = count($offers);
$count = 0;
$finalAccepted = array();

foreach ($offers as $fas) {
    $count++;
    if ($fas['day'] == $day && $fas['year'] == $year ) {
        $player = $fas['PlayerID'];
        $amt = $fas['final'];
        //var_dump($fas);
    }
    if ($player == $lastPlayer || $lastPlayer == NULL) {
        if ($lastAmt > $amt || $lastAmt == NULL) {
            if ($finalAmt == 0 || $finalAmt == NULL) {
                $finalAmt = $amt;
            } elseif ($finalAmt < $lastAmt) {
                $finalAmt = $lastAmt;
            }
        }
    } else {
        $accepted = array();
        foreach ($offers as $search) {
            if($search['final'] == $finalAmt && $search['PlayerID'] == $lastPlayer) {
                array_push($accepted, $search);
                array_push($finalAccepted, $accepted);
            }
        }
        $finalAmt = $amt;
    }
    $lastPlayer = $player;
    $lastAmt = $amt;
    $player = '';
    $amt = '';
}

if ($count == $tot) {
    $accepted = array();
    foreach ($offers as $search) {
        if($search['final'] == $finalAmt && $search['PlayerID'] == $lastPlayer) {
            array_push($accepted, $search);
            array_push($finalAccepted, $accepted);
        }
    }
    $finalAmt = $amt;
}

function idToAbbrev($id) {
    switch ($id) {
       case 1: return 'KC'; break;
       case 2: return 'MIA'; break;
       case 3: return 'GB'; break;  //HOU
       case 4: return 'OAK'; break;
       case 5: return 'BUF'; break;
       case 6: return 'NYG'; break;  //WIN
       case 7: return 'CIN'; break;
       case 8: return 'PRO'; break;
       case 9: return 'LON'; break;
       case 10: return 'IND'; break;  //GB1
       case 11: return 'CHI'; break;
       case 12: return 'DET'; break;
       case 13: return 'MIN'; break;
       case 14: return 'ATL'; break;
       case 15: return 'SF'; break;
       case 16: return 'TB'; break;  //PHI
       case 17: return 'WAS'; break;
       case 18: return 'BAL'; break; //CLM
       case 19: return 'SEA'; break; 
       case 20: return 'NYJ'; break;  
       // relocated teams for historical data 
       case 50: return 'CLM'; break;
       case 51: return 'GB1'; break;
       case 52: return 'HOU'; break;
       case 53: return 'PHI'; break;  
       case 0: return 'NONE'; break;
    }
}

foreach($finalAccepted as $facc) {
    $abbrev = idToAbbrev($facc[0]['TeamID']);

    //var_dump($facc);
    if ($facc[0]['amount2'] == NULL) {
        $a2 = 0;
    } else {
        $a2 = intval($facc[0]['amount2']);
    }

    if ($facc[0]['amount3'] == NULL) {
        $a3 = 0;
    } else {
        $a3 = intval($facc[0]['amount3']);
    }

    if ($facc[0]['amount4'] == NULL) {
        $a4 = 0;
    } else {
        $a4 = intval($facc[0]['amount4']);
    }

    if ($facc[0]['amount5'] == NULL) {
        $a5 = 0;
    } else {
        $a5 = intval($facc[0]['amount5']);
    }

    if ($facc[0]['amount6'] == NULL) {
        $a6 = 0;
    } else {
        $a6 = intval($facc[0]['amount6']);
    }

    $a1 = intval($facc[0]['amount1']);

    $pid = intval($facc[0]['PlayerID']);

    $name = $connection->query("SELECT FirstName, LastName FROM ptf_players WHERE PlayerId = {$facc[0]['PlayerID']} ORDER BY PlayerID DESC");
    while($row = $name->fetch_assoc()) {
        $playerName = $row['FirstName'] . ' ' . $row['LastName'] ;
    }

    echo $playerName . ' - ' . $abbrev . ' - ' . $a1 . ' - ' . $a2 . ' - ' . $a3 . ' - ' . $a4 . ' - ' . $a5 . ' - ' . $a6 . '<br>';
    echo '<br>';


    $update = $connection->query("UPDATE ptf_players SET TeamID = '" . $facc[0]['TeamID'] . "', Team = '" . $abbrev . "' WHERE PlayerID = " . $facc[0]['PlayerID']);

    $update2 = $connection->query("UPDATE ptf_players_salaries SET 
    `1987` = '" . $a1 . "', 
    `1988` = '" . $a2 . "', 
    `1989` = '" . $a3 . "', 
    `1990` = '" . $a4 . "', 
    `1991` = '" . $a5 . "', 
    `1992` = '" . $a6 . "',
    `1993` = 0 
     WHERE playerID = " . $pid); 

     $translog = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$facc[0]['PlayerID']},0,{$facc[0]['TeamID']}, 'fasign', NOW())");
    
    }


?>