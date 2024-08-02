<?php 
include 'adminheader.php';
session_start();
require('../../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '2') {
    echo 'You are not authorized to be here.';
} else {
    $charts = array();
    $dc = $connection->query('SELECT d.position,d.team,d.playerID,d.SimPos,d.SimAlt,d.Special,p.FirstName,p.LastName FROM `ptf_players_depth` d LEFT JOIN `ptf_players` p ON p.PlayerID = d.playerID');
    while($row = $dc->fetch_assoc()) {
        array_push($charts, $row);
    }

    $plays = array();
    $pc = $connection->query('SELECT * FROM `ptf_coaches`');
    while($row = $pc->fetch_assoc()) {
        array_push($plays, $row);
    }

    $strats = array();
    $gp = $connection->query('SELECT * FROM `ptf_gameplan`');
    while($row = $gp->fetch_assoc()) {
        array_push($strats, $row);
    }

    $stratoff = array('style','focus','tempo','passPref','passTargetPref','primaryRec','thirdDownBack','goalLineBack','runPref','rbRole','backfieldCom','teRole','qbTuck');
    $stratdef = array('styled','focusd','coverPref','dlUse','lbUse','primaryDef','matchWR','alignMan','target','margin','energyMin','energyMax','overrideWin','overrideLose','oPrep','dPrep');

    $posC1 = array('QB1','RB1','WR11','TE11','LT1','C1','RT1');
    $posC2 = array('QB2','RB2','WR12','TE12','LT2','C2','RT2');
    $posC3 = array('QB3','RB3','WR13','TE13','LT3','C3','RT3');
    $posC4 = array('--','FB1','WR21','TE21','LG1','--','RG1');
    $posC5 = array('--','FB2','WR22','TE22','LG2','--','RG2');
    $posC6 = array('--','FB3','WR23','TE23','LG3','--','RG3');
    $posC7 = array('--','--','WR31','--','--','--','--');
    $posC8 = array('--','--','WR32','--','--','--','--');
    $posC9 = array('--','--','WR33','--','--','--','--');
    $posC10 = array('LE1','DT11','MLB11','CB11','NB11','FS1','SS1');
    $posC11 = array('LE2','DT12','MLB12','CB12','NB12','FS2','SS2');
    $posC12 = array('LE3','DT13','MLB13','CB13','NB13','FS3','SS3');
    $posC13 = array('RE1','DT21','MLB21','CB21','NB21','--','--');
    $posC14 = array('RE2','DT22','MLB22','CB22','NB22','--','--');
    $posC15 = array('RE3','DT23','MLB23','CB23','NB23','--','--');
    $posC16 = array('--','--','LOLB1','ROLB1','--','--','--');
    $posC17 = array('--','--','LOLB2','ROLB2','--','--','--');
    $posC18 = array('--','--','LOLB3','ROLB3','--','--','--');
    $posC19 = array('K1','P1','KR11','KR21','PR1','NOTE1');
    $posC20 = array('K2','P2','KR12','KR22','PR2','NOTE2');
    $posC21 = array('K3','P3','KR13','KR23','PR3','NOTE3');
    $posC22 = array('--','--','--','--','--','NOTE4');
    $posC23 = array('--','--','--','--','--','NOTE5');

    echo '<h1>Current Strategy</h1>';
    echo '<p>NOTES: The player in position  ~ RB - 2 ~ will be the 3RD RB in 3 Back formations.   The player in position ~ WR1 - 2 ~ will be the 4TH WR in 4 wide formations.</p>';

    // -------------------------------- AFC ---------------------------------------------------- //

    for ($x = 1; $x <= 18; $x++) {
        echo '<h2>' . idToAbbrev($x) . '</h2>';
        echo '<h3>Game Plan</h3>';
        echo '<table border=1><tr>';
        foreach ($strats as $strat) {
            if ($strat['TeamID'] == $x) {
                foreach ($strat as $key => $data) {
                    if ($key == 'defaulto' && $data != 'Manual') {
                        echo '<h3>USE HEAD COACH - STRATEGY</h3>';
                    }
                    if (in_array($key, $stratoff)) {
                        echo '<th>' . $key . '</th>'; 
                    }
                }
                echo '<td></td><td></td><td></td></tr>';
                foreach ($strat as $key => $data) {
                    if (in_array($key, $stratoff)) {
                        echo '<td>' . $data . '</td>'; 
                    }
                }
                echo '<td></td><td></td><td></td></tr>';
                foreach ($strat as $key => $data) {
                    if (in_array($key, $stratdef)) {
                        echo '<th>' . $key . '</th>'; 
                    }
                }
                echo '</tr>';
                foreach ($strat as $key => $data) {
                    if (in_array($key, $stratdef)) {
                        echo '<td>' . $data . '</td>'; 
                    }
                }
            }
        }
        echo '</tr></table><br>';

        echo '<h3>COACH SETTINGS</h3>';
        echo '<table border=1><tr>';
        foreach ($plays as $play) {
            if ($play['TeamID'] == $x) {
                foreach ($play as $key => $data) {
                    if ($key == 'offUseCoach' && $data == 'HC') {
                        echo '<h3>USE HEAD COACH - OFFENSE</h3>';
                    }
                    if (!in_array($key, array('TeamID','offUseCoach','defUseCoach','last_pc'))) {
                        echo '<th>' . $key . '</th>'; 
                        if (strpos($key,'o') === 0) {
                            echo '<th>' . $key . '</th>'; 
                        }
                    }
                }
                echo '</tr>';
                foreach ($play as $key => $data) {
                    if (!in_array($key, array('TeamID','offUseCoach','defUseCoach','last_pc'))) {
                        echo '<th>' . $data . '</th>'; 
                        if (strpos($key,'o') === 0) {
                            echo '<th>' . $data . '</th>'; 
                        }
                    }
                }
                echo '</tr>';
                foreach ($play as $key => $data) {
                    if ($key == 'defUseCoach' && $data == 'HC') {
                        echo '<h3>USE HEAD COACH - DEFENSE</h3>';
                    }
                    if (!in_array($key, array('TeamID','offUseCoach','defUseCoach','last_pc'))) {
                        if (strpos($key,'d') === 0) {
                            echo '<th>' . $key . '</th>'; 
                        }
                    }
                }
                echo '</tr>';
                foreach ($play as $key => $data) {
                    if (!in_array($key, array('TeamID','offUseCoach','defUseCoach','last_pc'))) {
                        if (strpos($key,'d') === 0) {
                            echo '<th>' . $data . '</th>'; 
                        }
                    }
                }
            }
        }
        echo '</tr></table><br>';
        echo '<h3>Depth Chart</h3>';
        echo '<table border=1><tr><th colspan=2>QB</th><th colspan=2>RB</th><th colspan=2>WR</th><th colspan=2>TE</th><th colspan=2>L</th><th colspan=2>C</th><th colspan=2>R</th></tr>';
        echo '<tr>';
        foreach ($posC1 as $pos1) {
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos1) {
                        echo '<th>' . $pos1 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC2 as $pos2) {
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos2) {
                        echo '<th>' . $pos2 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC3 as $pos3) {
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos3) {
                        echo '<th>' . $pos3 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        foreach ($posC4 as $pos4) {
            if ($pos4 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos4) {
                        echo '<th>' . $pos4 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC5 as $pos5) {
            if ($pos5 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos5) {
                        echo '<th>' . $pos5 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC6 as $pos6) {
            if ($pos6 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos6) {
                        echo '<th>' . $pos6 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        foreach ($posC7 as $pos7) {
            if ($pos7 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos7) {
                        echo '<th>' . $pos7 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC8 as $pos8) {
            if ($pos8 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos8) {
                        echo '<th>' . $pos8 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC9 as $pos9) {
            if ($pos9 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos9) {
                        echo '<th>' . $pos9 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        echo '<th colspan=2>DE</th><th colspan=2>DT</th><th colspan=2>LB</th><th colspan=2>CB</th><th colspan=2>NB</th><th colspan=2>FS</th><th colspan=2>SS</th></tr><tr>';
        foreach ($posC10 as $pos10) {
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos10) {
                        echo '<th>' . $pos10 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC11 as $pos11) {
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos11) {
                        echo '<th>' . $pos11 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC12 as $pos12) {
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos12) {
                        echo '<th>' . $pos12 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        foreach ($posC13 as $pos13) {
            if ($pos13 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos13) {
                        echo '<th>' . $pos13 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC14 as $pos14) {
            if ($pos14 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos14) {
                        echo '<th>' . $pos14 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC15 as $pos15) {
            if ($pos15 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos15) {
                        echo '<th>' . $pos15 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        foreach ($posC16 as $pos16) {
            if ($pos16 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos16) {
                        echo '<th>' . $pos16 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC17 as $pos17) {
            if ($pos17 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos17) {
                        echo '<th>' . $pos17 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC18 as $pos18) {
            if ($pos18 == '--') {
                echo '<td></td><td></td>';
            }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos18) {
                        echo '<th>' . $pos18 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        echo '<th colspan=2>K</th><th colspan=2>P</th><th colspan=2>KR1</th><th colspan=2>KR2</th><th colspan=2>PR2</th><th colspan=6>NOTES</th></tr><tr>';
        foreach ($posC19 as $pos19) {
        if ($pos19 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos19) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos19 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos19 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC20 as $pos20) {
        if ($pos20 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos20) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos20 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos20 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC21 as $pos21) {
        if ($pos21 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos21) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos21 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos21 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC22 as $pos22) {
        if ($pos22 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos22) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos22 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos22 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC23 as $pos23) {
        if ($pos23 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos23) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos23 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos23 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
            
        echo '</tr></table><br><br>';
    }

}

?>