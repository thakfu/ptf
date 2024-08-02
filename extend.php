<?php

include 'header.php';

$teamService = teamService($_SESSION['TeamID']);
$team = $teamService[0];

echo '<h3><center>' . $team['FullName'] . ' have ' . $team['Extensions'] . ' extensions remaining!</center></h3>';

$playerService = playerService(0,$_GET['player'],0);
$player = $playerService[0];

if ($player[$year + 1] != 0) {
    echo 'This player is not eligible for a contract extension!';
} else {

    $faService = faPlayerService('extend', $_GET['player']);  //T.Greene

    //$faService = faPlayerService('extend', 'all');

    $day = 1;
    srand($_GET['player']);
    $rand = rand(5,25);
    //$rand = 0;

    srand($_GET['player'] * 2);
    $mult = rand(25,75);

    $players = array();
    $finaloffers = array();
    /*foreach ($faService as $fas) {
        array_push($players, $fas['PlayerID']);
        $demand = $fas['demandAmount'];
        $rand = rand(5,25);

        $randomValue = ($rand * $demand) / 100;
        if ($fas['Loyalty'] < 35) {
            echo 'I might wanna test FA';
        }
    } */
    //$allOffered = (array_unique($players));
    $allOffered = array(1,2,3,4,5,6);

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
    $topSal = $salaries[$player['Position'] . 'TOP'];

    foreach ($allOffered as $ao) {
        echo '<table border="1">';
        echo '<tr><th colspan="3">Data</th><th colspan="8">Demands</th><th colspan="7">Thoughts</th><th style="background-color:yellow">FINAL DEMAND</th></tr>';
        echo '<tr>
        <th>Years</th>
        <th>Player</th>
        <th>Team</th>
        <th style="background-color:yellow">Initial Demand</th>
        <th>Money</th>
        <th>Security</th>
        <th>Loyalty</th>
        <th>Winner</th>
        <th>Playing Time</th>
        <th>Close Home</th>
        <th>Market</th>
        <th>Security</th>
        <th>Loyalty</th>
        <th>Winning</th>
        <th>Playing Time</th>
        <th>Home</th>
        <th>Market</th>
        <th>Intangibles</th>
        <th style="background-color:yellow">BASED ON YOUR TEAM</th>
        </tr>';

        foreach ($faService as $fas) {

            echo '<h2>' . $fas['FullName'] . ' ' . $ao . ' Year Offer</h2>';

            $demand = $fas['demandAmount'];
    
            $randomValue = ($rand * $demand) / 100;

            // $secureMult - Multiplier given for a longer contract
            if ($ao == 1) {
                $length = 1;
                if ($fas['Security'] > 45) {
                    $secureMult = 0.8;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 1.2;
                }
            } elseif ($ao == 2) {
                $length = 2;
                if ($fas['Security'] > 45) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 1.1;
                }
            } elseif ($ao == 3) {
                $length = 3;
                if ($fas['Security'] > 45) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 1;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 1;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 1;
                }
            } elseif ($ao == 4) {
                $length = 4;
                if ($fas['Security'] > 45) {
                    $secureMult = 1;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 1;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 1;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 0.9;
                }
            } elseif ($ao == 5) {
                $length = 5;
                if ($fas['Security'] > 45) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 0.9;
                }
            } elseif ($ao == 6) {
                $length = 6;
                if ($fas['Security'] > 45) {
                    $secureMult = 1.2;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 0.8;
                }
            }

            // $prevBonus - Player's previous team gets a bonus
            if ($fas['previous'] == $fas['TeamID']) {
                if ($fas['Loyalty'] > 47) {
                    $prevBonus = 1.2;
                } elseif ($fas['Loyalty'] > 39 && $fas['Loyalty'] <= 47) {
                    $prevBonus = 1.15;
                } elseif ($fas['Loyalty'] > 32 && $fas['Loyalty'] <= 39) {
                    $prevBonus = 1.1;
                } elseif ($fas['Loyalty'] <= 32) {
                    $prevBonus = 1.05;
                }
            } else {
                $prevBonus = 1;
            }

            $wl1s = winlossService($team['TeamID'],$year-2); // CHANGE THIS TO -3 AFTER 1987
            $wl1 = $wl1s[0];
    
            $wl2s = winlossService($team['TeamID'],$year-1); // CHANGE THIS TO -2 AFTER 1987
            $wl2 = $wl2s[0];
    
            $wl3s = winlossService($team['TeamID'],$year-1);
            $wl3 = $wl3s[0];


            $winValY3 = $wl3['Wins'] * 1.5;
            $winValY2 = $wl2['Wins'];
            $winValY1 = $wl1['Wins'] * 0.5;
            // $winVal - Bonus given for a team's wins over the previous 3 seasons
            if ($fas['TeamID'] >= 19) {
                $winVal = 0.71;
            } else {
                $winVal = ($winValY1 + $winValY2 + $winValY3) / 21;
            }

            // $ptBonus - Will this player be the highest rated player at his position on his new team?
            $possibleStart = 1;
            $playerCheck = playerService($fas['TeamID'],0);
            foreach($playerCheck as $pc) {
                if ($pc['Position'] == $fas['Position']) {
                    if ($pc['Overall'] > $fas['Overall']) {
                        $possibleStart = 0;
                    }
                }
            }

            if ($possibleStart == 1) {
                if ($fas['PlayingTime'] >= 95) {
                    $ptBonus = 1.2;
                } elseif ($fas['PlayingTime'] >= 90 && $fas['PlayingTime'] < 95) {
                    $ptBonus = 1.15;
                } elseif ($fas['PlayingTime'] >= 80 && $fas['PlayingTime'] < 90) {
                    $ptBonus = 1.1;
                } elseif ($fas['PlayingTime'] < 80) {
                    $ptBonus = 1.05;
                }
            } else {
                if ($fas['PlayingTime'] >= 95) {
                    $ptBonus = 0.8;
                } elseif ($fas['PlayingTime'] >= 90 && $fas['PlayingTime'] < 95) {
                    $ptBonus = 0.85;
                } elseif ($fas['PlayingTime'] >= 80 && $fas['PlayingTime'] < 90) {
                    $ptBonus = 0.9;
                } elseif ($fas['PlayingTime'] < 80) {
                    $ptBonus = 0.95;
                }
            }
            if ($fas['Overall'] >= 80) {
                $ptBonus = 1;
            }

            // $homeBase - This needs to be done yet;
            $stateService = faPlayerService('college', $fas['PlayerID']);
            foreach ($stateService as $state) {
                if($fas['PlayerID'] == $state['PlayerID']) {
                    $homestate = $state['state'];
                    if ($fas['state'] == $state['state']) {
                        $home = 0.7;
                    } elseif (str_contains($state['bordering'], $fas['state'])) {
                        $home = 0.6;
                    } elseif (str_contains($state['within2'], $fas['state'])) {
                        $home = 0.55;
                    } else {
                         $home = 0.5;
                    }
                }
            }
            $homeBase = ($fas['CloseToHome']/100) + $home;

            // $marketBonus - Does the team's market match the player's want?
            if ($fas['market'] == 4) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 1.2;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 1.1;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 0.9;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 0.8;
                }
            } elseif ($fas['market'] == 3) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 1.1;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 0.9;
                }
            } elseif ($fas['market'] == 2) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 0.9;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 1.1;
                }
            } elseif ($fas['market'] == 1) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 0.8;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 0.9;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 1.1;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 1.2;
                }
            }

            // THE GRAND CALCULATION!!!!!!!!
            $totalOffer = $fas['amount1'] + $fas['amount2'] + $fas['amount3'] + $fas['amount4'] + $fas['amount5'] + $fas['amount6'];
            $m = $totalOffer / $length;
            $mm = $fas['Money'];
            $sm = $secureMult;
            $l = $prevBonus;
            if ($winVal < 0.8) {
                $winVal = 0.9;
            }
            $w = $winVal;
            $ww = $fas['Winning'];
            $p = $ptBonus;
            $h = $homeBase;
            $km = $marketBonus;
            //$part1 = (($mm - 70) / 100) + 1;
            $part1 = 1;

            $ww = 40;
            $winBonus = $w - ($ww / 1000);

            if ($winBonus > 1.2) {
                $winBonus = 1.2;
            }
            if ($winBonus < 0.9) {
                if ($fas['Winning'] < 50) {
                    $winBonus = 0.9;
                } else {
                    $winBonus = 0.85;
                }
            }

            // echo 'Wins: ' . $w . ' || Value: ' . $ww / 1000 . ' || FINAL: ' . $winBonus;


            //echo $fas['FullName'] . ' - ' . $demand .' * '. $part1 .' * '. $sm .' * '. $l .' * '. round($winBonus,2) .' * '. $p .' * '. $h .' * '. $km . '<br><br>';
            $final = $demand * $part1 * $sm * $l * round($winBonus,2) * $p * $h * $km;
            $afterRand = $final + $randomValue;


            // Post Offer Denial Check
            if ($afterRand < $demand) {
                $deny = 'Deny!';
            } else {
                $deny = '';
            }
            //if ($day == 7 && $demand <= 500000) {
            if ($day == 7) {
                $deny = '';
            }

            // Previous Team
            if ($fas['previous'] != 0) {
                $teamName = teamService($fas['previous']);
                foreach ($teamName as $tn) {
                    $prevTeam = $tn['City'] . ' ' . $tn['Mascot'];
                }
            } else {
                $prevTeam = '';
            }

            //30-50
            if ($fas['Security'] <= 35) {
                $sec = '1-2 Yrs';
            } elseif ($fas['Security'] <= 40 && $fas['Security'] > 35) {
                $sec = '1-4 Yrs';
            } elseif ($fas['Security'] <= 45 && $fas['Security'] > 40) {
                $sec = '3-6 Yrs';
            } elseif ($fas['Security'] >= 46) {
                $sec = '5-6 Yrs';
            }
            //30-50
            if ($fas['Loyalty'] <= 32) {
                $loy = 'D';
            } elseif ($fas['Loyalty'] <= 39 && $fas['Loyalty'] > 32) {
                $loy = 'C';
            } elseif ($fas['Loyalty'] <= 47 && $fas['Loyalty'] > 39) {
                $loy = 'B';
            } elseif ($fas['Loyalty'] >= 48) {
                $loy = 'A';
            }
            //30-70
            if ($fas['Winning'] <= 40) {
                $win = 'D';
            } elseif ($fas['Winning'] <= 50 && $fas['Winning'] > 40) {
                $win = 'C';
            } elseif ($fas['Winning'] <= 60 && $fas['Winning'] > 50) {
                $win = 'B';
            } elseif ($fas['Winning'] >= 61) {
                $win = 'A';
            }
            //70-100
            if ($fas['PlayingTime'] <= 75) {
                $pt = 'D';
            } elseif ($fas['PlayingTime'] <= 85 && $fas['PlayingTime'] > 75) {
                $pt = 'C';
            } elseif ($fas['PlayingTime'] <= 92 && $fas['PlayingTime'] > 85) {
                $pt = 'B';
            } elseif ($fas['PlayingTime'] >= 93) {
                $pt = 'A';
            }
            //30-50
            if ($fas['CloseToHome'] <= 32) {
                $cth = 'D';
            } elseif ($fas['CloseToHome'] <= 39 && $fas['CloseToHome'] > 32) {
                $cth = 'C';
            } elseif ($fas['CloseToHome'] <= 47 && $fas['CloseToHome'] > 39) {
                $cth = 'B';
            } elseif ($fas['CloseToHome'] >= 48) {
                $cth = 'A';
            }
            //30-70
            if ($fas['MarketSize'] <= 40) {
                $mar = 'Small';
            } elseif ($fas['MarketSize'] <= 50 && $fas['MarketSize'] > 40) {
                $mar = 'Sm-Mid';
            } elseif ($fas['MarketSize'] <= 60 && $fas['MarketSize'] > 50) {
                $mar = 'Lg-Mid';
            } elseif ($fas['MarketSize'] >= 61) {
                $mar = 'Large';
            }

            $needed = $final - $demand;
            $grandTotal = $demand - $needed + $randomValue;

            $failCount = 0;

            if ($secureMult == 1.2) {
                $thoughtS = 'Perfect!';
            } elseif ($secureMult >= 1.1) {
                $thoughtS = 'Good';
            } elseif ($secureMult >= 0.95 && $secureMult < 1.1) {
                $thoughtS = 'Acceptable';
            } elseif ($secureMult < 0.95 && $secureMult >= 0.9) { 
                $thoughtS = 'Not Great';
            } elseif ($secureMult <= 0.9) {
                $thoughtS = 'FAIL';
                $failCount++;
            }

            if ($prevBonus == 1.2) {
                $thoughtL = 'Perfect!';
            } elseif ($prevBonus >= 1.1) {
                $thoughtL = 'Good';
            } elseif ($prevBonus >= 0.95 && $prevBonus < 1.1) {
                $thoughtL = 'Acceptable';
            } elseif ($prevBonus < 0.95 && $prevBonus >= 0.9) { 
                $thoughtL = 'Not Great';
            } elseif ($prevBonus <= 0.9) {
                $thoughtL = 'FAIL';
                $failCount++;
            }

            if (round($winBonus,2) == 1.2) {
                $thoughtW = 'Perfect!';
            } elseif (round($winBonus,2) >= 1.1) {
                $thoughtW = 'Good';
            } elseif (round($winBonus,2) >= 0.95 && round($winBonus,2) < 1.1) {
                $thoughtW = 'Acceptable';
            } elseif (round($winBonus,2) < 0.95 && round($winBonus,2) >= 0.9) { 
                $thoughtW = 'Not Great';
            } elseif (round($winBonus,2) <= 0.9) {
                $thoughtW = 'FAIL';
                $failCount++;
            }

            if ($ptBonus == 1.2) {
                $thoughtP = 'Perfect!';
            } elseif ($ptBonus >= 1.1) {
                $thoughtP = 'Good';
            } elseif ($ptBonus >= 0.95 && $ptBonus < 1.1) {
                $thoughtP = 'Acceptable';
            } elseif ($ptBonus < 0.95 && $ptBonus >= 0.9) { 
                $thoughtP = 'Not Great';
            } elseif ($ptBonus <= 0.9) {
                $thoughtP = 'FAIL';
                $failCount++;
            }

            if ($homeBase == 1.2) {
                $thoughtH = 'Perfect!';
            } elseif ($homeBase >= 1.1) {
                $thoughtH = 'Good';
            } elseif ($homeBase >= 0.95 && $homeBase < 1.1) {
                $thoughtH = 'Acceptable';
            } elseif ($homeBase < 0.95 && $homeBase >= 0.9) { 
                $thoughtH = 'Not Great';
            } elseif ($homeBase <= 0.9) {
                if ($fas['CloseToHome'] <= 32) {
                    $thoughtH = 'Lucky, I don\'t care!';
                } else {
                    $thoughtH = 'FAIL';
                    $failCount++;
                }
            }

            if ($marketBonus == 1.2) {
                $thoughtM = 'Perfect!';
            } elseif ($marketBonus >= 1.1) {
                $thoughtM = 'Good';
            } elseif ($marketBonus >= 0.95 && $marketBonus < 1.1) {
                $thoughtM = 'Acceptable';
            } elseif ($marketBonus < 0.95 && $marketBonus >= 0.9) { 
                $thoughtM = 'Not Great';
            } elseif ($marketBonus <= 0.9) {
                $thoughtM = 'FAIL';
                $failCount++;
            }

            if ($rand == 5) {
                $thoughtI = 'No Issues!';
            } elseif ($rand <= 10) {
                $thoughtI = 'Nothing Too Major';
            } elseif ($rand <= 15 && $rand > 10) {
                $thoughtI = 'Gonna Need a Bit More';
            } elseif ($rand <= 20 && $rand > 15) { 
                $thoughtI = 'Premium to Resign';
            } elseif ($rand <= 25) {
                $thoughtI = 'I want ALOT more (FAIL)';
                $failCount++;
            }

                $offer = array();
                echo '<tr>
                <td>' . $ao . ' </td>
                <td>' . $fas['FullName'] . '</td>
                <td>' . $fas['TeamName'] . '</td>
                <td style="background-color:yellow">' . number_format($demand) . '</td>
                <td>' . $fas['Money'] . '</td>
                <td>' . $sec . '</td>
                <td>' . $loy . '</td>
                <td>' . $win . '</td>
                <td>' . $pt . '</td>
                <td>' . $cth . '</td>
                <td>' . $mar . '</td>
                <td>' . $thoughtS . '</td>
                <td>' . $thoughtL . '</td>
                <td>' . $thoughtW . '</td>
                <td>' . $thoughtP . '</td>
                <td>' . $thoughtH . '</td>
                <td>' . $thoughtM . '</td>
                <td>' . $thoughtI . '</td>
                <td style="background-color:yellow">' . number_format($grandTotal) . '</td>
                </tr></table>';

                $invalid1 = "false";
                $invalid2 = "false";
                $invalid3 = "false";
                $invalid4 = "false";
                $invalid5 = "false";
                $invalid6 = "false";
                $ext = 1;
                $num = ceil($grandTotal/10000) * 10000;
                $nodeal = 'false';
                echo '<p><center>Your team has failed <b>' . $failCount . '</b> categories...';
                if ($failCount == 3) {
                    echo ' this player doesnt believe your team to be a good fit for him anymore.';
                } elseif ($failCount == 2) {
                    echo ' this player will want more money than usual.';
                } elseif ($failCount == 1) {
                    echo ' if this player\'s loyalty is low, he will ask for more money.  If it is VERY LOW he will refuse to sign. ';
                } else {
                    echo ' this player thinks your team is a good fit.';
                }
                echo '</center></p>';
                if ($fas['Loyalty'] < 32 && ($thoughtM == 'FAIL' || $thoughtS == 'FAIL' || $thoughtL == 'FAIL' || $thoughtW == 'FAIL' || $thoughtP == 'FAIL' || $thoughtH == 'FAIL')) {
                    echo '<p><b><center>"This type of contract is NOT acceptable to me in any way!"</center></b></p>';
                    $nodeal = 'true';
                    if ($ao == 1) {
                        $invalid1 = "true";
                    } elseif ($ao == 2) {
                        $invalid2 = "true";
                    } elseif ($ao == 3) {
                        $invalid3 = "true";
                    } elseif ($ao == 4) {
                        $invalid4 = "true";
                    } elseif ($ao == 5) {
                        $invalid5 = "true";
                    } elseif ($ao == 6) {
                        $invalid6 = "true";
                    }
                } elseif ($failCount > 2) {
                    echo '<p><b><center>"This type of contract is NOT acceptable to me in any way!"</center></b></p>';
                    $nodeal = 'true';
                    if ($ao == 1) {
                        $invalid1 = "true";
                    } elseif ($ao == 2) {
                        $invalid2 = "true";
                    } elseif ($ao == 3) {
                        $invalid3 = "true";
                    } elseif ($ao == 4) {
                        $invalid4 = "true";
                    } elseif ($ao == 5) {
                        $invalid5 = "true";
                    } elseif ($ao == 6) {
                        $invalid6 = "true";
                    }
                } elseif (($fas['Loyalty'] < 39 && $failCount == 1) || $failCount == 2) {
                    $multiplier = $mult / 100;
                    $yroffer = $num + ($num * $multiplier);

                    if ($yroffer > $topSal) {
                        $yroffer = $topSal * 1.05;
                        $highest = 'In fact, I\'d like to be the highest paid player at my position!';
                    }

                    if ($yroffer < $player[$year]) {
                        echo 'hmmm';
                        $yroffer = $player[$year] * 1.05;
                    }
                    
                    echo '<p><b><center>"I might wanna test Free Agency this offseason, so I would need MORE THAN the usual amount to accept!"</center></b></p>';
                    echo '<p><center>"I will need an minimum offer of <b>$' . number_format($yroffer) . '</b> per year to a sign an extension of this length! ' . $highest .'"</center></p>';
                } else {
                    $yroffer = $num;

                    if ($yroffer > $topSal) {
                        $yroffer = $topSal * 1.05;
                        $highest = 'I\'d like to be the highest paid player at my position!';
                    }

                    if ($yroffer < $player[$year]) {
                        $yroffer = $player[$year] * 1.05;
                    }
                    echo '<p><center>"I will need an minimum offer of <b>$' . number_format($num) . '</b> per year to a sign an extension of this length! ' . $highest .'"</center></p>';
                }



                array_push($offer, $fas['OfferID'], $day, $fas['PlayerID'], $fas['TeamID'], $demand, $fas['amount1'], $fas['amount2'], $fas['amount3'], $fas['amount4'], $fas['amount5'], $fas['amount6'], $totalOffer,$rand,$final,$deny,$afterRand);
                array_push($finaloffers, $offer);

                if ($nodeal != 'true') {
                    echo '<form action="submit_release.php" method="POST">';
                    echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $_GET['player'] . '">'; 
                    echo '<table><tr><th></th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><td></td></tr>' .
                        '<tr><td>Offer:</td>
                        <td><input type="number" min="500000" max="'. $salaryCap - $totalnext .'" id="year1" name="year1" value="' . $yroffer . '"></td>';
                    echo '<input type="hidden" id="y1min" name="y1min" value="' . $yroffer . '">'; 
                    echo '<input type="hidden" id="y2min" name="y2min" value="999999999">'; 
                    echo '<input type="hidden" id="y3min" name="y3min" value="999999999">'; 
                    echo '<input type="hidden" id="y4min" name="y4min" value="999999999">'; 
                    echo '<input type="hidden" id="y5min" name="y5min" value="999999999">'; 
                    echo '<input type="hidden" id="y6min" name="y6min" value="999999999">'; 
                    echo '<input type="hidden" id="invalid1" name="invalid1" value="' . $invalid1 . '">'; 
                    echo '<input type="hidden" id="invalid2" name="invalid2" value="' . $invalid2 . '">'; 
                    echo '<input type="hidden" id="invalid3" name="invalid3" value="' . $invalid3 . '">'; 
                    echo '<input type="hidden" id="invalid4" name="invalid4" value="' . $invalid4 . '">'; 
                    echo '<input type="hidden" id="invalid5" name="invalid5" value="' . $invalid5 . '">'; 
                    echo '<input type="hidden" id="invalid6" name="invalid6" value="' . $invalid6 . '">'; 

                    if ($ao >= 2) {
                        echo '<td><input type="number" min="0" max="'. $salaryCap - $totalnext2 .'" id="year2" name="year2" value="' . $yroffer . '"></td>';
                        echo '<input type="hidden" id="y2min" name="y2min" value="' . $yroffer . '">'; 
                        $ext = 2;
                    } else {
                        echo '<td></td>';
                    }

                    if ($ao >= 3) {
                        echo '<td><input type="number" min="0" max="'. $salaryCap - $totalnext3 .'" id="year3" name="year3" value="' . $yroffer . '"></td>';
                        echo '<input type="hidden" id="y2min" name="y2min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y3min" name="y3min" value="' . $yroffer . '">'; 
                        $ext = 3;
                    } else {
                        echo '<td></td>';
                    }

                    if ($ao >= 4) {
                        echo '<td><input type="number" min="0" max="'. $salaryCap - $totalnext4 .'" id="year4" name="year4" value="' . $yroffer . '"></td>';
                        echo '<input type="hidden" id="y2min" name="y2min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y3min" name="y3min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y4min" name="y4min" value="' . $yroffer . '">'; 
                        $ext = 4;
                    } else {
                        echo '<td></td>';
                    }

                    if ($ao >= 5) {
                        echo '<td><input type="number" min="0" max="'. $salaryCap - $totalnext5 .'" id="year5" name="year5" value="' . $yroffer . '"></td>';
                        echo '<input type="hidden" id="y2min" name="y2min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y3min" name="y3min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y4min" name="y4min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y5min" name="y5min" value="' . $yroffer . '">'; 
                        $ext = 5;
                    } else {
                        echo '<td></td>';
                    }

                    if ($ao >= 6) {
                        echo '<td><input type="number" min="0" max="'. $salaryCap - $totalnext6 .'" id="year6" name="year6" value="' . $yroffer . '"></td>';
                        echo '<input type="hidden" id="y2min" name="y2min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y3min" name="y3min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y4min" name="y4min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y5min" name="y5min" value="' . $yroffer . '">'; 
                        echo '<input type="hidden" id="y6min" name="y6min" value="' . $yroffer . '">'; 
                        $ext = 6;
                    } else {
                        echo '<td></td>';
                    }

                    echo '<input type="hidden" id="extat" name="extat" value="' . $ext . '">'; 
                    echo '<td><input type="submit" name="extend" value="extend"></td></tr>';
                    echo '</form>';
                    echo '<tr><td>Cap Space:</td>
                    <td>' . number_format($salaryCap - $totalnext) . '</td>
                    <td>' . number_format($salaryCap - $totalnext2) . '</td>
                    <td>' . number_format($salaryCap - $totalnext3) . '</td>
                    <td>' . number_format($salaryCap - $totalnext4) . '</td>
                    <td>' . number_format($salaryCap - $totalnext5) . '</td>
                    <td>' . number_format($salaryCap - $totalnext6) . '</td><td></td></tr></table>';
                }
                
            
        }
        echo '<br>';
    }
}

if ($_GET['final'] == 'true')
{
    foreach ($finaloffers as $fo) {
        if($fo[14] == 'Deny!') {
            $offerresult = 0;
        } elseif($fo[14] == '') {
            $offerresult = 1;
        }
        //$offer = $connection->query("INSERT INTO ptf_fa_offers_accepted(OfferID, PlayerID, TeamID, year, amount1, amount2, amount3, amount4, amount5, amount6, total, demand, result, randomizer, valuation, day, final) VALUES ({$fo[0]},{$fo[2]},{$fo[3]},1986,{$fo[5]},{$fo[6]},{$fo[7]},{$fo[8]},{$fo[9]},{$fo[10]},{$fo[11]},{$fo[4]},{$offerresult},{$fo[12]},{$fo[13]},{$fo[1]},{$fo[15]})");
    }
}

?>