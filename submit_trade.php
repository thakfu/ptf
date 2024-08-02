<?php

include 'header.php';

echo "<h1>" . $_SESSION['mascot'] . " Trade Center</h1>";

if (isset($_POST['offertrade'])) {
    echo '<br>';
    $stmt4 = $connection->query("INSERT INTO ptf_trade_offers (sentTID,recTID,sentAssets,recAssets,time) VALUES (".$_POST['TeamUser'].",".$_POST['TeamOther'].",'".$_POST['UserOffer']."','".$_POST['TeamOffer']."',NOW())");
    echo 'Your Trade Offer has been sent, and the receiving team will be notified.   Good Luck!';
} else {
    echo "<h3>Please Confirm the deal below!</h3>";
    $teamService = teamService($_POST['TeamOther']);
    $team = $teamService[0];
    $useroffer = '';
    $recoffer = '';
    echo '<h4>The '. $_SESSION['mascot'] . ' Receive:</h4>';
        echo '<ul>';
        $totSalOther = 0;
        foreach ($_POST as $key => $p) {
            if (strpos($key,'other')) {
                if (strpos($key,'pick')) {
                    $picktrade = $connection->query("SELECT draftID, team, year, round, pick, owner FROM ptf_draft_picks WHERE draftID = " . $p);
                    $pick = $picktrade->fetch_assoc();
                    echo '<li>' . $pick['year'] . ' - Round ' . $pick['round'] . ' Pick (' . $pick['pick'] . ')';
                    $recoffer .= 'pick:' . $p . ';';
                } else {
                    $playerService = playerService($_POST['TeamOther'],0,2);
                    foreach($playerService as $player) {
                        if ($player['PlayerID'] == $p) {
                            echo '<li>' . $player['FullName'];
                            $totSalOther += $player[$year];
                            $recoffer .= 'play:' . $p . ';';
                        }
                    }
                }
            } 
        }
        echo '</ul>';
        echo 'Total Salary Incoming: ' . number_format($totSalOther);
        echo '<br><br>';
    echo '<h4>The '. $team['Mascot'] . ' Receive:</h4>';
        echo '<ul>';
        $totSal = 0;
        foreach ($_POST as $key => $p) {
            if (!strpos($key,'other')) {
                if (strpos($key,'pick')) {
                    $picktrade = $connection->query("SELECT draftID, team, year, round, pick, owner FROM ptf_draft_picks WHERE draftID = " . $p);
                    $pick = $picktrade->fetch_assoc();
                    echo '<li>' . $pick['year'] . ' - Round ' . $pick['round'] . ' Pick (' . $pick['pick'] . ')';
                    $useroffer .= 'pick:' . $p . ';';
                } else {
                    $playerService = playerService($_SESSION['TeamID'],0,2);
                    foreach($playerService as $player) {
                        if ($player['PlayerID'] == $p) {
                            echo '<li>' . $player['FullName'];
                            $totSal += $player[$year];
                            $useroffer .= 'play:' . $p . ';';
                        }
                    }
                }
            } 
        }
        echo '</ul>';
        echo 'Total Salary Incoming: ' . number_format($totSal);
        echo '<br><br><br>';
        $newTot = $total + $totSalOther - $totSal;
        echo $_SESSION['mascot'] . ' NEW Salary: $' . number_format($newTot) . '<br>';
        if ($salaryCap > $newTot) {
            echo 'THIS IS A VALID TRADE FOR ' . strtoupper($_SESSION['city']) . '!<br><br>';
            $userValid = 1;
        } else {
            echo ' THIS TRADE IS INVALID!!!<br><br>';
            $userValid = 0;
        }
        $stmt1 = $connection->query('SELECT sum(s.1985) as p1985, sum(s.1986) as p1986, sum(s.1987) as p1987, sum(s.1988) as p1988, sum(s.1989) as p1989, sum(s.1990) as p1990, sum(s.1991) as p1991  FROM ptf_players y LEFT JOIN ptf_players_salaries s ON y.PlayerID = s.PlayerID  WHERE y.Team = "' . $team['Abbrev'] . '"');
            while($sum = $stmt1->fetch_assoc()) {
                $othertotal = $sum['p' . $year];
            }
            $newTotOther = $othertotal + $totSal - $totSalOther;
        echo $team['Mascot'] . ' NEW Salary: $' . number_format($newTotOther) . '<br>';
        if ($salaryCap > $newTotOther) {
            $otherValid = 1;
            echo 'THIS IS A VALID TRADE FOR ' . strtoupper($team['City']) . '!<br><br>';
        } else {
            echo ' THIS TRADE IS INVALID!!!<br><br>';
            $otherValid = 0;
        }

        if ($userValid == 1 && $otherValid == 1) {
            echo 'Congratulations!   This is a VALID trade.  Confirm?';
            echo '<form action="submit_trade.php" method="POST">';
            echo '<input type="hidden" id="TeamUser" name="TeamUser" value="' . $_SESSION['TeamID'] . '">';
            echo '<input type="hidden" id="TeamOther" name="TeamOther" value="' . $_POST['TeamOther'] . '">';
            echo '<input type="hidden" id="UserOffer" name="UserOffer" value="' . $useroffer . '">';
            echo '<input type="hidden" id="TeamOffer" name="TeamOffer" value="' . $recoffer . '">';
            echo '<input type="submit" name="offertrade" value="offertrade"><br>';
            //offerHook($team['Mascot']);
        } else {
            echo 'This is an invalid trade, please go back and make some changes.';
        }
}

function offerHook($team) {
    //global $connection;

    $message = 'The ' . $team . ' have sent you a trade offer!';

    $url = 'https://discord.com/api/webhooks/1174883663457046569/bGRKx88xeep7TZePOMjE5W4zbHM1L5rlPRLhQkKBBSdL237XJleNwTVG4beYUSHmHrtq';
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'League Offices', 'content' => $message ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}
?>