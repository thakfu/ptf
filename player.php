<?php

include 'header.php';

$playerService = playerService(0,$_GET['player'],0);
$player = $playerService[0];
$teamService = teamService($player['TeamID']);
$team = $teamService[0];

//var_dump($player);

if ($player['RetiredSeason'] != 0) {
    $retired = ' - RETIRED (' . $player['RetiredSeason'] . ') - ';
}

echo '<h1>' . $player['FullName'] . $retired . '</h1>';
echo '<h3><center><i>' . $player['Nickname'] . '</i></center></h3>';
echo '<table id="'.$team['Abbrev'].'"><tr>';
echo '<td><img width="100px" src="export/Images/Players/' . str_replace(" ","_",$player['FullName']) . '.jpg" onerror="this.src=\'notfound.png\'"></td>';


if (!$team) {
    $teamName = 'No Team';
} else {
    $teamName = $team['FullName'];
}

$stateService = faPlayerService('college', '0');
            foreach ($stateService as $state) {
                if($player['PlayerID'] == $state['PlayerID']) {
                    $home = $state['state'];
                }
            }

echo '<td valign="top"><h2>#'. $player['Jersey'] . ' - ' . $player['Position'] . ' - ' . $teamName . '</h2>
        <ul style="text-align:left">
            <li>Age:' . $player['Age'] . 
            '<li>Experience:' . $player['Experience'] . 
            '<li>Height:' . floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) .
            '<li>Weight:' . $player['Weight'] . 
            '<li>College:' . $player['College'] . ' (' . $home . ')' . 
        '</ul>';
echo '<i>Drafted by ' . idToAbbrev($player['DraftedBy']) . ' in the ' . $player['DraftSeason'] . ' PTF Draft - Round ' . $player['DraftRound'] . ' Pick ' . $player['DraftPick'] . '</i>';
echo '</td>';
echo '<td><img src="images/' . $team['Abbrev'] . '_115.png" id="tmHelmet" onerror="this.src=\'images/ptf_logo.png\'"></td></tr>';
if ($player['Jersey'] < 10) {
    $x = 18;
} else {
    $x = 8;
}

echo '<tr><td colspan="2"><b>Team History</b><br>';
$champ = array();
$clubrus = array();
$clubrec = array();
$clubpas = array();
$pog = 0;
$pow = 0;
$statsServiceTotal = statsService(0,$_GET['player'],'season');
foreach ($statsServiceTotal as $sst) {
    $pog += $sst['POG'];
    $pow += $sst['POW'];
    if ($sst['Championships'] == 1) {
        array_push($champ, $sst['Season']);
    }
    if ($sst['RushYds'] > 1000) {
        array_push($clubrus, $sst['Season']);
    }
    if ($sst['RecYds'] > 1000) {
        array_push($clubrec, $sst['Season']);
    }
    if ($sst['PassYds'] > 3000) {
        array_push($clubpas, $sst['Season']);
    }
}
$teamhistory = array();
$stmt = $connection->query("SELECT DISTINCT (TeamID), Season from `ptf_players_game_stats_1985` where PlayerID = '" . $_GET['player'] . "' and WeekNumber <= '16'");
while($row = $stmt->fetch_assoc()) {
    array_push($teamhistory, $row);
}


foreach ($teamhistory as $teamhis) {
    //$teamColor = $teamhis['TeamID'];
    if ($teamColor == NULL) {
        $teamColor = 0;
    }
    if ($teamhis['Season'] == '1985') {
        if ($teamhis['TeamID'] == '3') {
            $teamColor = '52';
        } elseif ($teamhis['TeamID'] == '10') {
            $teamColor = '51';
        } elseif ($teamhis['TeamID'] == '18') {
            $teamColor = '50';
        } elseif ($teamhis['TeamID'] == '16') {
            $teamColor = '53';
        } elseif ($teamhis['TeamID'] == '6') {
            $teamColor = '54';
        }else {
            $teamColor = $teamhis['TeamID'];
        }
    } elseif ($teamhis['Season'] == '1986') {
        if ($teamhis['TeamID'] == '6') {
            $teamColor = '54';
        }else {
            $teamColor = $teamhis['TeamID'];
        }
    } else {
        $teamColor = $teamhis['TeamID'];
    }
    $teamServiceCol = teamService($teamColor);
    $teamCol = $teamServiceCol[0];
    
    if ($teamColor != $lastTeam || $lastTeam == NULL) {
        if(intval($teamhis['Season']) == (intval($player['DraftSeason']) - 1) || $teamColor == 0) {
            echo '';
        } else {
            echo '<svg viewBox="0,0,50,50" width="50" height="50" class="square"> 
            <title>' . $teamCol['FullName'] . ' - Joined ' . $teamhis['Season'] . '</title>
            <rect x="0" y="0" width="51" height="51" stroke="#'. $teamCol['color_2'] . '" stroke-width="1" fill="#'. $teamCol['color_2'] . '"></rect> 
            <rect x="6" y="6" width="39" height="39" stroke="#'. $teamCol['Color_1'] . '" stroke-width="1" fill="#'. $teamCol['Color_1'] . '"></rect> 
            <text x="' . $x . '" y="34" fill="#fff" >'. $player['Jersey'] .'</text> </svg>';
        }
    }
    echo ' ';
    $curteam = idToAbbrev($teamhis['TeamID']);
    //$lastTeam = $teamhis['TeamID'];
    $lastTeam = $teamColor;
    $teamService = teamService($teamhis['TeamID']);
    $team = $teamService[0];
}
echo '<br><br></td><td>';
foreach ($champ as $ch) {
    echo '<img src="rb-trophy.png" title="Super Bowl - ' . $ch . '" width="35">';
}
foreach ($clubrus as $ch) {
    echo '<img src="1000yards.png" title="1000 Yards Rushing - ' . $ch . '" width="35">';
}
foreach ($clubrec as $ch) {
    echo '<img src="1000yards.png" title="1000 Yards Receiving - ' . $ch . '" width="35">';
}
foreach ($clubpas as $ch) {
    echo '<img src="3000yards.png" title="3000 Yards Passing - ' . $ch . '" width="35">';
}
echo '</td></tr>';
echo '<tr><td colspan="3"><h4>Career Highlights</h4>';
$awards = explode("|",$player['Awards']);
echo '<ul style="text-align:left; padding-left:35%">';
foreach ($awards as $award) {
    if ($award) {
        echo  '<li>' .  $award;
    }
}
if ($pog > 0) {
    echo  '<li>Player of the Game: ' .  $pog;
}
if ($pow > 0) {
    echo  '<li>Player of the Week: ' .  $pow;
}
echo '</ul>';
echo '</td></tr>';
echo '<tr><td colspan="3"><h4>Controls</h4></td></tr>';
echo '<tr><td colspan="3"><h4>Transaction History</h4>';

$transInfo = array();
    $stmt = $connection->query("SELECT r.PlayerID, r.TeamID_Old, r.TeamID_New, r.type, p.FirstName, p.LastName, p.Position, t.City as CityOld, t.Mascot as MascotOld, tn.City as CityNew, tn.Mascot as MascotNew, r.date FROM ptf_transactions r LEFT JOIN ptf_players p ON r.PlayerID = p.PlayerID LEFT JOIN ptf_teams t ON r.TeamID_Old = t.TeamID LEFT JOIN ptf_teams tn ON r.TeamID_New = tn.TeamID WHERE r.PlayerID = " . $_GET['player'] . " ORDER by date DESC");
    
    while($row = $stmt->fetch_assoc()) {
        array_push($transInfo, $row);
    }

    foreach ($transInfo as $ti) { 
        if ($ti['type'] == 'cut') {
            echo '<p>' . substr($ti['date'],0,-8) . ': The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' release ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' to free agency!</p>';
        } else if ($ti['type'] == 'squad') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have demoted ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' to the practice squad!</p>';
        } else if ($ti['type'] == 'sign') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have signed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' from the free agent pool!</p>';
        } else if ($ti['type'] == 'promote') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have promoted ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' from the practice squad to the main roster!</p>';
        } else if ($ti['type'] == 'change') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have changed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '\'s position!</p>';
        } else if ($ti['type'] == 'draft') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have drafted ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '!</p>';
        } else if ($ti['type'] == 'fasign') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have signed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' in free agency!</p>';
        } else if ($ti['type'] == 'trade') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have traded ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' to the ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . '!</p>';
        } else if ($ti['type'] == 'ir') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have placed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' on injured reserve!</p>';
        } else if ($ti['type'] == 'expand') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have selected ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' in the expansion draft!</p>';
        } else if ($ti['type'] == 'supp') {
            echo '<p>' .substr($ti['date'],0,-8) . ': The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have selected ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' in the 1987 Supplemental draft!</tp>';
        }
    }

echo '</td></tr>';
echo '<tr><td colspan="3"><h4>Player Stats</h4>';
echo '<div align="left" style="padding-left:35%">';
echo '<a href="/ptf/export/Players/Player.html?id=' . $player['PlayerID'] . '">Sim Export Player Page</a><br>';
echo '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '&stat=ratings">Ratings & Contract</a><br>';
echo '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '&stat=logs">Game Logs</a><br>';
echo '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '&stat=career">Career Stats</a><br>';
echo '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '&stat=prog">Rating Progression</a><br>';
echo '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '&stat=records">Personal Records & Streaks</a>';
echo '<br><br></div></td></tr>';
echo '</table>';

if ($_GET['stat'] == NULL) {
    $stat = 'ratings';
} else {
    $stat = $_GET['stat'];
}

if ($stat == 'logs') {
    include 'gamelogs.php';
} elseif ($stat == 'career') {
    include 'careerstats.php';
} elseif ($stat == 'prog') {
    include 'progression.php';
} elseif ($stat == 'ratings') {
    include 'ratings.php';
} elseif ($stat == 'records') {
    include 'records.php';
}

?>