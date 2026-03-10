<?php

include 'header.php';

$draftYear = $_GET['year'];

$picks = draftPicks($draftYear);

$teamNameArr = teamHistory(0,$draftYear);

if ($draftYear == 1985) {
    $xAmt = 58;
} else {
    $xAmt = 7;
}

echo '<center><h1>' . $draftYear . ' PTF Draft</h1><br><br>Past Draft History:<br>';
foreach ($pastSeasons as $ps) {
    echo '<a href = "pastdraft.php?year=' . $ps . '">' . $ps . ' </a> - ';
}
echo '<a href = "pastdraft.php?year=' . $year . '">' . $year . ' </a></center><br>';

for ($x = 1; $x <= $xAmt; $x++) {
    echo '<h2>Round ' . $x . '</h2>';
    echo '<table><tr><th>Round</th><th>Pick</th><th>Team</th><th>Selection</th></tr>';
    foreach ($picks as $pick) {
        foreach ($teamNameArr as $tna) {
            if ($pick['owner'] == $tna['TeamID']) {
                $teamName = idToName($tna[$draftYear]);
            }
        }

        if ($pick['owner'] != $pick['team']) {
            $teamAbbrev = idToAbbrev($pick['team']);
            $string = " (from " . $teamAbbrev . ")";
        } else {
            $string = "";
        }

        if ($pick['round'] == $x) {
            echo '<tr><td>' . $pick['round'] . '</td><td>' . $pick['pick'] . '</td><td>' . $teamName .  $string . '</td><td>' . $pick['FirstName'] . ' ' . $pick['LastName'] . '</td></tr>' ;
        }
    }
    echo '</table><br>';
}
echo '<br>';


?>