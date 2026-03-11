<?php

$transInfo = transService('old','','');
$curyear = 1990;
$pastyears = array(1990,1989,1988,1987,1986,1985);
$masterarray = array();
foreach ($pastyears as $y) {
    $tlarray = array();
    $timeline = $connection->query("SELECT `TimeLine`, `Event`, `Active` FROM `ptf_timeline` ORDER by `TimeLine` DESC");
    while($t = $timeline->fetch_assoc()) {
        if ($y == $curyear && $t['Active'] == 1) {
            array_push($tlarray, $t);
        } 
        if ($y < $curyear) {
            array_push($tlarray, $t);
        }
    }
    foreach ($tlarray as $tl) {
        $eventitem = $y . ' ' .  $tl['Event'];
        array_push($masterarray,$eventitem);
    }
}

foreach ($masterarray as $m) {
    echo '<h3>' . $m . '</h3>';


foreach (array_slice($transInfo,0,$slice) as $ti) { 
    if ($ti['TimeFrame'] == $m) {
        if ($ti['TeamID_Old'] == $tteam || $ti['TeamID_New'] == $tteam || !$tteam || $tteam == 'menu') {
            if ($_GET['type'] == $ti['type'] || $_GET['type'] == 'all' || $_GET['type'] == NULL) {
                if ($ti['type'] == 'cut') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' release ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' to free agency!</td></tr></table><br>';
                } else if ($ti['type'] == 'squad') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have demoted ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' to the practice squad!</td></tr></table><br>';
                } else if ($ti['type'] == 'ir') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have placed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' on injured reserve!</td></tr></table><br>';
                } else if ($ti['type'] == 'activate') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have activated ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' from injured reserve!</td></tr></table><br>';
                } else if ($ti['type'] == 'sign') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have signed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' from the free agent pool!</td></tr></table><br>';
                } else if ($ti['type'] == 'extend') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have extended the contract of ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '!</td></tr></table><br>';
                } else if ($ti['type'] == 'extdecline') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have had an extension declined by ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '!</td></tr></table><br>';
                } else if ($ti['type'] == 'extbreak') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have had an extension declined by  ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ', and future talks are broken off!</td></tr></table><br>';
                } else if ($ti['type'] == 'tag') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have franchise tagged ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '!</td></tr></table><br>';
                } else if ($ti['type'] == 'promote') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have promoted ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' from the practice squad to the main roster!</td></tr></table><br>';
                } else if ($ti['type'] == 'change') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have changed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '\'s position!</td></tr></table><br>';
                } else if ($ti['type'] == 'expand') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have selected ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' in the expansion draft!</td></tr></table><br>';
                } else if ($ti['type'] == 'supp') {
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have selected ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' in the 1987 Supplemental draft!</td></tr></table><br>';
                } else if ($ti['type'] == 'draft') {
                    $ts = transService('new',$ti['PlayerID'],'draft');
                    $row = $ts[0];
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt">The ' . $row['City'] . ' ' . $row['Mascot'] . ' have drafted ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . '!</td></tr></table><br>';
                } else if ($ti['type'] == 'fasign') {
                    $ts = transService('new',$ti['PlayerID'],'fasign');
                    $row = $ts[0];
                    echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $row['Abbrev'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $row['City'] . ' ' . $row['Mascot'] . ' have signed ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' in free agency!</td></tr></table><br>';
                } else if ($ti['type'] == 'trade') {
                    if (str_starts_with($ti['PlayerID'],'p')) {
                        $asset = ltrim($ti['PlayerID'],'p');
                        $stmtpick = $connection->query("SELECT * from ptf_draft_picks WHERE draftID = " . $asset);
                        while($row = $stmtpick->fetch_assoc()) {
                            $pickString = $row['year'] . ' Round ' . $row['round'] . ' pick - ' . idToAbbrev($row['team']);
                        }
                        echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . ' have traded ' . $pickString .' to the ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . '!</td><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td></tr></table><br>';
                    } else {
                        echo '<table id="transtab"><tr><td id="transtd"><img src="images/' . $ti['AbbrevOld'] . '_115.png" width="30px"></td><td id="transtxt"> The ' . $ti['CityOld'] . ' ' . $ti['MascotOld'] . ' have traded ' . $ti['Position'] . ' ' . $ti['FirstName'] . ' ' . $ti['LastName'] . ' to the ' . $ti['CityNew'] . ' ' . $ti['MascotNew'] . '!</td><td id="transtd"><img src="images/' . $ti['AbbrevNew'] . '_115.png" width="30px"></td></tr></table><br>';
                    }
                } 
            }
        }
    }
}


}
echo '<br><br>';

?>