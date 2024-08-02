<?php 

include 'header.php';

include 'nav.php';

$teamService = teamService($_GET['team']);
$team = $teamService[0];

var_dump($team);

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
    JOIN ptf_bowl_games g on g.BowlID = h.BowlID where WinnerID = '" . $_GET['team'] . "'");
$bowls = array();
while($row = $bowlstmt->fetch_assoc()) {
    array_push($bowls, $row);
}



 
echo '<div class="tmPage">';
include 'teamhead.php';

echo '<table><tr>';
foreach ($bowls as $bowl) {
    if ($bowl['ShortName'] == 'SUPER BOWL') {
        $year = numberToRomanRepresentation($bowl['Year'] - 1984);
    } else {
        $year = $bowl['Year'];
    }
    echo '<td><table><tr><td><img src="rb-trophy.png" title="' . $bowl['ShortName'] . ' - ' . $year . '" width="75"></td></tr><tr><td>' . $bowl['ShortName'] . ' - ' . $year . '</td></tr></table></td>';
}
echo '</tr></table>';

echo '<h2>' . $team['Mascot'] . ' Year-By-Year</h2>';

foreach ($pastSeasons as $season) {
    echo '<h3>' . $season . '</h3>';
}





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