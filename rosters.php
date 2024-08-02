<?php

include 'header.php';
$playerService = playerService($_GET['team'],0,2);
$teamService = teamService($_GET['team']);
$team = $teamService[0];
$y1cap = 0;
$y2cap = 0;
$y3cap = 0;
$y4cap = 0;
$y5cap = 0;
$y6cap = 0;

if ($_GET['sort'] == NULL) {
    $_GET['sort'] = 'Jersey';
}

if ($_GET['order'] == 'asc') {
    $sorter = 'desc';
    usort($playerService, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
} else {
    $sorter = 'asc';
    usort($playerService, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
}

echo "<h1>" . $team['FullName'] . " Roster</h1>";
echo "<p> ** - denotes Practice Squad player</p>";
echo "<p> ## - denotes on Injured Reserve</p>";

echo '<h3>' . $year . ' Salary Cap - $' . number_format($salaryCap) . '</h3>';
echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Jersey&order=' . $sorter . '">Jersey</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=FirstName&order=' . $sorter . '">Name</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=PosSort&order=' . $sorter . '">Position</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Age&order=' . $sorter . '">Age</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Experience&order=' . $sorter . '">Exp</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Overall&order=' . $sorter . '">Overall</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Money&order=' . $sorter . '">Money</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Security&order=' . $sorter . '">Secur</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Loyalty&order=' . $sorter . '">Loyal</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=Winning&order=' . $sorter . '">Win</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=PlayingTime&order=' . $sorter . '">P.Time</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=CloseToHome&order=' . $sorter . '">Home</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=MarketSize&order=' . $sorter . '">Market</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=' . $year . '&order=' . $sorter . '">Year 1</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=' . $year + 1 . '&order=' . $sorter . '">Year 2</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=' . $year + 2 . '&order=' . $sorter . '">Year 3</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=' . $year + 3 . '&order=' . $sorter . '">Year 4</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=' . $year + 4 . '&order=' . $sorter . '">Year 5</a></th>';
echo '<th><a href="rosters.php?team=' . $_GET['team'] . '&sort=' . $year + 5 . '&order=' . $sorter . '">Year 6</a></th>';
echo '</tr>';

foreach ($playerService as $player) {
    $y1cap += $player[$year];
    $y2cap += $player[$year + 1];
    $y3cap += $player[$year + 2];
    $y4cap += $player[$year + 3];
    $y5cap += $player[$year + 4];
    $y6cap += $player[$year + 5];
    //30-50
    if ($player['Security'] <= 35) {
        $sec = '1-2 Yrs';
    } elseif ($player['Security'] <= 40 && $player['Security'] > 35) {
        $sec = '1-4 Yrs';
    } elseif ($player['Security'] <= 45 && $player['Security'] > 40) {
        $sec = '3-6 Yrs';
    } elseif ($player['Security'] >= 46) {
        $sec = '5-6 Yrs';
    }
    //30-50
    if ($player['Loyalty'] <= 32) {
        $loy = 'D';
    } elseif ($player['Loyalty'] <= 39 && $player['Loyalty'] > 32) {
        $loy = 'C';
    } elseif ($player['Loyalty'] <= 47 && $player['Loyalty'] > 39) {
        $loy = 'B';
    } elseif ($player['Loyalty'] >= 48) {
        $loy = 'A';
    }
    //30-70
    if ($player['Winning'] <= 40) {
        $win = 'D';
    } elseif ($player['Winning'] <= 50 && $player['Winning'] > 40) {
        $win = 'C';
    } elseif ($player['Winning'] <= 60 && $player['Winning'] > 50) {
        $win = 'B';
    } elseif ($player['Winning'] >= 61) {
        $win = 'A';
    }
    //70-100
    if ($player['PlayingTime'] <= 75) {
        $pt = 'D';
    } elseif ($player['PlayingTime'] <= 85 && $player['PlayingTime'] > 75) {
        $pt = 'C';
    } elseif ($player['PlayingTime'] <= 92 && $player['PlayingTime'] > 85) {
        $pt = 'B';
    } elseif ($player['PlayingTime'] >= 93) {
        $pt = 'A';
    }
    //30-50
    if ($player['CloseToHome'] <= 32) {
        $cth = 'D';
    } elseif ($player['CloseToHome'] <= 39 && $player['CloseToHome'] > 32) {
        $cth = 'C';
    } elseif ($player['CloseToHome'] <= 47 && $player['CloseToHome'] > 39) {
        $cth = 'B';
    } elseif ($player['CloseToHome'] >= 48) {
        $cth = 'A';
    }
    //30-70
    if ($player['MarketSize'] <= 40) {
        $mar = 'Small';
    } elseif ($player['MarketSize'] <= 50 && $player['MarketSize'] > 40) {
        $mar = 'Sm-Mid';
    } elseif ($player['MarketSize'] <= 60 && $player['MarketSize'] > 50) {
        $mar = 'Lg-Mid';
    } elseif ($player['MarketSize'] >= 61) {
        $mar = 'Large';
    }

    echo '<tr><td>'; 
    if ($player['SquadTeam'] == $_GET['team']) {
        echo '**' . $player['Jersey'];
    } elseif ($player['irteam'] == $_GET['team']) {
        echo '##' . $player['Jersey'];
    } else {
        echo $player['Jersey'];
    }
    echo '</td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
    $player['Position'] . '</td><td>' .
    $player['Age'] . '</td><td>'; 
    if ($player['Experience'] == 0) {
        echo '<b>R</b>';
    } else {
        echo $player['Experience'];
    }
    echo '</td><td>' .
    //floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) . '"</td><td>' .
    $player['Overall'] . '</td><td>' .
    $player['Money'] . '</td><td>' .
    $sec . '</td><td>' .
    $loy . '</td><td>' .
    $win . '</td><td>' .
    $pt . '</td><td>' .
    $cth . '</td><td>' .
    $mar . '</td><td>' .
    $player[$year] . '</td><td>' .
    $player[$year + 1] . '</td><td>' .
    $player[$year + 2] . '</td><td>' .
    $player[$year + 3] . '</td><td>' .
    $player[$year + 4] . '</td><td>' .
    $player[$year + 5] . '</td>' .
    '</tr>';
}
echo '<tr><th colspan=13></th><th>' . $y1cap . '</th><th>' . $y2cap . '</th><th>' . $y3cap . '</th><th>' . $y4cap . '</th><th>' . $y5cap . '</th><th>' . $y6cap . '</th></tr>';
echo '</table>';
echo '</table>';
echo '</table>';
echo '</table>';
echo '</table>';
echo '</table>';

?>