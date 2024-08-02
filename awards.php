<?php

include 'header.php';

$statsService = votingService(0,0,0);
$players = array();
foreach ($statsService as $player) {
    array_push($players,$player);
}
usort($players, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
$conferences = array('AFC','NFC');
$afc = array(1,2,3,4,5,6,7,8,9);
$nfc = array(10,11,12,13,14,15,16,17,18);


echo "<h1>End of Season Award Voting</h1>";
echo '<form name="awards" action="submit_vote.php" method="POST">';
        echo '<input type="hidden" id="Team" name="Team" value="' . $_SESSION['mascot'] . '">';
        echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
        echo '<input type="hidden" id="Time" name="Time" value="' . date('Y-m-d H:i:s') . '">';
        echo '<input type="hidden" id="Type" name="Type" value="Awards">';


    echo '<div align="center"><h3>Most Valuable Player <br>(Pick 3)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Pos</th>';
    echo '<th>G</th>';
    echo '<th>GS</th>';
    echo '<th>Pass Att</th>';
    echo '<th>Pass Cmp</th>';
    echo '<th>Pass Yds</th>';
    echo '<th>Pass TD</th>';
    echo '<th>Int</th>';
    echo '<th>Rush Att</th>';
    echo '<th>Rush Yds</th>';
    echo '<th>Rush TD</th>';
    echo '<th>Rec</th>';
    echo '<th>Rec Yds</th>';
    echo '<th>Rec TD</th>';
    echo '<th>Tac</th>';
    echo '<th>Sac</th>';
    echo '<th>Int</th>';
    echo '<th>Def TD</th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    foreach ($players as $player) {
        $teamsService = teamService($player['TeamID']);
            if ($player['PassYds'] > 1500 || $player['RushYds'] > 500 || $player['RecYds'] > 500 || $player['Pancakes'] > 25 || $player['Tackles'] > 25  || $player['Sacks'] > 4 || $player['Int'] > 2) {

                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo 
                $player['Position'] . '</td><td>' . 
                $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['PassAtt'] . '</td><td>' . 
                $player['PassCmp'] . '</td><td>' . 
                $player['PassYds'] . '</td><td>' . 
                $player['PassTD'] . '</td><td>' . 
                $player['PassInt'] . '</td><td>' . 
                $player['RushAtt'] . '</td><td>' . 
                $player['RushYds'] . '</td><td>' . 
                $player['RushTD'] . '</td><td>' . 
                $player['Catches'] . '</td><td>' . 
                $player['RecYds'] . '</td><td>' . 
                $player['RecTD'] . '</td><td>' . 
                $player['Tackles'] . '</td><td>' . 
                $player['Sacks'] . '</td><td>' . 
                $player['Int'] . '</td><td>' . 
                $player['DefensiveTD'] . '</td>' . 
                '<td><input type="checkbox" id="MVP'. $player['PlayerID'] . '" name="MVP'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></td>';
                echo '</tr>';
            }
        }

    echo '</table>';
echo '<br><br><br>';


    echo '<div align="center"><h3>Offensive Player of the Year <br>(Pick 3)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Pos</th>';
    echo '<th>G</th>';
    echo '<th>GS</th>';
    echo '<th>Pass Att</th>';
    echo '<th>Pass Cmp</th>';
    echo '<th>Pass Yds</th>';
    echo '<th>Pass TD</th>';
    echo '<th>Int</th>';
    echo '<th>Rush Att</th>';
    echo '<th>Rush Yds</th>';
    echo '<th>Rush TD</th>';
    echo '<th>Rec</th>';
    echo '<th>Rec Yds</th>';
    echo '<th>Rec TD</th>';
    echo '<th>Pancakes</a></th>';
    echo '<th>Sacks Allowed</a></th>';
    echo '<th>Missed Blks</a></th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    foreach ($players as $player) {
        $teamsService = teamService($player['TeamID']);
            if (($player['PassYds'] > 1500 || $player['RushYds'] > 500 || $player['RecYds'] > 500 || $player['Pancakes'] > 25) && in_array($player['Position'],array('QB','RB','FB','WR','TE','G','T','C'))) {

                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo 
                $player['Position'] . '</td><td>' . 
                $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['PassAtt'] . '</td><td>' . 
                $player['PassCmp'] . '</td><td>' . 
                $player['PassYds'] . '</td><td>' . 
                $player['PassTD'] . '</td><td>' . 
                $player['PassInt'] . '</td><td>' . 
                $player['RushAtt'] . '</td><td>' . 
                $player['RushYds'] . '</td><td>' . 
                $player['RushTD'] . '</td><td>' . 
                $player['Catches'] . '</td><td>' . 
                $player['RecYds'] . '</td><td>' . 
                $player['RecTD'] . '</td><td>' . 
                $player['Pancakes'] . '</td><td>' .
                $player['SacksAllowed'] . '</td><td>' .
                $player['MissedBlocks'] . '</td>' .
                '<td><input type="checkbox" id="OPY'. $player['PlayerID'] . '" name="OPY'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></td>';
                echo '</tr>';
            }
        }

    echo '</table>';
echo '<br><br><br>';

    echo '<div align="center"><h3>Defensive Player of the Year <br>(Pick 3)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Pos</th>';
    echo '<th>G</th>';
    echo '<th>GS</th>';
    echo '<th>Tackles</a></th>';
    echo '<th>TFL</a></th>';
    echo '<th>Missed Tac.</a></th>';
    echo '<th>Sacks</a></th>';
    echo '<th>Hurries</a></th>';
    echo '<th>Knockdowns</a></th>';
    echo '<th>Targeted</a></th>';
    echo '<th>Ints</a></th>';
    echo '<th>Int Yds</a></th>';
    echo '<th>Passes Def</a></th>';
    echo '<th>Force Fum</a></th>';
    echo '<th>Fum Recovered</a></th>';
    echo '<th>Fum Yds</a></th>';
    echo '<th>Def TDs</a></th>';
    echo '<th>Safeties</a></th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    foreach ($players as $player) {
        $teamsService = teamService($player['TeamID']);
            if (($player['Tackles'] > 25  || $player['Sacks'] > 4 || $player['Int'] > 2) && in_array($player['Position'],array('DT','DE','LB','CB','FS','SS'))) {

                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo 
                $player['Position'] . '</td><td>' . 
                $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Tackles'] . '</td><td>' .
                $player['TFL'] . '</td><td>' .
                $player['MissedTackles'] . '</td><td>' .
                $player['Sacks'] . '</td><td>' .
                $player['Hurries'] . '</td><td>' .
                $player['Knockdowns'] . '</td><td>' .
                $player['Tar'] . '</td><td>' .
                $player['Int'] . '</td><td>' .
                $player['IntReturnYds'] . '</td><td>' .
                $player['PassesDefensed'] . '</td><td>' .
                $player['ForcedFumbles'] . '</td><td>' .
                $player['FumblesRecovered'] . '</td><td>' .
                $player['FumReturnYds'] . '</td><td>' .
                $player['DefensiveTD'] . '</td><td>' .
                $player['Safeties'] . '</td>' .
                '<td><input type="checkbox" id="DPY'. $player['PlayerID'] . '" name="DPY'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></td>';
                echo '</tr>';
            }
        }

    echo '</table>';
echo '<br><br><br>';


    echo '<div align="center"><h3>Offensive Rookie of the Year <br>(Pick 3)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Pos</th>';
    echo '<th>G</th>';
    echo '<th>GS</th>';
    echo '<th>Pass Att</th>';
    echo '<th>Pass Cmp</th>';
    echo '<th>Pass Yds</th>';
    echo '<th>Pass TD</th>';
    echo '<th>Int</th>';
    echo '<th>Rush Att</th>';
    echo '<th>Rush Yds</th>';
    echo '<th>Rush TD</th>';
    echo '<th>Rec</th>';
    echo '<th>Rec Yds</th>';
    echo '<th>Rec TD</th>';
    echo '<th>Pancakes</a></th>';
    echo '<th>Sacks Allowed</a></th>';
    echo '<th>Missed Blks</a></th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    foreach ($players as $player) {
        $teamsService = teamService($player['TeamID']);
            if ($player['TeamID'] != 0  && $player['Experience'] == 0 && in_array($player['Position'],array('QB','RB','FB','WR','TE','G','T','C'))) {

                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo 
                $player['Position'] . '</td><td>' . 
                $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['PassAtt'] . '</td><td>' . 
                $player['PassCmp'] . '</td><td>' . 
                $player['PassYds'] . '</td><td>' . 
                $player['PassTD'] . '</td><td>' . 
                $player['PassInt'] . '</td><td>' . 
                $player['RushAtt'] . '</td><td>' . 
                $player['RushYds'] . '</td><td>' . 
                $player['RushTD'] . '</td><td>' . 
                $player['Catches'] . '</td><td>' . 
                $player['RecYds'] . '</td><td>' . 
                $player['RecTD'] . '</td><td>' . 
                $player['Pancakes'] . '</td><td>' .
                $player['SacksAllowed'] . '</td><td>' .
                $player['MissedBlocks'] . '</td>' .
                '<td><input type="checkbox" id="ORY'. $player['PlayerID'] . '" name="ORY'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></td>';
                echo '</tr>';
            }
        }

    echo '</table>';
echo '<br><br><br>';


    echo '<div align="center"><h3>Defensive Rookie of the Year <br>(Pick 3)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Pos</th>';
    echo '<th>G</th>';
    echo '<th>GS</th>';
    echo '<th>Tackles</a></th>';
    echo '<th>TFL</a></th>';
    echo '<th>Missed Tac.</a></th>';
    echo '<th>Sacks</a></th>';
    echo '<th>Hurries</a></th>';
    echo '<th>Knockdowns</a></th>';
    echo '<th>Targeted</a></th>';
    echo '<th>Ints</a></th>';
    echo '<th>Int Yds</a></th>';
    echo '<th>Passes Def</a></th>';
    echo '<th>Force Fum</a></th>';
    echo '<th>Fum Recovered</a></th>';
    echo '<th>Fum Yds</a></th>';
    echo '<th>Def TDs</a></th>';
    echo '<th>Safeties</a></th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    foreach ($players as $player) {
        $teamsService = teamService($player['TeamID']);
            if ($player['TeamID'] != 0 && $player['Experience'] == 0 && in_array($player['Position'],array('DT','DE','LB','CB','FS','SS'))) {

                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo 
                $player['Position'] . '</td><td>' . 
                $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Tackles'] . '</td><td>' .
                $player['TFL'] . '</td><td>' .
                $player['MissedTackles'] . '</td><td>' .
                $player['Sacks'] . '</td><td>' .
                $player['Hurries'] . '</td><td>' .
                $player['Knockdowns'] . '</td><td>' .
                $player['Tar'] . '</td><td>' .
                $player['Int'] . '</td><td>' .
                $player['IntReturnYds'] . '</td><td>' .
                $player['PassesDefensed'] . '</td><td>' .
                $player['ForcedFumbles'] . '</td><td>' .
                $player['FumblesRecovered'] . '</td><td>' .
                $player['FumReturnYds'] . '</td><td>' .
                $player['DefensiveTD'] . '</td><td>' .
                $player['Safeties'] . '</td>' .
                '<td><input type="checkbox" id="DRY'. $player['PlayerID'] . '" name="DRY'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></td>';

                echo '</tr>';
            }
        }

    echo '</table>';
echo '<br><br><br>';



$improveService = array();
$improvestmt = $connection->query(
    'SELECT y.PlayerID, y.Jersey, y.FirstName, y.LastName, CONCAT(y.FirstName," ",y.LastName) as FullName,y.College, y.Age, y.Experience, y.Height, y.Weight, g.TeamID, y.DraftedBy, y.DraftRound, y.DraftPick, y.DraftSeason, 
    y.HallOfFame, y.Injury, y.InjuryLength, y.Position, y.AltPosition, y.RetiredSeason, y.Awards, y.Overall, o.PosSort, g.Season, g.G, g.GS, g.Team, g.Championships, g.MadePlayoffs, g.Wins, g.Losses, g.Ties, g.WinPct, g.Round1Wins, g.Round1Losses, 
    g.Round2Wins, g.Round2Losses, g.Round3Wins, g.Round3Losses, g.ChampionshipWIns, g.ChampionshipLosses, g.Plays, g.RushAtt, g.Catches, g.PassAtt, g.PassCmp, g.PassInt, g.PassYds, 
    g.TotalYds, g.PassTD, g.PassRating, g.PassingAttemptsPerGame, g.PassingYardsPerAttempt, g.PassingYardsPerCompletion, g.SackPct, g.PassingYdsPerGame, g.RushYds, g.RecYds, g.RushTD, g.RecTD, g.RunLong, g.PassLong, g.RecLong, g.PuntLong, 
    g.KRLong, g.PRLong, g.FGLong, g.Int, g.IntReturnYds, g.Fumbles, g.FumblesLost, g.FumReturnYds, g.Pancakes, g.MissedTackles, g.DroppedPasses, g.PassesDefensed, g.SacksAllowed, g.MissedBlocks, g.WasSacked, g.SackedYards, g.Tackles, g.TFL, 
    g.Sacks, g.Hurries, g.Knockdowns, g.Safeties, g.ForcedFumbles, g.FumblesRecovered, g.DefensiveTD, g.BlockedFG, g.BlockedPAT, g.BlockedPunt, g.Penalties, g.PenaltyYds, g.Punts, g.PuntYds, g.PuntsInside20, g.PuntReturns, g.PuntReturnYds, 
    g.PuntReturnTD, g.KickoffReturns, g.KickoffReturnYds, g.KickoffReturnTD, g.FGA_U20, g.FGA_2029, g.FGA_3039, g.FGA_4049, g.FGA_50, g.FGM_U20, g.FGM_2029,  g.FGM_3039,  g.FGM_4049, g.FGM_50, g.FGA, g.FGM, g.XPA, g.XPM, 
    g.POG, g.RushAvg, g.PassAvg, g.PassPct, g.RecAvg, g.FGPct, g.XPPct, g.PuntAvg, g.PuntReturnAvg, g.KickReturnAvg, g.RushFDPct, g.RecFDPct, g.KickingPoints, g.Points, g.Rush20, g.Rush40, g.RushFD, g.Pass20, g.Pass40, g.PassFD, 
    g.Rec20, g.Rec40, g.RecFD, g.CtA, g.Tar , g.POW, g.POY, g.ProBowl, g.MVP, g.PlayoffMVP, g.ROY, g.RushingAttPerGame, g.RushingYdsPerGame, g.CatchesPerGame, g.ReceivingYdsPerGame, g.PassingYdsPerGame, g.PassingAttemptsPerGame 
    FROM `ptf_players_season_stats_1985` g
    LEFT JOIN `ptf_players` y on y.PlayerID = g.PlayerID 
    LEFT JOIN `ptf_pos_sort` o on y.Position = o.Position 
    WHERE y.PlayerID in (2040,2218,2163,2545,2853,2322) and g.Season = ' . $year
    );
while($row = $improvestmt->fetch_assoc()) {
    array_push($improveService,$row);
}

    echo '<div align="center"><h3>Surprise Player of the Year <br>(Pick 1 ONLY)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Pos</th>';
    echo '<th>G</th>';
    echo '<th>GS</th>';
    echo '<th>Pass Att</th>';
    echo '<th>Pass Cmp</th>';
    echo '<th>Pass Yds</th>';
    echo '<th>Pass TD</th>';
    echo '<th>Int</th>';
    echo '<th>Rush Att</th>';
    echo '<th>Rush Yds</th>';
    echo '<th>Rush TD</th>';
    echo '<th>Rec</th>';
    echo '<th>Rec Yds</th>';
    echo '<th>Rec TD</th>';
    echo '<th>Tac</th>';
    echo '<th>Sac</th>';
    echo '<th>Int</th>';
    echo '<th>Def TD</th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    foreach ($improveService as $player) {
        $teamsService = teamService($player['TeamID']);
                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo 
                $player['Position'] . '</td><td>' . 
                $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['PassAtt'] . '</td><td>' . 
                $player['PassCmp'] . '</td><td>' . 
                $player['PassYds'] . '</td><td>' . 
                $player['PassTD'] . '</td><td>' . 
                $player['PassInt'] . '</td><td>' . 
                $player['RushAtt'] . '</td><td>' . 
                $player['RushYds'] . '</td><td>' . 
                $player['RushTD'] . '</td><td>' . 
                $player['Catches'] . '</td><td>' . 
                $player['RecYds'] . '</td><td>' . 
                $player['RecTD'] . '</td><td>' . 
                $player['Tackles'] . '</td><td>' . 
                $player['Sacks'] . '</td><td>' . 
                $player['Int'] . '</td><td>' . 
                $player['DefensiveTD'] . '</td>' . 
                '<td><input type="checkbox" id="IMP'. $player['PlayerID'] . '" name="IMP'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></td>';

                echo '</tr>';
    }

    echo '</table>';

echo '<br><br><br>';



    echo '<div align="center"><h3>General Manager of the Year <br>(Pick 3)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Team</th>';
    echo '<th>Wins</th>';
    echo '<th>Losses</th>';
    echo '<th>Final Result</th>';
    echo '<th>Prev. Wins</th>';
    echo '<th>Prev. Losses</th>';
    echo '<th>VOTE</th>';
    echo '</tr>';

    $teamsLast = array();
    $teamsltmt = $connection->query("SELECT Team, TeamOwner, Wins, Losses FROM `ptf_teams_season_stats` where season = " . $year - 1);
    while($row = $teamsltmt->fetch_assoc()) {
        array_push($teamsLast,$row);
    }

    $teams = array();
    $teamstmt = $connection->query("SELECT Team, TeamOwner, Wins, Losses, Round2Losses, Round3Losses, ChampionshipWins, ChampionshipLosses FROM `ptf_teams_season_stats` where season = " . $year);
    while($row = $teamstmt->fetch_assoc()) {
        array_push($teams,$row);
    }

    foreach ($teamsLast as $teamLast) {
        foreach ($teams as $team) {
            if ($team['Team'] == $teamLast['Team']) {
                if($team['Round2Losses'] == 1) {
                    $result = 'Lost Divisional Playoffs';
                } elseif($team['Round3Losses'] == 1) {
                    $result = 'Lost Conference Championship';
                } elseif($team['ChampionshipLosses'] == 1) {
                    $result = 'Lost Super Bowl';
                } elseif($team['ChampionshipWins'] == 1) {
                    $result = 'WON Super Bowl';
                } else {
                    $result = 'Missed Playoffs';
                }

                echo '<tr>'; 
                echo '
                <td>' . $team['TeamOwner'] . '</td><td class="career" id="'.$team['Team'].'">' . 
                $team['Team'] . '</td><td>' . 
                $team['Wins'] . '</td><td>' .
                $team['Losses'] . '</td><td>' .
                $result . '</td><td>' .
                $teamLast['Wins'] . '</td><td>' .
                $teamLast['Losses'] . '</td>' .
                '<td><input type="checkbox" id="GMY'. $team['Team']  . '" name="GMY'  . $team['Team'] . '" value="' . $team['TeamOwner']  . '"></td>';

                echo '</tr>';
            }
        }
    }

    echo '</table>';
echo '<br><br><br>';



echo '<br><br><br>';
echo '<center><input type="submit" value="Submit Votes"></center><br><br>';
?>