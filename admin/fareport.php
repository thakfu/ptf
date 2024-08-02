<?php

include 'adminheader.php';

$transInfo = array();
$stmt = $connection->query("SELECT r.PlayerID, r.TeamID_Old, r.TeamID_New, r.type, p.FirstName, p.LastName, p.Position, t.Abbrev, t.City, t.Mascot, s.1986, s.1987, s.1988, s.1989, s.1990, s.1991, s.1992  
FROM ptf_transactions r 
LEFT JOIN ptf_players p ON r.PlayerID = p.PlayerID 
LEFT JOIN ptf_teams t ON r.TeamID_New = t.TeamID 
LEFT JOIN ptf_players_salaries s on p.PlayerID = s.PlayerID 
WHERE r.type = 'fasign' and r.TID > '3175' 
ORDER by r.TeamID_New ASC");

while($row = $stmt->fetch_assoc()) {
    array_push($transInfo, $row);
}

for ($x = 1; $x <= 20; $x++) {
    echo '<h2>' . idToName($x) . '</h2>';
    echo '<ul>';
        foreach($transInfo as $tran) {
            if ($tran['TeamID_New'] == $x) {
                echo '<li> ' . $tran['FirstName'] . ' ' . $tran['LastName'] . ' - ' . $tran['Position'] . ' - ' . 
                $tran[$year] . ' / ' . $tran[$year + 1] . ' / ' . $tran[$year + 2] . ' / ' . $tran[$year + 3] . ' / ' . $tran[$year + 4] . ' / ' . $tran[$year + 5];
            }
        }
    echo '</ul>';
}


?>