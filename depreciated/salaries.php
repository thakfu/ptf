<?php
include 'header.php';

$teams = array(1, 2, 4, 18, 20, 21, 22, 27, 29, 35);

echo '<ul>';
foreach ($teams as $team) {
    $stmt1 = $connection->query('SELECT 
        sum(s.1985) as p1985 FROM ptf_players y LEFT JOIN ptf_players_salaries s ON y.PlayerID = s.playerID  
        WHERE y.TeamID = "' . $team . '"');
    $sum = $stmt1->fetch_assoc();

    switch ($team) {
        case '1':
            $abbrev = 'BUF';
            break;
        case '2':
            $abbrev = 'NE';
            break;
        case '4':
            $abbrev = 'MIA';
            break;
        case '18':
            $abbrev = 'NYG';
            break;
        case '20':
            $abbrev = 'WAS';
            break;
        case '21':
            $abbrev = 'GB';
            break;
        case '22':
            $abbrev = 'CHI';
            break;
        case '27':
            $abbrev = 'ATL';
            break;
        case '29':
            $abbrev = 'SF';
            break;
        case '35':
            $abbrev = 'LAR';
            break;
    }
    echo '<li>' . $abbrev . ' - ' . number_format($sum['p1985']);

}
echo '</ul>';
?>