<?php

include 'header.php';

$statsService = statsService(0,0,'career');

echo "<h1>Career Player Stats</h1>";


echo '<div align="center"><h3>Passing</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
echo '<th>Starts</th>';
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
echo '<th>Yds / G</th>';
echo '<th>Att / G</th>';
echo '<th>Sacked</th>';
echo '<th>20+</th>';
echo '<th>40+</th>';
echo '<th>Long</th>';
echo '</tr>';

usort($statsService, fn($a, $b) => $b['PassYds'] <=> $a['PassYds']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Passing Yards Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

foreach ($statsService as $player) {
    if ($player['PassAtt'] > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
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
        $player['PassingYdsPerGame'] . '</td><td>' .
        $player['PassingAttemptsPerGame'] . '</td><td>' .
        $player['WasSacked'] . '</td><td>' .
        $player['Pass20'] . '</td><td>' .
        $player['Pass40'] . '</td><td>' .
        $player['PassLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';

echo '<div align="center"><h3>Rushing</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
echo '<th>Starts</th>';
echo '<th>Plays</th>';
echo '<th>Attempts</th>';
echo '<th>Yards</th>';
echo '<th>TDs</th>';
echo '<th>Fumbles</th>';
echo '<th>Rush Avg</th>';
echo '<th>Yds / G</th>';
echo '<th>Att / G</th>';
echo '<th>20+</th>';
echo '<th>40+</th>';
echo '<th>Long</th>';
echo '</tr>';

usort($statsService, fn($a, $b) => $b['RushYds'] <=> $a['RushYds']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Rushing Yards Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

foreach ($statsService as $player) {
    if ($player['RushAtt'] > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['RushAtt'] . '</td><td>' .
        $player['RushYds'] . '</td><td>' .
        $player['RushTD'] . '</td><td>' .
        $player['Fumbles'] . '</td><td>' .
        $player['RushAvg'] . '</td><td>' .
        $player['RushingYdsPerGame'] . '</td><td>' .
        $player['RushingAttPerGame'] . '</td><td>' .
        $player['Rush20'] . '</td><td>' .
        $player['Rush40'] . '</td><td>' .
        $player['RunLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';

echo '<div align="center"><h3>Receiving</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
echo '<th>Starts</th>';
echo '<th>Plays</th>';
echo '<th>Targets</th>';
echo '<th>Catches</th>';
echo '<th>Yards</th>';
echo '<th>TDs</th>';
echo '<th>Fumbles</th>';
echo '<th>Drops</th>';
echo '<th>Rec Avg</th>';
echo '<th>Yds / G</th>';
echo '<th>Catch / G</th>';
echo '<th>20+</th>';
echo '<th>40+</th>';
echo '<th>Long</th>';
echo '</tr>';

usort($statsService, fn($a, $b) => $b['RecYds'] <=> $a['RecYds']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Receiving Yards Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

foreach ($statsService as $player) {
    if ($player['Catches'] > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['Tar'] . '</td><td>' .
        $player['Catches'] . '</td><td>' .
        $player['RecYds'] . '</td><td>' .
        $player['RecTD'] . '</td><td>' .
        $player['Fumbles'] . '</td><td>' .
        $player['DroppedPasses'] . '</td><td>' .
        $player['RecAvg'] . '</td><td>' .
        $player['ReceivingYdsPerGame'] . '</td><td>' .
        $player['CatchesPerGame'] . '</td><td>' .
        $player['Rec20'] . '</td><td>' .
        $player['Rec40'] . '</td><td>' .
        $player['RecLong'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';


echo '<div align="center"><h3>Blocking</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
echo '<th>Starts</th>';
echo '<th>Plays</th>';
echo '<th>Pancakes</th>';
echo '<th>Sacks Allowed</th>';
echo '<th>Missed Blks</th>';
echo '<th>Fumbles Rec.</th>';
echo '</tr>';

usort($statsService, fn($a, $b) => $b['Pancakes'] <=> $a['Pancakes']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Pancakes Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

foreach ($statsService as $player) {
    if (($player['MissedBlocks'] + $player['Pancakes']) > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
        $player['GS'] . '</td><td>' .
        $player['Plays'] . '</td><td>' .
        $player['Pancakes'] . '</td><td>' .
        $player['SacksAllowed'] . '</td><td>' .
        $player['MissedBlocks'] . '</td><td>' .
        $player['FumblesRecovered'] . '</td>' .
        '</tr>';
    }
}
echo '</table>';


echo '<div align="center"><h3>Defense</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
echo '<th>Starts</th>';
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

usort($statsService, fn($a, $b) => $b['Tackles'] <=> $a['Tackles']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Tackles Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['Sacks'] <=> $a['Sacks']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Sacks Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['Int'] <=> $a['Int']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Interceptions Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['Tackles'] <=> $a['Tackles']);

foreach ($statsService as $player) {
    $defender = $player['Tackles'] + $player['Int'];
    if ($defender > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
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
echo '</table>';

echo '<div align="center"><h3>Kicking</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
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

usort($statsService, fn($a, $b) => $b['KickingPoints'] <=> $a['KickingPoints']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Points Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['PuntAvg'] <=> $a['PuntAvg']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Punting Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['KickingPoints'] <=> $a['KickingPoints']);

foreach ($statsService as $player) {
    $special = $player['FGA'] + $player['XPA'] + $player['Punts'];
    if ($special > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
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
echo '</table>';


echo '<div align="center"><h3>Returns</h3></div>';
echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
echo '<tr style="background-color:#FFFFFF">';
echo '<th>Name</th>';
echo '<th>Position</th>';
echo '<th>Team</th>';
echo '<th>Games</th>';
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

usort($statsService, fn($a, $b) => $b['KickoffReturnYds'] <=> $a['KickoffReturnYds']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Kick Return Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['PuntReturnYds'] <=> $a['PuntReturnYds']);
$top = 0;
$topArray = array();
foreach ($statsService as $player) {
    if ($top < 5) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
echo '<div align="center"><i>Career Punt Return Leaders</i><br>';
foreach ($topArray as $ta) {
    echo '<img width="100px" src="export/Images/Players/' . str_replace(" ","_",$ta) . '.jpg" onerror="this.src=\'notfound.png\'">'. str_repeat('&nbsp;', 10);
}
echo '</div>';

usort($statsService, fn($a, $b) => $b['KickoffReturnYds'] <=> $a['KickoffReturnYds']);

foreach ($statsService as $player) {
    $special = $player['KickoffReturns'] + $player['PuntReturns'];
    if ($special > 0) {

        echo '<tr>'; 
        echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
        if ($player['TeamID'] != 0) {
            echo idToAbbrev($player['TeamID']) . '</td><td>';
        } else {
            echo '</td><td>';
        }
        echo $player['G'] . '</td><td>' . 
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
echo '</table><br><br>';

?>