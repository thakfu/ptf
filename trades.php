<?php

include 'header.php';

echo "<h1>" . $_SESSION['mascot'] . " Trade Center</h1>";

$stmt4 = $connection->query("SELECT * FROM ptf_trade_offers WHERE recTID = ". $_SESSION['TeamID']);
$count = 0;
$offers = array();
while($row = $stmt4->fetch_assoc()) {
    $count++;
    array_push($offers, $row);
}
echo '<h4>You have '. $count .' trade offers!</h4><br>';

foreach ($offers as $offer) {
    $teamService = teamService($offer['sentTID']);
    $team = $teamService[0];
    echo 'The ' . $team['FullName'] . ' have sent you a trade offer!  <a href="trade_approval.php">You can view it here and make a decision!</a><br><br>';
}

$stmt5 = $connection->query("SELECT * FROM ptf_trade_offers WHERE sentTID = ". $_SESSION['TeamID']);
$count2 = 0;
$offers2 = array();
while($row = $stmt5->fetch_assoc()) {
    $count2++;
    array_push($offers2, $row);
}
echo '<h4>You have '. $count2 .' pending trade offers!</h4><br>';

foreach ($offers2 as $offer) {
    $teamService = teamService($offer['recTID']);
    $team = $teamService[0];
    echo 'You have sent a trade offer to the ' . $team['FullName'] . ', and they have yet to make a decision.  <a href="trade_approval.php?cancel=true&id=' . $offer['offerID'] . '">You can view or cancel it here!</a><br><br>';
}

echo "<h2>Who are you trading with?</h2>";

echo '<table><tr>
    <td '; if($_GET['trade'] == 14) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=14"><img src="/ptf/images/ATL_115.png"></a></td>
    <td '; if($_GET['trade'] == 18) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=18"><img src="/ptf/images/BAL_115.png"></a></td>
    <td '; if($_GET['trade'] == 5) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=5"><img src="/ptf/images/BUF_115.png"></a></td>
    <td '; if($_GET['trade'] == 11) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=11"><img src="/ptf/images/CHI_115.png"></a></td>
    <td '; if($_GET['trade'] == 7) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=7"><img src="/ptf/images/CIN_115.png"></a></td></tr>';
echo '<tr>
    <td '; if($_GET['trade'] == 12) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=12"><img src="/ptf/images/DET_115.png"></a></td>
    <td '; if($_GET['trade'] == 3) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=3"><img src="/ptf/images/GB_115.png"></a></td>
    <td '; if($_GET['trade'] == 10) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=10"><img src="/ptf/images/IND_115.png"></a></td>
    <td '; if($_GET['trade'] == 1) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=1"><img src="/ptf/images/KC_115.png"></a></td>
    <td '; if($_GET['trade'] == 9) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=9"><img src="/ptf/images/LON_115.png"></a></td></tr>';
echo '<tr>
    <td '; if($_GET['trade'] == 2) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=2"><img src="/ptf/images/MIA_115.png"></a></td>
    <td '; if($_GET['trade'] == 13) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=13"><img src="/ptf/images/MIN_115.png"></a></td>
    <td '; if($_GET['trade'] == 20) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=20"><img src="/ptf/images/NYJ_115.png"></a></td>
    <td '; if($_GET['trade'] == 4) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=4"><img src="/ptf/images/OAK_115.png"></a></td>
    <td '; if($_GET['trade'] == 8) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=8"><img src="/ptf/images/PRO_115.png"></a></td></tr>';
echo '<tr>
    <td '; if($_GET['trade'] == 15) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=15"><img src="/ptf/images/SF_115.png"></a></td>
    <td '; if($_GET['trade'] == 19) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=19"><img src="/ptf/images/SEA_115.png"></a></td>
    <td '; if($_GET['trade'] == 16) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=16"><img src="/ptf/images/TB_115.png"></a></td>
    <td '; if($_GET['trade'] == 17) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=17"><img src="/ptf/images/WAS_115.png"></a></td>
    <td '; if($_GET['trade'] == 6) { echo 'style="background-color:red"'; } else { echo 'style="background-color:white"';}
    echo '><a href="trades.php?trade=6"><img src="/ptf/images/NYG_115.png"></a></td></tr>';
echo '</table>';

if (isset($_GET['trade'])) {
    if ($_GET['trade'] == $_SESSION['TeamID']) {
        echo "Pick Again... you aren't allowed to trade with yourself.  Weirdo.";
    } else {
        $count = 0;
        $teamService = teamService($_GET['trade']);
        $team = $teamService[0];
        echo '<form action="submit_trade.php" method="POST">';
        echo '<table><tr><th><h4>'  . $_SESSION['mascot'] . '</h4></th><th><h4>' . $team['Mascot'] . '</h4></th></tr>';
        $stmt1 = $connection->query('SELECT 
            sum(s.' . $year . ') as pyear FROM ptf_players y LEFT JOIN ptf_players_salaries s ON y.PlayerID = s.PlayerID  
            WHERE y.TeamID = "' . $team['TeamID'] . '"');
        $sum = $stmt1->fetch_assoc();
        echo '<tr><th>$'  . number_format($total) . '</th><th>$' . number_format($sum['pyear']) . '</th></tr>';
        echo '<input type="hidden" id="TeamUser" name="TeamUser" value="' . $_SESSION['TeamID'] . '">';
        echo '<input type="hidden" id="TeamOther" name="TeamOther" value="' . $_GET['trade'] . '">';
        echo '<tr><td valign="top">';
            echo '<table class="roster" border=1 id="'.$_SESSION['abbreviation'].'"><tr><th>Player</th><th>Pos</th><th>Ovr</th><th>Salary</th><th>Add</th></tr>';
            $playerService = playerService($_SESSION['TeamID'],0,2);
            usort($playerService, fn($a, $b) => $a['PosSort'] <=> $b['PosSort']);
            foreach ($playerService as $player) {
                echo '<tr><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' . $player['Position'] . '</td><td>' . $player['Overall'] . '</td><td>' . number_format($player[$year]) . '</td><td><input type="checkbox" id="tradeuser'.$count.'" name="tradeuser'.$count.'" value="' . $player['PlayerID'] . '"></td></tr>'; 
                $count++;
            }
            $picksuser = $connection->query("SELECT draftID, team, year, round, owner FROM ptf_draft_picks WHERE year >= " . $year . " AND playerID = 0 AND owner = " . $_SESSION['TeamID']);
            while($row = $picksuser->fetch_assoc()) {
                echo '<tr><td>' . $row['year'] . ' - Rd:' .  $row['round'] . '(' . idToAbbrev($row['team']) . ')</td><td></td><td></td><td></td><td><input type="checkbox" id="tradeuserpick'.$count.'" name="tradeuserpick'.$count.'" value="' . $row['draftID'] . '"></td></tr>'; 
                $count++;
            }
            echo '</table><br>';

        echo '</td><td valign="top">';
            echo '<table class="roster" border=1 id="'.$team['Abbrev'].'"><tr><th>Player</th><th>Pos</th><th>Ovr</th><th>Salary</th><th>Add</th></tr>';
            $count = 0;
            $playerService = playerService($_GET['trade'],0,2);
            usort($playerService, fn($a, $b) => $a['PosSort'] <=> $b['PosSort']);
            foreach ($playerService as $player) {
                echo '<tr><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' . $player['Position'] . '</td><td>' . $player['Overall'] . '</td><td>' . number_format($player[$year]) . '</td><td><input type="checkbox" id="tradeother'.$count.'" name="tradeother'.$count.'" value="' . $player['PlayerID'] . '"></td></tr>'; 
                $count++;
            }
            $picktrade = $connection->query("SELECT draftID, team, year, round, owner FROM ptf_draft_picks WHERE year >= " . $year . " AND playerID = 0 AND owner = " . $_GET['trade']);
            while($row = $picktrade->fetch_assoc()) {
                echo '<tr><td>' . $row['year'] . ' - Rd:' .  $row['round'] . '(' . idToAbbrev($row['team']) . ')</td><td></td><td></td><td></td><td><input type="checkbox" id="tradeotherpick'.$count.'" name="tradeotherpick'.$count.'" value="' . $row['draftID'] . '"></td></tr>'; 
                $count++;
            }
            echo '</table><br>';

        echo '</td></tr></table>';





        echo '<br>';
        echo '<center><input type="submit" name="trade" value="trade"></center><br>';
        echo '</form>';
    }
}



?>