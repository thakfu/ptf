<?php

include 'header.php';

if (true != true) {
    echo 'Free Agency is currently closed.  There is nothing to see here.';
} else {



    $stmt = $connection->query('SELECT * FROM ptf_players y 
        LEFT JOIN ptf_players_salaries s ON y.PlayerID = s.playerID  
        WHERE y.PlayerID < ' . $draftStart . ' and y.TeamID <> 0 ORDER BY s.1986 DESC');
    $players = array();
    while($row = $stmt->fetch_assoc()) {
        array_push($players, $row);
    }

    echo '<h3>1987 Salary Cap - $' . number_format($salaryCap) . '</h3>';
    echo '<a href="league-pay-averages.csv">The League Averages report file can be downloaded HERE.</a><br>' ;
    echo '<h2>The Highest Paid Players by Position</h2>';
    $positionAverages = array();
    foreach ($positions as $pos) {
        echo $pos . '<br>';
        echo '<ul>';
        $top5 = 0;
        $posTop = 0;
        $posAvg = 0;
        $pos5Avg = 0;
        foreach ($players as $player) {
            if (str_contains($player['Position'],$pos) == 1) {
                echo '<li>' . $player['FirstName'] . ' ' . $player['LastName'] . ' - ' . number_format($player['1987']);
                $top5++;
                if ($top5 == 1) {
                    $posTop = $player['1987'];
                }
                if ($top5 <= 5) {
                    $pos5Avg = $pos5Avg + $player['1987'];
                }
                $posAvg = $posAvg + $player['1987'];
            }
        }
        echo '<li>--------------------------------------';
        $top5avg = $pos5Avg / 5;
        $allAvg = $posAvg / $top5;
        $positionAverages[$pos.'TOP'] = $posTop; 
        $positionAverages[$pos.'5'] = $top5avg; 
        $positionAverages[$pos.'ALL'] = $allAvg;
        echo '<li>Top 5 Average - ' . number_format($top5avg);
        echo '<li>Position Average - ' . number_format($allAvg);

    echo '</ul>';
    }
 //THIS DOESNT NEED TO BE SPITTING OUT THE REPORT RIGHT NOW
$csvFile = new SplFileObject('league-pay-averages.csv', 'w+');
    foreach ($positionAverages as $key => $value) {
        $csvFile->fputcsv(array($key, $value));
    }
$csvFile = null;

}
?>