<?php 

include 'header.php';

//include 'nav.php';

$teamService = teamService($_GET['team']);
$team = $teamService[0];

//var_dump($team);

$playerService = playerService($_GET['team'],0,0);

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
$top = 0;
$topArray = array();
foreach ($playerService as $player) {
    if ($top < 10) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}

$bowlstmt = $connection->query("SELECT * FROM ptf_bowl_history h
    JOIN ptf_bowl_games g on g.BowlID = h.BowlID where h.WinnerID = '" . $_GET['team'] . "'");
$bowls = array();
while($row = $bowlstmt->fetch_assoc()) {
    array_push($bowls, $row);
}



 
echo '<div class="tmPage">';
include 'teamhead.php';

echo '<table><tr>';
foreach ($bowls as $bowl) {
    if ($bowl['ShortName'] == 'SUPER BOWL') {
        $year = numberToRomanRepresentation($bowl['Year'] - 84);
        $img = "images/super.png";
    } else {
        $year = $bowl['Year'];
    }

    if ($bowl['ShortName'] == 'Retro Bowl') {
        $img = "images/retro.png";
    } elseif ($bowl['ShortName'] == 'Tecmo Bowl') {
        $img = "images/tecmo.png";
    } elseif ($bowl['ShortName'] == 'PrimeTime Bowl') {
        $img = "images/primetime.png";
    } elseif ($bowl['ShortName'] == 'HEAT Bowl') {
        $img = "images/heat.png";
    } elseif ($bowl['ShortName'] == 'Wheel Bowl') {
        $img = "images/wheel.png";
    } elseif ($bowl['ShortName'] == 'Irish Bowl') {
        $img = "images/irish.png";
    } elseif ($bowl['ShortName'] == 'International Bowl') {
        $img = "images/international.png";
    } elseif ($bowl['ShortName'] == 'Doritos Bowl') {
        $img = "images/doritos.png";
    } elseif ($bowl['ShortName'] == 'Porcelain Bowl') {
        $img = "images/toilet.png";
    } elseif ($bowl['ShortName'] == 'Playoff Bowl') {
        $img = "images/playoff.png";
    }
    echo '<td><table><tr><td><img src="' . $img . '" title="' . $bowl['ShortName'] . ' - ' . $year . '" height="150"></td></tr><tr><td>' . $bowl['ShortName'] . ' - ' . $year . '</td></tr></table></td>';
}
echo '</tr></table>';

echo '<h2>' . $team['Mascot'] . ' Year-By-Year</h2>';

echo '<table><tr><td>Year</td><td>Name</td><td>Wins</td><td>Losses</td><td>Playoffs</td><td>Champion</td><td>ELO</td><td>Pts For</td>
<td>Pts Alwd</td><td>TOT OFF</td><td>PASS OFF</td><td>RUN OFF</td><td>TOT DEF</td><td>PASS DEF</td><td>RUN DEF</td></tr>';

foreach ($allSeasons as $season) {
    $statsstmt = $connection->query("SELECT * FROM ptf_teams_season_stats where TeamID = '" . $_GET['team'] . "' and Season = " . $season);
    while($row = $statsstmt->fetch_assoc()) {

        echo '<tr><td>' . $season . '</td>';
        echo '<td>' . idtoname($team[$season]) . '</td>';
        echo '<td>' . $row['Wins'] . "</td><td>" . $row['Losses'] . '</td>';
        echo '<td>';
        if ($row['MadePlayoffs'] == 1) {
            echo 'X';
        } else {
            echo '-';
        } 
        echo '</td>';
        echo '<td>';
        if ($row['ChampionshipWins'] == 1) {
            echo 'X';
        } else {
            echo '-';
        }
        echo '</td>';
        echo '<td>' . $row['ELORating'] . '</td>';
        echo '<td>' . $row['PointsFor'] . '</td>';
        echo '<td>' . $row['PointsAgainst'] . '</td>';
        echo '<td>' . $row['TotalYds'] . '</td>';
        echo '<td>' . $row['PassYds'] . '</td>';
        echo '<td>' . $row['RushYds'] . '</td>';
        echo '<td>' . $row['TotalYdsAgainst'] . '</td>';
        echo '<td>' . $row['PassYdsAgainst'] . '</td>';
        echo '<td>' . $row['RushYdsAgainst'] . '</td>';
        echo '<tr>';
    }
}
echo '</table>';





echo '</div>';

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

?>