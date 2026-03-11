<?php
include 'adminheader.php';
session_start();
require('../../sql/phpmysqlconnect.php');

if ($_SESSION['admin'] !== '2') {
    echo 'Nope';
    exit;
}

$teams = array();
$stmt = $connection->query('SELECT TeamID, Mascot from ptf_teams');
while($row = $stmt->fetch_assoc()) {
    array_push($teams,$row);
}

echo '<select id="teamdrop" onchange="changeText()">';
echo '<option value="">Not Set</option>';
usort($teams, fn($a, $b) => $a['TeamID'] <=> $b['TeamID']);
foreach ($teams as $team) {
    echo '<option value="' . $team['TeamID'] . '" data-display="' . $team['Mascot'] . '">' . $team['Mascot'] . '</option>';
}
echo '</select><br><br>';

$teamId = $_GET['team'] ?? null;

echo '<form action="teameditor.php" method="post" enctype="multipart/form-data">';

if ($teamId) {
    $selTeam = array();
    $stmt = $connection->query(
        'SELECT * from ptf_teams t 
        JOIN ptf_teams_data d on t.TeamID = d.TeamID 
        JOIN ptf_teams_history h on t.TeamID = h.TeamID
        where t.TeamID = ' . $teamId);
    while($row = $stmt->fetch_assoc()) {
        array_push($selTeam,$row);
    }
    //var_dump($selTeam);
    echo 'City: <input type="text" id="City" name="City" value="' . $selTeam[0]['City'] . '"><br><br>';
    echo 'Mascot: <input type="text" id="Mascot" name="Mascot" value="' . $selTeam[0]['Mascot'] . '"><br><br>';
    echo 'Abbrev: <input type="text" id="Abbrev" name="Abbrev" value=" ' . $selTeam[0]['Abbrev'] . '"><br><br>';
    echo 'Conference: <input type="text" id="Conference" name="Conference" value=" ' . $selTeam[0]['Conference'] . '"><br><br>';
    echo 'Division: <input type="text" id="Division" name="Division" value=" ' . $selTeam[0]['Division'] . '"><br><br>';
    echo 'Owner: <input type="text" id="Owner" name="Owner" value=" ' . $selTeam[0]['Owner'] . '"><br><br>';
    echo 'Color 1: <input type="text" id="color_1" name="color_1" value=" ' . $selTeam[0]['color_1'] . '"><br><br>';
    echo 'Color 2: <input type="text" id="color_2" name="color_2" value=" ' . $selTeam[0]['color_2'] . '"><br><br>';
    echo 'Stadium: <input type="text" id="stadium" name="stadium" value=" ' . $selTeam[0]['stadium'] . '"><br><br>';
    echo 'State: <input type="text" id="state" name="state" value=" ' . $selTeam[0]['state'] . '"><br><br>';
    echo 'Market: <input type="number" min=0 max=8 id="market" name="market" value= ' . $selTeam[0]['market'] . '><br><br>';
    echo 'Stadium City: <input type="text" id="StadCity" name="StadCity" value=" ' . $selTeam[0]['StadCity'] . '"><br><br>';
    echo 'Stadium State: <input type="text" id="StadState" name="StadState" value=" ' . $selTeam[0]['StadState'] . '"><br><br>';
    echo 'Discord Team: <input type="text" id="DiscordTag" name="DiscordTag" value=" ' . $selTeam[0]['DiscordTag'] . '"><br><br>';
    echo 'Discord User: <input type="text" id="DiscordUser" name="DiscordUser" value=" ' . $selTeam[0]['DiscordUser'] . '"><br><br>';
    echo 'Franchise Tag Used: <input type="number" min=0 max=1 id="FranchiseTag" name="FranchiseTag" value=' . $selTeam[0]['FranchiseTag'] . '><br><br>';
    echo 'Extensions Left: <input type="number" min=-5 max=5 id="Extensions" name="Extensions" value=' . $selTeam[0]['Extensions'] . '><br><br>';
    echo 'Caphit 1: <input type="number" min=0 step=50000 id="caphit" name="caphit" value=' . $selTeam[0]['caphit'] . '><br><br>';
    echo 'Caphit 2: <input type="number" min=0 step=50000 id="caphit2" name="caphit2" value=' . $selTeam[0]['caphit2'] . '><br><br>';
    echo 'Caphit 3: <input type="number" min=0 step=50000 id="caphit3" name="caphit3" value=' . $selTeam[0]['caphit3'] . '><br><br>';
    echo 'Caphit 4: <input type="number" min=0 step=50000 id="caphit4" name="caphit4" value=' . $selTeam[0]['caphit4'] . '><br><br>';
    echo 'Caphit 5: <input type="number" min=0 step=50000 id="caphit5" name="caphit5" value=' . $selTeam[0]['caphit5'] . '><br><br>';
    echo 'Caphit 6: <input type="number" min=0 step=50000 id="caphit6" name="caphit6" value=' . $selTeam[0]['caphit6'] . '><br><br>';
    echo '1985 ID: <input type="number" min=0 max=500 id="1985" name="1985" value=' . $selTeam[0]['1985'] . '><br><br>';
    echo '1986 ID: <input type="number" min=0 max=500 id="1986" name="1986" value=' . $selTeam[0]['1986'] . '><br><br>';
    echo '1987 ID: <input type="number" min=0 max=500 id="1987" name="1987" value=' . $selTeam[0]['1987'] . '><br><br>';
    echo '1988 ID: <input type="number" min=0 max=500 id="1988" name="1988" value=' . $selTeam[0]['1988'] . '><br><br>';
    echo '1989 ID: <input type="number" min=0 max=500 id="1989" name="1989" value=' . $selTeam[0]['1989'] . '><br><br>';
    echo '1990 ID: <input type="number" min=0 max=500 id="1990" name="1990" value=' . $selTeam[0]['1990'] . '><br><br>';
    echo '1991 ID: <input type="number" min=0 max=500 id="1991" name="1991" value=' . $selTeam[0]['1991'] . '><br><br>';
    echo '<input type="submit" value="Edit Team">';
}

if ($_POST) {
    /*$stmt = $connection->query("UPDATE ptf_teams_data SET city = '" . $_POST['City'] . "',   
    mascot = '" . $_POST['Mascot'] . "', 
    conference = '" . $_POST['Conference'] . "', 
    abbreviation = '" . $_POST['Abbrev'] . "', 
    color_1 = '" . $_POST['color_1'] . "', 
    color_2 = '" . $_POST['color_2'] . "',
    stadium = '" . $_POST['stadium'] . "', 
    `state` = '" . $_POST['state'] . "', 
    market = '" . $_POST['market'] . "', 
    StadCity = '" . $_POST['StadCity'] . "',
    StadState = '" . $_POST['StadState'] . "', 
    DiscordTag = '" . $_POST['DiscordTag'] . "', 
    DiscordUser = '" . $_POST['DiscordUser'] . "', 
    Extensions = '" . $_POST['Extensions'] . "', 
    FranchiseTag = '" . $_POST['FranchiseTag'] . "',
    caphit = '" . $_POST['caphit'] . "', 
    caphit2 = '" . $_POST['caphit2'] . "', 
    caphit3 = '" . $_POST['caphit3'] . "', 
    caphit4 = '" . $_POST['caphit4'] . "',
    caphit5 = '" . $_POST['caphit5'] . "', 
    caphit6 = '" . $_POST['caphit6']  . "'
    WHERE TeamID = " . $_POST['TeamID']);*/
}

?>

<script type="text/javascript">
function changeText() {
    const teamId = document.getElementById("teamdrop").value;
    window.location.href = "teameditor.php?team=" + teamId;
}
</script>