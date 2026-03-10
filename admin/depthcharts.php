<?php 
include 'adminheader.php';
session_start();
require('../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '2') {
    echo 'You are not authorized to be here.';
} else {
    $charts = array();
    $dc = $connection->query('SELECT d.position,d.team,d.playerID,d.SimPos,d.SimAlt,d.Special,p.FirstName,p.LastName FROM `ptf_players_depth` d LEFT JOIN `ptf_players` p ON p.PlayerID = d.playerID');
    while($row = $dc->fetch_assoc()) {
        array_push($charts, $row);
    }

    $plays = array();
    $pc = $connection->query('SELECT c.*, e.Attitude, e.Style FROM `ptf_coaches` c JOIN `ptf_coaches_export` e on e.ID = c.CoachID  WHERE c.Job = "HeadCoach" or c.Job = "Head Coach"');
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
    $coachdata1 = array('FirstName','LastName','Experience','Reputation');
    $coachdata2 = array('Age','Salary','Years');
    $coachdata3 = array('Style','Attitude','Trait', 'Background');
    $coachtrain1 = array('Development','AttentionToDetail','Youngsters','PhysicalTraining');
    $coachtrain2 = array('TrainQB','TrainOffSkill','TrainOL','TrainDLine','TrainLBs','TrainDBs','TrainSTS');
    $coachdefaults = array('Scouting','AssessAbility','AssessPotential','CoachingOff','CoachingDef','Prep');
    $coachspers = array('Intelligence','Flexibility','Discipline','Motivating','Charisma','Leadership','Loyalty');
    $coachoff1 = array('OffScheme','Focus','RunPref','PassPref','PassTargetPref');
    $coachoff2 = array('QBPref','RBRole','FBRole','TERole','WRPref');
    $coachdef = array('DefScheme','KeyDefPos','DLUse','LBUse','CoverPref');
    $train1 = array('posdrills','athtrain','phystrain','gametape');
    $train2 = array('hcfocus','ocfocus','dcfocus');

    $posC1 = array('QB1','RB1','FB1','--','--');
    $posC2 = array('QB2','RB2','FB2','--','--');
    $posC3 = array('QB3','RB3','FB3','--','--');
    $posC4 = array('WR11','WR21','WR31','TE11','TE21');
    $posC5 = array('WR12','WR22','WR32','TE12','TE22');
    $posC6 = array('WR13','WR23','WR33','TE13','TE23');
    $posC7 = array('LT1','LG1','C1','RG1','RT1');
    $posC8 = array('LT2','LG2','C2','RG2','RT2');
    $posC9 = array('LT3','LG3','C3','RG3','RT3');
    $posC10 = array('DT11','DT21','RE1','LE1');
    $posC11 = array('DT12','DT22','RE2','LE2');
    $posC12 = array('DT13','DT23','RE3','LE3');
    $posC13 = array('MLB11','MLB21','ROLB1','LOLB1','SS1');
    $posC14 = array('MLB12','MLB22','ROLB2','LOLB2','SS2');
    $posC15 = array('MLB13','MLB23','ROLB3','LOLB3','SS3');
    $posC16 = array('CB11','CB21','NB11','NB21','FS1');
    $posC17 = array('CB12','CB22','NB12','NB22','FS2');
    $posC18 = array('CB13','CB23','NB13','NB23','FS3');

    $posC22 = array('K1','P1','--','NOTE1');
    $posC23 = array('K1','P2','--','NOTE2');
    $posC24 = array('K3','P3','--','NOTE3');
    $posC25 = array('KR11','KR21','PR1','NOTE4');
    $posC26 = array('KR11','KR22','PR2','NOTE5');
    $posC27 = array('KR13','KR23','PR3','--');

    echo '<h1>Current Strategy</h1>';

    echo '<a href="depthcharts.php?team=2">MIA</a> - '; 
    echo '<a href="depthcharts.php?team=8">SEA</a> - '; 
    echo '<a href="depthcharts.php?team=9">LON</a> - '; 
    echo '<a href="depthcharts.php?team=18">BAL</a> - '; 
    echo '<a href="depthcharts.php?team=20">NYT</a><br><br>'; 

    echo '<a href="depthcharts.php?team=1">KC</a> - '; 
    echo '<a href="depthcharts.php?team=4">OAK</a> - '; 
    echo '<a href="depthcharts.php?team=5">BUF</a> - '; 
    echo '<a href="depthcharts.php?team=7">CIN</a> - '; 
    echo '<a href="depthcharts.php?team=19">CLE</a><br><br>'; 

    echo '<a href="depthcharts.php?team=6">NYG</a> - '; 
    echo '<a href="depthcharts.php?team=10">IND</a> - '; 
    echo '<a href="depthcharts.php?team=14">ATL</a> - '; 
    echo '<a href="depthcharts.php?team=16">TB</a> - '; 
    echo '<a href="depthcharts.php?team=17">CHC</a><br><br>'; 

    echo '<a href="depthcharts.php?team=3">GB</a> - '; 
    echo '<a href="depthcharts.php?team=11">CHI</a> - '; 
    echo '<a href="depthcharts.php?team=12">DET</a> - '; 
    echo '<a href="depthcharts.php?team=13">MIN</a> - '; 
    echo '<a href="depthcharts.php?team=15">SF</a>'; 

    // -------------------------------- AFC ---------------------------------------------------- //

    $x = $_GET['team'];
        echo '<h2>' . idToAbbrev($x) . '</h2>';
        echo '<h3>Game Plan</h3>';
        echo '<table border=1><tr>';
        foreach ($strats as $strat) {
            if ($strat['TeamID'] == $x) {
                foreach ($strat as $key => $data) {
                    if ($key == 'defaulto' && $data != 'Manual') {
                        echo '<h1>USE DEFAULT STRATEGY</h1>';
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
        echo '<table><tr><td>';

        foreach ($plays as $play) {
            if ($play['TeamID'] == $x) {
                echo '<table border=1><tr>';
                foreach ($coachdata1 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachdata2 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachdata3 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachoff as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($train1 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($train2 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }


                echo '</table></td></tr></table><br><br>';
            }
        }

        echo '<table><tr><td>';

        foreach ($plays as $play) {
            if ($play['TeamID'] == $x) {
                echo '<table border=1><tr>';
                foreach ($coachtrain1 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachtrain2 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachdefaults as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachspers as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }


                echo '</table></td></tr></table><br><br>';
            }
        }

        echo '<table><tr><td>';

        foreach ($plays as $play) {
            if ($play['TeamID'] == $x) {
                echo '<table border=1><tr>';
                foreach ($coachoff1 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachoff2 as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }

                echo '</table></td><td>';
                echo '<table border=1><tr>';

                foreach ($coachdef as $cd) {
                    foreach ($play as $key => $data) {
                        if ($key == $cd) {
                            echo '<th>' . $key . '</th>'; 
                            echo '<td>' . $data . '</td>'; 
                        }
                    echo '</tr>';
                    }
                }


                echo '</table></td></tr></table><br><br>';
            }
        }

        
        

        echo '<h3>Depth Chart</h3>';
        echo '<table border=1><tr><th colspan=12>OFFENSE</th></tr>';
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
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr><th colspan=12>DEFENSE</th></tr>';
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


        echo '</tr><tr><th height=10 colspan=14></th></tr><tr><th colspan=12>Special Teams</th></tr>';
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
        echo '</tr><tr>';
        foreach ($posC24 as $pos24) {
        if ($pos24 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos24) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos24 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos24 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr><th height=10 colspan=14></th></tr><tr>';
        foreach ($posC25 as $pos25) {
        if ($pos25 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos25) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos25 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos25 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC26 as $pos26) {
        if ($pos26 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos26) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos26 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos26 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
        echo '</tr><tr>';
        foreach ($posC27 as $pos27) {
        if ($pos27 == '--') {
            echo '<td></td><td></td>';
        }
            foreach ($charts as $chart) {
                if ($chart['team'] == $x) {
                    if ($chart['position'] == $pos27) {
                        if (strpos($chart['position'],'NOTE') === 0) {
                            echo '<th>' . $pos27 . '</th><td colspan=3>' . $chart['Special'] . '</td>';
                        } else {
                            echo '<th>' . $pos27 . '</th><td>' . $chart['FirstName'] . ' ' . $chart['LastName'] . '</td>';
                        }
                    }
                }
            }
        }
            
        echo '</tr></table><br><br>';
    }


?>