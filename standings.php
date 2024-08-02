<?php

$conf = array('AFC','NFC');
$div = array('East','West');

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    // "called directly";

    include 'header.php';
    $teams = teamService('all');
    usort($teams, fn($a, $b) => $b['WinPct'] <=> $a['WinPct']);

    echo '<h1>PTF Standings</h1>';
    foreach ($conf as $c) {
        foreach ($div as $d) {
            echo '<h2>' . $c . ' ' . $d . '</h2>';
            echo '<table><tr><th colspan="10"></th><th colspan="3">Conference</th><th colspan="3">Division</th><th colspan="3">Home</th><th colspan="3">Away</th></tr>';
            echo '<tr><th>Pos</th><th>Team</th><th>Wins</th><th>Losses</th><th>Ties</th><th>Pct</th><th>GB</th><th>Pts For</th><th>Pts Agst</th><th>Streak</th><th>Wins</th><th>Losses</th><th>Ties</th><th>Wins</th><th>Losses</th><th>Ties</th><th>Wins</th><th>Losses</th><th>Ties</th><th>Wins</th><th>Losses</th><th>Ties</th></tr>';
            $pos = 1;
            foreach ($teams as $team) {
                if ($team['Conference'] == $c && $team['Division'] == $d) {
                    if ($pos == 1) {
                        $gb = '-';
                        $firstW = $team['Wins'];
                        $firstL = $team['Losses'];
                    } else {
                        $gb = (($firstW - $team['Wins']) + ($team['Losses'] - $firstL)) / 2;
                    }
                    echo '<tr><th>' . $pos . '</th><th>' . '<img src="images/' . $team['Abbrev'] . '_word.png" id="standLogo"><div id="standName">' . $team['Abbrev'] . '</div></th><td>' . $team['Wins'] . '</td><td>' . $team['Losses'] . '</td><td>' . $team['Ties'] . '</td><td>' . $team['WinPct'] . '</td><td>' . $gb . '</td><td>' . $team['PointsFor'] . '</td><td>' . $team['PointsAgainst'] . '</td><td>' . $team['Streak'] . '</td><td>' . $team['ConfWins'] . '</td><td>' . $team['ConfLosses'] . '</td><td>' . $team['ConfTies'] . '</td><td>' . $team['DivWins'] . '</td><td>' . $team['DivLosses'] . '</td><td>' . $team['DivTies'] . '</td><td>' . $team['HomeWins'] . '</td><td>' . $team['HomeLosses'] . '</td><td>' . $team['HomeTies'] . '</td><td>' . $team['AwayWins'] . '</td><td>' . $team['AwayLosses'] . '</td><td>' . $team['AwayTies'] . '</td></tr>';
                    $pos++;
                }
            }
            echo '</table>';
        }
    }
    echo '<br>';


  } else {
    // "included/required";

    $teams = teamService('all');
    usort($teams, fn($a, $b) => $b['WinPct'] <=> $a['WinPct']);

    foreach ($conf as $c) {
        foreach ($div as $d) {
            echo '<p align="center">' . $c . ' ' . $d . '</p>';
            echo '<table id="navstand">';
            echo '<tr><th>Pos</th><th>Team</th><th>W</th><th>L</th><th>T</th><th>%</th><th>GB</th></tr>';
            $pos = 1;
            foreach ($teams as $team) {
                if ($team['Conference'] == $c && $team['Division'] == $d) {
                    if ($pos == 1) {
                        $gb = '-';
                        $firstW = $team['Wins'];
                        $firstL = $team['Losses'];
                    } else {
                        $gb = (($firstW - $team['Wins']) + ($team['Losses'] - $firstL)) / 2;
                    }
                    echo '<tr><th>' . $pos . '</th><th id="citynav">' . $team['City'] . '</th><td>' . $team['Wins'] . '</td><td>' . $team['Losses'] . '</td><td>' . $team['Ties'] . '</td><td>' . $team['WinPct'] . '</td><td>' . $gb . '</td></tr>';
                    $pos++;
                }
            }
            echo '</table><br>';
        }
    }

  }


?>