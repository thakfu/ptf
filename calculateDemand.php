<?php

function calculateDemand($year, $day, $player, $data, $team, $datastate) {

$calc = array();
global $connection;

         // $percent - Daily decrease amount
         if($player['Money'] == 100) {
            $percent = 0;
        } elseif($player['Money'] >= 90 && $player['Money'] < 100) {
            $percent = 100 - $player['Money'];
        } else {
            $percent = 10;
        }

         // Adjusted Demand
        if ($day > 1 && $day < 7) {
            $demandAdj = ($percent / 100) * ($day - 1);
            $demand = $player['amount'] * (1 - $demandAdj);
        } elseif ($day == 7) {
            if ($player['Money'] >= 90 && $player['Money'] < 100) {
                $demand = ($player['amount'] * 0.5);
            } elseif ($player['Money'] == 100) {
                $demand = ($player['amount'] * 0.75);
            } else {
                $demand = 250000;
            }
        } else {
            $demandAdj = 0;
            $demand = $player['amount'];
        }

        // unsigned free agents
        if ($player[$year - 1] == 0) {
            $previous = 250000;
        } else {
            $previous = $player[$year - 1];
        }


        // SECURITY - Scale 30-50
        if ($player['Security'] <= 35) {
            $sec = '1-2 Yrs';
        } elseif ($player['Security'] <= 40 && $player['Security'] > 35) {
            $sec = '1-4 Yrs';
        } elseif ($player['Security'] <= 45 && $player['Security'] > 40) {
            $sec = '3-6 Yrs';
        } elseif ($player['Security'] >= 46) {
            $sec = '5-6 Yrs';
        }

        if ($data['year2'] == 0) {
            $length = 1;
            if ($player['Security'] > 45) {
                $secureMult = 0.85;
            } elseif ($player['Security'] > 40 && $player['Security'] <= 45) {
                $secureMult = 0.9;
            } elseif ($player['Security'] > 35 && $player['Security'] <= 40) {
                $secureMult = 1.1;
            } elseif ($player['Security'] <= 35) {
                $secureMult = 1.15;
            }
        } elseif ($data['year3'] == 0) {
            $length = 2;
            if ($player['Security'] > 45) {
                $secureMult = 0.9;
            } elseif ($player['Security'] > 40 && $player['Security'] <= 45) {
                $secureMult = 0.9;
            } elseif ($player['Security'] > 35 && $player['Security'] <= 40) {
                $secureMult = 1.1;
            } elseif ($player['Security'] <= 35) {
                $secureMult = 1.1;
            }
        } elseif ($data['year4'] == 0) {
            $length = 3;
            if ($player['Security'] > 45) {
                $secureMult = 0.9;
            } elseif ($player['Security'] > 40 && $player['Security'] <= 45) {
                $secureMult = 1;
            } elseif ($player['Security'] > 35 && $player['Security'] <= 40) {
                $secureMult = 1;
            } elseif ($player['Security'] <= 35) {
                $secureMult = 1;
            }
        } elseif ($data['year5'] == 0) {
            $length = 4;
            if ($player['Security'] > 45) {
                $secureMult = 1;
            } elseif ($player['Security'] > 40 && $player['Security'] <= 45) {
                $secureMult = 1;
            } elseif ($player['Security'] > 35 && $player['Security'] <= 40) {
                $secureMult = 1;
            } elseif ($player['Security'] <= 35) {
                $secureMult = 0.9;
            }
        } elseif ($data['year6'] == 0) {
            $length = 5;
            if ($player['Security'] > 45) {
                $secureMult = 1.1;
            } elseif ($player['Security'] > 40 && $player['Security'] <= 45) {
                $secureMult = 1.1;
            } elseif ($player['Security'] > 35 && $player['Security'] <= 40) {
                $secureMult = 0.9;
            } elseif ($player['Security'] <= 35) {
                $secureMult = 0.9;
            }
        } elseif ($data['year6'] > 0) {
            $length = 6;
            if ($player['Security'] > 45) {
                $secureMult = 1.15;
            } elseif ($player['Security'] > 40 && $player['Security'] <= 45) {
                $secureMult = 1.1;
            } elseif ($player['Security'] > 35 && $player['Security'] <= 40) {
                $secureMult = 0.9;
            } elseif ($player['Security'] <= 35) {
                $secureMult = 0.85;
            }
        }


        //LOYALTY - Scale 30-50
        if ($player['Loyalty'] <= 32) {
            $loy = 'D';
        } elseif ($player['Loyalty'] <= 39 && $player['Loyalty'] > 32) {
            $loy = 'C';
        } elseif ($player['Loyalty'] <= 47 && $player['Loyalty'] > 39) {
            $loy = 'B';
        } elseif ($player['Loyalty'] >= 48) {
            $loy = 'A';
        }

        if ($player['previous'] == $team) {
            if ($player['Loyalty'] > 47) {
                $prevBonus = 1.15;
            } elseif ($player['Loyalty'] > 39 && $player['Loyalty'] <= 47) {
                $prevBonus = 1.1;
            } elseif ($player['Loyalty'] > 32 && $player['Loyalty'] <= 39) {
                $prevBonus = 1.05;
            } elseif ($player['Loyalty'] <= 32) {
                $prevBonus = 1.025;
            }
        } else {
            $prevBonus = 1;
        }


        //WINNING - Scale 30-70
        if ($player['Winning'] <= 40) {
            $win = 'D';
        } elseif ($player['Winning'] <= 50 && $player['Winning'] > 40) {
            $win = 'C';
        } elseif ($player['Winning'] <= 60 && $player['Winning'] > 50) {
            $win = 'B';
        } elseif ($player['Winning'] >= 61) {
            $win = 'A';
        }

        $wl1s = winlossService($team,$year-2); // CHANGE THIS TO -3 AFTER 1987
        $wl1 = $wl1s[0];

        $wl2s = winlossService($team,$year-1); // CHANGE THIS TO -2 AFTER 1987
        $wl2 = $wl2s[0];

        $wl3s = winlossService($team,$year-1);
        $wl3 = $wl3s[0];


        $winValY3 = $wl3['Wins'] * 1.5;
        $winValY2 = $wl2['Wins'];
        $winValY1 = $wl1['Wins'] * 0.5;
        // $winVal - Bonus given for a team's wins over the previous 3 seasons
        if ($team >= 19) {
            $winVal = 0.75;
        } else {
            $winVal = ($winValY1 + $winValY2 + $winValY3) / 24;
        }

        if ($player['Winning'] <= 40) {
            if ($winVal >= 1.35) {
                $winBonus = 1.025;
            } elseif ($winVal > 1.15 && $winVal < 1.35) {
                $winBonus = 1.01875;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1.0125;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal >= 0.75 && $winVal < 0.85) {
                $winBonus = 0.995;
            } elseif ($winVal < 0.75) {
                $winBonus = 0.99;
            }
        } elseif ($player['Winning'] <= 50 && $player['Winning'] > 40) {
            if ($winVal >= 1.35) {
                $winBonus = 1.05;
            } elseif ($winVal > 1.15 && $winVal < 1.35) {
                $winBonus = 1.0375;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1.025;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal >= 0.75 && $winVal < 0.85) {
                $winBonus = 0.99;
            } elseif ($winVal < 0.75) {
                $winBonus = 0.98;
            }
        } elseif ($player['Winning'] <= 60 && $player['Winning'] > 50) {
            if ($winVal >= 1.35) {
                $winBonus = 1.1;
            } elseif ($winVal > 1.15 && $winVal < 1.35) {
                $winBonus = 1.075;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1.05;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal >= 0.75 && $winVal < 0.85) {
                $winBonus = 0.985;
            } elseif ($winVal < 0.75) {
                $winBonus = 0.97;
            }
        } elseif ($player['Winning'] >= 61) {
            if ($winVal >= 1.35) {
                $winBonus = 1.15;
            } elseif ($winVal > 1.15 && $winVal < 1.35) {
                $winBonus = 1.1125;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1.075;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal >= 0.75 && $winVal < 0.85) {
                $winBonus = 0.975;
            } elseif ($winVal < 0.75) {
                $winBonus = 0.95;
            }
        }


        //PLAYING TIME - Scale 70-100
        if ($player['PlayingTime'] <= 75) {
            $pt = 'D';
        } elseif ($player['PlayingTime'] <= 85 && $player['PlayingTime'] > 75) {
            $pt = 'C';
        } elseif ($player['PlayingTime'] <= 92 && $player['PlayingTime'] > 85) {
            $pt = 'B';
        } elseif ($player['PlayingTime'] >= 93) {
            $pt = 'A';
        }


        // $ptBonus - Will this player be the highest rated player at his position on his new team?
        $possibleStart = 1;
        $playerCheck = playingtimeService($team, $player['Position']);
        foreach($playerCheck as $pc) {
            if ($pc > $player['Overall']) {
                $possibleStart = 0;
            }
        }

        if ($possibleStart == 1) {
            if ($player['PlayingTime'] >= 95) {
                $ptBonus = 1.15;
            } elseif ($player['PlayingTime'] >= 90 && $player['PlayingTime'] < 95) {
                $ptBonus = 1.1;
            } elseif ($player['PlayingTime'] >= 80 && $player['PlayingTime'] < 90) {
                $ptBonus = 1.05;
            } elseif ($player['PlayingTime'] < 80) {
                $ptBonus = 1.025;
            }
        } else {
            if ($player['PlayingTime'] >= 95) {
                $ptBonus = 0.9;
            } elseif ($player['PlayingTime'] >= 90 && $player['PlayingTime'] < 95) {
                $ptBonus = 0.925;
            } elseif ($player['PlayingTime'] >= 80 && $player['PlayingTime'] < 90) {
                $ptBonus = 0.95;
            } elseif ($player['PlayingTime'] < 80) {
                $ptBonus = 0.975;
            }
        }
        if ($player['Overall'] >= 80) {
            $ptBonus = 1;
        }


        //CLOSE TO HOME - Scale 30-50
        if ($player['CloseToHome'] <= 32) {
            $cth = 'D';
        } elseif ($player['CloseToHome'] <= 39 && $player['CloseToHome'] > 32) {
            $cth = 'C';
        } elseif ($player['CloseToHome'] <= 47 && $player['CloseToHome'] > 39) {
            $cth = 'B';
        } elseif ($player['CloseToHome'] >= 48) {
            $cth = 'A';
        }

        // THE STATE THE PLAYER IS FROM!!!
        $stateService = faPlayerService('college', $player['PlayerID']);
        foreach ($stateService as $state) {
            if($player['PlayerID'] == $state['PlayerID']) {
                //echo $state['state'] . '-' . $datastate;
                $homestate = $state['state'];
                if ($datastate == $state['state']) {
                    if ($cth == 'D') {
                        $homeBase = 1.025;
                    } elseif ($cth == 'C') {
                        $homeBase = 1.05;
                    } elseif ($cth == 'B') {
                        $homeBase = 1.1;
                    } elseif ($cth == 'A') {
                        $homeBase = 1.15;
                    }
                } elseif (str_contains($state['bordering'], $datastate)) {
                    if ($cth == 'D') {
                        $homeBase = 1.02;
                    } elseif ($cth == 'C') {
                        $homeBase = 1.03;
                    } elseif ($cth == 'B') {
                        $homeBase = 1.065;
                    } elseif ($cth == 'A') {
                        $homeBase = 1.1;
                    }
                } elseif (str_contains($state['within2'], $datastate)) {
                    if ($cth == 'D') {
                        $homeBase = 1.015;
                    } elseif ($cth == 'C') {
                        $homeBase = 1.02;
                    } elseif ($cth == 'B') {
                        $homeBase = 1.035;
                    } elseif ($cth == 'A') {
                        $homeBase = 1.05;
                    }
                } else {
                    $homeBase = 0.95;
                }
            }
        }

        //MARKET SIZE - Scale 30-70
        if ($player['MarketSize'] <= 40) {
            $mar = 'Small';
        } elseif ($player['MarketSize'] <= 50 && $player['MarketSize'] > 40) {
            $mar = 'Sm-Mid';
        } elseif ($player['MarketSize'] <= 60 && $player['MarketSize'] > 50) {
            $mar = 'Lg-Mid';
        } elseif ($player['MarketSize'] >= 61) {
            $mar = 'Large';
        }

        $result = $connection->query("SELECT market from `ptf_teams_data` where TeamID = " . $team);
        $market = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($market['market'] == 4) {
            $marStr = 'Large';
            if($player['MarketSize'] > 60) {
                $marketBonus = 1.15;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 1.1;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 0.9;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 0.85;
            }
        } elseif ($market['market'] == 3) {
            $marStr = 'Lg-Mid';
            if($player['MarketSize'] > 60) {
                $marketBonus = 1.1;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 0.9;
            }
        } elseif ($market['market'] == 2) {
            $marStr = 'Sm-Mid';
            if($player['MarketSize'] > 60) {
                $marketBonus = 0.9;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 1.1;
            }
        } elseif ($market['market'] == 1) {
            $marStr = 'Small';
            if($player['MarketSize'] > 60) {
                $marketBonus = 0.85;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 0.9;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 1.1;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 1.15;
            }
        }



        if ($demand == '250000') {
            $finaldemand = $demand;
        } else {
            $finaldemand = floor($demand/50000) * 50000;
            if ($finaldemand < 250000) {
                $finaldemand = 250000;
            }
        }


        $calc['winVal'] = $winVal;
        $calc['previous'] = $previous;
        $calc['sec'] = $sec;
        $calc['loy'] = $loy;
        $calc['win'] = $win;
        $calc['pt'] = $pt;
        $calc['cth'] = $cth;
        $calc['mar'] = $mar;
        $calc['string'] = $player['string'];
        $calc['final'] = number_format($finaldemand);
        $calc['finalAmt'] = $finaldemand;
        $calc['length'] = $length;
        $calc['secureMult'] = $secureMult;
        $calc['prevBonus'] = $prevBonus;
        $calc['ptBonus'] = $ptBonus;
        $calc['homeBase'] = $homeBase;
        $calc['marketBonus'] = $marketBonus;
        $calc['demand'] = $finaldemand;
        $calc['homestate'] = $homestate;
        $calc['state'] = $datastate;
        $calc['winBonus'] = $winBonus;
        $calc['market'] = $marStr;

        return $calc;
}

?>