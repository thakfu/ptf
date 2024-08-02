<?php
include 'header.php';

$hook = '';

if ($_POST['confirm'] == 'true') {
    echo "Your trade offer has been cancelled... Good day!";
    $stmt2 = $connection->query("DELETE FROM ptf_trade_offers WHERE offerID = " . $_POST['id']);
    exit;
}

if ($_GET['cancel'] == 'true') {
    $stmt = $connection->query("SELECT * FROM ptf_trade_offers WHERE offerID = " . $_GET['id']);
    $offer = $stmt->fetch_assoc();

    $teamService = teamService($offer['recTID']);
    $team = $teamService[0];
    echo 'The ' . $_SESSION['city'] . ' ' . $_SESSION['mascot'] . ' have offered the ' . $team['FullName'] . ' a trade!' . "<br><br>";
    $in = explode(';',$offer['recAssets']);
    foreach ($in as $i) {
        $str = strpos($i,'pick');
        if ($str !== false) {
            $asset = str_replace('pick:','',$i);
            $pick = 'p' . $asset;
            $stmtpick = $connection->query("SELECT * from ptf_draft_picks WHERE draftID = " . $asset);
            while($row = $stmtpick->fetch_assoc()) {
                echo $row['year'] . ' Round ' . $row['round'] . ' pick - ' . idToAbbrev($row['team'])  . ' to ' . $_SESSION['abbreviation'] . '<br>';;
            }

        }
        $str2 = strpos($i,'play');
        if ($str2 !== false) {
            $asset2 = str_replace('play:',' ',$i);
            $playerService = playerService(0, $asset2, 0);
            $player = $playerService[0]['FullName'];
            echo  $player . ' to ' . $_SESSION['abbreviation'] . "<br>";
        }
    }
    echo '<br><br>';

    $out = explode(';',$offer['sentAssets']);
    foreach ($out as $i) {
        $str = strpos($i,'pick');
        if ($str !== false) {
            $asset = str_replace('pick:','',$i);
            $pick = 'p' . $asset;
            $stmtpick = $connection->query("SELECT * from ptf_draft_picks WHERE draftID = " . $asset);
            while($row = $stmtpick->fetch_assoc()) {
                echo $row['year'] . ' Round ' . $row['round'] . ' pick - ' . idToAbbrev($row['team'])  . ' to ' . idToAbbrev($team['TeamID']) . '<br>';;
            }
        }
        $str2 = strpos($i,'play');
        if ($str2 !== false) {
            $asset2 = str_replace('play:',' ',$i);
            $playerService = playerService(0, $asset2, 0);
            $player = $playerService[0]['FullName'];
            echo  $player . ' to ' . idToAbbrev($team['TeamID']) . "<br>";
        }
    }

    echo '<br>';
    echo 'Do you want to cancel this trade?';

    echo '<form action="trade_approval.php" method="POST">';
    echo '<input type="hidden" id="id" name="id" value="' . $offer['offerID'] . '">';
    echo '<input type="hidden" id="confirm" name="confirm" value="true">';
    echo '<input type="submit" name="accepttrade" value="Cancel Trade"><br>';

} elseif (isset($_POST['accepttrade'])) {
    $stmt = $connection->query("SELECT * FROM ptf_trade_offers WHERE offerID = " . $_POST['Offer']);
    $offer = $stmt->fetch_assoc();

    $teamService = teamService($offer['sentTID']);
    $team = $teamService[0];
    $hook .= 'The ' . $_SESSION['city'] . ' ' . $_SESSION['mascot'] . ' and the ' . $team['FullName'] . ' have completed a trade!' . "\n";
    $in = explode(';',$offer['sentAssets']);
    foreach ($in as $i) {
        $str = strpos($i,'pick');
        if ($str !== false) {
            $asset = str_replace('pick:','',$i);
            $picktrade = $connection->query("UPDATE ptf_draft_picks SET owner = ". $_SESSION['TeamID'] . " WHERE draftID = " . $asset);
            echo $pick = 'p' . $asset;
            $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ('{$pick}',{$offer['recTID']}, {$offer['sentTID']}, 'trade', NOW())");
            $stmtpick = $connection->query("SELECT * from ptf_draft_picks WHERE draftID = " . $asset);
            while($row = $stmtpick->fetch_assoc()) {
                $pickString = $row['year'] . ' Round ' . $row['round'] . ' pick - ' . idToAbbrev($row['team']);
            }
            echo 'PickID ' . $asset . ' to ' . $_SESSION['abbreviation'] . '<br>';
            $hook .= 'The ' . $_SESSION['city'] . ' ' . $_SESSION['mascot'] . ' acquire ' . $pickString . '.' . "\n";

        }
        $str2 = strpos($i,'play');
        if ($str2 !== false) {
            $asset2 = str_replace('play:',' ',$i);
            $roster = $connection->query("UPDATE ptf_players SET TeamID = '{$_SESSION['TeamID']}', Team = '{$_SESSION['Abbreviation']}' WHERE PlayerID = " . $asset2);
            $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$asset2},{$offer['sentTID']}, {$offer['recTID']}, 'trade', NOW())");
            $playerService = playerService(0, $asset2, 0);
            $player = $playerService[0]['FullName'];
            echo 'PlayerID ' . $asset2 . ' to ' . $_SESSION['abbreviation'] . '<br>';
            $hook .= 'The ' . $_SESSION['city'] . ' ' . $_SESSION['mascot'] . ' acquire ' . $player . '.' . "\n";
        }
    }

    $out = explode(';',$offer['recAssets']);
    $teamService = teamService($offer['sentTID']);
    $team = $teamService[0];
    foreach ($out as $o) {
        $str = strpos($o,'pick');
        if ($str !== false) {
            $asset = str_replace('pick:','',$o);
            $picktrade = $connection->query("UPDATE ptf_draft_picks SET owner = ". $offer['sentTID'] . " WHERE draftID = " . $asset);
            $pick = 'p' . $asset;
            $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ('{$pick}',{$offer['sentTID']}, {$offer['recTID']}, 'trade', NOW())");
            $stmtpick = $connection->query("SELECT * from ptf_draft_picks WHERE draftID = " . $asset);
            while($row = $stmtpick->fetch_assoc()) {
                $pickString = $row['year'] . ' Round ' . $row['round'] . ' pick - ' . idToAbbrev($row['team']);
            }
            echo 'PickID ' . $asset . ' to ' . $team['Abbrev'] . '<br>';
            $hook .= 'The ' . $team['FullName'] . ' acquire ' . $pickString . '.' . "\n";
        }
        $str2 = strpos($o,'play');
        if ($str2 !== false) {
            $asset2 = str_replace('play:',' ',$o);
            $roster = $connection->query("UPDATE ptf_players SET TeamID = '{$offer['sentTID']}', Team = '{$team['Abbrev']}' WHERE PlayerID = " . $asset2);
            $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$asset2},{$offer['recTID']}, {$offer['sentTID']}, 'trade', NOW())");
            $playerService = playerService(0, $asset2 ,0);
            $player = $playerService[0]['FullName'];
            echo 'PlayerID ' . $asset2 . ' to ' . $team['Abbrev'] . '<br>';
            $hook .= 'The ' . $team['FullName'] . ' acquire ' . $player . '.' . "\n";
        }
    }

    tradeHook($hook);
    echo '<br>';
    echo 'The trade has been accepted and all assets now belong to their new teams!';
    $stmt2 = $connection->query("DELETE FROM ptf_trade_offers WHERE offerID = " . $_POST['Offer']);

} elseif (isset($_POST['declinetrade'])) {

    echo '<br>';
    echo 'The trade has been DECLINED and removed from the offer system.';
    $stmt2 = $connection->query("DELETE FROM ptf_trade_offers WHERE offerID = " . $_POST['Offer']);
    
} else {

    $in = array();
    $out = array();

    $stmt4 = $connection->query("SELECT * FROM ptf_trade_offers WHERE recTID = " . $_SESSION['TeamID']);
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
        echo 'The ' . $team['FullName'] . ' have sent you a trade offer!  <br><br>';

        echo '<h4>The '. $_SESSION['mascot'] . ' Receive:</h4>';
        echo '<ul>';
        $totSal = 0;
        $in = explode(';',$offer['sentAssets']);
        foreach ($in as $i) {
            $str = strpos($i,'pick');
            if ($str !== false) {
                $asset = str_replace('pick:',' ',$i);
                $picktrade = $connection->query("SELECT draftID, team, year, round, owner FROM ptf_draft_picks WHERE draftID = " . $asset);
                $pick = $picktrade->fetch_assoc();
                echo '<li>' . $pick['year'] . ' - Round ' . $pick['round'] . ' Pick (' . idToAbbrev($pick['owner']) . ')';
            }

            $str2 = strpos($i,'play');
            if ($str2 !== false) {
                $asset2 = str_replace('play:',' ',$i);
                $playerService = playerService($offer['sentTID'],0,2);
                foreach($playerService as $player) {
                    if ($player['PlayerID'] == $asset2) {
                        echo '<li><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a>: ' . $player['Position'] . ' (' . $player['Overall'] . ' ovr)' . ' - ' . $player[$year] . ' / ' . $player[$year + 1] . ' / ' . $player[$year + 2] . ' / ' . $player[$year + 3] . ' / ' . $player[$year + 4] . ' / ' . $player[$year + 5];
                        $totSal += $player[$year];
                    }
                }
            }
        }
        echo '</ul>';
        echo $year . ' Salary Incoming: ' . number_format($totSal);
        echo '<br><br>';

        echo '<h4>The '. $team['FullName'] . ' Receive:</h4>';
        echo '<ul>';
        $totSalOther = 0;
        $out = explode(';',$offer['recAssets']);
        foreach ($out as $o) {
            $str = strpos($o,'pick');
            if ($str !== false) {
                $asset = str_replace('pick:',' ',$o);
                $picktrade = $connection->query("SELECT draftID, team, year, round, owner FROM ptf_draft_picks WHERE draftID = " . $asset);
                $pick = $picktrade->fetch_assoc();
                echo '<li>' . $pick['year'] . ' - Round ' . $pick['round'] . ' Pick (' . idToAbbrev($pick['owner']) . ')';
            }
            $str2 = strpos($o,'play');
            if ($str2 !== false) {
                $asset2 = str_replace('play:',' ',$o);
                $playerService = playerService($offer['recTID'],0,2);
                foreach($playerService as $player) {
                    if ($player['PlayerID'] == $asset2) {
                        echo '<li><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a>: ' . $player['Position'] . ' (' . $player['Overall'] . ' ovr)' . ' - ' .  $player[$year] . ' / ' . $player[$year + 1] . ' / ' . $player[$year + 2] . ' / ' . $player[$year + 3] . ' / ' . $player[$year + 4] . ' / ' . $player[$year + 5];
                        $totSalOther += $player[$year];
                    }
                }
            }
        }
        echo '</ul>';
        echo $year . ' Salary Incoming: ' . number_format($totSalOther);
        echo '<br><br>';

            echo '<br><br><br>';
            $newTot = $total + $totSal - $totSalOther;
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
                $newTotOther = $othertotal + $totSalOther - $totSal;
            echo $team['Mascot'] . ' NEW Salary: $' . number_format($newTotOther) . '<br>';
            if ($salaryCap > $newTotOther) {
                $otherValid = 1;
                echo 'THIS IS A VALID TRADE FOR ' . strtoupper($team['City']) . '!<br><br>';
            } else {
                echo ' THIS TRADE IS INVALID!!!<br><br>';
                $otherValid = 0;
            }

            echo '<form action="trade_approval.php" method="POST">';
            echo '<input type="hidden" id="Offer" name="Offer" value="' . $offer['offerID'] . '">';
            echo '<input type="submit" name="accepttrade" value="Accept Trade"> -or-<br>';
            echo '<input type="submit" name="declinetrade" value="Decline Trade"><br>';
    
    }
}

function tradeHook($message) {
    //global $connection;


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