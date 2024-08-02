<?php

include 'header.php';

echo '<h2>Team Financial Information</h2>';

$teamService = teamService('all');

echo '<table class="sortable"><tr><th>Team</th><th>Total Salary</th><th>Cap Room</th><th>Roster</th><th>Squad</th><th>IR</th><th>ALL</th>
<th>Franchise</th><th>Extensions</th><th>Market Size</th><th>Win Rating (3yr)</th>
<th>1988</th><th>1989</th><th>1990</th><th>1991</th><th>1992</th></tr>';
foreach ($teamService as $team) {
    if ($team['TeamID'] <= 20) {
        $stmt1 = $connection->query('SELECT 
        sum(s.' . $year . ') as pyear, sum(s.' . $year + 1 . ') as pyear1, sum(s.' . $year + 2 . ') as pyear2, sum(s.' . $year + 3 . ') as pyear3, sum(s.' . $year + 4 . ') as pyear4, sum(s.' . $year + 5 . ') as pyear5
         FROM ptf_players y LEFT JOIN ptf_players_salaries s ON y.PlayerID = s.PlayerID  
        WHERE y.TeamID = "' . $team['TeamID'] . '"');

        $sum = $stmt1->fetch_assoc();

        $rowcount = 0;
        $squadcount = 0;
        $ircount = 0;
        $playerService = playerService($team['TeamID'],0,2);
        foreach ($playerService as $notSquad) {
            if ($notSquad['SquadTeam'] == NULL && $notSquad['irteam'] == NULL) {
                $rowcount++; 
            } elseif ($notSquad['SquadTeam'] != NULL) {
                $squadcount++; 
            } elseif ($notSquad['irteam'] != NULL) {
                $ircount++; 
            }
        }

        $allow = allowanceService($team['TeamID']);
        if ($allow[0]['FranchiseTag'] == 1) {
            $tag = 'Available';
        } else {
            $tag = 'USED';
        }

        $wl1s = winlossService($team['TeamID'],$year-2); // CHANGE THIS TO -2 AFTER 1987
        $wl1 = $wl1s[0];

        $wl2s = winlossService($team['TeamID'],$year-1); // CHANGE THIS TO -2 AFTER 1987
        $wl2 = $wl2s[0];

        $wl3s = winlossService($team['TeamID'],$year-1);
        $wl3 = $wl3s[0];


        $winValY3 = $wl3['Wins'] * 1.5;
        $winValY2 = $wl2['Wins'];
        $winValY1 = $wl1['Wins'] * 0.5;
        // $winVal - Bonus given for a team's wins over the previous 3 seasons
        $winVal = ($winValY1 + $winValY2 + $winValY3) / 24;
        if ($team['TeamID'] >= 19) {
            $winVal = 0.75;
        }

        echo '<tr style="background-color:#'.$team['Color_1']. ';color:#'.$team['color_2'].'"><td>' . $team['FullName'] . '</td>
                <td>' . number_format($sum['pyear']) . '</td>
                <td>' . number_format($salaryCap - $sum['pyear']) . '</td>
                <td>' . $rowcount . '</td>
                <td>' . $squadcount . '</td>
                <td>' . $ircount . '</td>
                <td>' . $rowcount + $squadcount + $ircount . '</td>
                <td>' . $tag . '</td>
                <td>' . $allow[0]['Extensions'] . '</td>
                <td>' . $allow[0]['market'] . '</td>
                <td>' . number_format($winVal, 2) . '</td>
                <td>' . number_format($sum['pyear1']) . '</td>
                <td>' . number_format($sum['pyear2']) . '</td>
                <td>' . number_format($sum['pyear3']) . '</td>
                <td>' . number_format($sum['pyear4']) . '</td>
                <td>' . number_format($sum['pyear5']) . '</td>
                </tr>';
    }
}

echo '</table>';






?>