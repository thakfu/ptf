<?php

include 'header.php';
include 'calculateDemand.php';

//var_dump($_SESSION);

if ($_SESSION['admin'] != '2') {
    if ($freeagency == 0) {
        echo 'Free Agency is not currently active';
        exit;
    }
} else {
    //echo 'ADMIN ACCESS';
}

$day = $faday;

$playerService2 = playerService($_SESSION['TeamID'],0,2);
$posCount = array();
foreach ($positions as $pos) {
    $count = 0;
    foreach ($playerService2 as $ps2) {
        if ($ps2['Position'] == $pos && $ps2['SquadTeam'] == 0) {
            $count++;
        }
    }
    $posCount[$pos] .= $count;
    $totalCount = 0;
    foreach ($posCount as $pc) {
        $totalCount = $totalCount + $pc;
    }
}

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
foreach ($positionAverages as $key => $value) {
    $csvFile->fputcsv(array($key, $value));
}
$csvFile = null;

echo '<h2>'. $_SESSION['city'] . ' ' . $_SESSION['mascot'] . ' Postion Needs</h2>';

echo '<table><tr><th></th><th>SUM</th><th>QB</th><th>RB</th><th>FB</th><th>WR</th><th>TE</th><th>G</th><th>T</th><th>C</th><th>DT</th><th>DE</th>
<th>LB</th><th>CB</th><th>SS</th><th>FS</th><th>P</th><th>K</th></tr>';

echo '<tr><th>MINIMUM</th><th>--</th><th>'.$rosMin['QB'].'</th><th>'.$rosMin['RB'].'</th><th>'.$rosMin['FB'].'</th><th>'.$rosMin['WR'].'</th><th>'.$rosMin['TE'].'</th><th>'.$rosMin['G'].'</th><th>'.$rosMin['T'].'</th><th>'.$rosMin['C'].'</th><th>'.$rosMin['DT'].'</th><th>'.$rosMin['DE'].'</th>
<th>'.$rosMin['LB'].'</th><th>'.$rosMin['CB'].'</th><th>'.$rosMin['SS'].'</th><th>'.$rosMin['FS'].'</th><th>'.$rosMin['P'].'</th><th>'.$rosMin['K'].'</th></tr>';

echo '<tr style="background-color:white"><td>TEAM COUNT</td><td>'.$totalCount.'<td>'.$posCount['QB'].'</td><td>'.$posCount['RB'].'</td><td>'.$posCount['FB'].'</td><td>'.$posCount['WR'].'</td><td>'.$posCount['TE'].'</td><td>'.$posCount['G'].'</td><td>'.$posCount['T'].'</td><td>'.$posCount['C'].'</td><td>'.$posCount['DT'].'</td><td>'.$posCount['DE'].'</td>
<td>'.$posCount['LB'].'</td><td>'.$posCount['CB'].'</td><td>'.$posCount['SS'].'</td><td>'.$posCount['FS'].'</td><td>'.$posCount['P'].'</td><td>'.$posCount['K'].'</td></tr>';

echo '<tr><th>MAXIMUM</th><th>53</th><th>'.$rosMax['QB'].'</th><th>'.$rosMax['RB'].'</th><th>'.$rosMax['FB'].'</th><th>'.$rosMax['WR'].'</th><th>'.$rosMax['TE'].'</th><th>'.$rosMax['G'].'</th><th>'.$rosMax['T'].'</th><th>'.$rosMax['C'].'</th><th>'.$rosMax['DT'].'</th><th>'.$rosMax['DE'].'</th>
<th>'.$rosMax['LB'].'</th><th>'.$rosMax['CB'].'</th><th>'.$rosMax['SS'].'</th><th>'.$rosMax['FS'].'</th><th>'.$rosMax['P'].'</th><th>'.$rosMax['K'].'</th></tr></table>';

$offerService = faPlayerService('player', 0);

usort($offerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);

$faService = faPlayerService('offer', $_SESSION['TeamID']);

$removePlayers = array();
$offers = array();
foreach ($faService as $fas) {
    array_push($offers, $fas);
}
//var_dump($offers);

echo '<h2>Cap Space</h2>';

echo '<table><tr><th></th><th>' . $year . '</th><th>' . $year + 1 . '</th><th>' . $year + 2 . '</th><th>' . $year + 3 . '</th><th>' . $year + 4 . '</th><th>' . $year + 5 . '</th></tr>';
echo '<tr><th></th><th>' . number_format($salaryCap) . '</th><th>' . number_format($salaryCap) . '</th><th>' . number_format($salaryCap) . '</th><th>' . number_format($salaryCap) . '</th><th>' . number_format($salaryCap) . '</th><th>' . number_format($salaryCap) . '</th></tr>';

echo '<tr><td>Total Salary</td><td>'.number_format($total).'</td><td>'.number_format($totalnext).'</td><td>'.number_format($totalnext2).'</td><td>'.number_format($totalnext3).'</td><td>'.number_format($totalnext4).'</td><td>'.number_format($totalnext5).'</td></tr>';

echo '<tr style="background-color:white"><td>Available Cap Space</td><td>'.number_format($salaryCap-$total).'</td><td>'.number_format($salaryCap-$totalnext).'</td><td>'.number_format($salaryCap-$totalnext2).'</td><td>'.number_format($salaryCap-$totalnext3).'</td><td>'.number_format($salaryCap-$totalnext4).'</td><td>'.number_format($salaryCap-$totalnext5).'</td></tr></table>';

    $sum1 = intval(str_replace(",","",$total));
    $sum2 = intval(str_replace(",","",$totalnext));
    $sum3 = intval(str_replace(",","",$totalnext2));
    $sum4 = intval(str_replace(",","",$totalnext3));
    $sum5 = intval(str_replace(",","",$totalnext4));
    $sum6 = intval(str_replace(",","",$totalnext5));
echo '<h2>Offers Made</h2>';
echo '<table><tr><th>Name</th><th>Position</th><th>' . $year . '</th><th>' . $year + 1 . '</th><th>' . $year + 2 . '</th><th>' . $year + 3 . '</th><th>' . $year + 4 . '</th><th>' . $year + 5 . '</th><th>Total</th><th>Demand</th><th>Change</th></tr>';
echo '<tr style="background-color:white"><th>Team Total</th><th></th><th>' . number_format($total) . '</th><th>' . number_format($totalnext) . '</th><th>' . number_format($totalnext2) . '</th><th>' . number_format($totalnext3) . '</th><th>' . number_format($totalnext4) . '</th><th>' . number_format($totalnext5) . '</th><th></th><th></th><th></th></tr>';
foreach ($offers as $offer) {
    $sum1 = $sum1 + $offer['amount1'];
    $sum2 = $sum2 + $offer['amount2'];
    $sum3 = $sum3 + $offer['amount3'];
    $sum4 = $sum4 + $offer['amount4'];
    $sum5 = $sum5 + $offer['amount5'];
    $sum6 = $sum6 + $offer['amount6'];
    array_push($removePlayers, $offer['PlayerID']);
    echo '<tr><td>' . $offer['FirstName'] . ' ' . $offer['LastName'] . ' </td><td>'. $offer['Position'] .'</td><td>' . number_format($offer['amount1']) . '</td><td>' . number_format($offer['amount2']) . '</td><td>' . number_format($offer['amount3']) . '</td><td>' . number_format($offer['amount4']) . '</td><td>' . number_format($offer['amount5']) . '</td><td>' . number_format($offer['amount6']) . '</td><td>' . number_format($offer['total']) . ' </td><td>' . number_format(roundSalary($offer['demand']))  . '</td><td><a href="negotiate.php?PlayerID='. $offer['PlayerID'] . '&Demand=revoke">Revoke / Edit</a></td></tr>';
}

echo '<tr style="background-color:white"><th>After Signing</th><th></th><th>' . number_format($sum1) . '</th><th>' . number_format($sum2) . '</th><th>' . number_format($sum3) . '</th><th>' . number_format($sum4) . '</th><th>' . number_format($sum5) . '</th><th>' . number_format($sum6) . '</th><th></th><th></th><th></th></tr>';
$_SESSION['currentCap'] = $sum1;
echo '</table>';


foreach ($removePlayers as $rp) {
    foreach ($offerService as $key => $player) {
        if ($player['PlayerID'] == $rp) {
            unset($offerService[$key]);
        }
    }
}
echo '<h2>Rating Range - LOW to HIGH</h2>';
echo '<table><tr><th>Money</th><th>Security</th><th>Loyalty</th><th>Winning</th><th>Playing Time</th><th>Home</th><th>Market</th></tr>';
echo '<tr><th>70-100</th><th>30-50</th><th>30-50</th><th>30-70</th><th>70-100</th><th>30-50</th><th>30-70</th></tr></table>';

// ----------------------------------------------------------------------------------------------------------- //

echo '<h2>The Following Players on your team are eligible for free agency</h2>';

echo '<table>
        <tr><th colspan="4">Player</th>
            <th>Money</th>
            <th>Security</th>
            <th>Loyalty</th>
            <th colspan="2">Winning</th>
            <th>PT</th>
            <th colspan="3">Close To Home</th>
            <th colspan="2">Market Size</th>
            <th colspan="3">Make Offer</th></tr>
        <tr><th>Name (Pos)</th><th>Age</th><th>' . $year . ' Salary</th><th>Overall</th>
            <th style="background-color:#B0ECA0">Money</th>
            <th style="background-color:#A0CCEC">Security</th>
            <th style="background-color:#E5B5F3">Loyalty</th>
            <th style="background-color:#F0F388">Winning</th>
            <th style="background-color:#F0F388">Win Value</th>
            <th style="background-color:#F6ACC8">Playing Time</th>
            <th style="background-color:#F6C272">Home</th>
            <th style="background-color:#F6C272">College</th>
            <th style="background-color:#F6C272">Loc Value</th>
            <th style="background-color:#CFCECE">Market</th>
            <th style="background-color:#CFCECE">Your Market</th>
            <th>Initial Demand</th><th>Detailed Demand</th><th>Offer Contract</th></tr>';
foreach ($offerService as $player) {
    if ($player['RetiredSeason'] == 0 && $player['previous'] == $_SESSION['TeamID']) {

        $calc = calculateDemand($year, $day, $player, $_POST, $_SESSION['TeamID'], $_SESSION['state']);

        echo '<tr><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">'. $player['FirstName'] . ' ' . $player['LastName'] .' (' . $player['Position'] . ')</td><td>' . $player['Age'] . '<td>' . number_format($calc['previous']) . '</td><td>' . $player['Overall'] . '</td>
        <td style="background-color:#B0ECA0">' . $player['Money'] . '</td>
        <td style="background-color:#A0CCEC">' . $calc['sec'] . '</td>
        <td style="background-color:#E5B5F3">' . $calc['loy'] . '</td>
        <td style="background-color:#F0F388">' . $calc['win'] . '</td>
        <td style="background-color:#F0F388">' . number_format($calc['winVal'],2) . '</td>
        <td style="background-color:#F6ACC8">' . $calc['pt'] . '</td>
        <td style="background-color:#F6C272">' . $calc['cth'] . '</td>
        <td style="background-color:#F6C272">' . $player['College'] . '(' . $calc['homestate'] . ')</td>
        <td style="background-color:#F6C272">' . $calc['homebase'] . '(' . $calc['state'] . ')</td>
        <td style="background-color:#CFCECE">' . $calc['mar']. '</td>
        <td style="background-color:#CFCECE">' . $calc['market']. '</td>
        <td>' . $calc['string'] . '</td><td>' . $calc['final'] . '</td>';
        echo '<td><a href="negotiate.php?Tag=y&PlayerID='. $player['PlayerID'] . '&Demand=' . $calc['finalAmt']  . '">Offer Me a Deal!</a></td></tr>';
    }
}
echo '</table><br>';

// ----------------------------------------------------------------------------------------------------------- //

echo '<h2>The Following Players NOT on your team are eligible for free agency</h2>';

foreach ($positions as $position) {
    echo '<h3><center>' . $position . '</center></h3><br>';
    echo '<center><b>HIGHEST SALARY: ' . number_format(floor($salaries[$position . 'TOP']/50000) * 50000) . ' ----- TOP 5 AVERAGE: ' . number_format(floor($salaries[$position . '5']/50000) * 50000) . ' ----- AVERAGE: ' . number_format(floor($salaries[$position . 'ALL']/50000) * 50000) . '</b></center><br>';
    echo '<table>
            <tr><th colspan="4">Player</th>
            <th>Money</th>
            <th>Security</th>
            <th>Loyalty</th>
            <th colspan="2">Winning</th>
            <th>PT</th>
            <th colspan="3">Close To Home</th>
            <th colspan="2">Market Size</th>
            <th colspan="3">Make Offer</th></tr>
    <tr><th>Name</th><th>Age</th><th>' . $year . ' Salary</th><th>Overall</th>
    <th style="background-color:#B0ECA0">Money</th>
    <th style="background-color:#A0CCEC">Security</th>
    <th style="background-color:#E5B5F3">Loyalty</th>
    <th style="background-color:#F0F388">Winning</th>
    <th style="background-color:#F0F388">Win Value</th>
    <th style="background-color:#F6ACC8">Playing Time</th>
    <th style="background-color:#F6C272">Home</th>
    <th style="background-color:#F6C272">College</th>
    <th style="background-color:#F6C272">Loc Value</th>
    <th style="background-color:#CFCECE">Market</th>
    <th style="background-color:#CFCECE">Your Market</th>
    <th>Initial Demand</th><th>Detailed Demand</th><th>Offer Contract</th></tr>';
    foreach ($offerService as $player) {
        if ($player['RetiredSeason'] == 0 && $player['previous'] != $_SESSION['TeamID']) {
            if ($player['Position'] == $position) {

                $calc = calculateDemand($year, $day, $player, $_POST, $_SESSION['TeamID'], $_SESSION['state']);

                echo '<tr><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">'. $player['FirstName'] . ' ' . $player['LastName'] .' (' . $player['Position'] . ')</td><td>' . $player['Age'] . '<td>' . number_format($calc['previous']) . '</td><td>' . $player['Overall'] . '</td>
                <td style="background-color:#B0ECA0">' . $player['Money'] . '</td>
                <td style="background-color:#A0CCEC">' . $calc['sec'] . '</td>
                <td style="background-color:#E5B5F3">' . $calc['loy'] . '</td>
                <td style="background-color:#F0F388">' . $calc['win'] . '</td>
                <td style="background-color:#F0F388">' . number_format($calc['winVal'],2) . '</td>
                <td style="background-color:#F6ACC8">' . $calc['pt'] . '</td>
                <td style="background-color:#F6C272">' . $calc['cth'] . '</td>
                <td style="background-color:#F6C272">' . $player['College'] . '(' . $calc['homestate'] . ')</td>
                <td style="background-color:#F6C272">' . $calc['homebase'] . '(' . $calc['state'] . ')</td>
                <td style="background-color:#CFCECE">' . $calc['mar']. '</td>
                <td style="background-color:#CFCECE">' . $calc['market']. '</td>
                <td>' . $calc['string'] . '</td><td>' . $calc['final'] . '</td>';
                echo '<td><a href="negotiate.php?Tag=n&PlayerID='. $player['PlayerID'] . '&Demand=' . $calc['finalAmt']  . '">Offer Me a Deal!</a></td></tr>';
            }
        }
    }
    echo '</table><br>';
}

?>