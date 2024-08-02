<?php

include 'header.php';
$statsService = statsService(0,0,'career');

usort($statsService, fn($a, $b) => $a['FirstName'] <=> $b['FirstName']);

if ($_GET['sort'] == NULL) {
    $_GET['sort'] = 'PosSort';
}

if ($_GET['year'] == NULL) {
    $_GET['year'] = $year;
}

if ($_GET['order'] == 'desc') {
    $sorter = 'asc';
    usort($statsService, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
} else {
    $sorter = 'desc';
    usort($statsService, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
}

echo "<h1>Retired Players</h1>";

if ($freeagency == 1) {
    echo 'Free Agency is active!   You can\'t sign free agents outright until it ends!';
} else {
    foreach ($positions as $pos) {
        echo '<div id="poscat"><b>' . $pos . '</div><br>';
        echo '<table border=1 id="'.$_SESSION['abbreviation'].'">';
        echo '<th><a href="retired.php?sort=FirstName&order=' . $sorter . '">Name</a></th>';
        echo '<th><a href="retired.php?sort=PosSort&order=' . $sorter . '">Position</a></th>';
        echo '<th><a href="retired.php?sort=PassAtt&order=' . $sorter . '">Pass Att.</a></th>';
        echo '<th><a href="retired.php?sort=PassCmp&order=' . $sorter . '">Pass Cmp.</a></th>';
        echo '<th><a href="retired.php?sort=PassYds&order=' . $sorter . '">Pass Yds.</a></th>';
        echo '<th><a href="retired.php?sort=PassTD&order=' . $sorter . '">Pass TDs</a></th>';
        echo '<th><a href="retired.php?sort=PassInt&order=' . $sorter . '">Pass INTs</a></th>';
        echo '<th><a href="retired.php?sort=RushAtt&order=' . $sorter . '">Rush Att.</a></th>';
        echo '<th><a href="retired.php?sort=RushYds&order=' . $sorter . '">Rush Yds.</a></th>';
        echo '<th><a href="retired.php?sort=RushTD&order=' . $sorter . '">Rush TDs</a></th>';
        echo '<th><a href="retired.php?sort=Fumbles&order=' . $sorter . '">Fumbles</a></th>';
        echo '<th><a href="retired.php?sort=Catches&order=' . $sorter . '">Rec.</a></th>';
        echo '<th><a href="retired.php?sort=RecYds&order=' . $sorter . '">Rec Yds.</a></th>';
        echo '<th><a href="retired.php?sort=RecTD&order=' . $sorter . '">Rec TDs</a></th>';
        echo '<th><a href="retired.php?sort=Tar&order=' . $sorter . '">Targ</a></th>';
        echo '<th><a href="retired.php?sort=Tackles&order=' . $sorter . '">Tackles</a></th>';
        echo '<th><a href="retired.php?sort=Sacks&order=' . $sorter . '">Sacks</a></th>';
        echo '<th><a href="retired.php?sort=Int&order=' . $sorter . '">Ints</a></th>';
        echo '<th><a href="retired.php?sort=DefensiveTD&order=' . $sorter . '">Def. TDs</a></th>';
        echo '</tr>';
        foreach ($statsService as $player) {
            if ($player['Position'] == $pos && $player['RetiredSeason'] != 0) {
                echo '<tr><td>' . 
                '<a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FirstName'] . ' ' . $player['LastName'] . '</a></td><td>' .
                $player['Position'] . '</td><td>' .
                $player['PassAtt'] . '</td><td>' . 
                $player['PassCmp'] . '</td><td>' .
                $player['PassYds'] . '</td><td>' .
                $player['PassTD'] . '</td><td>' .
                $player['PassInt'] . '</td><td>' .
                $player['RushAtt'] . '</td><td>' .
                $player['RushYds'] . '</td><td>' .
                $player['RushTD'] . '</td><td>' .
                $player['Fumbles'] . '</td><td>' .
                $player['Catches'] . '</td><td>' .
                $player['RecYds'] . '</td><td>' .
                $player['RecTD'] . '</td><td>' .
                $player['Tar'] . '</td><td>' .
                $player['Tackles'] . '</td><td>' .
                $player['Sacks'] . '</td><td>' .
                $player['Int'] . '</td><td>' .
                $player['DefensiveTD'] . '</td>' .

                '</tr>';

            }
        }
        echo '</table><br>';
    }
}

?>