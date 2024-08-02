<?php

$seasons = array_reverse($pastSeasons);

function winCalc(&$wins, &$losses, $player, $isHome) {
    if ($isHome == 1) {
        if ($player['HomeScore'] > $player['AwayScore']) {
            $result = 'Win (' . $player['HomeScore'] . '-' . $player['AwayScore'] . ')';
            $wins++;
        } else {
            $result = 'Loss (' . $player['AwayScore'] . '-' . $player['HomeScore'] . ')';
            $losses++;
        }
    } else {
        if ($player['HomeScore'] > $player['AwayScore']) {
            $result = 'Loss (' . $player['HomeScore'] . '-' . $player['AwayScore'] . ')';
            $losses++;
        } else {
            $result = 'Win (' . $player['AwayScore'] . '-' . $player['HomeScore'] . ')';
            $wins++;
        }
    }
    return $result;
}

foreach ($seasons as $season) {
    echo '<h2>' .  $player['FullName'] . ' ' . $season . ' Game Log</h2>';

    $statsServiceTotal = statsService(0,$_GET['player'],'season');
    $playerSeason = array();
    foreach ($statsServiceTotal as $sst) {
        if ($sst['Season'] == $season) {
            $playerSeason = $sst;
        }
    }

    $played = 0;
    $playedPos = array('QB');
    if (in_array($playerSeason['Position'],$playedPos)) {
        $played = $playerSeason['Plays'];
    }

    if ($playerSeason['PassAtt'] > 0 || $played > 0) {
        echo '<h3 align="center">Passing</h3>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>Start</th>';
        echo '<th>Plays</th>';
        echo '<th>Attempts</th>';
        echo '<th>Completions</th>';
        echo '<th>Yards</th>';
        echo '<th>TDs</th>';
        echo '<th>INTs</th>';
        echo '<th>Rating</th>';
        echo '<th>Pass Pct</th>';
        echo '<th>Yds / Att</th>';
        echo '<th>Yds / Cmp</th>';
        echo '<th>Sacked</th>';
        echo '<th>20+</th>';
        echo '<th>40+</th>';
        echo '<th>Long</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Plays'] . '</td><td>' .
                $player['PassAtt'] . '</td><td>' .
                $player['PassCmp'] . '</td><td>' .
                $player['PassYds'] . '</td><td>' .
                $player['PassTD'] . '</td><td>' .
                $player['PassInt'] . '</td><td>' .
                $player['PassRating'] . '</td><td>' .
                $player['PassPct'] . '</td><td>' .
                $player['PassingYardsPerAttempt'] . '</td><td>' .
                $player['PassingYardsPerCompletion'] . '</td><td>' .
                $player['WasSacked'] . '</td><td>' .
                $player['Pass20'] . '</td><td>' .
                $player['Pass40'] . '</td><td>' .
                $player['PassLong'] . '</td>' .
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['GS'] . '</th><th>' .
                $player['Plays'] . '</th><th>' .
                $player['PassAtt'] . '</th><th>' .
                $player['PassCmp'] . '</th><th>' .
                $player['PassYds'] . '</th><th>' .
                $player['PassTD'] . '</th><th>' .
                $player['PassInt'] . '</th><th>' .
                $player['PassRating'] . '</th><th>' .
                $player['PassPct'] . '</th><th>' .
                $player['PassingYardsPerAttempt'] . '</th><th>' .
                $player['PassingYardsPerCompletion'] . '</th><th>' .
                $player['WasSacked'] . '</th><th>' .
                $player['Pass20'] . '</th><th>' .
                $player['Pass40'] . '</th><th>' .
                $player['PassLong'] . '</th>';
                echo '</tr>';
                echo '</table>';
            }
        }
    }

    $played = 0;
    $playedPos = array('RB','FB');
    if (in_array($playerSeason['Position'],$playedPos)) {
        $played = $playerSeason['Plays'];
    }
    if ($playerSeason['RushAtt'] > 0 || $played > 0) {
        echo '<div align="center"><h3>Rushing</h3></div>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>Start</th>';
        echo '<th>Plays</th>';
        echo '<th>Attempts</th>';
        echo '<th>Yards</th>';
        echo '<th>TDs</th>';
        echo '<th>Fumbles</th>';
        echo '<th>Rush Avg</th>';
        echo '<th>20+</th>';
        echo '<th>40+</th>';
        echo '<th>Long</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Plays'] . '</td><td>' .
                $player['RushAtt'] . '</td><td>' .
                $player['RushYds'] . '</td><td>' .
                $player['RushTD'] . '</td><td>' .
                $player['Fumbles'] . '</td><td>' .
                $player['RushAvg'] . '</td><td>' .
                $player['Rush20'] . '</td><td>' .
                $player['Rush40'] . '</td><td>' .
                $player['RunLong'] . '</td>' .
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['GS'] . '</th><th>' .
                $player['Plays'] . '</th><th>' .
                $player['RushAtt'] . '</th><th>' .
                $player['RushYds'] . '</th><th>' .
                $player['RushTD'] . '</th><th>' .
                $player['Fumbles'] . '</th><th>' .
                $player['RushAvg'] . '</th><th>' .
                $player['Rush20'] . '</th><th>' .
                $player['Rush40'] . '</th><th>' .
                $player['RunLong'] . '</th>' .
                '</tr>';
                echo '</table>';
            }
        }
    }

    $played = 0;
    $playedPos = array('WR','TE');
    if (in_array($playerSeason['Position'],$playedPos)) {
        $played = $playerSeason['Plays'];
    }
    if ($playerSeason['Catches'] > 0 || $played > 0) {
        echo '<div align="center"><h3>Receiving</h3></div>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>Start</th>';
        echo '<th>Plays</th>';
        echo '<th>Targets</th>';
        echo '<th>Catches</th>';
        echo '<th>Yards</th>';
        echo '<th>TDs</th>';
        echo '<th>Fumbles</th>';
        echo '<th>Drops</th>';
        echo '<th>Rec Avg</th>';
        echo '<th>20+</th>';
        echo '<th>40+</th>';
        echo '<th>Long</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Plays'] . '</td><td>' .
                $player['Tar'] . '</td><td>' .
                $player['Catches'] . '</td><td>' .
                $player['RecYds'] . '</td><td>' .
                $player['RecTD'] . '</td><td>' .
                $player['Fumbles'] . '</td><td>' .
                $player['DroppedPasses'] . '</td><td>' .
                $player['RecAvg'] . '</td><td>' .
                $player['Rec20'] . '</td><td>' .
                $player['Rec40'] . '</td><td>' .
                $player['RecLong'] . '</td>' .
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['GS'] . '</th><th>' .
                $player['Plays'] . '</th><th>' .
                $player['Tar'] . '</th><th>' .
                $player['Catches'] . '</th><th>' .
                $player['RecYds'] . '</th><th>' .
                $player['RecTD'] . '</th><th>' .
                $player['Fumbles'] . '</th><th>' .
                $player['DroppedPasses'] . '</th><th>' .
                $player['RecAvg'] . '</th><th>' .
                $player['Rec20'] . '</th><th>' .
                $player['Rec40'] . '</th><th>' .
                $player['RecLong'] . '</th>' .
                '</tr>';
                echo '</table>';
            }
        }
    }

    $played = 0;
    $playedPos = array('G','T','C','TE','FB');
    if (in_array($playerSeason['Position'],$playedPos)) {
        $played = $playerSeason['Plays'];
    }
    if ((($playerSeason['MissedBlocks'] + $playerSeason['Pancakes']) > 0) || $played > 0) {
        echo '<div align="center"><h3>Blocking</h3></div>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>Start</th>';
        echo '<th>Plays</th>';
        echo '<th>Pancakes</th>';
        echo '<th>Sacks Allowed</th>';
        echo '<th>Missed Blks</th>';
        echo '<th>Fumble Rec.</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Plays'] . '</td><td>' .
                $player['Pancakes'] . '</td><td>' .
                $player['SacksAllowed'] . '</td><td>' .
                $player['MissedBlocks'] . '</td><td>' .
                $player['FumblesRecovered'] . '</td>' .
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['GS'] . '</th><th>' .
                $player['Plays'] . '</th><th>' .
                $player['Pancakes'] . '</th><th>' .
                $player['SacksAllowed'] . '</th><th>' .
                $player['MissedBlocks'] . '</th><th>' .
                $player['FumblesRecovered'] . '</th>' .
                '</tr>';
                echo '</table>';
            }
        }
    }
    $played = 0;
    $playedPos = array('DE','DT','LB','CB','FS','SS','DB');
    if (in_array($playerSeason['Position'],$playedPos)) {
        $played = $playerSeason['Plays'];
    }
    if ((($playerSeason['Tackles'] + $playerSeason['Int'] +  $playerSeason['Hurries'] + $playerSeason['Knockdowns'] +  $playerSeason['PassesDefensed']) > 0) || $played > 0) {
        echo '<div align="center"><h3>Defense</h3></div>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>Start</th>';
        echo '<th>Plays</th>';
        echo '<th>Tackles</th>';
        echo '<th>TFL</th>';
        echo '<th>Missed Tac.</th>';
        echo '<th>Sacks</th>';
        echo '<th>Hurries</th>';
        echo '<th>Knockdowns</th>';
        echo '<th>Targeted</th>';
        echo '<th>Ints</th>';
        echo '<th>Int Yds</th>';
        echo '<th>Passes Def</th>';
        echo '<th>Force Fum</th>';
        echo '<th>Fum Recovered</th>';
        echo '<th>Fum Yds</th>';
        echo '<th>Def TDs</th>';
        echo '<th>Safeties</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['GS'] . '</td><td>' .
                $player['Plays'] . '</td><td>' .
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
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['GS'] . '</th><th>' .
                $player['Plays'] . '</th><th>' .
                $player['Tackles'] . '</th><th>' .
                $player['TFL'] . '</th><th>' .
                $player['MissedTackles'] . '</th><th>' .
                $player['Sacks'] . '</th><th>' .
                $player['Hurries'] . '</th><th>' .
                $player['Knockdowns'] . '</th><th>' .
                $player['Tar'] . '</th><th>' .
                $player['Int'] . '</th><th>' .
                $player['IntReturnYds'] . '</th><th>' .
                $player['PassesDefensed'] . '</th><th>' .
                $player['ForcedFumbles'] . '</th><th>' .
                $player['FumblesRecovered'] . '</th><th>' .
                $player['FumReturnYds'] . '</th><th>' .
                $player['DefensiveTD'] . '</th><th>' .
                $player['Safeties'] . '</th>' .
                '</tr>';
                echo '</table>';
            }
        }
    }

    if (($playerSeason['KickingPoints'] + $playerSeason['Punts']) > 0) {
        echo '<div align="center"><h3>Kicking</h3></div>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>XPA</th>';
        echo '<th>XPM</th>';
        echo '<th>XP Pct</th>';
        echo '<th>FGA</th>';
        echo '<th>FGM</th>';
        echo '<th>FG Pct</th>';
        echo '<th>FGA 50+</th>';
        echo '<th>FGM 50+</th>';
        echo '<th>FG Long</th>';
        echo '<th>Points</th>';
        echo '<th>Punts</th>';
        echo '<th>PuntYds</th>';
        echo '<th>PuntsInside20</th>';
        echo '<th>PuntLong</th>';
        echo '<th>PuntAvg</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['XPA'] . '</td><td>' .
                $player['XPM'] . '</td><td>' .
                $player['XPPct'] . '</td><td>' .
                $player['FGA'] . '</td><td>' .
                $player['FGM'] . '</td><td>' .
                $player['FGPct'] . '</td><td>' .
                $player['FGA_50'] . '</td><td>' .
                $player['FGM_50'] . '</td><td>' .
                $player['FGLong'] . '</td><td>' .
                $player['KickingPoints'] . '</td><td>' .
                $player['Punts'] . '</td><td>' .
                $player['PuntYds'] . '</td><td>' .
                $player['PuntsInside20'] . '</td><td>' .
                $player['PuntLong'] . '</td><td>' .
                $player['PuntAvg'] . '</td>' .
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['XPA'] .'</th><th>' .
                $player['XPM'] .'</th><th>' .
                $player['XPPct'] .'</th><th>' .
                $player['FGA'] .'</th><th>' .
                $player['FGM'] .'</th><th>' .
                $player['FGPct'] .'</th><th>' .
                $player['FGA_50'] .'</th><th>' .
                $player['FGM_50'] .'</th><th>' .
                $player['FGLong'] .'</th><th>' .
                $player['KickingPoints'] .'</th><th>' .
                $player['Punts'] .'</th><th>' .
                $player['PuntYds'] .'</th><th>' .
                $player['PuntsInside20'] .'</th><th>' .
                $player['PuntLong'] .'</th><th>' .
                $player['PuntAvg'] . '</th>' .
                '</tr>';
                echo '</table>';
            }
        }
    }

    if (($playerSeason['PuntReturns'] + $playerSeason['KickoffReturns']) > 0) {
        echo '<div align="center"><h3>Returns</h3></div>';
        echo '<table class="roster" border=1 id="'.$curteam.'">';
        echo '<tr>';
        echo '<th>Week</th>';
        echo '<th>Opponent</th>';
        echo '<th>Result</th>';
        echo '<th>Kick Returns</th>';
        echo '<th>Kick Ret Yds</th>';
        echo '<th>Kick Ret TD</th>';
        echo '<th>Kick Ret Avg</th>';
        echo '<th>KR Long</th>';
        echo '<th>Punt Returns</th>';
        echo '<th>Punt Ret Yds</th>';
        echo '<th>Punt Ret TD</th>';
        echo '<th>Punt Ret Avg</th>';
        echo '<th>PR Long</th>';
        echo '</tr>';

        $wins = $losses = 0;
        $statsService = statsService(0,$_GET['player'],'game');
        usort($statsService, fn($a, $b) => $a['WeekNumber'] <=> $b['WeekNumber']);
        foreach ($statsService as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season && $player['WeekNumber'] > 0 && $player['WeekNumber'] < 17) {
                if ($player['HomeTeamID'] == $player['TeamID']) {
                    $game = 'at ' . idToAbbrev($player['AwayTeamID']);
                    $isHome = 1;
                } else {
                    $game = 'vs ' . idToAbbrev($player['HomeTeamID']);
                    $isHome = 0;
                }
                $result = winCalc($wins, $losses, $player, $isHome);
                echo '<tr><td>' . 
                $player['WeekNumber'] . '</td><td>' . 
                $game . '</td><td>' .
                $result . '</td><td>' . 
                $player['KickoffReturns'] . '</td><td>' .
                $player['KickoffReturnYds'] . '</td><td>' .
                $player['KickoffReturnTD'] . '</td><td>' .
                $player['KickReturnAvg'] . '</td><td>' .
                $player['KRLong'] . '</td><td>' .
                $player['PuntReturns'] . '</td><td>' .
                $player['PuntReturnYds'] . '</td><td>' .
                $player['PuntReturnTD'] . '</td><td>' .
                $player['PuntReturnAvg'] . '</td><td>' .
                $player['PRLong'] . '</td>' .
                '</tr>';
            }
        }

        foreach ($statsServiceTotal as $player) {
            if ($player['PlayerID'] == $_GET['player'] && $player['Season'] == $season) {
                echo '<tr>' .
                '<th>TOTAL</th><th>' . 
                '</th><th>' . 
                $wins . ' - ' . $losses .'</th><th>' .
                $player['KickoffReturns'] . '</th><th>' .
                $player['KickoffReturnYds'] . '</th><th>' .
                $player['KickoffReturnTD'] . '</th><th>' .
                $player['KickReturnAvg'] . '</th><th>' .
                $player['KRLong'] . '</th><th>' .
                $player['PuntReturns'] . '</th><th>' .
                $player['PuntReturnYds'] . '</th><th>' .
                $player['PuntReturnTD'] . '</th><th>' .
                $player['PuntReturnAvg'] . '</th><th>' .
                $player['PRLong'] . '</th>' .
                '</tr>';
                echo '</table>';
            }
        }
    }

    echo '<br>';
}
?>