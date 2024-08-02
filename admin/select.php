<?php

include 'adminheader.php';

if (!isset($_POST['draft'])) {
    $stmt2= $connection->query('SELECT * FROM ptf_draft_picks d JOIN ptf_teams t ON d.team = t.TeamID WHERE d.year = "1987" and d.current = "1" ORDER BY d.round ASC, d.pick ASC');
    $draftID = array();
    while($row = $stmt2->fetch_assoc()) {
        array_push($draftID, $row);
    }

    $playerInfo = $connection->query("SELECT FirstName, LastName, Position, AltPosition FROM ptf_players WHERE PlayerID = " . $_GET['PlayerID']);
    $playerInf = $playerInfo->fetch_assoc();
    $player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];
    $pos = $playerInf['Position'];

    if ($draftID[0]['TeamID'] == $_GET['team']) {
        echo '<div id="release">Are you sure you want to draft ' . $player . '?  </br><br>';
        echo '<form action="select.php" method="POST">';
        echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
        echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
        echo '<input type="hidden" id="Pos" name="Pos" value="' . $pos . '">';
        echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_GET['team'] . '">';
        echo '<input type="hidden" id="draftID" name="draftID" value="' . $draftID[0]['draftID'] . '">';
        echo '<input type="hidden" id="round" name="round" value="' . $draftID[0]['round'] . '">';
        echo '<input type="hidden" id="pick" name="pick" value="' . $draftID[0]['pick'] . '">';
        //echo '<input type="hidden" id="Abbreviation" name="Abbreviation" value="' . $_SESSION['abbreviation'] . '">';

        echo 'Draft ' . $player . ': ';

        echo '<input type="submit" name="draft" value="draft"><br><br>';
        echo '</form>';
    } else {
        echo 'TEAM MISMATCH.';
    }


} else {
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

    //echo "UPDATE ptf_players SET TeamID = " . $_POST['TeamID'] . ", Team = " . idToAbbrev($_POST['TeamID']) . " WHERE PlayerID = " . $_POST['PlayerID'];
    if($check['playerID'] == 0) {
        $roster = $connection->query("UPDATE ptf_draft_picks SET playerID = '{$_POST['PlayerID']}' WHERE draftID = " . $_POST['draftID']);

        $currentpick = $connection->query("UPDATE ptf_draft_picks SET current = '0' WHERE draftID = " . $_POST['draftID']);

        $curTime = date("Y-m-d H:i:s", time());
        $next = $connection->query("UPDATE ptf_draft_picks SET current = '1', `time` = '".$curTime."' WHERE round = '" . $nextround . "' AND pick = '" . $nextpick . "'");

        $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'draft', NOW())");

        $roster = $connection->query("UPDATE ptf_players SET TeamID = " . $_POST['TeamID'] . ", Team = '" . idToAbbrev($_POST['TeamID']) . "' WHERE PlayerID = " . $_POST['PlayerID']);

        draftHook($_POST['Player'], $_POST['TeamID'], $_POST['pick'], $_POST['round'], $_POST['Pos']);
    }

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

    $stmt2= $connection->query('SELECT t.DiscordTag, t.DiscordUser FROM ptf_draft_picks d JOIN ptf_teams_data t ON d.team = t.TeamID WHERE d.year = "1987" and d.round = '. $round .' and d.pick = ' . $nextup);
    $nextteam = array();
    while($row = $stmt2->fetch_assoc()) {
        array_push($nextteam, $row);
    }
    $discordtag = $nextteam[0]['DiscordTag'];
    $discordUser = $nextteam[0]['DiscordUser'];

    $message = 'With the number ' . $pick . ' pick of round ' . $roundtag . ', the ' . $teamname['FullName'] . ' select ' . $pos . ' - ' . $player . '.  ' . $discordtag . ' - ' . $discordUser . ' is on the clock!  (Pick made by admin via list)';

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