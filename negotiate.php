<?php

// This opens the file that contains the league averages at each position and puts them into the $salaries array.
$csvFile = fopen('league-pay-averages.csv', 'r');
$salaries = array();
while(($data = fgetcsv($csvFile, 100, ',')) !== FALSE){
    for($i = 0; $i < count($data); $i++) {
        if ($i == 0 || $i % 2 == 0) {
            //even
            $key = $data[$i];
        } else {
            //odd
            $value = $data[$i];
            $salaries[$key] = $value;
        }
    }
}

$csvFile = null;
// CSV is now closed and the array takes over.

include 'header.php';

$playerInfo = $connection->query("SELECT FirstName, LastName, Position, AltPosition FROM ptf_players WHERE PlayerID = " . $_GET['PlayerID']);
$playerInf = $playerInfo->fetch_assoc();
$player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];

$demandAmt = $salaries[$playerInf['Position'] . '5'];

echo '<h3>' . $player . '</h3>';

if ($_GET['Demand'] == 'revoke') {
    echo '<form action="submit_release.php" method="POST">';
} else {
    echo '<form action="feedback.php" method="POST">';
}   

if ($_GET['Demand'] == 'revoke') {
    echo '<div id="release">You can revoke ' . $player . '\'s deal.  If you choose to do this you will need to go back and offer him a new deal if you wish.</div><br>';

    echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
    echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
    echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
    echo '<input type="submit" name="revoke" value="Revoke the Offer!"><br><br>';
} else {
    echo 'Contract Offer:<br>';
    echo $year   . ': <input type="number" min="250000" max="'. $_SESSION['currentCap'] .'" id="year1" name="year1" value="' . $_GET['Demand'] . '" step="50000"><br>';
    echo $year+1 . ': <input type="number" min="0" max="'. $_SESSION['currentCap'] .'" id="year2" name="year2" value="' . $_GET['Demand'] . '" step="50000"><button type="button" onclick="clearRow(2)">Clear</button><br>';
    echo $year+2 . ': <input type="number" min="0" max="'. $_SESSION['currentCap'] .'" id="year3" name="year3" value="' . $_GET['Demand'] . '" step="50000"><button type="button" onclick="clearRow(3)">Clear</button><br>';
    echo $year+3 . ': <input type="number" min="0" max="'. $_SESSION['currentCap'] .'" id="year4" name="year4" value="' . $_GET['Demand'] . '" step="50000"><button type="button" onclick="clearRow(4)">Clear</button><br>';
    echo $year+4 . ': <input type="number" min="0" max="'. $_SESSION['currentCap'] .'" id="year5" name="year5" value="' . $_GET['Demand'] . '" step="50000"><button type="button" onclick="clearRow(5)">Clear</button><br>';
    echo $year+5 . ': <input type="number" min="0" max="'. $_SESSION['currentCap'] .'" id="year6" name="year6" value="' . $_GET['Demand'] . '" step="50000"><button type="button" onclick="clearRow(6)">Clear</button><br>';

    echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['PlayerID'] . '">';
    echo '<input type="hidden" id="Demand" name="Demand" value="' . $_GET['Demand'] . '">';
    echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
    echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
    echo '<input type="hidden" id="Abbreviation" name="Abbreviation" value="' . $_SESSION['abbreviation'] . '">';

    $tagInfo = $connection->query("SELECT FranchiseTag FROM  ptf_teams_data WHERE TeamID = " . $_SESSION['TeamID']);
    $tagCheck = $tagInfo->fetch_assoc();

    if ($_GET['Tag'] == 'y' && $tagCheck['FranchiseTag'] == 1) {
        echo '<br><h3>You can FRANCHISE TAG this player for the top 5 average salary at his position for the next 2 seasons:<br><b> ' . number_format($demandAmt) . ' / ' . number_format($demandAmt) . ':  </b>';
        echo '<input type="hidden" id="franchise" name="franchise" value="' . $demandAmt . '">';
        echo '<input type="submit" name="tag" value="tag"></h3><br>';
    }

    echo '<br><h4> You have used $' . number_format($_SESSION['currentCap']) . ' in cap space, and have $' . number_format($salaryCap - $_SESSION['currentCap']) . ' remaining to spend this season.</h4><br>';
    echo '<div id="release">Are you sure you want to sign ' . $player . ' to a contract?  This will formally submit this offer.</div><br>';

    echo 'Sign ' . $player . ': ';

    echo '<input type="submit" name="offer" value="FEEDBACK"><br><br>';
    echo '</form>';

}

?>

<script>
function clearRow(row) {
    document.getElementById("year"+row).value = "0";
}
</script>