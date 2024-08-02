<?php 
//include '../header.php';
session_start();
require('../../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '2') {
    echo 'You are not authorized to be here.';
} else {
    $transInfo = array();
    $stmt = $connection->query("SELECT r.PlayerID, r.TeamID_Old, r.TeamID_New, r.type, p.FirstName, p.LastName, p.Position, t.City, t.Mascot FROM ptf_transactions r LEFT JOIN ptf_players p ON r.PlayerID = p.PlayerID LEFT JOIN ptf_teams t ON r.TeamID_Old = t.TeamID ORDER by date ASC");   
    while($row = $stmt->fetch_assoc()) {
        array_push($transInfo, $row);
    }

    echo '<h1>Team Roster Moves</h1>';
    echo '<p>NOTES: .</p>';

    // -------------------------------- AFC ---------------------------------------------------- //



    echo '<table><tr><td>';
        echo '<h3>Buffalo Bills - Ross Gates</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '1' || $ti['TeamID_Old'] == '1'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>New England Patriots - Specbob</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '2' || $ti['TeamID_Old'] == '2'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>Miami Dolphins - Joe Satre</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '4' || $ti['TeamID_Old'] == '4'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>Washington FT - Randy Lilley</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '20' || $ti['TeamID_Old'] == '20'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>Atlanta Falcons - Mike Ngo</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '27' || $ti['TeamID_Old'] == '27'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td></tr>';

    // -------------------------------- NFC ---------------------------------------------------- //




    echo '<tr><td>';
        echo '<h3>Green Bay Packers - Mike Kent</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '21' || $ti['TeamID_Old'] == '21'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>Chicago Bears - Matt Patterson</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '22' || $ti['TeamID_Old'] == '22'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>Los Angeles Rams - Patrick Abrams</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '35' || $ti['TeamID_Old'] == '35'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>New York Giants - Ross Tirona</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '18' || $ti['TeamID_Old'] == '18'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td><td>';
        echo '<h3>San Francisco 49ers - Mel Baltazar</h3>';
        echo '<table border=1><tr><th>Player</th><th>Type</th><th>Value</th></tr>';
        foreach ($transInfo as $ti) {
            if ($ti['TeamID_New'] == '29' || $ti['TeamID_Old'] == '29'  ) {
                if ($ti['type'] == 'change') {
                    $value = $ti['Position'];
                } else {
                    $value = '';
                }
                echo '<tr><td>' . $ti['FirstName'] . ' ' . $ti['LastName'] . '</td><td>' . $ti['type'] . '</td><td>' .  $value . '</td></tr>';
            }
        }
        echo '</table>';
    echo '</td></tr>';

    echo '</table>';
}

?>