<?php

require('../../../sql/phpmysqlconnect.php');
$files = array(
    'Games'=>'games', 
    'Coaches'=>'coaches_export', 
    'LeagueRecords'=>'league_records',
    'PlayerAttributes'=>'players_attributes',
    'PlayerCareerStats'=>'players_career_stats',
    'PlayerCombine'=>'players_combine_1985',
    //'PlayerGameStats'=>'players_game_stats_1985',  
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

foreach ($files as $filekey=>$filevalue) {
    $csv = fopen('../dataexports/PTF_Master_Official_' . $filekey . '.csv','r');
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
}

?>