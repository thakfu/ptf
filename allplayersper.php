<?php 

include 'header.php';

if ($_GET['team']) {
    $team = $_GET['team'];
    $col1 = '#';
    $sort = 'Jersey';
} else {
    $team = 0;
    $col1 = 'Team';
    $sort = 'Overall';
}

$playerService = newPlayerService($team,0,'pro');

if ($_GET['team']) {
    usort($playerService, fn($a, $b) => $a['Jersey'] <=> $b['Jersey']);
} else {
    usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
}

if ($_GET['pos'] == NULL) {
    $_GET['pos'] = 'all';
}

if (!$_GET['team']) {
    echo '<h2>All Players</h2>';
    echo '<div align="center">';
    echo '<a href="allplayers.php?pos=all">ALL</a>';
    foreach($positions as $pos) {
        echo ' - <a href="allplayers.php?pos='. $pos .'">'. $pos .'</a>';
    }
    echo '<br><br>';
    echo '<a href="allplayers.php">Attributes</a> - <a href="allplayersper.php">Personality</a> - <a href="allplayerssal.php">Salary</a></div><br>';
} else {
    echo '<h2>' . idToName($team) . ' Roster</h2>';
    echo '<div align="center">';
    echo '<a href="allplayers.php?team=' . $team . '">Attributes</a> - <a href="allplayersper.php?team=' . $team . '">Personality</a> - <a href="allplayerssal.php?team=' . $team . '">Salary</a></div><br>';
}

echo '<table class="sortable" border=1 id="'.idToAbbrev($team).'"><tr style="background-color:#FFFFFF">';
echo '<th>' . $col1 . '</th>';
echo '<th>Name</th>';
echo '<th title="Position">Pos</th>';
echo '<th title="Leadership">Lead</th>';
echo '<th title="Work Ethic">Work</th>';
echo '<th title="Competitive">Compet</th>';
echo '<th title="Team Player">Team</th>';
echo '<th title="Sportsmanship">Sports</th>';
echo '<th title="Social Dispo">Social</th>';
echo '<th title="Money">Money</th>';
echo '<th title="Security">Sec</th>';
echo '<th title="Loyalty">Loy</th>';
echo '<th title="Winning">Win</th>';
echo '<th title="Playing Time">PT</th>';
echo '<th title="Close to Home">Home</th>';
echo '<th title="Market">Mar</th>';
echo '<th title="Morale">Mor</th>';
echo '</tr>';


foreach ($playerService as $player) {
    if (($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') && $player['ProRetire'] == 0) {

        if ($player['Security'] > 50) {
            $security = $player['Security'] - 20;
        } else {
            $security = $player['Security'];
        }

        if ($player['Loyalty'] > 50) {
            $loyalty = $player['Loyalty'] - 20;
        } else {
            $loyalty = $player['Loyalty'];
        }

        if ($_GET['team']) {
            echo '<tr><td class="career"><b>'; 
            echo $player['Jersey'];
        } else {
            echo '<tr><td class="career" id="'.idToAbbrev($player['TeamID']).'"><b>';
            echo idToAbbrev($player['TeamID']); 
        }
        echo '</b></td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>' .
        $player['Position'] . '</td><td>' .
        $player['Leadership'] . '</td><td>' .
        $player['WorkEthic'] . '</td><td>' .
        $player['Competitiveness'] . '</td><td>' .
        $player['TeamPlayer'] . '</td><td>' .
        $player['Sportsmanship'] . '</td><td>' .
        $player['SocialDisposition'] . '</td><td>' .
        $player['Money'] . '</td><td>' .
        $security . '</td><td>' .
        $loyalty . '</td><td>' .
        $player['Winning'] . '</td><td>' .
        $player['PlayingTime'] . '</td><td>' .
        $player['CloseToHome'] . '</td><td>' .
        $player['MarketSize'] . '</td><td>' .
        $player['Morale'] . '</td>' .
        '</tr>';
    }
}

echo '</table><br><br>';