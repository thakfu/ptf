<?php

include 'header.php';

//var_dump($_POST);

$ballot = array();
$votes = array();
if ($_POST['Type'] == 'Awards') {
    foreach ($_POST as $key => $value) {
        if($key == 'TeamID') {
            $teamid = $value;
            continue;
        }
        if (!str_contains($key,'Team') && !str_contains($key,'Time') && !str_contains($key,'Type')) {

            $votes['key'] = substr($key,0,3);
            if ($votes['key'] == 'GMY') {
                $votes['id'] = '0000';
            } else {
                $votes['id'] = substr($key,3);
            }
            $votes['playername'] = $value;
            $votes['teamid'] = $teamid;
            array_push($ballot,$votes);
        }
    }
} else {
    foreach ($_POST as $key => $value) {
        if($key == 'TeamID') {
            $teamid = $value;
            continue;
        }
        if($key == 'Team' || $key == 'Time') {
            continue;
        }
        $votes['teamid'] = $teamid;
        $votes['conf'] = substr($key,0,3);
        $votes['playerid'] = substr($key,-4);
        $votes['playername'] = $value;
        $key = substr($key,3);
        $key = substr($key,0,-4);
        if ($key == 'Return') {
            $key = 'KR';
        }
        $votes['pos'] = $key;
        array_push($ballot,$votes);
    }
}

foreach ($ballot as $bal) {
  //  echo "INSERT INTO ptf_voting_probowl (TeamID, Pos, Conf, PlayerID, FullName) VALUES ({$bal['teamid']},{$bal['pos']},{$bal['conf']},{$bal['playerid']},{$bal['playername']})";
   if ($_POST['Type'] == 'Awards') {
        echo $bal['key'] . ' - ' . $bal['id'] . ' - ' . $bal['playername'] . '<br>';
        $update1 = $connection->query("INSERT INTO ptf_voting_awards (TeamID, Cat, PlayerID, FullName) VALUES ({$bal['teamid']},'{$bal['key']}',{$bal['id']},'{$bal['playername']}')");
   } else {
        echo $bal['pos'] . ' - ' . $bal['conf'] . ' - ' . $bal['playername'] . '<br>';
        $update1 = $connection->query("INSERT INTO ptf_voting_probowl (TeamID, Pos, Conf, PlayerID, FullName) VALUES ({$bal['teamid']},'{$bal['pos']}','{$bal['conf']}',{$bal['playerid']},'{$bal['playername']}')");
   }
}

echo 'Your Vote Has Been Submitted!   If you made a mistake and need to cast a new ballot, talk to ThakFu.';

?>