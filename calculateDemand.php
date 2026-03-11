<?php

function calculateDemand($year, $day, $player, $data, $team, $datastate) {

$calc = array();
global $connection;


//var_dump($player);

if ($data == 'extend') {
    $data = array();
    if ($player['yearsOffered'] == 1) {
        $data['year6'] = $data['year5'] = $data['year4'] = $data['year3'] = $data['year2'] = 0;
        $data['year1'] = 1;
    } elseif ($player['yearsOffered'] == 2) {
        $data['year6'] = $data['year5'] = $data['year4'] = $data['year3'] = 0;
        $data['year2'] = $data['year1'] = 1;
    } elseif ($player['yearsOffered'] == 3) {
        $data['year6'] = $data['year5'] = $data['year4'] = 0;
        $data['year3'] = $data['year2'] = $data['year1'] = 1;
    } elseif ($player['yearsOffered'] == 4) {
        $data['year6'] = $data['year5'] = 0;
        $data['year4'] = $data['year3'] = $data['year2'] = $data['year1'] = 1;
    } elseif ($player['yearsOffered'] == 5) {
        $data['year6'] = 0;
        $data['year5'] = $data['year4'] = $data['year3'] = $data['year2'] = $data['year1'] = 1;
    } elseif ($player['yearsOffered'] == 6) {
        $data['year6'] = $data['year5'] = $data['year4'] = $data['year3'] = $data['year2'] = $data['year1'] = 1;
    }

    $demand = $player['demandAmount'];

} else {
    // $percent - Daily decrease amount
    if($player['Money'] == 100) {
        $percent = 10;
    } elseif($player['Money'] >= 90 && $player['Money'] < 100) {
        $percent = 15;
    } else {
        $percent = 20; 
    }

        // Adjusted Demand
    if ($day > 1 && $day < 5) {
        $demandAdj = ($percent / 100) * ($day - 1);
        $demand = $player['amount'] * (1 - $demandAdj);
    } elseif ($day == 5) {
        if ($player['Money'] >= 90 && $player['Money'] < 100) {
            $demand = ($player['amount'] * 0.10);  
        } elseif ($player['Money'] == 100) {
            $demand = ($player['amount'] * 0.25);  
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
}



        // SECURITY - Scale 30-50  // 
        if ($player['Security'] > 50) {
            $security = $player['Security'] - 20;
        } else {
            $security = $player['Security'];
        }

        if ($security <= 35) {
            $sec = '1-2 Yrs';
        } elseif ($security <= 40 && $security > 35) {
            $sec = '1-4 Yrs';
        } elseif ($security <= 45 && $security > 40) {
            $sec = '3-6 Yrs';
        } elseif ($security >= 46) {
            $sec = '5-6 Yrs';
        }

        if ($data['year2'] == 0) {
            $length = 1;
            if ($security > 45) {
                $secureMult = 0.9;
            } elseif ($security > 40 && $security <= 45) {
                $secureMult = 0.95;
            } elseif ($security > 35 && $security <= 40) {
                $secureMult = 1.05;
            } elseif ($security <= 35) {
                $secureMult = 1.1;
            }
        } elseif ($data['year3'] == 0) {
            $length = 2;
            if ($security > 45) {
                $secureMult = 0.9;
            } elseif ($security > 40 && $security <= 45) {
                $secureMult = 0.95;
            } elseif ($security > 35 && $security <= 40) {
                $secureMult = 1.05;
            } elseif ($security <= 35) {
                $secureMult = 1.1;
            }
        } elseif ($data['year4'] == 0) {
            $length = 3;
            if ($security > 45) {
                $secureMult = 0.95;
            } elseif ($security > 40 && $security <= 45) {
                $secureMult = 1.05;
            } elseif ($security > 35 && $security <= 40) {
                $secureMult = 1.05;
            } elseif ($security <= 35) {
                $secureMult = 0.95;
            }
        } elseif ($data['year5'] == 0) {
            $length = 4;
            if ($security > 45) {
                $secureMult = 0.95;
            } elseif ($security > 40 && $security <= 45) {
                $secureMult = 1.05;
            } elseif ($security > 35 && $security <= 40) {
                $secureMult = 1.05;
            } elseif ($security <= 35) {
                $secureMult = 0.95;
            }
        } elseif ($data['year6'] == 0) {
            $length = 5;
            if ($security > 45) {
                $secureMult = 1.1;
            } elseif ($security > 40 && $security <= 45) {
                $secureMult = 1.05;
            } elseif ($security > 35 && $security <= 40) {
                $secureMult = 0.95;
            } elseif ($security <= 35) {
                $secureMult = 0.9;
            }
        } elseif ($data['year6'] > 0) {
            $length = 6;
            if ($security > 45) {
                $secureMult = 1.1;
            } elseif ($security > 40 && $security <= 45) {
                $secureMult = 1.05;
            } elseif ($security > 35 && $security <= 40) {
                $secureMult = 0.95;
            } elseif ($security <= 35) {
                $secureMult = 0.9;
            }
        }

        //echo $secureMult;


        //LOYALTY - Scale 30-50     // NOW 30-70
        if ($player['Loyalty'] > 50) {
            $loyalty = $player['Loyalty'] - 20;
        } else {
            $loyalty = $player['Loyalty'];
        }

        if ($loyalty <= 33) {
            $loy = 'D';
        } elseif ($loyalty <= 39 && $loyalty > 33) {
            $loy = 'C';
        } elseif ($loyalty <= 46 && $loyalty > 39) {
            $loy = 'B';
        } elseif ($loyalty >= 47) {
            $loy = 'A';
        }

        if ($player['previous'] == $team) {
            if ($loyalty >= 47) {
                $prevBonus = 1.1;
            } elseif ($loyalty > 39 && $loyalty <= 46) {
                $prevBonus = 1.05;
            } elseif ($loyalty > 33 && $loyalty <= 39) {
                $prevBonus = 1.025;
            } elseif ($loyalty <= 33) {
                $prevBonus = 1;
            }
        } else {
            $prevBonus = 1;
        }

        //echo ' * ' . $prevBonus;


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

        $wl1s = winlossService($team,$year-3);
        $wl1 = $wl1s[0];

        $wl2s = winlossService($team,$year-2); 
        $wl2 = $wl2s[0];

        $wl3s = winlossService($team,$year-1);
        $wl3 = $wl3s[0];


        $winValY3 = $wl3['Wins'] * 1.5;
        $winValY2 = $wl2['Wins'];
        $winValY1 = $wl1['Wins'] * 0.5;
        // $winVal - Bonus given for a team's wins over the previous 3 seasons

        $winVal = ($winValY1 + $winValY2 + $winValY3) / 24;
        if ($winVal < 0.75) {
            $winVal = 0.75;
        }
        if ($winVal > 1.5) {
            $winVal = 1.5;
        }

        if ($player['Winning'] <= 40) {
            if ($winVal >= 1.3) {
                $winBonus = 1.025;
            } elseif ($winVal > 1.15 && $winVal < 1.3) {
                $winBonus = 1.015;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal < 0.85) {
                $winBonus = 0.995;
            }

        } elseif ($player['Winning'] <= 50 && $player['Winning'] > 40) {
            if ($winVal >= 1.3) {
                $winBonus = 1.05;
            } elseif ($winVal > 1.15 && $winVal < 1.3) {
                $winBonus = 1.025;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal < 0.85) {
                $winBonus = 0.99;
            }

        } elseif ($player['Winning'] <= 60 && $player['Winning'] > 50) {
            if ($winVal >= 1.3) {
                $winBonus = 1.075;
            } elseif ($winVal > 1.15 && $winVal < 1.3) {
                $winBonus = 1.05;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1.025;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 1;
            } elseif ($winVal < 0.85) {
                $winBonus = 0.975;
            }

        } elseif ($player['Winning'] >= 61) {
            if ($winVal >= 1.3) {
                $winBonus = 1.1;
            } elseif ($winVal > 1.15 && $winVal < 1.3) {
                $winBonus = 1.05;
            } elseif ($winVal >= 1 && $winVal < 1.15) {
                $winBonus = 1.025;
            } elseif ($winVal >= 0.85 && $winVal < 1) {
                $winBonus = 0.975;
            } elseif ($winVal < 0.85) {
                $winBonus = 0.95;
            }
        }

        //echo ' * ' . $winBonus;


        //PLAYING TIME - Scale 70-100
        if ($player['PlayingTime'] <= 75) {
            $pt = 'D';
        } elseif ($player['PlayingTime'] <= 85 && $player['PlayingTime'] > 75) {
            $pt = 'C';
        } elseif ($player['PlayingTime'] <= 95 && $player['PlayingTime'] > 85) {
            $pt = 'B';
        } elseif ($player['PlayingTime'] >= 95) {
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
                $ptBonus = 1.1;
            } elseif ($player['PlayingTime'] >= 90 && $player['PlayingTime'] < 95) {
                $ptBonus = 1.075;
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

        //echo ' * ' . $ptBonus;


        //CLOSE TO HOME - Scale 30-50     // NOW 30-70
        /*if ($player['CloseToHome'] <= 32) {
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
        }*/

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
                $marketBonus = 1.1;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 1.05;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 0.95;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 0.9;
            }
        } elseif ($market['market'] == 3) {
            $marStr = 'Lg-Mid';
            if($player['MarketSize'] > 60) {
                $marketBonus = 1.05;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 0.95;
            }
        } elseif ($market['market'] == 2) {
            $marStr = 'Sm-Mid';
            if($player['MarketSize'] > 60) {
                $marketBonus = 0.95;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 1;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 1.05;
            }
        } elseif ($market['market'] == 1) {
            $marStr = 'Small';
            if($player['MarketSize'] > 60) {
                $marketBonus = 0.9;
            } elseif($player['MarketSize'] > 50 && $player['MarketSize'] < 61) {
                $marketBonus = 0.95;
            } elseif($player['MarketSize'] > 40 && $player['MarketSize'] < 51) {
                $marketBonus = 1.05;
            } elseif($player['MarketSize'] < 41) {
                $marketBonus = 1.1;
            }
        }


        //echo ' * ' . $marketBonus;



        if ($demand == '250000') {
            $finaldemand = $demand;
        } else {
            $finaldemand = floor($demand/50000) * 50000;
            if ($finaldemand < 250000) {
                $finaldemand = 250000;
            }
        }

        //echo ' = ' . $finaldemand;


        $calc['winVal'] = $winVal;
        $calc['previous'] = $previous;
        $calc['sec'] = $sec;
        $calc['loy'] = $loy;
        $calc['win'] = $win;
        $calc['pt'] = $pt;
        //$calc['cth'] = $cth;
        $calc['mar'] = $mar;
        $calc['string'] = $player['string'];
        $calc['final'] = number_format($finaldemand);
        $calc['finalAmt'] = $finaldemand;
        $calc['length'] = $length;
        $calc['secureMult'] = $secureMult;
        $calc['prevBonus'] = $prevBonus;
        $calc['ptBonus'] = $ptBonus;
        //$calc['homeBase'] = $homeBase;
        $calc['marketBonus'] = $marketBonus;
        $calc['demand'] = $finaldemand;
        //$calc['homestate'] = $homestate;
        //$calc['state'] = $datastate;
        $calc['winBonus'] = $winBonus;
        $calc['market'] = $marStr;

        return $calc;
}

?>