<?php

include 'adminheader.php';
include '../calculateDemand.php';

if ($_SESSION['admin'] !== '2') {
    echo 'You are not authorized to be here.';
} else {

    $faService = faPlayerService('offer', 'all');
    $day = $faday;

    usort($faService, fn($a, $b) => $a['LastName'] <=> $b['LastName']);

    $players = array();
    $finaloffers = array();
    foreach ($faService as $fas) {
        array_push($players, $fas['PlayerID']);
    }
    $allOffered = (array_unique($players));

    foreach ($allOffered as $ao) {

        echo '<table border="1">';
        echo '<tr>
        <th>Day</th>
        <th>Player</th>
        <th>ID</th>
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

            $data = array();
            $data['year1'] = $fas['amount1'];
            $data['year2'] = $fas['amount2'];
            $data['year3'] = $fas['amount3'];
            $data['year4'] = $fas['amount4'];
            $data['year5'] = $fas['amount5'];
            $data['year6'] = $fas['amount6'];


            $calc = calculateDemand($year, $day, $fas, $data, $fas['TeamID'], $fas['state']);

           // $liked = '<b>What I like about this offer:</b> ';
           // $disliked = '<b>What I DON\'T like about this offer:</b> ';

            // $percent - Daily decrease amount
           /* if($fas['Money'] == 100) {
                $percent = 0;
            } elseif($fas['Money'] >= 90 && $fas['Money'] < 100) {
                $percent = 100 - $fas['Money'];
            } else {
                $percent = 10;
            }

            // $secureMult - Multiplier given for a longer contract
            if ($fas['amount2'] == 0) {
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
            } elseif ($fas['amount3'] == 0) {
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
            } elseif ($fas['amount4'] == 0) {
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
            } elseif ($fas['amount5'] == 0) {
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
            } elseif ($fas['amount6'] == 0) {
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
            } elseif ($fas['amount6'] > 0) {
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
            $stateService = faPlayerService('college', '0');
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
            } */

             // THE GRAND CALCULATION!!!!!!!!
             $totalOffer = $fas['amount1'] + $fas['amount2'] + $fas['amount3'] + $fas['amount4'] + $fas['amount5'] + $fas['amount6'];
             $avgOffer = $totalOffer / $calc['length'];
             $mm = $fas['Money'];
             $sm = $calc['secureMult'];
             $l = $calc['prevBonus'];
             $w = $calc['winVal'];
             $ww = $fas['Winning'];
             $p = $calc['ptBonus'];
             $h = $calc['homeBase'];
             $km = $calc['marketBonus'];
             //$part1 = (($mm - 70) / 100) + 1;
             $wb = $calc['winBonus'];
             $demand = $calc['demand'];
             $rand = rand(-5,15);
 
             //echo $final .'='. $m .'*'. $part1 .'*'. $sm .'*'. $l .'*'. round($winBonus,2) .'*'. $p .'*'. $h .'*'. $km;
             //$final = $m * $part1 * $sm * $l * round($winBonus,2) * $p * $h * $km;
 
             // Adjusted Demand
                 $randomValue = $avgOffer * (1 + $rand / 100) - $avgOffer;
                 $sAdj = $avgOffer * $sm - $avgOffer;
                 $lAdj = $avgOffer * $l - $avgOffer;
                 $wAdj = $avgOffer * $wb - $avgOffer;
                 $pAdj = $avgOffer * $p - $avgOffer;
                 $hAdj = $avgOffer * $h - $avgOffer;
                 $mAdj = $avgOffer * $km - $avgOffer;
                 $final = $avgOffer + $sAdj + $lAdj + $wAdj + $pAdj + $hAdj + $mAdj;
                 $final = floor($final/50000) * 50000;
                 $afterRand = $final + $randomValue;
 
 
             // Post Offer Denial Check
             if ($afterRand < $demand) {
                 $deny = 'Deny!';
                 $color = 'style="background-color:#F6ACC8"';
             } else {
                 $deny = 'Accept!';
                 $color = 'style="background-color:#B0ECA0"';
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

             
           /* // THE GRAND CALCULATION!!!!!!!!
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
            $part1 = (($mm - 70) / 100) + 1;
            $winBonus = $w + ($ww / 1000);
            if ($winBonus > 1.2) {
                $winBonus = 1.2;
            }
            if ($winBonus < 0.9) {
                $winBonus = 0.9;
            }
            $rand = rand(-5,15);
            $randomValue = ($rand * $m) / 100;

            $final = $m * $part1 * $sm * $l * round($winBonus,2) * $p * $h * $km;
            $afterRand = $final + $randomValue;

            // Adjusted Demand
            if ($day > 1 && $day < 7) {
                $demandAdj = ($percent / 100) * ($day - 1);
                $demand = $fas['demandAmount'] * (1 - $demandAdj);
            } elseif ($day == 7) {
                if ($fas['Money'] >= 90 && $fas['Money'] < 100) {
                    $demand = ($fas['demandAmount'] * 0.5);
                } elseif ($fas['Money'] == 100) {
                    $demand = ($fas['demandAmount'] * 0.75);
                } else {
                    $demand = 250000;
                }
            } else {
                $demandAdj = 0;
                $demand = $fas['demandAmount'];
            }


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
            }  */

            if ($fas['PlayerID'] == $ao) {
                $offer = array();
                echo '<tr>
                <td>' . $day . ' </td>
                <td>' . $fas['FullName'] . '</td>
                <td>' . $fas['PlayerID'] . '</td>
                <td>' . $fas['TeamName'] . '</td>
                <td>' . $prevTeam . '</td>
                <td style="background-color:#F6C272"><b>' . number_format($demand) . '</b></td>
                <td>' . number_format($fas['amount1']) . '</td>
                <td>' . number_format($fas['amount2']) . '</td>
                <td>' . number_format($fas['amount3']) . '</td>
                <td>' . number_format($fas['amount4']) . '</td>
                <td>' . number_format($fas['amount5']) . '</td>
                <td>' . number_format($fas['amount6']) . '</td>
                <td>' . number_format($totalOffer) . '</td>
                <td>' . $fas['Money'] . '</td>
                <td>' . $calc['sec'] . '</td>
                <td>' . $calc['loy'] . '</td>
                <td>' . $calc['win'] . '</td>
                <td>' . $calc['pt'] . '</td>
                <td>' . $calc['cth'] . '</td>
                <td>' . $calc['mar'] . '</td>
                <td><b>' . $sm . '</b></td>
                <td><b>' . $l . '</b></td>
                <td><b>' . $wb . '</b></td>
                <td><b>' . $p . '</b></td>
                <td>' . $fas['College'] . '</td>
                <td>' . $fas['state'] . '</td>
                <td><b>' . $h . '</b></td>
                <td><b>' . $km . '</b></td>
                <td><b>' . $rand . '</b></td>';
                echo '<td ' . $color . '><b>' . number_format($final) . '</b></td>
                <td ' . $color . '><b>' . $deny . '</b></td>
                <td style="background-color:yellow">' . number_format($afterRand) . '</td>
                </tr>';

                $value = $demand / $final;

            /*if ($fas['PlayerID'] == $ao) {
                $offer = array();
                echo '<tr>
                <td>' . $day . ' </td>
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
                <td><b>' . $secureMult . '</b></td>
                <td><b>' . $prevBonus . '</b></td>
                <td><b>' . round($winBonus,2) . '</b></td>
                <td><b>' . $ptBonus . '</b></td>
                <td>' . $fas['College'] . '</td>
                <td>' . $homestate . '</td>
                <td><b>' . $homeBase . '</b></td>
                <td><b>' . $marketBonus . '</b></td>
                <td><b>' . $rand . '</b></td>
                <td>' . number_format($final) . '</td>
                <td>' . $deny . '</td>
                <td style="background-color:yellow">' . number_format($afterRand) . '</td>
                </tr>';

                $value = $demand / $afterRand;

               /* if ($value < 0.75) {
                    $comment = 'I would have taken ALOT less money!  Thanks for the SWEET deal!!';
                } elseif ($value < 0.95 && $value >= 0.75) {
                    $comment = 'I would have taken less money, but I do appreciate the generous offer!';
                } elseif ($value < 1 && $value >= 0.95) {
                    $comment = 'It was pretty close, but I found it acceptable!';
                } elseif ($value >= 1 && $value <= 1.05) {
                    $comment = 'A little bit more money would have gotten it done.';
                } elseif ($value > 1.05 && $value <= 1.25) {
                    $comment = 'We were pretty far apart on this offer.';
                } else {
                    $comment = 'This offer was pretty insulting, I\'m not gonna lie.';
                }

                if ($secureMult > 1) {
                    $liked .= 'Length of Contract, ';
                } elseif ($secureMult < 1) {
                    $disliked .= 'Length of Contract, ';
                }

                if ($prevBonus > 1) {
                    $liked .= 'I\'m loyal to my team, ';
                }

                if (round($winBonus,2) > 1) {
                    $liked .= 'Your Team is Good, ';
                } elseif (round($winBonus,2) < 1) {
                    $disliked .= 'Your Team isn\'t Very Good, ';
                }

                if ($ptBonus > 1) {
                    $liked .= 'Opportunity for Playing Time, ';
                } elseif ($ptBonus < 1) {
                    $disliked .= 'No Opportunity for Playing Time, ';
                }

                if ($homeBase > 1) {
                    $liked .= 'Close to Home, ';
                } elseif ($homeBase < 1) {
                    $disliked .= 'Too Far From Home, ';
                }

                if ($marketBonus > 1) {
                    $liked .= 'Market Size, ';
                } elseif ($marketBonus < 1) {
                    $disliked .= 'Market Size, ';
                }

                if ($rand >= 7) {
                    $liked .= 'I can\'t Put My Finger on it, But I Like it! ';
                    $disliked .= 'Nothing else to add.';
                } elseif ($rand < 2) {
                    $liked .= 'Nothing else to add.';
                    $disliked .= 'Something Seems Off, I Don\'t Know What... ';
                } else {
                    $liked .= 'Nothing else to add.';
                    $disliked .= 'Nothing else to add.';
                }



                echo '<tr><td colspan=31><b>FEEDBACK: </b>' . $comment . '<br> ' . $liked . ' <br> ' . $disliked . '</td></tr>'; */
                array_push($offer, $fas['OfferID'], $day, $fas['PlayerID'], $fas['TeamID'], $demand, $fas['amount1'], $fas['amount2'], $fas['amount3'], $fas['amount4'], $fas['amount5'], $fas['amount6'], $totalOffer,$rand,$final,$deny,$afterRand);
                array_push($finaloffers, $offer);

                //var_dump($finaloffers);

               // $comment = '';
               // $liked = '';
               // $disliked = '';
                
            }
        }
        echo '</table><br>';
    }
}

echo 'end';

if ($_GET['final'] == 'true')
{
    foreach ($finaloffers as $fo) {
        if($fo[14] == 'Deny!') {
            $offerresult = 0;
        } else {
            $offerresult = 1;
        }
        //echo 'INSERT INTO ptf_fa_offers_accepted(OfferID, PlayerID, TeamID, year, amount1, amount2, amount3, amount4, amount5, amount6, total, demand, result, randomizer, valuation, day, final) VALUES (' . $fo[0] . ',' . $fo[2] . ',' . $fo[3] . ',1987,' . $fo[5] . ',' . $fo[6] . ',' . $fo[7] . ',' . $fo[8] . ',' . $fo[9] . ',' . $fo[10] . ',' . $fo[11] . ',' . $fo[4] . ',' . $offerresult . ',' . $fo[12] . ',' . $fo[13] . ',' . $fo[1] . ',' . $fo[15] . ')';
        $offer = $connection->query("INSERT INTO ptf_fa_offers_accepted(OfferID, PlayerID, TeamID, year, amount1, amount2, amount3, amount4, amount5, amount6, total, demand, result, randomizer, valuation, day, final) VALUES ({$fo[0]},{$fo[2]},{$fo[3]},1987,{$fo[5]},{$fo[6]},{$fo[7]},{$fo[8]},{$fo[9]},{$fo[10]},{$fo[11]},{$fo[4]},{$offerresult},{$fo[12]},{$fo[13]},{$fo[1]},{$fo[15]})");
    }
}

?>