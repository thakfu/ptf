<?php

include 'header.php';

foreach($_POST as $header => $row) {
    echo $header . ': ' . $row . '<br>';
}

$date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['Time'])));

if($_POST['Type'] == 'playcalling') {
    $update1 = $connection->query('UPDATE ptf_playcalling SET last_pc = "'.$date.'", offUseCoach = "'.$_POST["offUseCoach"].'", defUseCoach = "'.$_POST["defUseCoach"].'", o1st10 = '.$_POST["o1st10"].', o1stSh = '.$_POST["o1stSh"].', o1stMd = '.$_POST["o1stMd"].', 
    o1stLg = '.$_POST["o1stLg"].', o2ndSh = '.$_POST["o2ndSh"].', o2ndMd = '.$_POST["o2ndMd"].',
    o2ndLg = '.$_POST["o2ndLg"].', o3rdSh = '.$_POST["o3rdSh"].', o3rdMd = '.$_POST["o3rdMd"].', 
    o3rdLg = '.$_POST["o3rdLg"].', o4thSh = '.$_POST["o4thSh"].', o4thMd = '.$_POST["o4thMd"].', 
    o4thLg = '.$_POST["o4thLg"].', oGL = '.$_POST["oGL"].', oEoHW = '.$_POST["oEoHW"].', 
    oEoHL = '.$_POST["oEoHL"].', d1st10 = '.$_POST["d1st10"].', d1stSh = '.$_POST["d1stSh"].', 
    d1stMd = '.$_POST["d1stMd"].', d1stLg = '.$_POST["d1stLg"].', d2ndSh = '.$_POST["d2ndSh"].', 
    d2ndMd = '.$_POST["d2ndMd"].', d2ndLg = '.$_POST["d2ndLg"].', d3rdSh = '.$_POST["d3rdSh"].', 
    d3rdMd = '.$_POST["d3rdMd"].', d3rdLg = '.$_POST["d3rdLg"].', d4thSh = '.$_POST["d4thSh"].', 
    d4thMd = '.$_POST["d4thMd"].', d4thLg = '.$_POST["d4thLg"].', dGL = '.$_POST["dGL"].', 
    dEoHW = '.$_POST["dEoHW"].', dEoHL = '.$_POST["dEoHL"].' WHERE TeamID = '.$_POST["TeamID"]);
    echo 'Play Calling submitted successfully!';
    exit;
} elseif ($_POST['Type'] == 'gameplan') {
    $update1 = $connection->query('UPDATE ptf_gameplan SET last_gp = "'.$date.'", defaulto = "'.$_POST["defaulto"].'", 
    style = "'.$_POST["style"].'", focus  = "'.$_POST["focus"].'", tempo  = "'.$_POST["tempo"].'", 
    passPref = "'.$_POST["passPref"].'",  passTargetPref = "'.$_POST["passTargetPref"].'",  primaryRec = "'.$_POST["primaryRec"].'", 
    thirdDownBack = "'.$_POST["thirdDownBack"].'",  goalLineBack = "'.$_POST["goalLineBack"].'", runPref  = "'.$_POST["runPref"].'", 
    rbRole = "'.$_POST["rbRole"].'",  backfieldCom = "'.$_POST["backfieldCom"].'",  teRole = "'.$_POST["teRole"].'", 
    qbTuck = "'.$_POST["qbTuck"].'", defaultd  = "'.$_POST["defaultd"].'",  styled = "'.$_POST["styled"].'", 
    focusd = "'.$_POST["focusd"].'", coverPref  = "'.$_POST["coverPref"].'", dlUse  = "'.$_POST["dlUse"].'", 
    lbUse = "'.$_POST["lbUse"].'", primaryDef  = "'.$_POST["primaryDef"].'",  matchWR = "'.$_POST["matchWR"].'", 
    alignMan = "'.$_POST["alignMan"].'", target  = "'.$_POST["target"].'", defaultSub  = "'.$_POST["defaultSub"].'", 
    margin = '.$_POST["margin"].',  energyMin = '.$_POST["energyMin"].', energyMax  = '.$_POST["energyMax"].', overrideWin = "'.$_POST["overrideWin"].'", 
    overrideLose = "'.$_POST["overrideLose"].'", handlePrep  = "'.$_POST["handlePrep"].'", oPrep  = "'.$_POST["oPrep"].'", 
    dPrep = "'.$_POST["dPrep"].'" WHERE TeamID = '.$_POST["TeamID"]);
    echo 'Game Plan submitted successfully!';
    exit;
} elseif ($_POST['Type'] == 'depth') {
    exit;
}

$players = array();
$stmt = $connection->query('SELECT PlayerID, FirstName, LastName, Position, AltPosition FROM `ptf_players` ORDER BY PlayerID ASC');
while($row = $stmt->fetch_assoc()) {
    array_push($players, $row);
}
$depthchart = array();
$teamID = (int)$_POST['TeamID'];
unset($_POST['Team']);
unset($_POST['Time']);
unset($_POST['TeamID']);
foreach($_POST as $header => $row) {
    $teamchart = array();
    if ($row == 'none') {
        $player = 0;
        $Position = $header;
        $pos = $alt = $special = '';
    } else {
        $player = $row;
    }
    if (!str_contains($header, 'special') ) {
        foreach($players as $p) {
            if ($row == $p['PlayerID']) {
                $pos = $p['Position'];
                $alt = $p['AltPosition'];
                $special = '';
                $Position = $header;
            }
        }
    } else {
        $pos = $alt = '';
        $special = $row;
        $num = str_replace('special','',$header);
        $Position = 'NOTE' . $num;
        $player = 0;
    }
    array_push($teamchart,$Position,$teamID,$player,$pos,$alt,$special);
    array_push($depthchart,$teamchart);
    unset($teamchart);
    //echo  "UPDATE ptf_players_depth SET Position = '$Position', Team = '$teamID', PlayerID = '$player', SimPos = '$pos', SimAlt = '$alt', Special = '$special' WHERE Position = '$Position' AND Team = '$teamID'";
    //$update1 = $connection->query("UPDATE ptf_players_depth SET Position = '$Position', Team = $teamID, PlayerID = $player, SimPos = '$pos', SimAlt = '$alt', Special = '$special' WHERE Position = '$Position' AND Team = $teamID");
}

foreach ($depthchart as $team) {
    //echo "UPDATE ptf_players_depth SET Position = '$team[0]', Team = $team[1], PlayerID = $team[2], SimPos = '$team[3]', SimAlt = '$team[4]', Special = '$team[5]' WHERE Position = '$team[0]' AND Team = $team[1]";
    $update1 = $connection->query("UPDATE ptf_players_depth SET Position = '$team[0]', Team = $team[1], PlayerID = $team[2], SimPos = '$team[3]', SimAlt = '$team[4]', Special = '$team[5]' WHERE Position = '$team[0]' AND Team = $team[1]");

}
$update2 = $connection->query("UPDATE ptf_users SET Last_DC = '$date' WHERE TeamID = '$teamID'");
echo 'Depth Chart submitted successfully!';
?>