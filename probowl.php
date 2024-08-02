<?php

include 'header.php';

$statsService = votingService(0,0,0);
$players = array();
foreach ($statsService as $player) {
    array_push($players,$player);
}
$conferences = array('AFC','NFC');
$afc = array(1,2,3,4,5,6,7,8,9);
$nfc = array(10,11,12,13,14,15,16,17,18);


echo "<h1>Pro Bowl Voting</h1>";
echo '<form action="submit_vote.php" method="POST">';
        echo '<input type="hidden" id="Team" name="Team" value="' . $_SESSION['mascot'] . '">';
        echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
        echo '<input type="hidden" id="Time" name="Time" value="' . date('Y-m-d H:i:s') . '">';

foreach ($conferences as $conf) {
    echo '<div align="center"><h3>' . $conf . ' Quarterback <br>(Pick 2)</h3></div>';
    echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
    echo '<tr style="background-color:#FFFFFF">';
    echo '<th>Name</th>';
    echo '<th>Cur. Team</th>';
    echo '<th>Games</th>';
    echo '<th>Starts</th>';
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
    echo '<th>VOTE</th>';

    echo '</tr>';

    foreach ($players as $player) {
        $teamsService = teamService($player['TeamID']);
        if ($teamsService[0]['Conference'] == $conf) {
            if ($player['PassAtt'] > 0 && $player['Position'] == 'QB') {

                echo '<tr>'; 
                echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</td>
                <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                if ($player['TeamID'] != 0) {
                    echo idToAbbrev($player['TeamID']) . '</td><td>';
                } else {
                    echo '</td><td>';
                }
                echo $player['G'] . '</td><td>' . 
                $player['GS'] . '</td><td>' .
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
                $player['PassingAttemptsPerGame'] . '</td>';
                echo '<th><input type="checkbox" id="' . $conf . $player['Position'] . $player['PlayerID'] . '" name="' . $conf . $player['Position']  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';
                echo '</tr>';
            }
        }
    }

    echo '</table>';
}
echo '<br><br><br>';

$posrush = array('RB','FB');
foreach ($conferences as $conf) {
    foreach ($posrush as $pos) {
        echo '<div align="center"><h3>' . $conf . ' ' . $pos . ' <br>';
        if ($pos == 'RB') {
            echo '(Pick 2)</h3></div>';
        } elseif ($pos == 'FB')  {
            echo '(Pick 1)</h3></div>';
        }
        echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
        echo '<tr style="background-color:#FFFFFF">';
        echo '<th>Name</th>';
        echo '<th>Cur. Team</th>';
        echo '<th>Games</th>';
        echo '<th>Starts</th>';
        echo '<th>Attempts</th>';
        echo '<th>Yards</th>';
        echo '<th>TDs</th>';
        echo '<th>Fumbles</th>';
        echo '<th>Rush Avg</th>';
        echo '<th>Yds / G</th>';
        echo '<th>Att / G</th>';
        echo '<th>VOTE</th>';
        echo '</tr>';

        foreach ($players as $player) {
            if (in_array($player['TeamID'],$afc)) {
                $playerConf = 'AFC';
            } elseif (in_array($player['TeamID'],$nfc)) {
                $playerConf = 'NFC';
            }

            if ($playerConf == $conf) {
                if ($player['RushAtt'] > 0 && $player['Position'] == $pos) {

                    echo '<tr>'; 
                    echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                    if ($player['TeamID'] != 0) {
                        echo idToAbbrev($player['TeamID']) . '</td><td>';
                    } else {
                        echo '</td><td>';
                    }
                    echo $player['G'] . '</td><td>' . 
                    $player['GS'] . '</td><td>' .
                    $player['RushAtt'] . '</td><td>' .
                    $player['RushYds'] . '</td><td>' .
                    $player['RushTD'] . '</td><td>' .
                    $player['Fumbles'] . '</td><td>' .
                    $player['RushAvg'] . '</td><td>' .
                    $player['RushingYdsPerGame'] . '</td><td>' .
                    $player['RushingAttPerGame'] . '</td>';

                    echo '<th><input type="checkbox" id="' . $conf . $player['Position'] . $player['PlayerID'] . '" name="' . $conf . $player['Position']  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';

                    echo '</tr>';
                }
            }
        }
        echo '</table>';
    }
}
echo '<br><br><br>';

$posrush = array('WR','TE');
foreach ($conferences as $conf) {
    foreach ($posrush as $pos) {
        echo '<div align="center"><h3>' . $conf . ' ' . $pos . ' <br>';
        if ($pos == 'WR') {
            echo '(Pick 3)</h3></div>';
        } elseif ($pos == 'TE')  {
            echo '(Pick 1)</h3></div>';
        }
        echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
        echo '<tr style="background-color:#FFFFFF">';
        echo '<th>Name</a></th>';
        echo '<th>Cur. Team</a></th>';
        echo '<th>Games</a></th>';
        echo '<th>Starts</a></th>';
        echo '<th>Targets</a></th>';
        echo '<th>Catches</a></th>';
        echo '<th>Yards</a></th>';
        echo '<th>TDs</a></th>';
        echo '<th>Fumbles</a></th>';
        echo '<th>Drops</a></th>';
        echo '<th>Rec Avg</a></th>';
        echo '<th>Yds / G</a></th>';
        echo '<th>Catch / G</a></th>';
        echo '<th>VOTE</th>';
        echo '</tr>';

        foreach ($players as $player) {
            if (in_array($player['TeamID'],$afc)) {
                $playerConf = 'AFC';
            } elseif (in_array($player['TeamID'],$nfc)) {
                $playerConf = 'NFC';
            }

            if ($playerConf == $conf) {
                if ($player['Catches'] > 0 && $player['Position'] == $pos) {

                    echo '<tr>'; 
                    echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td>
                    <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
                    if ($player['TeamID'] != 0) {
                        echo idToAbbrev($player['TeamID']) . '</td><td>';
                    } else {
                        echo '</td><td>';
                    }
                    echo $player['G'] . '</td><td>' . 
                    $player['GS'] . '</td><td>' .
                    $player['Tar'] . '</td><td>' .
                    $player['Catches'] . '</td><td>' .
                    $player['RecYds'] . '</td><td>' .
                    $player['RecTD'] . '</td><td>' .
                    $player['Fumbles'] . '</td><td>' .
                    $player['DroppedPasses'] . '</td><td>' .
                    $player['RecAvg'] . '</td><td>' .
                    $player['ReceivingYdsPerGame'] . '</td><td>' .
                    $player['CatchesPerGame'] . '</td>';
                    echo '<th><input type="checkbox" id="' . $conf . $player['Position'] . $player['PlayerID'] . '" name="' . $conf . $player['Position']  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';

                    echo '</tr>';
                }
            }
        }
        echo '</table>';
    }
}
echo '<br><br><br>';

$posrush = array('G','T','C');
foreach ($conferences as $conf) {
    foreach ($posrush as $pos) {
        echo '<div align="center"><h3>' . $conf . ' ' . $pos . ' <br>';
        if ($pos == 'G' || $pos == 'T') {
            echo '(Pick 2)</h3></div>';
        } elseif ($pos == 'C')  {
            echo '(Pick 1)</h3></div>';
        }
        echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
        echo '<tr style="background-color:#FFFFFF">';
        echo '<th>Name</a></th>';
        echo '<th>Cur. Team</a></th>';
        echo '<th>Games</a></th>';
        echo '<th>Starts</a></th>';
        echo '<th>Plays</a></th>';
        echo '<th>Pancakes</a></th>';
        echo '<th>Sacks Allowed</a></th>';
        echo '<th>Missed Blks</a></th>';
        echo '<th>Fumbles Rec.</a></th>';
        echo '<th>VOTE</th>';
        echo '</tr>';

        foreach ($players as $player) {
            if (in_array($player['TeamID'],$afc)) {
                $playerConf = 'AFC';
            } elseif (in_array($player['TeamID'],$nfc)) {
                $playerConf = 'NFC';
            }

            if ($playerConf == $conf) {
                if (($player['MissedBlocks'] + $player['Pancakes']) > 0 && $player['Position'] == $pos) {

                    echo '<tr>'; 
                    echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td>
                    <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
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
                    $player['FumblesRecovered'] . '</td>';
                    echo '<th><input type="checkbox" id="' . $conf . $player['Position'] . $player['PlayerID'] . '" name="' . $conf . $player['Position']  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';

                    echo '</tr>';
                }
            }
        }
        echo '</table>';
    }
}

echo '<br><br><br>';

$posrush = array('DE','DT','LB', 'CB', 'FS', 'SS');
foreach ($conferences as $conf) {
    foreach ($posrush as $pos) {
        echo '<div align="center"><h3>' . $conf . ' ' . $pos . ' <br>';
        if ($pos == 'DE' || $pos == 'DT' || $pos == 'CB') {
            echo '(Pick 2)</h3></div>';
        } elseif ($pos == 'FS' || $pos == 'SS')  {
            echo '(Pick 1)</h3></div>';
        } elseif ($pos == 'LB')  {
            echo '(Pick 4)</h3></div>';
        }
        echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
        echo '<tr style="background-color:#FFFFFF">';
        echo '<th>Name</a></th>';
        echo '<th>Cur. Team</a></th>';
        echo '<th>Games</a></th>';
        echo '<th>Starts</a></th>';
        echo '<th>Plays</a></th>';
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
            if (in_array($player['TeamID'],$afc)) {
                $playerConf = 'AFC';
            } elseif (in_array($player['TeamID'],$nfc)) {
                $playerConf = 'NFC';
            }

            if ($playerConf == $conf) {
                if (($player['Tackles'] + $player['Int']) > 0 && $player['Position'] == $pos) {
                    echo '<tr>'; 
                    echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td>
                    <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
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
                    $player['Safeties'] . '</td>';
                    echo '<th><input type="checkbox" id="' . $conf . $player['Position'] . $player['PlayerID'] . '" name="' . $conf . $player['Position']  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';

                    '</tr>';
                }
            }
        }
        echo '</table>';
    }
}

echo '<br><br><br>';

$posrush = array('K','P');
foreach ($conferences as $conf) {
    foreach ($posrush as $pos) {
        echo '<div align="center"><h3>' . $conf . ' ' . $pos . ' <br>(Pick 1)</h3></div>';
        echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
        echo '<tr style="background-color:#FFFFFF">';
        echo '<th>Name</a></th>';
        echo '<th>Cur. Team</a></th>';
        echo '<th>Games</a></th>';
        echo '<th>XPA</a></th>';
        echo '<th>XPM</a></th>';
        echo '<th>XP Pct</a></th>';
        echo '<th>FGA</a></th>';
        echo '<th>FGA</a></th>';
        echo '<th>FG Pct</a></th>';
        echo '<th>FGA 50+</a></th>';
        echo '<th>FGM 50+</a></th>';
        echo '<th>FG Long</a></th>';
        echo '<th>Points</a></th>';
        echo '<th>Punts</a></th>';
        echo '<th>PuntYds</a></th>';
        echo '<th>PuntsInside20</a></th>';
        echo '<th>PuntLong</a></th>';
        echo '<th>PuntAvg</a></th>';
        echo '<th>VOTE</th>';
        echo '</tr>';

        foreach ($players as $player) {
            if (in_array($player['TeamID'],$afc)) {
                $playerConf = 'AFC';
            } elseif (in_array($player['TeamID'],$nfc)) {
                $playerConf = 'NFC';
            }

            if ($playerConf == $conf) {
                if (($player['FGA'] + $player['XPA'] + $player['Punts']) > 0 && $player['Position'] == $pos) {

                    echo '<tr>'; 
                    echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td>
                    <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
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
                    $player['PuntAvg'] . '</td>';
                    echo '<th><input type="checkbox" id="' . $conf . $player['Position'] . $player['PlayerID'] . '" name="' . $conf . $player['Position']  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';

                    '</tr>';
                }
            }
        }
        echo '</table>';
    }
}

echo '<br><br><br>';

$posrush = array('Return Specialists');
foreach ($conferences as $conf) {
    foreach ($posrush as $pos) {
        echo '<div align="center"><h3>' . $conf . ' ' . $pos . ' <br>(Pick 2)</h3></div>';
        echo '<table class="sortable" border=1 id="'.$team['Abbrev'].'">';
        echo '<tr style="background-color:#FFFFFF">';
        echo '<th>Name</a></th>';
        echo '<th>Cur. Team</a></th>';
        echo '<th>Games</a></th>';
        echo '<th>Kick Returns</a></th>';
        echo '<th>Kick Ret Yds</a></th>';
        echo '<th>Kick Ret TD</a></th>';
        echo '<th>Kick Ret Avg</a></th>';
        echo '<th>KR Long</a></th>';
        echo '<th>Punt Returns</a></th>';
        echo '<th>Punt Ret Yds</a></th>';
        echo '<th>Punt Ret TD</a></th>';
        echo '<th>Punt Ret Avg</a></th>';
        echo '<th>PR Long</a></th>';
        echo '<th>VOTE</th>';
        echo '</tr>';

        foreach ($players as $player) {
            if (in_array($player['TeamID'],$afc)) {
                $playerConf = 'AFC';
            } elseif (in_array($player['TeamID'],$nfc)) {
                $playerConf = 'NFC';
            }

            if ($playerConf == $conf) {
                if (($player['PuntReturns'] + $player['KickoffReturns']) > 0) {

                    echo '<tr>'; 
                    echo '<td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td>
                    <td class="career" id="'.idToAbbrev($player['TeamID']).'">' ;
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
                    $player['PRLong'] . '</td>';
                    echo '<th><input type="checkbox" id="' . $conf . 'Return' . $player['PlayerID'] . '" name="' . $conf . 'Return'  . $player['PlayerID'] . '" value="' . $player['FullName'] . '"></th>';

                    '</tr>';
                }
            }
        }
    echo '</table>';
    }
}

echo '<br><br><br>';
echo '<center><input type="submit" value="Submit Votes"></center><br><br>';
?>