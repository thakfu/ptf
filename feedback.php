<?php

include 'header.php';
include 'calculateDemand.php';

if ($_POST['tag'] == 'tag') {
    echo '<h4>Franchise Tag Processed!   You Bought em!</h4>';
    $roster = $connection->query("UPDATE ptf_teams_data SET FranchiseTag = 0 WHERE TeamID = " . $_POST['TeamID']);
    $roster = $connection->query("UPDATE ptf_players SET TeamID = '{$_POST['TeamID']}', Team = '{$_POST['Abbreviation']}' WHERE PlayerID = " . $_POST['PlayerID']);
    $roster = $connection->query("UPDATE ptf_players_salaries SET `" . $year . "` = '" . $_POST['franchise'] . "', `" . $year + 1 . "` = '" . $_POST['franchise'] . "' WHERE PlayerID = " . $_POST['PlayerID']);
    $log = $connection->query("INSERT INTO ptf_transactions (PlayerID, TeamID_Old, TeamID_New, type, date) VALUES ({$_POST['PlayerID']},0, {$_POST['TeamID']}, 'tag', NOW())");
    transactionHook($_POST['Player'], $_POST['TeamID'], $_POST['Pos'], 'tag');
}

    echo '<h2>OFFER SUMMARY</h2>';

    $faService = playerService($_SESSION['TeamID'],$_POST['PlayerID']);
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
        <th>Demand</th>
        <th>Y1</th>
        <th>Y2</th>
        <th>Y3</th>
        <th>Y4</th>
        <th>Y5</th>
        <th>Y6</th>
        <th>Total</th>
        <th>Money</th>
        <th>Security</th>
        <th>Loyalty</th>
        <th>Winning</th>
        <th>Playing Time</th>
        <th>Close Home</th>
        <th>Market Size</th>';
        /*<th>Sec. Mult</th>
        <th>Loy. Bonus</th>
        <th>Wins Bonus</th>
        <th>PT Bonus</th>
        <th>College</th>
        <th>Home</th>
        <th>Home Base</th>
        <th>Market Bonus</th>
        <th>Random!</th> */
        echo '<th>VALUATION</th>
        <th>Accepted?</th>
        </tr>';

        if ($_POST['year1'] == '') {
            $_POST['year1'] = 0;
        }

        if ($_POST['year2'] == '') {
            $_POST['year2'] = 0;
        }

        if ($_POST['year3'] == '') {
            $_POST['year3'] = 0;
        }

        if ($_POST['year4'] == '') {
            $_POST['year4'] = 0;
        }

        if ($_POST['year5'] == '') {
            $_POST['year5'] = 0;
        }

        if ($_POST['year6'] == '') {
            $_POST['year6'] = 0;
        }

        foreach ($faService as $fas) {
            $liked = '<b>What I like about this offer:</b> ';
            $disliked = '<b>What I DON\'T like about this offer:</b> ';

            $calc = calculateDemand($year, $day, $fas, $_POST, $_SESSION['TeamID'], $_SESSION['state']);

            // THE GRAND CALCULATION!!!!!!!!
            $totalOffer = $_POST['year1'] + $_POST['year2'] + $_POST['year3'] + $_POST['year4'] + $_POST['year5'] + $_POST['year6'];
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
            if ($final < $demand) {
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


            if ($fas['PlayerID'] == $ao) {
                $offer = array();
                echo '<tr>
                <td>' . $day . ' </td>
                <td>' . $fas['FullName'] . '</td>
                <td style="background-color:#F6C272"><b>' . number_format($demand) . '</b></td>
                <td>' . number_format($_POST['year1']) . '</td>
                <td>' . number_format($_POST['year2']) . '</td>
                <td>' . number_format($_POST['year3']) . '</td>
                <td>' . number_format($_POST['year4']) . '</td>
                <td>' . number_format($_POST['year5']) . '</td>
                <td>' . number_format($_POST['year6']) . '</td>
                <td>' . number_format($totalOffer) . '</td>
                <td>' . $fas['Money'] . '</td>
                <td>' . $calc['sec'] . '</td>
                <td>' . $calc['loy'] . '</td>
                <td>' . $calc['win'] . '</td>
                <td>' . $calc['pt'] . '</td>
                <td>' . $calc['cth'] . '</td>
                <td>' . $calc['mar'] . '</td>';
                /*<td><b>' . $secureMult . '</b></td>
                <td><b>' . $prevBonus . '</b></td>
                <td><b>' . round($winBonus,2) . '</b></td>
                <td><b>' . $ptBonus . '</b></td>
                <td>' . $fas['College'] . '</td>
                <td>' . $homestate . '</td>
                <td><b>' . $homeBase . '</b></td>
                <td><b>' . $marketBonus . '</b></td>
                <td><b>' . $rand . '</b></td> */
                echo '<td ' . $color . '><b>' . number_format($final) . '</b></td>
                <td ' . $color . '><b>' . $deny . '</b></td>
                </tr>';

                echo '<tr><td></td><td colspan="7"></td><td style="background-color:#B0ECA0"><b>Deal Value:</b></td>' . 
                '<td style="background-color:#B0ECA0">' . number_format($avgOffer) . '</td>' . 
                '<td><b>Bonuses:</b></td>' . 
                '<td>' . number_format($sAdj) . '</td>' . 
                '<td>' . number_format($lAdj) . '</td>' . 
                '<td>' . number_format($wAdj) . '</td>' . 
                '<td>' . number_format($pAdj) . '</td>' . 
                '<td>' . number_format($hAdj) . '</td>' . 
                '<td>' . number_format($mAdj) . '</td>' . 
                '<td ' . $color . '><i>Rounded</i></td><td></td></tr>';

                $value = $demand / $final;

                if ($value < 0.75) {
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

                if ($calc['secureMult'] >= 1) {
                    $liked .= 'Length of Contract, ';
                } elseif ($calc['secureMult'] < 1) {
                    $disliked .= 'Length of Contract, ';
                }

                if ($calc['prevBonus'] > 1) {
                    $liked .= 'I\'m loyal to this team, ';
                }

                if (round($wb,2) >= 1) {
                    $liked .= 'Your Team is Good, ';
                } elseif (round($wb,2) < 1) {
                    $disliked .= 'Your Team isn\'t Very Good, ';
                }

                if ($calc['ptBonus'] >= 1) {
                    $liked .= 'Opportunity for Playing Time, ';
                } elseif ($calc['ptBonus'] < 1) {
                    $disliked .= 'No Opportunity for Playing Time, ';
                }

                if ($calc['homeBase'] >= 1) {
                    $liked .= 'Close to Home, ';
                } elseif ($calc['homeBase'] < 1) {
                    $disliked .= 'Too Far From Home, ';
                }

                if ($calc['marketBonus'] >= 1) {
                    $liked .= 'Market Size, ';
                } elseif ($calc['marketBonus'] < 1) {
                    $disliked .= 'Market Size, ';
                }

                $liked .= 'Nothing else to add.';
                $disliked .= 'Nothing else to add.';


                $playerInfo = $connection->query("SELECT FirstName, LastName, Position, AltPosition FROM ptf_players WHERE PlayerID = " . $fas['PlayerID']);
                $playerInf = $playerInfo->fetch_assoc();
                $player = $playerInf['FirstName'] . ' ' . $playerInf['LastName'];



                echo '<tr><td colspan=31><b>FEEDBACK: </b>' . $comment . '<br><br> ' . $liked . ' <br><br> ' . $disliked . '</td></tr>'; 
                //array_push($offer, $fas['OfferID'], $day, $fas['PlayerID'], $fas['TeamID'], $demand, $_POST['year1'], $_POST['year2'], $_POST['year3'], $_POST['year4'], $_POST['year5'], $_POST['year6'], $totalOffer,$rand,$final,$deny,$afterRand);
                //array_push($finaloffers, $offer);

                $comment = '';
                $liked = '';
                $disliked = '';
                
            }
        }
        echo '</table><br><center>Keep in mind, this <b>DOES NOT</b> factor in the randomizer!<br><br>';
        echo 'Randomizer MIN / MAX = <b>' . number_format($final * 0.95) . ' / ' . number_format($final * 1.15) . '</b><br><br>';


        echo '<form action="submit_release.php" method="POST">';
        echo '<input type="hidden" id="PlayerID" name="PlayerID" value="' . $fas['PlayerID'] . '">';
        echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
        echo '<input type="hidden" id="Player" name="Player" value="' . $player . '">';
        echo '<input type="hidden" id="Demand" name="Demand" value="' . $demand . '">';
        echo '<input type="hidden" id="Abbreviation" name="Abbreviation" value="' . $_SESSION['abbreviation'] . '">';
        echo '<input type="hidden" id="year1" name="year1" value="' . $_POST['year1'] . '">';
        echo '<input type="hidden" id="year2" name="year2" value="' . $_POST['year2'] . '">';
        echo '<input type="hidden" id="year3" name="year3" value="' . $_POST['year3'] . '">';
        echo '<input type="hidden" id="year4" name="year4" value="' . $_POST['year4'] . '">';
        echo '<input type="hidden" id="year5" name="year5" value="' . $_POST['year5'] . '">';
        echo '<input type="hidden" id="year6" name="year6" value="' . $_POST['year6'] . '">';
        echo '<input type="submit" name="offer" value="offer"></center><br><br>';
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



                // $percent - Daily decrease amount
            /*if($fas['Money'] == 100) {
                $percent = 0;
            } elseif($fas['Money'] >= 90 && $fas['Money'] < 100) {
                $percent = 100 - $fas['Money'];
            } else {
                $percent = 10;
            } 

            // $secureMult - Multiplier given for a longer contract
            if ($_POST['year2'] == 0) {
                $length = 1;
                if ($fas['Security'] > 45) {
                    $secureMult = 0.85;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 1.15;
                }
            } elseif ($_POST['year3'] == 0) {
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
            } elseif ($_POST['year4'] == 0) {
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
            } elseif ($_POST['year5'] == 0) {
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
            } elseif ($_POST['year6'] == 0) {
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
            } elseif ($_POST['year6'] > 0) {
                $length = 6;
                if ($fas['Security'] > 45) {
                    $secureMult = 1.15;
                } elseif ($fas['Security'] > 40 && $fas['Security'] <= 45) {
                    $secureMult = 1.1;
                } elseif ($fas['Security'] > 35 && $fas['Security'] <= 40) {
                    $secureMult = 0.9;
                } elseif ($fas['Security'] <= 35) {
                    $secureMult = 0.85;
                }
            } 

            // $prevBonus - Player's previous team gets a bonus
            if ($fas['previous'] == $_SESSION['TeamID']) {
                if ($fas['Loyalty'] > 47) {
                    $prevBonus = 1.15;
                } elseif ($fas['Loyalty'] > 39 && $fas['Loyalty'] <= 47) {
                    $prevBonus = 1.1;
                } elseif ($fas['Loyalty'] > 32 && $fas['Loyalty'] <= 39) {
                    $prevBonus = 1.05;
                } elseif ($fas['Loyalty'] <= 32) {
                    $prevBonus = 1.025;
                }
            } else {
                $prevBonus = 1;
            } 

            // $winVal - Bonus given for a team's wins over the previous 3 seasons
            $winVal = (($wins['Wins'] * 1.5) + ($wins['Wins'] * 1) + ($wins['Wins'] * 0.5)) / 21;

            // $ptBonus - Will this player be the highest rated player at his position on his new team?
            $possibleStart = 1;
            $playerCheck = playerService($_SESSION['TeamID'],0);
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
            if ($market['market'] == 4) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 1.15;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 1.1;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 0.9;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 0.85;
                }
            } elseif ($market['market'] == 3) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 1.1;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 0.9;
                }
            } elseif ($market['market'] == 2) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 0.9;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 1;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 1.1;
                }
            } elseif ($market['market'] == 1) {
                if($fas['MarketSize'] > 60) {
                    $marketBonus = 0.85;
                } elseif($fas['MarketSize'] > 50 && $fas['MarketSize'] < 61) {
                    $marketBonus = 0.9;
                } elseif($fas['MarketSize'] > 40 && $fas['MarketSize'] < 51) {
                    $marketBonus = 1.1;
                } elseif($fas['MarketSize'] < 41) {
                    $marketBonus = 1.15;
                }
            } */

                        /*//30-50
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
            } */


?>