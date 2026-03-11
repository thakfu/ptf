<?php 
include 'adminheader.php';
session_start();
require('../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '0') {
    $dashboard = array();
    $stmt = $connection->query('SELECT * FROM `ptf_league`');
    while($row = $stmt->fetch_assoc()) {
        array_push($dashboard,$row);
    }

    $users = array();
    $stmt2 = $connection->query('SELECT * FROM `ptf_users` ORDER BY `TeamID` ASC');
    while($row = $stmt2->fetch_assoc()) {
        array_push($users,$row);
    }

    //var_dump($_POST);

    //MASTER TABLE//
    echo '<table><tr><td>';

    //SWITCHES//
    echo '<table border="1">';

    //---------------------------------------- DRAFT TOGGLE ----------------------------------------//
    if(isset($_POST['draftToggle'])) {
        if($_POST['draftValue'] == 1) {
            $draftVal = 0;
        } else {
            $draftVal = 1;
        }

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $draftVal . ' WHERE `id` = 8');
        echo 'Draft is toggled to ' . ($draftVal == 1 ? 'On' : 'Off');
    } else {
        $draftVal = $dashboard[8]['value'];
    }
    echo '<tr align="center"><th> DRAFT</th><th> ' . ($draftVal == 1 ? 'On' : 'Off') . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="hidden" name="draftValue" value="' . $draftVal . '">
            <input type="submit" name="draftToggle" value="DRAFT ON/OFF">
        </form></td></tr>';

    //---------------------------------------- WAIVER TOGGLE ----------------------------------------//
    if(isset($_POST['waiverToggle'])) {
        if($_POST['waiverValue'] == 1) {
            $waiverVal = 0;
        } else {
            $waiverVal = 1;
        }

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $waiverVal . ' WHERE `id` = 15');
        echo 'Waivers is toggled to ' . ($waiverVal == 1 ? 'On' : 'Off');
    } else {
        $waiverVal = $dashboard[15]['value'];
    }
    echo '<tr align="center"><th> WAIVERS</th><th> ' . ($waiverVal == 1 ? 'On' : 'Off') . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="hidden" name="waiverValue" value="' . $waiverVal . '">
            <input type="submit" name="waiverToggle" value="WAIVERS ON/OFF">
        </form></td></tr>';

    //---------------------------------------- FREE AGENCY TOGGLE ----------------------------------------//
    if(isset($_POST['faToggle'])) {
        if($_POST['faValue'] == 1) {
            $faVal = 0;
        } else {
            $faVal = 1;
        }

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $faVal . ' WHERE `id` = 7');
        echo 'Free Agency is toggled to ' . ($faVal == 1 ? 'On' : 'Off');
    } else {
        $faVal = $dashboard[7]['value'];
    }
    echo '<tr align="center"><th> FREE AGENCY</th><th> ' . ($faVal == 1 ? 'On' : 'Off') . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="hidden" name="faValue" value="' . $faVal . '">
            <input type="submit" name="faToggle" value="FREE AGENCY ON/OFF">
        </form></td></tr>';

    //---------------------------------------- EXTENSIONS TOGGLE ----------------------------------------//
    if(isset($_POST['extensionToggle'])) {
        if($_POST['extensionValue'] == 1) {
            $extensionVal = 0;
        } else {
            $extensionVal = 1;
        }

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $extensionVal . ' WHERE `id` = 16');
        echo 'Extensions is toggled to ' . ($extensionVal == 1 ? 'On' : 'Off');
    } else {
        $extensionVal = $dashboard[16]['value'];
    }
    echo '<tr align="center"><th> EXTENSIONS</th><th> ' . ($extensionVal == 1 ? 'On' : 'Off') . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="hidden" name="extensionValue" value="' . $extensionVal . '">
            <input type="submit" name="extensionToggle" value="EXTENSIONS ON/OFF">
        </form></td></tr>';

    //---------------------------------------- POSITION CHANGE TOGGLE ----------------------------------------//
    if(isset($_POST['changeToggle'])) {
        if($_POST['changeValue'] == 1) {
            $changeVal = 0;
        } else {
            $changeVal = 1;
        }

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $changeVal . ' WHERE `id` = 0');
        echo 'Position Change is toggled to ' . ($changeVal == 1 ? 'On' : 'Off');
    } else {
        $changeVal = $dashboard[0]['value'];
    }
    echo '<tr align="center"><th> POSITION CHANGES</th><th> ' . ($changeVal == 1 ? 'On' : 'Off') . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="hidden" name="changeValue" value="' . $changeVal . '">
            <input type="submit" name="changeToggle" value="POS CHANGES ON/OFF">
        </form></td></tr>';

//---------------------------------------- FRANCHISE TAG TOGGLE ----------------------------------------//
    if(isset($_POST['tagToggle'])) {
        if($_POST['tagValue'] == 1) {
            $tagVal = 0;
        } else {
            $tagVal = 1;
        }

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $tagVal . ' WHERE `id` = 25');
        echo 'Franchise Tags is toggled to ' . ($tagVal == 1 ? 'On' : 'Off');
    } else {
        $tagVal = $dashboard[0]['value'];
    }
    echo '<tr align="center"><th> FRANCHISE TAGS</th><th> ' . ($tagVal == 1 ? 'On' : 'Off') . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="hidden" name="tagValue" value="' . $tagVal . '">
            <input type="submit" name="tagToggle" value="TAGS ON/OFF">
        </form></td></tr>';

    //echo '<li> PRO BOWL VOTING: ';
    //echo '<li> AWARDS VOTING: ';
    //---------------------------------------- YEAR CHANGE ----------------------------------------//
    if(isset($_POST['yearChange'])) {
        $yearVal = $_POST['yearValue'];

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $yearVal . ' WHERE `id` = 5');
        echo 'Year changed to ' . $yearVal;
    } else {
        $yearVal = $dashboard[5]['value'];
    }
    echo '<tr align="center"><th> YEAR</th><th> ' . $yearVal . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="text" name="yearValue" value="' . $yearVal . '"><br>
            <input type="submit" name="yearChange" value="CHANGE YEAR">
        </form></td></tr>';

    //---------------------------------------- TRANSACTION PERIOD ----------------------------------------//
    /*if(isset($_POST['transChange'])) {
        $transVal = $_POST['transValue'];

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $transVal . ' WHERE `id` = 21');
        echo 'Week changed to ' . $weekVal;
        $stmt2 = $connection->query('UPDATE `ptf_timeline` SET `Active` = 1 WHERE `Event` = "Week ' . $weekVal . '"');
    } else {
        $transVal = $dashboard[21]['value'];
    }
    echo '<tr align="center"><th> WEEK</th><th> ' . $transVal . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="text" name="weekValue" value="' . $transVal . '"><br>
            <input type="submit" name="weekChange" value="CHANGE WEEK">
        </form></td></tr>';
        */
    //---------------------------------------- WEEK CHANGE ----------------------------------------//
    if(isset($_POST['weekChange'])) {
        $weekVal = $_POST['weekValue'];

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $weekVal . ' WHERE `id` = 14');
        echo 'Week changed to ' . $weekVal;
        $stmt2 = $connection->query('UPDATE `ptf_timeline` SET `Active` = 1 WHERE `Event` = "Week ' . $weekVal . '"');
    } else {
        $weekVal = $dashboard[14]['value'];
    }
    echo '<tr align="center"><th> WEEK</th><th> ' . $weekVal . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="text" name="weekValue" value="' . $weekVal . '"><br>
            <input type="submit" name="weekChange" value="CHANGE WEEK">
        </form></td></tr>';

    //---------------------------------------- SIM DATE CHANGE ----------------------------------------//
    if(isset($_POST['simChangeWeek'])) {
        $simVal = date("Y-m-d H:i:s", strtotime($dashboard[19]['value']. ' + 7 Days'));

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = "' . $simVal . '" WHERE `id` = 19');
        echo 'Fictional Sim Date changed to ' . $simVal;
    } else {
        if(isset($_POST['simChange'])) {
            $time = strtotime($_POST['simValue']);
            $simVal = date("Y-m-d H:i:s", $time);

            $stmt = $connection->query('UPDATE `ptf_league` SET `value` = "' . $simVal . '" WHERE `id` = 19');
            echo 'Fictional Sim Date changed to ' . $simVal;
        } else {
            $simVal = $dashboard[19]['value'];
        }
    }

    echo '<tr align="center"><th> SIM DATE</th><th> ' . date("m-d-Y", strtotime($simVal)) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="date" name="simValue" value="' . $simVal . '"><br>
            <input type="submit" name="simChange" value="CHANGE SIM DATE"><br><br>
            <input type="submit" name="simChangeWeek" value="ADD 7 DAYS">
        </form></td></tr>';

    //---------------------------------------- LAST SIM CHANGE ----------------------------------------//
    if(isset($_POST['lastChange'])) {
        $time = strtotime($dashboard[12]['value']);
        $lastVal = date("Y-m-d H:i:s", $time);

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = "' . $lastVal . '" WHERE `id` = 11');
        echo 'Last Sim changed to ' . $lastVal;
    } else {
        $lastVal = $dashboard[11]['value'];
    }

    echo '<tr align="center"><th> LAST SIM</th><th> ' . date("m-d-Y", strtotime($lastVal)) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="submit" name="lastChange" value="NEXT SIM TO LAST SIM">
        </form></td></tr>';

    //---------------------------------------- NEXT SIM CHANGE ----------------------------------------//
    if(isset($_POST['nextChange'])) {
        $time = strtotime($_POST['nextValue']);
        $nextVal = date("Y-m-d H:i:s", $time);

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = "' . $nextVal . '" WHERE `id` = 12');
        echo 'Next Sim changed to ' . $nextVal;
    } else {
        $nextVal = $dashboard[12]['value'];
    }

    echo '<tr align="center"><th> NEXT SIM</th><th> ' . date("m-d-Y", strtotime($nextVal)) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="date" name="nextValue" value="' . $nextVal . '"><br>
            <input type="submit" name="nextChange" value="CHANGE NEXT SIM">
        </form></td></tr>';

    //---------------------------------------- SEASON START CHANGE ----------------------------------------//
    if(isset($_POST['startChange'])) {
        $time = strtotime($_POST['startValue']);
        $startVal = date("Y-m-d H:i:s", $time);

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = "' . $startVal . '" WHERE `id` = 13');
        echo 'Season Start changed to ' . $startVal;
    } else {
        $startVal = $dashboard[13]['value'];
    }

    echo '<tr align="center"><th> START DATE</th><th> ' . date("m-d-Y", strtotime($startVal)) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="date" name="startValue" value="' . $startVal . '"><br>
            <input type="submit" name="startChange" value="CHANGE START DATE">
        </form></td></tr>';

    //---------------------------------------- DRAFT START ID CHANGE ----------------------------------------//
    if(isset($_POST['dsChange'])) {
        $dsVal = $_POST['dsValue'];

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $dsVal . ' WHERE `id` = 2');
        echo 'Draft Class Start ID changed to ' . number_format($capVal);
    } else {
        $dsVal = $dashboard[2]['value'];
    }
    echo '<tr align="center"><th> DRAFT START ID</th><th> ' . number_format($dsVal) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="text" name="dsValue" value="' . $dsVal . '"><br>
            <input type="submit" name="dsChange" value="CHANGE DRAFT START">
        </form></td></tr>';

    //---------------------------------------- SALARY CAP CHANGE ----------------------------------------//
    if(isset($_POST['capChange'])) {
        $capVal = $_POST['capValue'];

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $capVal . ' WHERE `id` = 6');
        echo 'Salary Cap changed to ' . number_format($capVal);
    } else {
        $capVal = $dashboard[6]['value'];
    }
    echo '<tr align="center"><th> SALARY CAP</th><th> ' . number_format($capVal) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="text" name="capValue" value="' . $capVal . '"><br>
            <input type="submit" name="capChange" value="CHANGE CAP">
        </form></td></tr>';

    //---------------------------------------- FA DAY CHANGE ----------------------------------------//
    if(isset($_POST['fadayChange'])) {
        $fadayVal = $_POST['fadayValue'];

        $stmt = $connection->query('UPDATE `ptf_league` SET `value` = ' . $fadayVal . ' WHERE `id` = 20');
        echo 'Free Agency Day changed to ' . number_format($fadayVal);
    } else {
        $fadayVal = $dashboard[20]['value'];
    }
    echo '<tr align="center"><th> FREE AGENCY DAY</th><th> ' . number_format($fadayVal) . '</th><td>';
    echo '<form method="POST" action="dashboard.php"><br>
            <input type="text" name="fadayValue" value="' . $fadayVal . '"><br>
            <input type="submit" name="fadayChange" value="CHANGE FA DAY">
        </form></td></tr>';

    echo '<br><br></table>';

    //---------------------------------------- CALENDAR ----------------------------------------//
    $startDate = date_create($startVal);  // RESET HERE TO ACTUAL DATE!!!!!!
    $format = "D, m-d-Y";

    $here = "YOU ARE HERE ==> ";
    echo '</td><td>';
    echo 'We will list all the seasonal breakpoints here and maybe have some hotlinks to mass change settings!<br>
    <ol>
        <li>Post Super Bowl
        <ul>
            <li>TRADES: ON
            <li>WAIVERS:  OFF
            <li>EXTENSIONS:  OFF
            <li>FRANCHISE TAGS: ON
            <li>COACH FREE EDIT: ON
            <li>Run HighestPaid.php Link here
        </ul>
        <li>IR and Squad Cleared
        <ul>
            <li>Query emptying PS
            <li>Query emptying IR
        </ul>
        <li>Bowls Announced
        <ul>
            <li>Link to Game Editor
        </ul>
        <li>Sim Flips to Offseason
        <ul>
            <li>Import Master Roster File To Sim
            <li>Schedule - Refer to Excel Sheet
            <li>YEAR +1
        </ul>
        <li>Realignment/Relocation
        <ul>
            <li>Link to Team Editor
            <li>IF EXPANSION... DO HERE (protection lists, draft)
        </ul>
        <li>Draft Order Finalized
        <ul>
            <li>Sim ahead to get correct order OR do custom tiebreakers
            <li>Draft Pick Order updated in DB
            <li>Draft Editor?
        </ul>
        <li>POST SEASON "SIM"
        <ul>
            <li>Announce Bowl Games
            <li>Announce Retirements
            <li>Announce Franchise Tag Deadline
        </ul>
        <li>Sim Flips to New Season
        <ul>
            <li>FRANCHISE TAGS: OFF
            <li>Reset Tags for next season
            <li>Reset Extensions for next season
        </ul>
        <li>Staff Changes - SKIP THROUGH
        <li>Pre-Draft FA - SKIP THROUGH
        <li>Open Draft
        <ul>
            <li>Draft Pick Order updated in sim
            <li>Follow in sim IF ready
            <li>Rookie Contract Uploads
        </ul>
        <li>Free Agency Open
        <ul>
            <li>Process Demands
            <li>DOUBLE CHECK DEMANDS
            <li>FREE AGENCY: ON
        </ul>
        <li>Free Agency Close
        <ul>
            <li>Clear FA Table for Next Season
            <li>Announce Deadline for Position Changes
            <li>Announce Deadline for Coach Settings
            <li>Process Waiver Order
            <li>WAIVERS: ON
        </ul>
        <li>Training Camp
        <ul>
            <li>POSITION CHANGES: OFF
            <li>COACH FREE EDIT: OFF
            <li>Announce Camp results
            <li>Final Retirements
        </ul>
        <li>Preseason Prep
        <ul>
            <li>Announce 53 Man Minimum Deadline
            <li>Announce Practice Squads Reopen
        </ul>';
        //<li>' . $herepoint . '<b>' . date_format($startDate, $format) . ':</b> BEGIN NEW SEASON!
    date_format($startDate, $format);
    date_add($startDate,date_interval_create_from_date_string("7 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Unsaved Preseason (6 Weeks)
            <ul>
                <li>Injuries OFF (in Sim)
                <li>DO NOT SAVE
            </ul>';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Preseason (3 Weeks)
            <ul>
                <li>Injuries ON (in Sim)
            </ul>';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Rosters to 53 + 5 + IR';
    date_add($startDate,date_interval_create_from_date_string("1 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Bowl Games (Week 1)
            <ul>
                <li>Contract Extensions <span style="color:green"><b>ON</b></span>
            </ul>';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 2';
    date_add($startDate,date_interval_create_from_date_string("4 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 3';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 4';
    date_add($startDate,date_interval_create_from_date_string("4 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 5';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 6';
    date_add($startDate,date_interval_create_from_date_string("4 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 7';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 8';
    date_add($startDate,date_interval_create_from_date_string("4 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 9';
    date_add($startDate,date_interval_create_from_date_string("3 days"));
    echo   '<li><b>' . date_format($startDate, $format) . ':</b> Week 10';
    echo '<li>Trade Deadline
                * BEFORE WEEK 11 IS SIMMED!
            <li>Pro Bowl Voting
                * OPENS AFTER WEEK 11 IS SIMMED!
            <li>Week 11
            <li>Following Season Draft Class Announced
            <li>Week 12
            <li>Week 13
            <li>Week 14
            <li>Week 15
            <li>Pro Bowl Announcement
            <ul>
                <li>We need an automated vote counting system
                <li>Ranked voting as well
            </ul>
            <li>Position Changes Lock
            <ul>
                <li>Turn Position Changes OFF prior to Week 16
            </ul>
            <li>Week 16
                * Begin Postseason Prep Period
                * 7 Days until playoff sim MINIMUM
            <li>Free Signing Close
            <ul>
                <li>Turn Waivers OFF
            </ul>
            <li>Contract Extensions Close
            <ul>
                <li>Turn Extensions OFF
            </ul>
            <li>Awards Voting
            <ul>
                <li>OPENS BEFORE PLAYOFFS ARE SIMMED!
            </ul>
            <li>Playoffs
            <ul>
                <li>Be sure to update schedule after each round
                <li>Allow GMs time to prep
            </ul>
            <li>Awards Announced
                * 2-3 Days after Playoffs End
            <li>Pro Bowl
                * Same Time as Awards
        </ul>
    </ol>';

    //END MASTER TABLE//
    echo '</td></tr></table>';
    echo '<br><br>';

    //---------------------------------------- USERS TABLE ----------------------------------------//

    $timeFormat = "m/d/Y-H:i - l, F j - g:iA";
    echo '<table class="sortable" border="1"><tr><th>Name</th><th>TeamID</th><th>Team</th><th>Last Seen</th><th>Roster Valid</th><th>Strategy</th><th>Depth Chart</th><th>Coach Change</th><th>PB Vote</th><th>Award Vote</th><th>Exten. Used</th><th>Franchise Tag</th><th>FA Bids</th></tr>';

    foreach ($users as $user) {
        if ($user['online'] == 1) {
            $online = '<br><span style="color:green"><b>ONLINE NOW</b></span>';
        } else {
            $online = '';
        }
        if ($user['TeamID'] != 0) {
            $rowcount = 0;
            $squadcount = 0;
            $ircount = 0;
            $playerService = playerService($user['TeamID'],0,2);
            foreach ($playerService as $notSquad) {
                if ($notSquad['SquadTeam'] == NULL && $notSquad['irteam'] == NULL) {
                    $rowcount++; 
                } elseif ($notSquad['SquadTeam'] != NULL) {
                    $squadcount++; 
                } elseif ($notSquad['irteam'] != NULL) {
                    $ircount++; 
                }
            }
            if ($rowcount != 53) {
                $color = 'red';
                if ($rowcount < 53) {
                    $alert = 'LOW';
                } else {
                    $alert = 'HI';
                }
            } else {
                $color = 'green';
                $alert = '';
            }


            echo '<tr><th>' . $user['first_name']  . ' ' . $user['last_name'] . ' - (' . $user['username'] . ') ' . $online .'</th><th>' . $user['TeamID']  . '</th><th>' . idToAbbrev($user['TeamID'])  . '</th><td>' . date($timeFormat,strtotime($user['last_seen'])) . '</td><td><b>'. $rowcount . '</b><span style="color: ' . $color . '"> ' . $alert . ' </span>(ROS) :: <b>' . $squadcount . '</b> (PS) :: <b>' . $ircount . '</b> (IR) :: <b>' . $rowcount + $squadcount + $ircount . '</b> TOTAL</td><td>Week ' . $user['Last_Strat'] . '</td><td>Week ' . $user['Last_DC'] . '</td><td>Week ' . $user['coach_update'] . '</td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
    }

    echo '</table><br><br>';
} else {
    echo 'Sup?';
}

?>

