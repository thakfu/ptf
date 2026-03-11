<?php

require('../../sql/phpmysqlconnect.php');
$files = array(
    'PlayerGameStats'=>'players_game_stats_1985',  
    'Coaches'=>'coaches_export', 
    'Games'=>'games', 
    'LeagueRecords'=>'league_records', 
    'PlayerAttributes'=>'players_attributes',
    'PlayerCareerStats'=>'players_career_stats',
    'PlayerCombine'=>'players_combine_1985',
    'PlayerPersonalities'=>'players_personalities',  
    'PlayerRecords'=>'players_records',
    'Players'=>'players', 
    'PlayerSeasonStats'=>'players_season_stats_1985', 
    'PlayerSkills'=>'players_skills',     
    'PlayerStreaks'=>'players_streaks',    
    'TeamCareerStats'=>'teams_career_stats',
    'TeamPostseasonStats'=>'teams_postseason_stats',       
    'TeamRecords'=>'teams_records',
    'Teams'=>'teams',
    'TeamSeasonStats'=>'teams_season_stats'  
);

// CoachCareerStats, CoachPostseasonStats, CoachSeasonStats NOT USED
$leaguestmt = $connection->query("SELECT * FROM ptf_league ");
$league = array();
while($row = $leaguestmt->fetch_assoc()) {
    array_push($league, $row);
}

$year = $league[5]['value'];
$week = $league[14]['value'];

if ($_GET['file']) {
    if ($_GET['file'] == 'pss2') {
        $filekey = 'PlayerSeasonStats';

        $csv = fopen('../dataexports/PTF_Master_Official_' . $filekey . '.csv','r');

        $array = array();
        while(! feof($csv))
        {
            array_push($array, fgetcsv($csv));
        }
        fclose($csv);

        foreach($array[0] as $keyy) {
            $key = str_replace(' ','',$keyy);
            $str1 .= '`' . $key . '`,';
            $update .= '`' . $key . '` = VALUES(`' . $key . '`),';
        }
        
        $newArray = array();

        foreach ($array as $line) {
            if ($line[2] == $year) {
                array_push($newArray, $line);
            }
        }
        empty($array);
        $array = $newArray;

        $count = count($array) - 1;

        for($x = 1500; $x < $count; $x++) {
            $str2 .= '(';
            foreach($array[$x] as $value) {
                $strVal .= '"' . utf8_encode($value) . '",';
            } 
            $val = substr($strVal, 0, -1);
            $str2 .= $val . '),';
            $strVal = '';
        }

        $values = substr($str2, 0, -1);
        $keys = substr($str1, 0, -1);
        $upsert = substr($update, 0, -1);

        //echo "INSERT INTO ptf_players_season_stats_1985 (".$keys.") VALUES ".$values." ON DUPLICATE KEY UPDATE ".$upsert."<br><br>";
        //exit;
        echo 'Remaining Done!<br>';
        $stmt = $connection->query('INSERT INTO ptf_players_season_stats_1985 ('.$keys.') VALUES '.$values.' ON DUPLICATE KEY UPDATE '.$upsert. '');

        if ($stmt) {
            echo 'ptf_players_season_stats_1985 SUCCEEDED<br>';
        } else {
            echo 'ptf_players_season_stats_1985 FAILED<br>';
        }
        $str1 = ' ';
        $str2 = ' ';
        $update = ' ';
        unset($array);
        $count = 0;

        echo '<br>';

        foreach ($files as $filekey=>$filevalue) {
            echo '<a href="dataimport.php?file=' . $filekey . '">'. $filekey .'</a><br>' ;
        }
        exit;
    } else {
        foreach ($files as $filekey=>$filevalue) {
            if ($filekey == $_GET['file']) {
                $csv = fopen('../dataexports/PTF_Master_Official_' . $filekey . '.csv','r');

                $array = array();
                while(! feof($csv))
                {
                    array_push($array, fgetcsv($csv));
                }
                fclose($csv);

                foreach($array[0] as $keyy) {
                    $key = str_replace(' ','',$keyy);
                    $str1 .= '`' . $key . '`,';
                    $update .= '`' . $key . '` = VALUES(`' . $key . '`),';
                }

                
                $newArray = array();
                if ($_GET['file'] == 'PlayerSeasonStats') {
                    foreach ($array as $line) {
                        if ($line[2] == $year) {
                            array_push($newArray, $line);
                        }
                    }
                    empty($array);
                    $array = $newArray;
                }

                if ($_GET['file'] == 'PlayerGameStats') {
                    foreach ($array as $line) {
                        if ($line[0] == $week - 1) {
                            array_push($newArray, $line);
                        }
                    }
                    empty($array);
                    $array = $newArray;
                }

                $count = count($array) - 1;

                if ($_GET['file'] == 'PlayerSeasonStats' && $count > 1500) {
                    $count = 1500;
                    for($x = 1; $x < $count; $x++) {
                        $str2 .= '(';
                        foreach($array[$x] as $value) {
                            $strVal .= '"' . utf8_encode($value) . '",';
                        } 
                        $val = substr($strVal, 0, -1);
                        $str2 .= $val . '),';
                        $strVal = '';
                    }

                    $values = substr($str2, 0, -1);
                    $keys = substr($str1, 0, -1);
                    $upsert = substr($update, 0, -1);

                    //echo "INSERT INTO ptf_" . $filevalue . " (".$keys.") VALUES ".$values." ON DUPLICATE KEY UPDATE ".$upsert."<br><br>";
                    //exit;
                    $stmt = $connection->query('INSERT INTO ptf_' . $filevalue . ' ('.$keys.') VALUES '.$values.' ON DUPLICATE KEY UPDATE '.$upsert. '');
                    echo '1500 Done!<br><a href="dataimport.php?file=pss2">Click Here to do the rest!</a>';
                } else {
                    for($x = 1; $x < $count; $x++) {
                        $str2 .= '(';
                        foreach($array[$x] as $value) {
                            $strVal .= '"' . utf8_encode($value) . '",';
                        } 
                        $val = substr($strVal, 0, -1);
                        $str2 .= $val . '),';
                        $strVal = '';
                    }

                    $values = substr($str2, 0, -1);
                    $keys = substr($str1, 0, -1);
                    $upsert = substr($update, 0, -1);

                    //echo "INSERT INTO ptf_" . $filevalue . " (".$keys.") VALUES ".$values." ON DUPLICATE KEY UPDATE ".$upsert."<br><br>";
                    //exit;
                    $stmt = $connection->query('INSERT INTO ptf_' . $filevalue . ' ('.$keys.') VALUES '.$values.' ON DUPLICATE KEY UPDATE '.$upsert. '');
                    
                }

                if ($stmt) {
                    echo 'ptf_' . $filevalue . ' SUCCEEDED<br>';
                } else {
                    echo 'ptf_' . $filevalue . ' FAILED<br>';
                }
                $str1 = ' ';
                $str2 = ' ';
                $update = ' ';
                unset($array);
                $count = 0;

                echo '<br>';

                foreach ($files as $filekey=>$filevalue) {
                    echo '<a href="dataimport.php?file=' . $filekey . '">'. $filekey .'</a><br>' ;
                }


            } else {

            }
        }
    }

} else {
    foreach ($files as $filekey=>$filevalue) {
        echo '<a href="dataimport.php?file=' . $filekey . '">'. $filekey .'</a><br>' ;
    }
}

exit;
  /*  $csv = fopen('../dataexports/PTF_Master_Official_' . $filekey . '.csv','r');
    //$csv = fopen('../dataexports/preseason/PTF_Master_Official_Preseason_' . $filekey . '.csv','r');

    $array = array();
    while(! feof($csv))
    {
        array_push($array, fgetcsv($csv));
    }
    fclose($csv);

    foreach($array[0] as $keyy) {
        $key = str_replace(' ','',$keyy);
        $str1 .= '`' . $key . '`,';
        $update .= '`' . $key . '` = VALUES(`' . $key . '`),';
    }

    $newArray = array();
    if ($_GET['file'] == 'PlayerSeasonStats') {
        foreach ($array as $line) {
            if ($line[2] == $year) {
                array_push($newArray, $line);
            }
        }
        empty($array);
        $array = $newArray;
    }

    if ($_GET['file'] == 'PlayerGameStats') {
        foreach ($array as $line) {
            if ($line[0] == $week - 1) {
                array_push($newArray, $line);
            }
        }
        empty($array);
        $array = $newArray;
    }

    $count = count($array) - 1;

    for($x = 1; $x < $count; $x++) {
        $str2 .= '(';
        foreach($array[$x] as $value) {
            $strVal .= '"' . utf8_encode($value) . '",';
        } 
        $val = substr($strVal, 0, -1);
        $str2 .= $val . '),';
        $strVal = '';
    }

    $values = substr($str2, 0, -1);
    $keys = substr($str1, 0, -1);
    $upsert = substr($update, 0, -1);


     //echo "INSERT INTO ptf_" . $filevalue . " (".$keys.") VALUES ".$values." ON DUPLICATE KEY UPDATE ".$upsert."<br><br>";
     //exit;
    $stmt = $connection->query('INSERT INTO ptf_' . $filevalue . ' ('.$keys.') VALUES '.$values.' ON DUPLICATE KEY UPDATE '.$upsert. '');

    if ($stmt) {
        echo 'ptf_' . $filevalue . ' SUCCEEDED<br>';
    } else {
        echo 'ptf_' . $filevalue . ' FAILED<br>';
    }
    $str1 = ' ';
    $str2 = ' ';
    $update = ' ';
    unset($array);
    $count = 0;

*/
?>