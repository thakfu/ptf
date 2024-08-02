<?php 
include 'header.php';

$stmt = $connection->query('SELECT p.PlayerID, p.FirstName, p.LastName, p.Position, p.Overall FROM ptf_players p LEFT JOIN ptf_players_squad s ON s.PlayerID = p.PlayerID WHERE TeamID = ' . $_SESSION['TeamID'] . ' AND s.PlayerID IS NULL ORDER BY Overall DESC');

$players = array();
$chart = array();
$order = [1,2,3];
$positions_off = ['QB','RB','FB','WR1','WR2','WR3','TE1','TE2','LT','LG','C','RG','RT'];
$positions_def = ['DT1','DT2','RE','LE','MLB1','MLB2','ROLB','LOLB','CB1','CB2','NB1','NB2','SS','FS'];
$positions_st = ['K','P','KR1','KR2','PR'];
$scenarios = ['First and 10','First and Short','Second and Short','Second and Long','Third and Short','Third and Long','Fourth and Short','Fourt and Long','Goal Line','End of Half or Game (Winning)','End of Half or Game (Losing)'];

while($row = $stmt->fetch_assoc()) {
    array_push($players, $row);
}

if ($_SESSION['TeamID'] != 0) {
    $depth = $connection->query('SELECT Position, Team, PlayerID, Special FROM `ptf_players_depth` WHERE Team = ' . $_SESSION['TeamID'] . ' ORDER BY Position DESC');
    while($row = $depth->fetch_assoc()) {
        array_push($chart, $row);
    }


    echo '<h1>' . $_SESSION['mascot'] . ' Depth Chart</h1>';
    echo "<p id='dcnote'>NOTES: The player in position  ~ RB - 2 ~ will be the 3RD RB in 3 Back formations.   The player in position ~ WR1 - 2 ~ will be the 4TH WR in 4 wide formations.</p>";
    echo '<div id="depthcharts"><table class="depth" id="' . $_SESSION['abbreviation'] . '"><tr><td valign="top"><h2>OFFENSE</h2>';


    foreach ($positions_off as $p_off) {
        switch ($p_off) {
            case 'QB':
                $legal1 = 'QB';
                $legal2 = '';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'RB':
            case 'FB':
                $legal1 = 'RB';
                $legal2 = 'FB';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'WR1':
            case 'WR2':
            case 'WR3':
                $legal1 = 'WR';
                $legal2 = 'TE';
                $legal3 = 'RB';
                $legal4 = 'FB';
                break;
            case 'TE1':
            case 'TE2':
                $legal1 = 'TE';
                $legal2 = 'WR';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'LT':
            case 'LG':
            case 'C': 
            case 'RG': 
            case 'RT':
                $legal1 = 'T';
                $legal2 = 'G';
                $legal3 = 'C';
                $legal4 = '';
                break;
        }

        echo '<h2>' . $p_off . '</h2>';
        echo '<i>Legal Positions: ' . $legal1 . '  ' . $legal2 . '  ' . $legal3 . '  ' . $legal4 . '</i><br><br>';
        echo '<form action="submit_strategy.php" method="POST">';
        echo '<input type="hidden" id="Team" name="Team" value="' . $_SESSION['mascot'] . '">';
        echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
        echo '<input type="hidden" id="Time" name="Time" value="' . date('Y-m-d H:i:s') . '">';
        echo '<table><tr><th>Order</th><th>Player</th></tr>';

        foreach ($order as $row) {
            echo '<tr><td>'.$p_off.' - '.$row.'</td><td><select id="'.$p_off.$row.'" name="'.$p_off.$row.'">';     
            foreach ($players as $player) {
                if ($player['Position'] == $legal1 || $player['Position'] == $legal2 || $player['Position'] == $legal3 || $player['Position'] == $legal4) {
                    echo '<option value="' . $player['PlayerID'] . '" ';
                    foreach ($chart as $dc) {
                        echo ($dc['Position'] == $p_off.$row && $player['PlayerID'] == $dc['PlayerID'] ? 'selected' : '');
                    }
                    echo '>' . $player['FirstName'] . ' ' . $player['LastName'] . ' - ' . $player['Position'] . ' (' . $player['Overall'] . ')</option>';
                }
            } 
            if ($row == 3) {
                echo '<option value="none"';
                foreach ($chart as $dc) {
                    echo ($dc['Position'] == $p_off.$row && 0 == $dc['PlayerID'] ? 'selected' : '');
                }
                echo '>EMPTY</option>';
            } 
        }
        echo '</select></td></tr></table>';
    } 

    echo "<br><br></td></tr></table><table class='depth' id='" . $_SESSION['abbreviation'] . "'><tr><td valign='top'><h2>DEFENSE</h2>";

    foreach ($positions_def as $p_def) {
        switch ($p_def) {
            case 'DT1':
            case 'DT2':
            case 'RE':
            case 'LE':
                $legal1 = 'DT';
                $legal2 = 'DE';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'MLB1':
            case 'MLB2':
            case 'ROLB':
            case 'LOLB':
                $legal1 = 'LB';
                $legal2 = 'DE';
                $legal3 = 'SS';
                $legal4 = '';
                break;
            case 'CB1':
            case 'CB2':
                $legal1 = 'CB';
                $legal2 = 'FS';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'NB1':
            case 'NB2':
                $legal1 = 'CB';
                $legal2 = 'FS';
                $legal3 = 'SS';
                $legal4 = 'LB';
                break;
            case 'FS':
                $legal1 = 'SS';
                $legal2 = 'FS';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'SS':
                $legal1 = 'SS';
                $legal2 = 'FS';
                $legal3 = 'LB';
                $legal4 = '';
                break;
        }

        echo '<h2>' . $p_def . '</h2>';
        echo '<i>Legal Positions: ' . $legal1 . '  ' . $legal2 . '  ' . $legal3 . '  ' . $legal4 . '</i><br><br>';
        echo '<table><tr><th>Order</th><th>Player</th></tr>';

        foreach ($order as $row) {
            echo '<tr><td>'.$p_def.' - '.$row.'</td><td><select id="'.$p_def.$row.'" name="'.$p_def.$row.'">';     
            foreach ($players as $player) {
                if ($player['Position'] == $legal1 || $player['Position'] == $legal2 || $player['Position'] == $legal3 || $player['Position'] == $legal4) {
                    echo '<option value="' . $player['PlayerID'] . '" ';
                    foreach ($chart as $dc) {
                        echo ($dc['Position'] == $p_def.$row && $player['PlayerID'] == $dc['PlayerID'] ? 'selected' : '');
                    }
                    echo '>' . $player['FirstName'] . ' ' . $player['LastName'] . ' - ' . $player['Position'] . ' (' . $player['Overall'] . ')</option>';
                }
            } 
            if ($row == 3) {
                echo '<option value="none"';
                foreach ($chart as $dc) {
                    echo ($dc['Position'] == $p_def.$row && 0 == $dc['PlayerID'] ? 'selected' : '');
                }
                echo '>EMPTY</option>';
            } 
        }
        echo '</select></td></tr></table>';
    }


    echo "<br><br></td></tr><table class='depth' id='" . $_SESSION['abbreviation'] . "'><tr><td valign='top'><h2>SPECIAL TEAMS</h2>";

    foreach ($positions_st as $p_st) {
        switch ($p_st) {
            case 'K':
            case 'P':
                $legal1 = 'K';
                $legal2 = 'P';
                $legal3 = '';
                $legal4 = '';
                break;
            case 'KR1':
            case 'KR2':
            case 'PR':
                $legal1 = 'CB';
                $legal2 = 'WR';
                $legal3 = 'RB';
                $legal4 = 'FS';
                break;
        }

        echo '<h2>' . $p_st . '</h2>';
        echo '<i>Legal Positions: ' . $legal1 . '  ' . $legal2 . '  ' . $legal3 . '  ' . $legal4 . '</i><br><br>';
        echo '<table><tr><th>Order</th><th>Player</th></tr>';

        foreach ($order as $row) {
            echo '<tr><td>'.$p_st.' - '.$row.'</td><td><select id="'.$p_st.$row.'" name="'.$p_st.$row.'">';     
            foreach ($players as $player) {
                if ($player['Position'] == $legal1 || $player['Position'] == $legal2 || $player['Position'] == $legal3 || $player['Position'] == $legal4) {
                    echo '<option value="' . $player['PlayerID'] . '" ';
                    foreach ($chart as $dc) {
                        echo ($dc['Position'] == $p_st.$row && $player['PlayerID'] == $dc['PlayerID'] ? 'selected' : '');
                    }
                    echo '>' . $player['FirstName'] . ' ' . $player['LastName'] . ' - ' . $player['Position'] . ' (' . $player['Overall'] . ')</option>';
                }
            }
            if ($row == 3) {
                echo '<option value="none"';
                foreach ($chart as $dc) {
                    echo ($dc['Position'] == $p_st.$row && 0 == $dc['PlayerID'] ? 'selected' : '');
                }
                echo '>EMPTY</option>';
            } 
        }
        echo '</select></td></tr></table>';
    }


    foreach ($chart as $dc) {
        if ($dc['Position'] == 'NOTE1') {
            $Note1 = $dc['Special'];
        } elseif ($dc['Position'] == 'NOTE2') {
            $Note2 = $dc['Special'];
        } elseif ($dc['Position'] == 'NOTE3') {
            $Note3 = $dc['Special'];
        } elseif ($dc['Position'] == 'NOTE4') {
            $Note4 = $dc['Special'];
        } elseif ($dc['Position'] == 'NOTE5') {
            $Note5 = $dc['Special'];
        }
    }

    echo '<br><br>';
    echo '<p><b>Position Notes:</b><br> You are allowed to play up to 5 players outside<br> of their normal positions per game.  <br>List them below in the following format:<br><i>ex: Deion Sanders - WR2 - 1</i><br></p>';
    
    echo '<label for="special1">Position Note 1:</label>';
    echo '<input type="text" id="special1" name="special1" value="'.$Note1.'"><br>';

    echo '<label for="special2">Position Note 2:</label>';
    echo '<input type="text" id="special2" name="special2" value="'.$Note2.'"><br>';
    
    echo '<label for="special3">Position Note 3:</label>';
    echo '<input type="text" id="special3" name="special3" value="'.$Note3.'"><br>';
    
    echo '<label for="special4">Position Note 4:</label>';
    echo '<input type="text" id="special4" name="special4" value="'.$Note4.'"><br>';
    
    echo '<label for="special5">Position Note 5:</label>';
    echo '<input type="text" id="special5" name="special5" value="'.$Note5.'"><br>';
    
    echo '<br><br><input type="submit" value="Submit Depth"><br><br>';

    echo "</td>";
    /*echo "<td valign='top'><h2>STRATEGY</h2>

    <h2>Offense Run/Pass Ratio</h2>
    <table>
        <tr>
            <th>Scenario</th>
            <th>Percent</th>
        </tr>";


        foreach ($scenarios as $scen) {
            foreach ($chart as $row) {
                if ($row['Position'] == 'off_' . str_replace(' ','_',$scen)) {
                    echo '<tr><td>' . $scen . '</td><td><input type="number" min="1" max="100" value="'. $row['PlayerID'].'" name="off '.$scen. '" id="off '.$scen. '"></td></tr>';
                }
            }
        }

    echo "</table>

    <h2>Defense Blitz Weight</h2>
    <table>
        <tr>
            <th>Scenario</th>
            <th>Percent</th>
        </tr>";

        foreach ($scenarios as $scen) {
            foreach ($chart as $row) {
                if ($row['Position'] == 'def_' . str_replace(' ','_',$scen)) {
                    echo '<tr><td>' . $scen . '</td><td><input type="number" min="1" max="100" value="'. $row['PlayerID'].'" name="def '.$scen. '" id="def '.$scen. '"></td></tr>';
                }
            }
        }

    echo '</table>
    <br><br>'; */
    echo '</tr></table><br><br></div><br><br>';
} else {
    echo 'You Dont Have a Team...';
}
?>














































