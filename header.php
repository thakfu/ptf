<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/sort.js"></script>
<?php

session_start();

require('../sql/phpmysqlconnect.php');
require('../ptf-services/sq-service.php');
require('../ptf-services/player-service.php');
require('../ptf-services/team-service.php');
require('../ptf-services/stats-service.php');
require('../ptf-services/voting-service.php');
require('../ptf-services/fa-service.php');
require('../ptf-services/transaction-service.php');
date_default_timezone_set('America/Chicago');

$username = $_SESSION['user_id'];
$userteam = $_SESSION['TeamID'];
if(isset($username)){
    $curTime =  date("Y-m-d H:i:s", time());
    $stmtseen = $connection->query("UPDATE ptf_users SET last_seen='".$curTime."' WHERE user_id = " . $username);
}

$members = array();
$stmtlist = $connection->query('SELECT user_id, last_seen FROM ptf_users');
while($row = $stmtlist->fetch_assoc()) {
    array_push($members, $row);
}

foreach ($members as $member) {
    if ($member['last_seen'] < date("Y-m-d H:i:s",strtotime('-30 minutes 0 seconds', time()))){
        //echo $member['last_seen'] . ' < ' . date("Y-m-d H:i:s",strtotime('-30 minutes 0 seconds', time())) . '<br>';
        $stmtoff = $connection->query("UPDATE ptf_users SET online = 0 WHERE user_id = " . $member['user_id']);
    } else {
        //echo $member['last_seen'] . ' > ' . date("Y-m-d H:i:s",strtotime('-30 minutes 0 seconds', time())) . ' - set 1 <br>';
        //echo $member . ' - off';
        $stmtonn = $connection->query("UPDATE ptf_users SET online = 1 WHERE user_id = " . $member['user_id']);
    }
}

$result = $connection->query("SELECT COUNT(*) as totalno FROM ptf_users WHERE online = 1");
$count = mysqli_fetch_assoc($result);



//------------------------------------------ DONT FORGET TO UPDATE ADMIN HEADER ANYTHING BELOW THIS POINT -------------------------------------------------------//

// VARIABLES //
$leaguestmt = $connection->query("SELECT * FROM ptf_league ");
$league = array();
while($row = $leaguestmt->fetch_assoc()) {
    array_push($league, $row);
}


// PROBABLY THE MOST IMPORTANT VARIABLES ON THE SITE //
$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 
$positionchanges =  $league[0]['value']; 
$tradesOpen =       $league[1]['value']; 
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
$transPer =         $league[21]['value'];
$altHelm =          $league[22]['value'];
$isPlayoffs =       $league[23]['value'];
$isPreseason =      $league[24]['value'];
$franchiseTags =    $league[25]['value'];
//$toogle =       $league[26]['value'];  backend use primarily
$leaguestmt->close();

// ARRAYS //
$pastSeasons = array();
for ($x = 1985; $x < $year; $x++) {
    array_push($pastSeasons, $x);
}

$allSeasons = array();
for ($x = 1985; $x <= $year; $x++) {
    array_push($allSeasons, $x);
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

function cashConvert($amt) {
    if ($amt == 0) {
        return '--';
    } else {
        return "$" . number_format($amt / 1000000, 2) . "M";
    }
}


//------------------------------------------------ END OF ADMIN UPDATE AREA -----------------------------------------------------//


// USER TEAM SALARY CAP (Doesnt need to go into admin header!) //
$salarySum = salarySpace($userteam);
$total = $salarySum['p' . $year];
$totalnext = $salarySum['p' . $year + 1];
$totalnext2 = $salarySum['p' . $year + 2];
$totalnext3 = $salarySum['p' . $year + 3];
$totalnext4 = $salarySum['p' . $year + 4];
$totalnext5 = $salarySum['p' . $year + 5];
$totalnext6 = $salarySum['p' . $year + 6];


//------------------------------------------- EVERYTHING BELOW THIS IS THE VISIBLE HEADER ---------------------------------------//
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $leagueName . ' - Official Website'; ?></title>
<style>
<?php include 'css/ptf.css'; ?>
</style>
</head>
<body>

<div id='title'>
    <div id='logo'><img src='images/ptf_logo.png' id='logo'></div>
    <div id='topnav1'>
        <div id='upcoming'>
        <?php              
        if ($_SESSION['username'] != '') {
            echo "LOGGED IN AS <a href='profile.php'>" . $_SESSION['username'] . "</a>";
            if ($_SESSION['admin'] == '2') {
                echo ' - <a href="admin/index.php">ADMIN</a>';
            } elseif ($_SESSION['admin'] == '1') {
                echo ' - <a href="admin/draftadmin.php">DRAFT ADMIN</a>';
            }
            $stmt4 = $connection->query("SELECT * FROM ptf_trade_offers WHERE recTID = " . $_SESSION['TeamID']);
            $offercount = 0;
            $offers = array();
            while($row = $stmt4->fetch_assoc()) {
                $offercount++;
            }
            if ($offercount > 0) {
              echo ' - <a href="profile.php">You have ' . $offercount . ' trade offers!</a>';
            }
        } else {
            echo "<a href='login.php'>Login</a>";
        } ?>
        </div>
        <div id='count'>Currently <a href='members.php'><?=$count['totalno']?></a> users online</div>
    </div>
</div>
<?php 
        if ($curWeek < 1) {
            $schedWeek = -4;
        } else {
            $schedWeek = $curWeek;
        }

        echo '
        <nav class="navbar navbar-inverse">
          <div id="topmenu" class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a href="index.php" id="homelink" >HOME</a>';
        if ($_SESSION['username'] != '') {
            echo '
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Info</a>
                <ul class="dropdown-menu">
                    <li><a href="standings.php">Standings</a>
                    <li><a href="schedule.php?week='.$schedWeek.'">Schedule</a></li>
                    <li><a href="translog.php?team=menu&type=all">Transaction Log</a></li>
                    <li><a href="injuries.php">Injury Log</a></li>
                    <li><a href="finances.php">Team Finances</a>
                    <li><a href="stats.php?page=Index">Sim Export</a></li>
                    <li><a href="http://thakfu.com/ptf/export/Index.html">Sim Export Mobile</a></li>
                </ul>
            </li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Strategy</a>
                <ul class="dropdown-menu">
                    <li><a href="status.php">STATUS</a>
                    <li><a href="depth.php">Depth Chart</a>
                    <li><a href="edit_coach.php">Coaching Profile</a></li>
                    <li><a href="gameplan.php">Game Planning</a></li>
                </ul>
            </li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Offseason</a>
                <ul class="dropdown-menu">
                    <li><a href="upcoming-fa.php?sort=Overall&order=desc&pos=all">Upcoming Free Agents</a>
                    <li><a href="freeagency.php?abbrev=' . $_SESSION['abbreviation'] . '">Free Agency</a>';

            if ($draftactive == 1) {
                echo '<li><a href="draftall.php?pos=all">' . $year . ' Draft Pool</a></li>';
                echo '<li><a href="pastdraft.php?year=' . $year . '">' . $year . ' Draft</a>';
            } else {
                echo '<li><a href="draftpreview.php?pos=all">' . $year + 1 . ' Draft Preview</a></li>';
            }
                    echo '<li><a href="http://www.thakfu.com/ptf/combine90/Index.html">1990 Combine Stats</a>
                </ul>
            </li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Rosters</a>
                <ul class="dropdown-menu">
                    <li><a href="allplayers.php">All Players</a>
                    <li><a href="rosters.php?team=' . $_SESSION['TeamID'] . '&table=y&sort=Jersey&order=asc">Roster</a>
                    <li><a href="transactions.php">Transactions</a>
                    <li><a href="trades.php">Trades</a>
                    <li><a href="freeagents.php">Free Agents</a>
                </ul>
            </li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Stats</a>
              <ul class="dropdown-menu">
                    <li><a href="teamstats.php">Team Stats</a>
                    <li><a href="league_leaders.php">League Leaders</a>
                    <li><a href="career_stats.php">Player Career Stats</a>
                    <li><a href="league_records.php">League Records</a>
              </ul>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">History</a>
                <ul class="dropdown-menu">
                    <li><a href="pastdraft.php?year=' . $year . '">Draft History</a>
                    <li><a href="retired.php">Retired Players</a>
                </ul>';
            echo   '</li><li><a id="homelink" href="awards.php">AWARDS VOTING</a></li>';
            //echo '</li><li><a id="homelink" href="probowl.php">Pro Bowl VOTING</a></li>';
            if ($_SESSION['admin'] == '2') { 
                echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">ADMIN</a>
                <ul class="dropdown-menu">
                    <li><a href="transactions_checker.php?team=0">Transactions</a>
                </ul>';
            }
                echo '</ul>'
          ; 

            } else {
                echo
                '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Info</a>
                    <ul class="dropdown-menu">
                        <li><a href="standings.php">Standings</a>
                        <li><a href="schedule.php?week='.$schedWeek.'">Schedule</a></li>
                        <li><a href="translog.php?team=menu&type=all">Transaction Log</a></li>
                        <li><a href="injuries.php">Injury Log</a></li>
                        <li><a href="finances.php">Team Finances</a>
                        <li><a href="1985draft.php">Dispersal Draft</a></li>
                        <li><a href="stats.php?page=Index">Sim Export</a></li>
                        <li><a href="http://thakfu.com/ptf/export/Index.html">Sim Export Mobile</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Transactions</a>
                <ul class="dropdown-menu">
                    <li><a href="allplayers.php?sort=Overall&order=desc&pos=all">All Players</a>
                    <li><a href="upcoming-fa.php?sort=Overall&order=desc&pos=all">Upcoming Free Agents</a>
                </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Stats</a>
                    <ul class="dropdown-menu">
                            <li><a href="teamstats.php">Team Stats</a>
                            <li><a href="league_leaders.php">League Leaders</a>
                            <li><a href="career_stats.php">Player Career Stats</a>
                            <li><a href="league_records.php">League Records</a>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">History</a>
                    <ul class="dropdown-menu">
                        <li><a href="pastdraft.php?year=' . $year . '">Draft History</a>
                        <li><a href="retired.php">Retired Players</a>
                    </ul>
                </li>';
            } 
              echo '</ul>';
            echo '</div></nav>';

            if ($curWeek == 1 || $curWeek == 10) {
                $suff = '_A';
            } else {
                $suff = '';
            }

            $suffm = '';

            echo '<div align="center" id="teammenu" class="container-fluid">
            <a href="team.php?team=2"><img src="images/MIA_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=5"><img src="images/BUF_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=9"><img src="images/LON_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=18"><img src="images/BAL_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=1"><img src="images/NYT_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=20"><img src="images/LOU_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=4"><img src="images/OAK_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=7"><img src="images/CIN_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=8"><img src="images/SEA_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=17"><img src="images/CHC_115' . $suff . '.png" id="tmMenu"></a><br>
            <a href="team.php?team=6"><img src="images/NYG_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=10"><img src="images/IND_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=14"><img src="images/ATL_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=16"><img src="images/TB_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=19"><img src="images/WAS_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=3"><img src="images/GB_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=11"><img src="images/CHI_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=12"><img src="images/DET_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=13"><img src="images/MIN_115' . $suff . '.png" id="tmMenu"></a>
            <a href="team.php?team=15"><img src="images/SF_115' . $suff . '.png" id="tmMenu"></a></div>'; 

?>






