<?php

include 'adminheader.php';
if ($_SESSION['admin'] !== '2') {
    echo 'You are not authorized to be here.';
} else {

    //$faService = faPlayerService('extend', 1994);  //T.Greene
    $faService = faPlayerService('extend', 'all');

    $day = 1;

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

    foreach ($allOffered as $ao) {
        echo '<table border="1">';
        echo '<tr>
        <th>Years</th>
        <th>Player</th>
        <th>Team</th>
        <th>Last Team</th>
        <th>Demand</th>
        <th>Y1</th>
        <th>Y2</th>
        <th>Y3</th>
        <th>Y4</th>
        <th>Y5</th>
        <th>Y6</th>
        <th>Total</th>
        <th>Mon</th>
        <th>Sec</th>
        <th>Loy</th>
        <th>Win</th>
        <th>PT</th>
        <th>Hom</th>
        <th>Mar</th>
        <th>Sec. Mult</th>
        <th>Loy. Bonus</th>
        <th>Wins Bonus</th>
        <th>PT Bonus</th>
        <th>College</th>
        <th>Home</th>
        <th>Home Base</th>
        <th>Market Bonus</th>
        <th>Random!</th>
        <th>TOTAL</th>
        <th>Accepted?</th>
        <th style="background-color:yellow">w/ RANDOM</th>
        </tr>';

        foreach ($faService as $fas) {

            $demand = $fas['demandAmount'];
            $rand = rand(5,25);
    
            $randomValue = ($rand * $demand) / 100;
            if ($fas['Loyalty'] < 35) {
                echo 'I might wanna test FA';
            }

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

            // $winVal - Bonus given for a team's wins over the previous 3 seasons
            $winVal = (($fas['Wins'] * 1.5) + ($fas['Wins'] * 1) + ($fas['Wins'] * 0.5)) / 21;

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
            $w = $winVal;
            $ww = $fas['Winning'];
            $p = $ptBonus;
            $h = $homeBase;
            $km = $marketBonus;
            //$part1 = (($mm - 70) / 100) + 1;
            $part1 = 1;
            $winBonus = $w + ($ww / 1000);
            if ($winBonus > 1.2) {
                $winBonus = 1.2;
            }
            if ($winBonus < 0.8) {
                $winBonus = 0.8;
            }


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

                $offer = array();
                echo '<tr>
                <td>' . $ao . ' </td>
                <td>' . $fas['FullName'] . '</td>
                <td>' . $fas['TeamName'] . '</td>
                <td>' . $prevTeam . '</td>
                <td>' . number_format($demand) . '</td>
                <td>' . number_format($fas['amount1']) . '</td>
                <td>' . number_format($fas['amount2']) . '</td>
                <td>' . number_format($fas['amount3']) . '</td>
                <td>' . number_format($fas['amount4']) . '</td>
                <td>' . number_format($fas['amount5']) . '</td>
                <td>' . number_format($fas['amount6']) . '</td>
                <td>' . number_format($totalOffer) . '</td>
                <td>' . $fas['Money'] . '</td>
                <td>' . $sec . '</td>
                <td>' . $loy . '</td>
                <td>' . $win . '</td>
                <td>' . $pt . '</td>
                <td>' . $cth . '</td>
                <td>' . $mar . '</td>
                <td>' . $secureMult . '</td>
                <td>' . $prevBonus . '</td>
                <td>' . round($winBonus,2) . '</td>
                <td>' . $ptBonus . '</td>
                <td>' . $fas['College'] . '</td>
                <td>' . $homestate . '</td>
                <td>' . $homeBase . '</td>
                <td>' . $marketBonus . '</td>
                <td>' . $rand . '</td>
                <td>' . number_format($final) . '</td>
                <td>' . number_format($needed) . '</td>
                <td style="background-color:yellow">' . number_format($grandTotal) . '</td>
                </tr>';

                array_push($offer, $fas['OfferID'], $day, $fas['PlayerID'], $fas['TeamID'], $demand, $fas['amount1'], $fas['amount2'], $fas['amount3'], $fas['amount4'], $fas['amount5'], $fas['amount6'], $totalOffer,$rand,$final,$deny,$afterRand);
                array_push($finaloffers, $offer);
                
            
        }
        echo '</table><br>';
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