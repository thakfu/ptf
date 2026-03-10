<?php

include 'header.php';

$stmt = $connection->query("SELECT *, g.GameID as GID FROM ptf_games g 
    LEFT JOIN ptf_games_data d on g.GameID = d.GameID 
    LEFT JOIN ptf_broadcast b on b.bcID = d.bcID 
    LEFT JOIN ptf_teams_data t on d.homeID = t.TeamID 
    WHERE g.GameID = " . $_GET['id']);
$matchup = array();
while($row = $stmt->fetch_assoc()) {
    array_push($matchup, $row);
}
$game = $matchup[0];

if ($game['Week'] < 1) {
    $week = 'Preseason WEEK ' .  $game['Week'] + 5;
} elseif ($game['Week'] == 21) {
    $week = 'Wildcard Game';
} elseif ($game['Week'] == 22) {
    $week = 'Divisional Playoff';
} elseif ($game['Week'] == 23) {
    $week = 'Conference Championship';
} elseif ($game['Week'] == 24) {
    $week = 'Super Bowl';
} elseif ($game['Week'] == 1) {
    $week = 'Bowl Week';
} else {
    $week = 'WEEK ' . $game['Week'];
}

if ($week == 'Bowl Week') {
    $suff = '_ALT';
} else {
    $suff = '';
}

$aplayer = statsService($game['AwayTeamID'],0,'season');
$lastRush = 0;
$lastPass = 0;
$lastRec = 0;
$lastTack = 0;
foreach ($aplayer as $play) {
    if ($play['RushYds'] > $lastRush) {
        $arushLead = $play['FullName'];
        $arushAmt = $play['RushYds'];
        $lastRush = $play['RushYds'];
    }

    if ($play['PassYds'] > $lastPass) {
        $apassLead = $play['FullName'];
        $apassAmt = $play['PassYds'];
        $lastPass = $play['PassYds'];
    }

    if ($play['RecYds'] > $lastRec) {
        $arecLead = $play['FullName'];
        $arecAmt = $play['RecYds'];
        $lastRec = $play['RecYds'];
    }

    if ($play['Tackles'] > $lastTack) {
        $atackLead = $play['FullName'];
        $atackAmt = $play['Tackles'];
        $lastTack = $play['Tackles'];
    }
}

$hplayer = statsService($game['HomeTeamID'],0,'season');
$lastRush = 0;
$lastPass = 0;
$lastRec = 0;
$lastTack = 0;
foreach ($hplayer as $play) {
    if ($play['RushYds'] > $lastRush) {
        $hrushLead = $play['FullName'];
        $hrushAmt = $play['RushYds'];
        $lastRush = $play['RushYds'];
    }

    if ($play['PassYds'] > $lastPass) {
        $hpassLead = $play['FullName'];
        $hpassAmt = $play['PassYds'];
        $lastPass = $play['PassYds'];
    }

    if ($play['RecYds'] > $lastRec) {
        $hrecLead = $play['FullName'];
        $hrecAmt = $play['RecYds'];
        $lastRec = $play['RecYds'];
    }

    if ($play['Tackles'] > $lastTack) {
        $htackLead = $play['FullName'];
        $htackAmt = $play['Tackles'];
        $lastTack = $play['Tackles'];
    }
}

$stats = statsService(0,1990,'rankings');

$totOff = buildRankings($stats, 'TotalYds', 'o');
foreach ($totOff as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $atotalOff = $data[1];
        $atotalOffRank = $key + 1;
    }
    if ($data[0] == $game['HomeTeamID']) {
        $htotalOff = $data[1];
        $htotalOffRank = $key + 1;
    }
}
$pasOff = buildRankings($stats, 'PassYds', 'o');
foreach ($pasOff as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $apassOff = $data[1];
        $apassOffRank = $key + 1;
    }
    if ($data[0] == $game['HomeTeamID']) {
        $hpassOff = $data[1];
        $hpassOffRank = $key + 1;
    }
}
$rusOff = buildRankings($stats, 'RushYds', 'o');
foreach ($rusOff as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $arushOff = $data[1];
        $arushOffRank = $key + 1;
    }
    if ($data[0] == $game['HomeTeamID']) {
        $hrushOff = $data[1];
        $hrushOffRank = $key + 1;
    }
}

$totDef = buildRankings($stats, 'TotalYdsAgainst', 'd');
foreach ($totDef as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $atotalDef = $data[1];
        $atotalDefRank = $key + 1;
    }
    if ($data[0] == $game['HomeTeamID']) {
        $htotalDef = $data[1];
        $htotalDefRank = $key + 1;
    }
}
$pasDef = buildRankings($stats, 'PassYdsAgainst', 'd');
foreach ($pasDef as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $apassDef = $data[1];
        $apassDefRank = $key + 1;
    }
    if ($data[0] == $game['HomeTeamID']) {
        $hpassDef = $data[1];
        $hpassDefRank = $key + 1;
    }
}
$rusDef = buildRankings($stats, 'RushYdsAgainst', 'd');
foreach ($rusDef as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $arushDef = $data[1];
        $arushDefRank = $key + 1;
    }
    if ($data[0] == $game['HomeTeamID']) {
        $hrushDef = $data[1];
        $hrushDefRank = $key + 1;
    }
}

$turnovers = buildRankings($stats, 'Turnovers', 'o');
foreach ($turnovers as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $atos = $data[1];
    }
    if ($data[0] == $game['HomeTeamID']) {
        $htos = $data[1];
    }
}
$takeaways = buildRankings($stats, 'TakeAways', 'o');
foreach ($takeaways as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $atas = $data[1];
    }
    if ($data[0] == $game['HomeTeamID']) {
        $htas = $data[1];
    }
}
$penalties = buildRankings($stats, 'Penalties', 'o');
foreach ($penalties as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $apens = $data[1];
    }
    if ($data[0] == $game['HomeTeamID']) {
        $hpens = $data[1];
    }
}

$penaltyYds = buildRankings($stats, 'PenaltyYds', 'o');
foreach ($penaltyYds as $key=>$data) {
    if ($data[0] == $game['AwayTeamID']) {
        $apenyds = $data[1];
    }
    if ($data[0] == $game['HomeTeamID']) {
        $hpenyds = $data[1];
    }
}

if ($atas - $atos > 0) {
    $adiff = '+' .  $atas - $atos;
} else {
    $adiff = $atas - $atos;
}

if ($htas - $htos > 0) {
    $hdiff = '+' .  $htas - $htos;
} else {
    $hdiff = $htas - $htos;
}

$teamServiceH = teamService($game['HomeTeamID']);
$teamH= $teamServiceH[0];

$stmt = $connection->query('SELECT * FROM ptf_coaches c LEFT JOIN ptf_teams t on t.TeamID = c.TeamID  WHERE c.TeamID = ' . $game['HomeTeamID'] . ' AND c.Job = "Head Coach"');
$coachh = array();
while($row = $stmt->fetch_assoc()) {
    array_push($coachh, $row);
}
$coaH = $coachh[0];

$teamServiceA = teamService($game['AwayTeamID']);
$teamA= $teamServiceA[0];

$playerServiceA = playerService($game['AwayTeamID'],0,0);
$playerServiceH = playerService($game['HomeTeamID'],0,0);

$stmt = $connection->query('SELECT * FROM ptf_coaches c LEFT JOIN ptf_teams t on t.TeamID = c.TeamID  WHERE c.TeamID = ' . $game['AwayTeamID'] . ' AND c.Job = "Head Coach"');
$coacha = array();
while($row = $stmt->fetch_assoc()) {
    array_push($coacha, $row);
}
$coaA = $coacha[0];

$aStarters = array();
$astart = $connection->query("SELECT d.Position, d.PlayerID, CONCAT(p.FirstName, ' ',p.LastName) as FullName FROM ptf_players_depth d 
    LEFT JOIN ptf_players p on p.PlayerID = d.PlayerID
    WHERE d.Team = " . $game['AwayTeamID']);
while($row = $astart->fetch_assoc()) {
    array_push($aStarters, $row);
}

$aoffStart = array();
$adefStart = array();
foreach ($aStarters as $aSt) {
    if (in_array($aSt['Position'],array('QB1','RB1','FB1','WR11','WR21','TE11','LG1','LT1','C1','RG1','RT1'))) {
        $aoffStart[$aSt['Position']] = $aSt['FullName'];
    }

    if (in_array($aSt['Position'],array('LE1','DT11','DT21','RE1','LOLB1','MLB11','MLB21','ROLB1','CB11','CB21','FS1','SS1','K1','P1'))) {
        $adefStart[$aSt['Position']] = $aSt['FullName'];
    }
}


$hStarters = array();
$hstart = $connection->query("SELECT d.Position, d.PlayerID, CONCAT(p.FirstName, ' ',p.LastName) as FullName FROM ptf_players_depth d 
    LEFT JOIN ptf_players p on p.PlayerID = d.PlayerID
    WHERE d.Team = " . $game['HomeTeamID']);
while($row = $hstart->fetch_assoc()) {
    array_push($hStarters, $row);
}

$hoffStart = array();
$hdefStart = array();
foreach ($hStarters as $hSt) {
    if (in_array($hSt['Position'],array('QB1','RB1','FB1','WR11','WR21','TE11','LG1','LT1','C1','RG1','RT1'))) {
        $hoffStart[$hSt['Position']] = $hSt['FullName'];
    }

    if (in_array($hSt['Position'],array('LE1','DT11','DT21','RE1','LOLB1','MLB11','MLB21','ROLB1','CB11','CB21','FS1','SS1','K1','P1'))) {
        $hdefStart[$hSt['Position']] = $hSt['FullName'];
    }
}


$matchup = array();
$history = $connection->query("SELECT HomeTeamID, AwayTeamID, HomeScore, AwayScore, Season, Week, GameType FROM `ptf_games` where HomeTeamID in (" . $game['HomeTeamID'] . "," . $game['AwayTeamID'] . ") AND AwayTeamID in (" . $game['HomeTeamID'] . "," . $game['AwayTeamID'] . ") ORDER BY Season ASC, Week ASC");
while($row = $history->fetch_assoc()) {
    array_push($matchup, $row);
}

if ($matchup[0]['HomeTeamID'] == $game['HomeTeamID']) {
    $homeTeam = $mu['HomeTeamID'];
    $awayTeam = $mu['AwayTeamID'];
} else {
    $homeTeam = $mu['AwayTeamID'];
    $awayTeam = $mu['HomeTeamID'];
}

$hWins = 0;
$aWins = 0;
$ties = 0;
foreach ($matchup as $mu) {
    if ($mu['Season'] < $year && $mu['GameType'] == 'RegularSeason') {
        if ($mu['HomeScore'] > $mu['AwayScore']) {
            if ($mu['HomeTeamID'] == $game['HomeTeamID']) {
                $hWins++;
            } else {
                $aWins++;
            }
        } else if ($mu['HomeScore'] == $mu['AwayScore']) {
            $ties++;
        } else {
            if ($mu['HomeTeamID'] == $game['HomeTeamID']) {
                $aWins++;
            } else {
                $hWins++;
            }
        }
    } 
    if ($mu['Season'] != $year && $mu['Week'] > $curWeek) {
        $lastGame = 'Week ' . $mu['Week'] . ', ' . $mu['Season'] . ' - ' . idToName($mu['AwayTeamID']) . ' - ' . $mu['AwayScore'] . ' @ ' . idToName($mu['HomeTeamID']) . ' - ' . $mu['HomeScore'];
    }
}

echo '<h1>' .  $week .  ' - ' . $game['Season'] . '<h1>';
echo '<h2>' . idToName($game['AwayTeamID']) . ' at ' . idToName($game['HomeTeamID']) . '</h2>';

echo "<center><p>at " . $game['stadium'] . " in " . $game['StadCity'] . ", " . $game['StadState'] . "...";
//echo 'home: ' . $game['HomeTeamID'] . ' - id: ' . $game['GID'];
echo $game['timeslot'] . " on " . $game['network'] . ". " . $game['playbyplay'] . " and " . $game['color'] . " on the call!</center>";
echo "<center><h3><b>" . $game['title'] . "</b></h3></center>";


echo '<table>';
echo '</tr><th colspan = 2>All Time Matchup</th></tr>';
echo '</tr><td colspan = 2><h6>' . idToAbbrev($game['AwayTeamID']) . ' - ' . $aWins . ' || ' . idToAbbrev($game['HomeTeamID']) . ' - ' . $hWins . ' || ' . $ties . ' ties<br>Last Game: ' . $lastGame . '</h6></td></tr>';
echo '<tr>';
echo '<td><div align="center"><img src="images/' . idToAbbrev($game['AwayTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></div>';
echo '<table>';
echo '<tr><th>Record</th><td>' . $teamA['Wins'] . '-' . $teamA['Losses'] . '-' . $teamA['Ties'] . ' (' . $teamA['AwayWins'] . '-' . $teamA['AwayLosses'] . '-' . $teamA['AwayTies'] . ' Away)</td></tr>';
echo '<tr><th>Coach</th><td>' . $coaA['FirstName'] . ' ' . $coaA['LastName'] . '</td></tr>';
echo '<tr><th>Off Scheme</th><td>' . $coaA['OffScheme'] . '</td></tr>';
echo '<tr><th>Def Scheme</th><td>' . $coaA['DefScheme'] . '</td></tr>';
echo '<tr><th>Offense</th><td>Overall - ' . $atotalOff . '(' . $atotalOffRank . ') : Pass - ' . $apassOff . '(' . $apassOffRank . ') : Rush - ' . $arushOff . '(' . $arushOffRank . ')</td></tr>';
echo '<tr><th>Defense</th><td>Overall - ' . $atotalDef . '(' . $atotalDefRank . ') : Pass - ' . $apassDef . '(' . $apassDefRank . ') : Rush - ' . $arushDef . '(' . $arushDefRank . ')</td></tr>';
echo '<tr><th>Leading Passer</th><td>' . $apassLead . ' - ' . $apassAmt . '</td></tr>';
echo '<tr><th>Leading Rusher</th><td>' . $arushLead . ' - ' . $arushAmt . '</td></tr>';
echo '<tr><th>Leading Receiver</th><td>' . $arecLead . ' - ' . $arecAmt . '</td></tr>';
echo '<tr><th>Leading Tackler</th><td>' . $atackLead . ' - ' . $atackAmt . '</td></tr>';
echo '<tr><th>Turnovers</th><td>' . $atas .  ' Take Aways / ' . $atos .  ' Turn Overs (' . $adiff . ')</td></tr>';
echo '<tr><th>Penalties</th><td>' . $apens .  ' For ' . $apenyds .  ' Yards</td></tr>';
echo '</table></td>';

echo '<td><div align="center"><img src="images/' . idToAbbrev($game['HomeTeamID']) . '_cbs' . $suff . '.png" id="cbsBanner"></div>';

echo '<table>';
echo '<tr><th>Record</th><td>' . $teamH['Wins'] . '-' . $teamH['Losses'] . '-' . $teamH['Ties'] . ' (' . $teamH['HomeWins'] . '-' . $teamH['HomeLosses'] . '-' . $teamH['HomeTies'] . ' Home)</td></tr>';
echo '<tr><th>Coach</th><td>' . $coaH['FirstName'] . ' ' . $coaH['LastName'] . '</td></tr>';
echo '<tr><th>Off Scheme</th><td>' . $coaH['OffScheme'] . '</td></tr>';
echo '<tr><th>Def Scheme</th><td>' . $coaH['DefScheme'] . '</td></tr>';
echo '<tr><th>Offense</th><td>Overall - ' . $htotalOff . '(' . $htotalOffRank . ') : Pass - ' . $hpassOff . '(' . $hpassOffRank . ') : Rush - ' . $hrushOff . '(' . $hrushOffRank . ')</td></tr>';
echo '<tr><th>Defense</th><td>Overall - ' . $htotalDef . '(' . $htotalDefRank . ') : Pass - ' . $hpassDef . '(' . $hpassDefRank . ') : Rush - ' . $hrushDef . '(' . $hrushDefRank . ')</td></tr>';
echo '<tr><th>Leading Passer</th><td>' . $hpassLead . ' - ' . $hpassAmt . '</td></tr>';
echo '<tr><th>Leading Rusher</th><td>' . $hrushLead . ' - ' . $hrushAmt . '</td></tr>';
echo '<tr><th>Leading Receiver</th><td>' . $hrecLead . ' - ' . $hrecAmt . '</td></tr>';
echo '<tr><th>Leading Tackler</th><td>' . $htackLead . ' - ' . $htackAmt . '</td></tr>';
echo '<tr><th>Turnovers</th><td>' . $htas .  ' Take Aways / ' . $htos .  ' Turn Overs (' . $hdiff . ')</td></tr>';
echo '<tr><th>Penalties</th><td>' . $hpens .  ' For ' . $hpenyds .  ' Yards</td></tr>';
echo '</table></td>';

echo '<tr><th colspan = 2>Last Game Starters</th></tr>';
echo '<tr><td><br>' . idToName($game['AwayTeamID']) . ' Offense';
echo '<table>';
echo '<tr><th>Quarterback</th><td>' . $aoffStart['QB1'] . '</td>';
echo '<tr><th>Running Backs</th><td>' . $aoffStart['RB1'] . ' - ' . $aoffStart['FB1'] . '</td>';
echo '<tr><th>Receivers</th><td>' . $aoffStart['WR11'] . ' - ' . $aoffStart['WR21'] . '</td>';
echo '<tr><th>Tight End</th><td>' . $aoffStart['TE11'] . '</td>';
echo '<tr><th>Offensive Line</th><td>' . $aoffStart['LT1'] . ' - ' . $aoffStart['LG1'] . ' - ' . $aoffStart['C1'] . ' - ' . $aoffStart['RG1'] . ' - ' . $aoffStart['RT1'] . '</td>';
echo '</table><br>';
echo idToName($game['AwayTeamID']) . ' Defense<br>';
echo '<table>';
if ($coaA['DefScheme'] == 'D43' || $coaA['DefScheme'] == 'D43Hybrid' ) {
    $adline = $adefStart['LE1'] . ' - ' . $adefStart['DT11'] . ' - ' . $adefStart['DT21'] . ' - ' . $adefStart['RE1'];
    $alb = $adefStart['LOLB1'] . ' - ' . $adefStart['MLB11'] . ' - ' . $adefStart['ROLB1'];
} else {
    $adline = $adefStart['LE1'] . ' - ' . $adefStart['DT11'] . ' - ' . $adefStart['RE1'];
    $alb = $adefStart['LOLB1'] . ' - ' . $adefStart['MLB11'] . ' - ' . $adefStart['MLB21'] . ' - ' . $adefStart['ROLB1'];
}
echo '<tr><th>Defensive Line</th><td>' . $adline . '</td>';
echo '<tr><th>Linebackers</th><td>' . $alb . '</td>';
echo '<tr><th>Cornerbacks</th><td>' . $adefStart['CB11'] . ' - ' . $adefStart['CB21'] . '</td>';
echo '<tr><th>Safeties</th><td>' . $adefStart['FS1'] . ' - ' . $adefStart['SS1'] . '</td>';
echo '<tr><th>Kicker / Punter</th><td>' . $adefStart['K1'] . ' - ' . $adefStart['P1'] . '</td>';
echo '</table><br>';
echo '</td>';

echo '<td><br>' . idToName($game['HomeTeamID']) . ' Offense';
echo '<table>';
echo '<tr><th>Quarterback</th><td>' . $hoffStart['QB1'] . '</td>';
echo '<tr><th>Running Backs</th><td>' . $hoffStart['RB1'] . ' - ' . $hoffStart['FB1'] . '</td>';
echo '<tr><th>Receivers</th><td>' . $hoffStart['WR11'] . ' - ' . $hoffStart['WR21'] . '</td>';
echo '<tr><th>Tight End</th><td>' . $hoffStart['TE11'] . '</td>';
echo '<tr><th>Offensive Line</th><td>' . $hoffStart['LT1'] . ' - ' . $hoffStart['LG1'] . ' - ' . $hoffStart['C1'] . ' - ' . $hoffStart['RG1'] . ' - ' . $hoffStart['RT1'] . '</td>';
echo '</table><br>';
echo idToName($game['HomeTeamID']) . ' Defense<br>';
echo '<table>';
if ($coaH['DefScheme'] == 'D43' || $coaH['DefScheme'] == 'D43Hybrid' ) {
    $hdline = $hdefStart['LE1'] . ' - ' . $hdefStart['DT11'] . ' - ' . $hdefStart['DT21'] . ' - ' . $hdefStart['RE1'];
    $hlb = $hdefStart['LOLB1'] . ' - ' . $hdefStart['MLB11'] . ' - ' . $hdefStart['ROLB1'];
} else {
    $hdline = $hdefStart['LE1'] . ' - ' . $hdefStart['DT11'] . ' - ' . $hdefStart['RE1'];
    $hlb = $hdefStart['LOLB1'] . ' - ' . $hdefStart['MLB11'] . ' - ' . $hdefStart['MLB21'] . ' - ' . $hdefStart['ROLB1'];
}
echo '<tr><th>Defensive Line</th><td>' . $hdline . '</td>';
echo '<tr><th>Linebackers</th><td>' . $hlb . '</td>';
echo '<tr><th>Cornerbacks</th><td>' . $hdefStart['CB11'] . ' - ' . $hdefStart['CB21'] . '</td>';
echo '<tr><th>Safeties</th><td>' . $hdefStart['FS1'] . ' - ' . $hdefStart['SS1'] . '</td>';
echo '<tr><th>Kicker / Punter</th><td>' . $hdefStart['K1'] . ' - ' . $hdefStart['P1'] . '</td>';
echo '</table><br>';
echo '</td></tr>';


echo '<tr><th colspan = 2>Injuries</th></tr>';
echo '<tr><td><br>' . idToName($game['AwayTeamID']) . ' Injuries';
echo'<br><table>';
foreach ($playerServiceA as $playerA) {
    if ($playerA['InjuryLength'] != '') {
        echo '<tr><th>' . $playerA['FullName'] . ' (' . $playerA['Position'] . ') </th><td> ' . $playerA['Injury'] . ' (' . $playerA['InjuryLength'] . ')</td></tr>';
    }
}
echo '</table><br></td>';

echo '<td><br>' . idToName($game['HomeTeamID']) . ' Injuries';
echo'<br><table>';
foreach ($playerServiceH as $playerH) {
    if ($playerH['InjuryLength'] != '') {
        echo '<tr><th>' . $playerH['FullName'] . ' (' . $playerH['Position'] . ') </th><td> ' . $playerH['Injury'] . ' (' . $playerH['InjuryLength'] . ')</td></tr>';
    }
}
echo '</table><br>';
echo '</td></tr>';
echo '</table><br><br>';

function buildRankings($stats, $type, $side) {

    if ($side == "o") {
        usort($stats, fn($a, $b) => $b[$type] <=> $a[$type]);
    } else {
        usort($stats, fn($a, $b) => $a[$type] <=> $b[$type]);
    }
    $totStat =  array();
    foreach ($stats as $stat) {
        $tmtotStat = array();
        array_push($tmtotStat,$stat['TeamID'],$stat[$type]);
        array_push($totStat, $tmtotStat);
    }
    //var_dump($totStat);
    return $totStat;
    
}


?>