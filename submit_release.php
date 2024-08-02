<?php

include 'header.php';

if (isset($_POST['demote'])) {
    $demoteCheck = $connection->query("SELECT count(squadTeam) as 'check' FROM `ptf_players_squad` where squadTeam = {$_POST['TeamID']}");
    $check = $demoteCheck->fetch_assoc();

    if ($check['check'] >= 5) {
        echo 'Your Practice Squad is already full!';
    } else {
        echo $_POST['Player'] . ' has been demoted to your Practice Squad.  He\'s gonna prove you wrong!';
        $roster = $connection->query("INSERT INTO ptf_players_squad (PlayerID, squadTeam) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']})");
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'squad', NOW())");
    }
} elseif (isset($_POST['promote'])) {
    $promoteCheck1 = $connection->query("SELECT count(squadTeam) as 'check' FROM `ptf_players_squad` where squadTeam = {$_POST['TeamID']}");
    $check1 = $promoteCheck1->fetch_assoc();
    $promoteCheck2 = $connection->query("SELECT count(squadTeam) as 'check' FROM `ptf_players_ir` where squadTeam = {$_POST['TeamID']}");
    $check2 = $promoteCheck2->fetch_assoc();
    $promoteCheck3 = $connection->query("SELECT count(TeamID) as 'check' FROM `ptf_players` where TeamID = {$_POST['TeamID']}");
    $check3 = $promoteCheck3->fetch_assoc();
    $count = $check3['check'] - $check2['check'] - $check1['check'];

    if ($count >= 53) {
        echo 'Your Roster is full!  You cannot promote a player unless you have an open roster position.  Please release a player or place them on IR first!';
    } else {
        echo $_POST['Player'] . ' has been promoted from your Practice Squad.  Let\'s go!';
        $squad = $connection->query("DELETE FROM ptf_players_squad WHERE PlayerID = " . $_POST['PlayerID']);
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'promote', NOW())");
    }
} elseif (isset($_POST['IR'])) {
    $irCheck = $connection->query("SELECT InjuryLength as 'check' FROM `ptf_players` where PlayerID = {$_POST['PlayerID']}");
    $check = $irCheck->fetch_assoc();

    if (!str_contains($check['check'],"Out")) {
        echo 'This player is not eligible to be placed on Injured Reserve!';
    } else {
        echo $_POST['Player'] . ' has been placed on Injured Reserve.  He must remain there for atleast 3 weeks.';
        $roster = $connection->query("INSERT INTO ptf_players_ir (PlayerID, squadTeam, start) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$curWeek})");
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'ir', NOW())");
    } 
} elseif (isset($_POST['activate'])) {
    $irCheck = $connection->query("SELECT start as 'check' FROM `ptf_players_ir` where PlayerID = {$_POST['PlayerID']}");
    $check = $irCheck->fetch_assoc();
    $left = $curWeek - $check['check'];
    if ($left < 3) {
        echo 'This player cannot be activated yet!  He has ' . 3 - $left  . ' weeks remaining on IR.';
    } else {
        echo $_POST['Player'] . ' has been activated from your Injured Reserve.  Let\'s go!';
        $squad = $connection->query("DELETE FROM ptf_players_ir WHERE PlayerID = " . $_POST['PlayerID']);
        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'activate', NOW())");
    }
}elseif (isset($_POST['release'])) {
    echo $_POST['Player'] . ' has been released and should now appear in the free agency pool.  I hope your happy.';

    $roster = $connection->query("UPDATE ptf_players SET TeamID = '0', Team = 'N/A' WHERE PlayerID = " . $_POST['PlayerID']);

    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']}, 0, 'cut', NOW())");

    $squad = $connection->query("DELETE FROM ptf_players_squad WHERE PlayerID = " . $_POST['PlayerID']);
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'release');
} elseif (isset($_POST['change'])) {
    echo $_POST['Player'] . ' has changed his position to ' . $_POST['pos'] . '.  Get that man a new playbook!';

    $roster = $connection->query("UPDATE ptf_players SET Position = '{$_POST['pos']}' WHERE PlayerID = " . $_POST['PlayerID']);

    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},{$_POST['TeamID']}, 'change', NOW())");
} elseif (isset($_POST['sign'])) {
    echo $_POST['Player'] . ' has been signed and should now appear on your roster.  Go on, give him a hug!';

    $roster = $connection->query("UPDATE ptf_players SET TeamID = '{$_POST['TeamID']}', Team = '{$_POST['Abbreviation']}' WHERE PlayerID = " . $_POST['PlayerID']);
    $roster = $connection->query("UPDATE ptf_players_salaries SET `" . $year . "` = '250000' WHERE PlayerID = " . $_POST['PlayerID']);

    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'sign', NOW())");
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'sign');
} elseif (isset($_POST['revoke'])) {
    echo $_POST['Player'] . '\'s offer has been revoked.  Try again... or don\'t.  I don\'t care.';

    $squad = $connection->query("DELETE FROM ptf_fa_offers WHERE PlayerID = " . $_POST['PlayerID'] . " AND TeamID = " . $_POST['TeamID']);
} elseif (isset($_POST['extend'])) {
    if ($_POST['year1'] > ($salaryCap - $totalnext)) {
        echo 'Your offer puts you over the cap by $' . number_format($_POST['year1'] - ($salaryCap - $totalnext)) . ' and is invalid!  Go back and try again!';
        exit;
    }

    if ($_POST['year1'] < $_POST['y1min']) {
        echo 'Year 1 offer is too low!  Go back and try again!';
        exit;
    }
    if ($_POST['year2'] < $_POST['y2min'] && $_POST['extat'] >= 2) {
        echo 'Year 2 offer is too low!  Go back and try again!';
        exit;
    }
    if ($_POST['year3'] < $_POST['y3min'] && $_POST['extat'] >= 3) {
        echo 'Year 3 offer is too low!  Go back and try again!';
        exit;
    }
    if ($_POST['year4'] < $_POST['y4min'] && $_POST['extat'] >= 4) {
        echo 'Year 4 offer is too low!  Go back and try again!';
        exit;
    }
    if ($_POST['year5'] < $_POST['y5min'] && $_POST['extat'] >= 5) {
        echo 'Year 5 offer is too low!  Go back and try again!';
        exit;
    }
    if ($_POST['year6'] < $_POST['y6min'] && $_POST['extat'] >= 6) {
        echo 'Year 6 offer is too low!  Go back and try again!';
        exit;
    }

    if ($_POST['extat'] == 6) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3'] . ', `' . $year + 4 . '` = ' . $_POST['year4'] . ', `' . $year + 5 . '` = ' . $_POST['year5'] . ', `' . $year + 6 . '` = ' . $_POST['year6']; 
        if (!$_POST['year6'] || !$_POST['year5'] || !$_POST['year4'] || !$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 5) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3'] . ', `' . $year + 4 . '` = ' . $_POST['year4'] . ', `' . $year + 5 . '` = ' . $_POST['year5']; 
        if (!$_POST['year5'] || !$_POST['year4'] || !$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 4) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3'] . ', `' . $year + 4 . '` = ' . $_POST['year4']; 
        if (!$_POST['year4'] || !$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 3) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2'] . ', `' . $year + 3 . '` = ' . $_POST['year3']; 
        if (!$_POST['year3'] || !$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 2) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1'] . ', `' . $year + 2 . '` = ' . $_POST['year2']; 
        if (!$_POST['year2']) {
            echo 'Please make sure all years have an amount in the contract length row you selected!';
            exit;
        }
    } elseif ($_POST['extat'] == 1) {
        $string = '`' . $year + 1 . '` = ' . $_POST['year1']; 
    }

    $sum = $_POST['year6'] + $_POST['year5'] + $_POST['year4'] + $_POST['year3'] + $_POST['year2'] + $_POST['year1'];

    $playerService = playerService(0,$_POST['PlayerID'],0);
    $player = $playerService[0];

    $extension = $connection->query("UPDATE ptf_teams_data SET Extensions = Extensions - 1 WHERE TeamID = " . $_SESSION['TeamID']);
    $roster = $connection->query("UPDATE ptf_players_salaries SET " . $string . " WHERE PlayerID = " . $_POST['PlayerID']);
    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},0, {$_SESSION['TeamID']}, 'extend', NOW())");
    transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'extend');
    echo $player['FullName'] . ' has accepted your offer of ' . $_POST['extat'] . ' years at a total of ' . number_format($sum) . '.';


} elseif (isset($_POST['offer'])) {

    if ($_POST['year1'] > (300000000 - $_SESSION['currentCap'])) {
        echo 'Your offer puts you over the cap by $' . number_format($_POST['year1'] - (300000000 - $_SESSION['currentCap'])) . ' and is invalid!  Go back and try again!';
        exit;
    }

    $year1 = intval(str_replace(",","",$_POST['year1']));
    $year2 = intval(str_replace(",","",$_POST['year2']));
    $year3 = intval(str_replace(",","",$_POST['year3']));
    $year4 = intval(str_replace(",","",$_POST['year4']));
    $year5 = intval(str_replace(",","",$_POST['year5']));
    $year6 = intval(str_replace(",","",$_POST['year6']));
    $year7 = intval(str_replace(",","",$_POST['year7']));
    $demand = intval(str_replace(",","",$_POST['Demand']));
    $sum = $year1+$year2+$year3+$year4+$year5+$year6+$year7;
    $years = 1;
    if ($_POST['year2'] > 0) $years++;
    if ($_POST['year3'] > 0) $years++;
    if ($_POST['year4'] > 0) $years++;
    if ($_POST['year5'] > 0) $years++;
    if ($_POST['year6'] > 0) $years++;
    if ($_POST['year7'] > 0) $years++;

    if ($sum / $years >= $demand) {
        $result = 1;
    } else {
        $result = 0;
    }

    if ($result == 1) {
        echo $_POST['Player'] . ' has been offered a contract and will make his decision shortly.  Hope you didnt cheap out on him.';
    } else {
        echo $_POST['Player'] . ' has received your contract offer, but it might not meet his demand.  We shall see I guess.  Good luck!';
    }

    echo ' ' . $years . ' years at a total of ' . number_format($sum) . '.';

    $offer = $connection->query("INSERT INTO ptf_fa_offers (playerID, teamID, year, amount1, amount2, amount3, amount4, amount5, amount6, total, demand, result) VALUES ({$_POST['PlayerID']},{$_POST['TeamID']},1987,{$year1},{$year2},{$year3},{$year4},{$year5},{$year6},{$sum},{$demand},{$result})");

} elseif (isset($_POST['tag'])) {
    echo $_POST['Player'] . ' has been franchise tagged! He may or may not be happy, but he for sure is getting paid!';
    $franchise = intval(str_replace(",","",$_POST['franchise']));

    $roster = $connection->query("UPDATE ptf_players_salaries SET `" . $year . "` = '" . $franchise . "', `" . $year + 1 . "` = '" . $franchise . "' WHERE PlayerID = " . $_POST['PlayerID']);
    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'tag', NOW())");
    
    $playerService = playerService(0,$_POST['PlayerID'],0);
    $player = $playerService[0];
    transactionHook($player['FullName'], $_SESSION['TeamID'], '', 'tag');

} elseif (isset($_POST['draft'])) {
    $nextpick = $_POST['pick'] + 1;
    if ($nextpick == 21) {
        $nextpick = 1;
        $nextround = $_POST['round'] + 1;
    } else {
        $nextround = $_POST['round'];
    }

    echo $_POST['Player'] . ' has been drafted!  What a special day!';

    $stmt2= $connection->query('SELECT playerID FROM ptf_draft_picks  WHERE draftID = ' . $_POST['draftID']);
    $check = $stmt2->fetch_assoc();

    if($check['playerID'] == 0) {
        $roster = $connection->query("UPDATE ptf_draft_picks SET playerID = '{$_POST['PlayerID']}' WHERE draftID = " . $_POST['draftID']);

        $currentpick = $connection->query("UPDATE ptf_draft_picks SET current = '0' WHERE draftID = " . $_POST['draftID']);

        $curTime = date("Y-m-d H:i:s", time());
        $next = $connection->query("UPDATE ptf_draft_picks SET current = '1', `time` = '".$curTime."' WHERE round = '" . $nextround . "' AND pick = '" . $nextpick . "'");

        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'draft', NOW())");

        $roster = $connection->query("UPDATE ptf_players SET TeamID = " . $_POST['TeamID'] . ", Team = '" . idToAbbrev($_POST['TeamID']) . "' WHERE PlayerID = " . $_POST['PlayerID']);

        draftHook($_POST['Player'], $_POST['TeamID'], $_POST['pick'], $_POST['round'], $_POST['pos']);
    }

}

if (isset($_POST['offer']) || isset($_POST['revoke'])) {
    echo '<br><br><a href="freeagency.php?abbrev=' . $_SESSION['abbreviation'] . '">Go Back to Free Agency</a>'; 
} else {
    echo '<br><br><a href="transactions.php">Go Back to Transactions</a>'; 
}

function transactionHook($player, $team, $pos, $type) {
    //global $connection;
    $teamService = teamService($team);
    $teamname = $teamService[0];

    if ($type == 'sign') {
        $message = 'The ' . $teamname['FullName'] . ' have signed free agent ' . $pos . ' - ' . $player . ' to a 1 year contract for the league minimum.';
    } elseif ($type == 'release') {
        $message = 'The ' . $teamname['FullName'] . ' have released ' . $pos . ' - ' . $player . ' to the free agency pool.';
    } elseif ($type == 'extend') {
        $message = 'The ' . $teamname['FullName'] . ' have agreed to a contract extension with ' . $player . '!';
    } elseif ($type == 'tag') {
        $message = 'The ' . $teamname['FullName'] . ' have franchise tagged ' . $player . '!';
    }


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


function draftHook($player, $team, $pick, $round, $pos) {
    global $connection;
    $teamService = teamService($team);
    $teamname = $teamService[0];
    $nextup = $pick + 1;
    if ($nextup == 21) {
        $nextup = 1;
        $round = $round + 1;
    }

    if ($nextup == 1) {
        $roundtag = $round - 1;
    } else {
        $roundtag = $round;
    }

    $stmt2= $connection->query('SELECT t.DiscordTag, t.DiscordUser FROM ptf_draft_picks d JOIN ptf_teams_data t ON d.owner = t.TeamID WHERE d.year = "1987" and d.round = '. $round .' and d.pick = ' . $nextup);
    $nextteam = array();
    while($row = $stmt2->fetch_assoc()) {
        array_push($nextteam, $row);
    }
    $discordtag = $nextteam[0]['DiscordTag'];
    $discordUser = $nextteam[0]['DiscordUser'];

    $message = 'With the number ' . $pick . ' pick of round ' . $roundtag . ', the ' . $teamname['FullName'] . ' select ' . $pos . ' - ' . $player . '.  ' . $discordtag . ' - ' . $discordUser . ' is on the clock!';

    //$url = 'https://discord.com/api/webhooks/1208652742453624872/N9WcLuNn98u-hDWya1l8tuQRcZTBs7hluZzAG6YEzRQ4iQdwdFUOGwIND5hyomrFWplK';   - 1986
    $url = 'https://discord.com/api/webhooks/1248126643432853505/8Qgtz_9lrOlZVTIq_7EDvRNIS7dg6ipVdSntay5FT-BMuGG1TtBwDRvVN6MXEQ2tnFeW';
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