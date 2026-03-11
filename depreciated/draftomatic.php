<?php
include 'header.php';

$stmt3= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.team = t.TeamID WHERE d.year = "1985" ORDER BY d.round ASC, d.pick ASC');
$picksmade = array();
while($row = $stmt3->fetch_assoc()) {
    array_push($picksmade, $row);
}
$picked = array();
foreach($picksmade as $pick) {
    if ($pick['playerID'] != 0) {
        array_push($picked, $pick['playerID']);
    }

    if($pick['current'] == 1) {
        $up = $pick['City'] . ' ' . $pick['Mascot'];
        $uppick = $pick['pick'];
        $upround = $pick['round'];

        $datetime1 = new DateTime();
        $datetime2 = new DateTime($pick['time']);
        $datetime2->add(new DateInterval('PT6H'));
        $interval = $datetime2->diff($datetime1);

        if($timer == 0) {
            $pause = 'Draft Timer is paused!';
            $elapsed = '';
        } else {
            $pause = '';
            $elapsed = $interval->format('%h hours %i minutes %s seconds');
        }
    }
}

$draftPlayers = playerService(0, 0, 0);
//usort($draftPlayers, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
array_multisort(array_column($draftPlayers, 'Overall'), SORT_DESC,
                array_column($draftPlayers, 'FirstName'),      SORT_ASC,
                $draftPlayers);
$draftlist = array('NONE','Best Available');
foreach ($draftPlayers as $player) {
    if(!in_array($player['PlayerID'],$picked)) {
        $playertitle = $player['FullName'] . ' ' . $player['Position'] . ' (' . $player['Overall'] . ')';
        array_push($draftlist, $playertitle);
    }
}
array_unshift($positions,'ANY');



echo '<h1>The War Room</h2>';

if(isset($_POST['submit'])){
    echo '<h2>' . $_POST['Team']  . ' List Submitted for Rounds 31-50!</h2><ol>'; 
    for ($x = 31; $x <= 50; $x++) {
        $y = $x - 30;
        ${"choice$y"} = $_POST['pos'.$x];
       /* if ($_POST['player'.$x] == 'Best Available') {
            ${"choice$x"} = $_POST['player'.$x] . ' - ' . $_POST['pos'.$x] . ' under age ' . $_POST['age'.$x];
        } else {
            ${"choice$x"} = $_POST['player'.$x];
        } */
        echo '<li>'. ${"choice$y"};
    }
    echo '</ol>';
    $roster = $connection->query("INSERT INTO ptf_draft_list (TeamID, Choice1, Choice2, Choice3, Choice4, Choice5, Choice6, Choice7, Choice8, Choice9, Choice10, Choice11, Choice12, Choice13, Choice14, Choice15, Choice16, Choice17, Choice18, Choice19, Choice20, Round, LastRound, Time ) 
    VALUES ({$_POST['TeamID']},'{$choice1}','{$choice2}','{$choice3}','{$choice4}','{$choice5}','{$choice6}','{$choice7}','{$choice8}','{$choice9}','{$choice10}','{$choice11}','{$choice12}','{$choice13}','{$choice14}','{$choice15}','{$choice16}','{$choice17}','{$choice18}','{$choice19}','{$choice20}',31,50,NOW())");
}

echo '<h2>Dispersal Draft</h2>';
echo '<p align="center">Rounds 31-50 will be conducted via BPA lists.</p>';
//echo '<p align="center">Note: Draft picks will not be automatically made by the website.  A league official must still manually make the draft selection when a draft list is present!</p>';
//echo '<p align="center">Note: If you select a specific player you DO NOT need to choose position and age!</p>';

echo '<p align="center"><b>The ' . $up . ' are on the clock with Pick ' . $uppick  . ' of Round ' . $upround . '.</p></b>';
/*
if ($timer == 0) {
    echo '<p align="center">' . $pause . '</p>';
} else {
    echo '<p align="center"> They have ' . $elapsed . ' left to pick!</p>';
}
echo '<div style="margin-left:30%" align="center"><p align="left"><b>Clock Starts - 09:00:00 -- Clock Ends - 22:00:00</b></p>';
echo '<p align="left">Eastern Time Zone:<b>' . date("Y-m-d H:i:s",strtotime('1 hour 0 minutes 0 seconds', time())) . '</b><i> - ATLANTA, CHICAGO, CINCINNATI, MIAMI, CLEMSON</i></p>';
echo '<p align="left">Central Time Zone:<b>' . date("Y-m-d H:i:s",strtotime('0 hour 0 minutes 0 seconds', time())). '</b><i> - KANSAS CITY, MINNESOTA, PHILADELPHIA, PROVIDENCE, WINNIPEG, HOUSTON</i></p>';
echo '<p align="left">Pacific Time Zone:<b>' . date("Y-m-d H:i:s",strtotime('-2 hour 0 minutes 0 seconds', time())). '</b><i> - OAKLAND, SAN FRANCISCO, DETROIT, WASHINGTON</i></p>';
echo '<p align="left">England Time Zone:<b>' . date("Y-m-d H:i:s",strtotime('6 hour 0 minutes 0 seconds', time())). '</b><i> - LONDON, GREEN BAY</i></p>';
echo '<p align="left">Japan Time Zone:<b>' . date("Y-m-d H:i:s",strtotime('14 hour 0 minutes 0 seconds', time())). '</b><i> - BUFFALO</i></p></div>';
*/


echo '<form action="draftomatic.php" method="POST"><div align="center">';
echo '<input type="hidden" id="Team" name="Team" value="' . $_SESSION['city'] . '">';

if ($_SESSION['admin'] == 2) {
    echo '<label for="TeamID">Team: </label>';
    echo '<select name="TeamID" id="TeamID">';
    for($x = 1;$x < 19;$x++) {
        echo '<option value="' . $x . '">' . idToAbbrev($x) . '</option>';
    }
    echo '</select><br><br>';
} else {
    echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
}

/*echo '<label for="round">Start Round: </label>';
echo '<select name="round" id="round">';
for ($x = 1; $x <= 30; $x++) {
    echo '<option value="' . $x . '">' . $x . '</option>';
}
echo '</select><br><br>';

echo '<label for="round">End Round: </label>';
echo '<select name="lastround" id="lastround">';
for ($x = 1; $x <= 30; $x++) {
    echo '<option value="' . $x . '">' . $x . '</option>';
}
echo '</select><br><br>';*/
for ($x = 31; $x <= 50; $x++) {
    echo 'Round ' . $x . ': ';
    /*echo '<select name="player'.$x.'" id="player'.$x.'">';
    foreach ($draftlist as $list) {
        echo '<option value="' . $list . '">' . $list . '</option></select>';
    }*/
    echo 'Position: ';
    echo '<select name="pos'.$x.'" id="pos'.$x.'">';
    foreach ($positions as $pos) {
        echo '<option value="' . $pos . '">' . $pos . '</option>';
    }
    /*echo '</select> Max Age: ';
    echo '<select name="age'.$x.'" id="age'.$x.'">';
    for ($y = 40; $y >= 20; $y--) {
        echo '<option value="' . $y . '">' . $y . '</option>';
    }
    */echo '</select><br>';
    echo '<br>';
}
echo '<input type="submit" name="submit" value="Submit List"><br><br>';

echo '<h2>Your Latest List</h2>';
$roster = $connection->query("SELECT * FROM ptf_draft_list WHERE TeamID = " . $_SESSION['TeamID'] . " ORDER BY TIME DESC");
$list = $roster->fetch_assoc();
echo '<ul align="left">';
echo '<li align="left">' . $list['Choice1'];
echo '<li align="left">' . $list['Choice2'];
echo '<li align="left">' . $list['Choice3'];
echo '<li align="left">' . $list['Choice4'];
echo '<li align="left">' . $list['Choice5'];
echo '<li align="left">' . $list['Choice6'];
echo '<li align="left">' . $list['Choice7'];
echo '<li align="left">' . $list['Choice8'];
echo '<li align="left">' . $list['Choice9'];
echo '<li align="left">' . $list['Choice10'];
echo '<li align="left">' . $list['Choice11'];
echo '<li align="left">' . $list['Choice12'];
echo '<li align="left">' . $list['Choice13'];
echo '<li align="left">' . $list['Choice14'];
echo '<li align="left">' . $list['Choice15'];
echo '<li align="left">' . $list['Choice16'];
echo '<li align="left">' . $list['Choice17'];
echo '<li align="left">' . $list['Choice18'];
echo '<li align="left">' . $list['Choice19'];
echo '<li align="left">' . $list['Choice20'];

echo '</ul><br>';
?>