<script type="text/javascript" src="../js/sort.js"></script>
<?php

session_start();
require('../../../sql/phpmysqlconnect.php');
require('../../ptf-services/player-service.php');
require('../../ptf-services/team-service.php');
require('../../ptf-services/fa-service.php');
require('../../ptf-services/sq-service.php');




//---------------------------------------------- CURRENT TO HEADER.PHP -- 7/26/24 9:58 AM ------------------------------------------------//




// VARIABLES //
$leaguestmt = $connection->query("SELECT * FROM ptf_league ");
$league = array();
while($row = $leaguestmt->fetch_assoc()) {
    array_push($league, $row);
}


// PROBABLY THE MOST IMPORTANT VARIABLES ON THE SITE //
$positionchanges =  $league[0]['value']; 
//$thisisnothing =    $league[1]['value'];
$draftStart =       $league[2]['value']; // IMPORTANT!!!!   The Players file includes incoming draft class.  This is the PLAYERID number where draft picks BEGIN! //
$leagueName =       $league[3]['value'];
$leagueabbrev =     $league[4]['value'];
$year =             $league[5]['value'];
$salaryCap =        $league[6]['value'];
$freeagency =       $league[7]['value']; 
$draftactive =      $league[8]['value'];
$teamNum =          $league[9]['value'];
$timer =            $league[10]['value'];
$lastSim =          $league[11]['value'];
$nextSim =          $league[12]['value'];
$offEnd =           $league[13]['value'];
$curWeek =          $league[14]['value'];
$waivers =          $league[15]['value'];  
$extensions =       $league[16]['value'];  
$probowlVote =      $league[17]['value'];  
$awardsVote =       $league[18]['value'];  
$simDate =          $league[19]['value'];  
$faday =            $league[20]['value'];
$leaguestmt->close();

// ARRAYS //
$pastSeasons = array();
for ($x = 1985; $x < $year; $x++) {
    array_push($pastSeasons, $x);
}

$positions = array('QB','RB','FB','WR','TE','G','T','C','DT','DE','LB','CB','SS','FS','P','K');
$rosMin = array("QB"=>3,"RB"=>3,"FB"=>1,"WR"=>5,"TE"=>2,"G"=>3,"T"=>3,"C"=>2,"P"=>1,"K"=>1,"DT"=>4,"DE"=>3,"LB"=>6,"CB"=>3,"SS"=>2,"FS"=>2);
$rosMax = array("QB"=>4,"RB"=>5,"FB"=>2,"WR"=>8,"TE"=>3,"G"=>5,"T"=>5,"C"=>3,"P"=>2,"K"=>2,"DT"=>5,"DE"=>5,"LB"=>8,"CB"=>6,"SS"=>3,"FS"=>3);

// FUNCTIONS //
function roundSalary($n) {
    return ceil($n/100000) * 100000;
}

function idToAbbrev($id) {
    return idConvert('abbrev', $id);
}

function idToName($id) {
    return idConvert('full', $id);
}

?>