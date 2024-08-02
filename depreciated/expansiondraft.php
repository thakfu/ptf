<?php 

include 'header.php';

$playerService = array();
$playerstmt = $connection->query(
    "SELECT y.PlayerID, y.Jersey, y.FirstName, y.LastName, CONCAT(y.FirstName,' ',y.LastName) as FullName,y.College, y.Age, y.Experience, y.Height, y.Weight, y.TeamID, y.DraftedBy, y.DraftRound, y.DraftPick, y.DraftSeason, 
    y.HallOfFame, y.Injury, y.InjuryLength, y.Position, y.AltPosition, y.RetiredSeason, y.Awards, y.Strength, y.Agility, y.Arm, y.Speed, y.Hands, y.Intelligence, y.Accuracy, y.RunBlocking, 
    y.PassBlocking, y.Tackling, y.Endurance, y.KickDistance, y.KickAccuracy, y.Overall, p.Leadership, p.WorkEthic, p.Competitiveness, p.TeamPlayer, p.Sportsmanship, p.SocialDisposition, p.Money, 
    p.Security, p.Loyalty, p.Winning, p.PlayingTime, p.CloseToHome, p.MarketSize, p.Morale, s.1985, s.1986, s.1987, s.1988, s.1989, s.1990, s.1991, s.1992, k.QB, k.RB, k.FB, k.WR, k.TE, k.G, k.T, k.C, k.P, 
    k.K, k.DT, k.DE, k.LB, k.CB, k.SS, k.FS, k.Type, q.SquadTeam, o.PosSort, f.amount, f.tier, f.previous, t.trait1, t.trait2, t.trait3, i.squadTeam as irteam, i.start, d.Nickname    
    FROM `ptf_players` y
    LEFT JOIN `ptf_players_personalities` p on y.PlayerID = p.PlayerID 
    LEFT JOIN `ptf_players_salaries` s on y.PlayerID = s.playerID 
    LEFT JOIN `ptf_players_skills` k on y.PlayerID = k.PlayerID 
    LEFT JOIN `ptf_players_traits` t on y.PlayerID = t.PlayerID 
    LEFT JOIN `ptf_players_squad` q on y.PlayerID = q.PlayerID 
    LEFT JOIN `ptf_players_ir` i on y.PlayerID = i.PlayerID 
    LEFT JOIN `ptf_pos_sort` o on y.Position = o.Position 
    LEFT JOIN `ptf_players_data` d on y.PlayerID = d.PlayerID 
    LEFT JOIN `ptf_fa_demands` f on y.PlayerID = f.PlayerID 
    WHERE y.PlayerID in (1945,1968,2173,2202,2344,2784,3530,2017,3378,3592,2490,2285,3516,1987,3401,2286,3528,3596,3556,3330,1963,2298,2281,2233,2300,3338,2731,2506,2370,3510,2124,2301,2817,2184,3445,3591,3435,2381,2521,3386,2129,3427,2179,2516,2081,3402,2306,3525,2444,2132,2050,3296,2133,3430,3342,2159,3299,3615,3383,2718,2785,3128,2787,2603,2458,2136,2385,3028,3324,2091,2759,2390,2054,2260,2264,2923,2982,2207,2267,2773,2716,3340,3374,2630,1885,2995,2998,2568,2406,2567)");
while($row = $playerstmt->fetch_assoc()) {
    array_push($playerService,$row);
}

$takenJ = array(0,1885,2202,2300,2301,2306,3296,2173,2091,2285,2385,2233,2785,2017,3435,3596,2923,3340,2516,2133,2129,2458,2490,3592,2444,3383,2718,2298);
$takenS = array(0,1945,3374,2132,2124,2787,2264,2179,2370,3342,3591,3556,2281,2982,2081,2055,1963,2521,2603,2286,2136,3324,3338,2050,2630,3378,3525,3028);
$returned = array(0,1);

usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
$top = 0;
$topArray = array();
foreach ($playerService as $player) {
    if ($top < 10) {
        $top++;
        array_push($topArray, $player['FullName']);
    }
}
if ($_GET['pos'] == NULL) {
    if ($_GET['sort'] == 'all');
}

if ($_GET['sort'] == NULL) {
    $_GET['sort'] = 'PosSort';
    $_GET['order'] = 'asc';
}

if ($_GET['order'] == 'asc') {
    $sorter = 'desc';
    usort($playerService, fn($a, $b) => $a[$_GET['sort']] <=> $b[$_GET['sort']]);
} else {
    $sorter = 'asc';
    usort($playerService, fn($a, $b) => $b[$_GET['sort']] <=> $a[$_GET['sort']]);
}

echo '<h2>' . $year . ' All Players</h2>';
echo '<div align="center">';
echo '<a href="expansiondraft.php?pos=all">ALL</a>';
foreach($positions as $pos) {
    echo ' - <a href="expansiondraft.php?pos='. $pos .'">'. $pos .'</a>';
}
echo '</div><br>';

echo '<table class="roster" border=1 id="'.$team['Abbrev'].'">';
echo '<th><a href="expansiondraft.php?sort=TeamID&order=' . $sorter . '&pos='. $_GET['pos']  .'">Team</a></th>';
echo '<th><a href="expansiondraft.php?sort=FirstName&order=' . $sorter . '&pos='. $_GET['pos']  .'">Name</a></th>';
echo '<th><a href="expansiondraft.php?sort=PosSort&order=' . $sorter . '&pos='. $_GET['pos']  .'">Position</a></th>';
echo '<th><a href="expansiondraft.php?sort=Age&order=' . $sorter . '&pos='. $_GET['pos']  .'">Age</a></th>';
//echo '<th><a href="expansiondraft.php?sort=Experience&order=' . $sorter . '">Exp</a></th>';
//echo '<th><a href="expansiondraft.php?sort=College&order=' . $sorter . '">College</a></th>';
echo '<th><a href="expansiondraft.php?sort=Height&order=' . $sorter . '&pos='. $_GET['pos']  .'">Height</a></th>';
echo '<th><a href="expansiondraft.php?sort=Weight&order=' . $sorter . '&pos='. $_GET['pos']  .'">Weight</a></th>';
echo '<th><a href="expansiondraft.php?sort=Overall&order=' . $sorter . '&pos='. $_GET['pos']  .'">OVRL</a></th>';
echo '<th><a href="expansiondraft.php?sort=Strength&order=' . $sorter . '&pos='. $_GET['pos']  .'">Str</a></th>';
echo '<th><a href="expansiondraft.php?sort=Agility&order=' . $sorter . '&pos='. $_GET['pos']  .'">Agil</a></th>';
echo '<th><a href="expansiondraft.php?sort=Arm&order=' . $sorter . '&pos='. $_GET['pos']  .'">Arm</a></th>';
echo '<th><a href="expansiondraft.php?sort=Speed&order=' . $sorter . '&pos='. $_GET['pos']  .'">Speed</a></th>';
echo '<th><a href="expansiondraft.php?sort=Hands&order=' . $sorter . '&pos='. $_GET['pos']  .'">Hand</a></th>';
echo '<th><a href="expansiondraft.php?sort=Intelligence&order=' . $sorter . '&pos='. $_GET['pos']  .'">Int</a></th>';
echo '<th><a href="expansiondraft.php?sort=Accuracy&order=' . $sorter . '&pos='. $_GET['pos']  .'">Acc</a></th>';
echo '<th><a href="expansiondraft.php?sort=RunBlocking&order=' . $sorter . '&pos='. $_GET['pos']  .'">Run Bl</a></th>';
echo '<th><a href="expansiondraft.php?sort=PassBlocking&order=' . $sorter . '&pos='. $_GET['pos']  .'">Pass Bl</a></th>';
echo '<th><a href="expansiondraft.php?sort=Tackling&order=' . $sorter . '&pos='. $_GET['pos']  .'">Tack</a></th>';
echo '<th><a href="expansiondraft.php?sort=Endurance&order=' . $sorter . '&pos='. $_GET['pos']  .'">End</a></th>';
echo '<th><a href="expansiondraft.php?sort=KickDistance&order=' . $sorter . '&pos='. $_GET['pos']  .'">Kick Dis.</a></th>';
echo '<th><a href="expansiondraft.php?sort=KickAccuracy&order=' . $sorter . '&pos='. $_GET['pos']  .'">Kick Acc.</a></th>';
echo '<th><a href="expansiondraft.php?sort=1987&order=' . $sorter . '&pos='. $_GET['pos']  .'">1987</a></th>';
echo '<th><a href="expansiondraft.php?sort=1988&order=' . $sorter . '&pos='. $_GET['pos']  .'">1988</a></th>';
echo '<th><a href="expansiondraft.php?sort=1989&order=' . $sorter . '&pos='. $_GET['pos']  .'">1989</a></th>';
echo '<th><a href="expansiondraft.php?sort=1990&order=' . $sorter . '&pos='. $_GET['pos']  .'">1990</a></th>';
echo '<th><a href="expansiondraft.php?sort=1991&order=' . $sorter . '&pos='. $_GET['pos']  .'">1991</a></th>';
echo '</tr>';


foreach ($playerService as $player) {
    if (($player['Position'] == $_GET['pos'] || $_GET['pos'] == 'all') && $player['RetiredSeason'] == 0) {

        echo '<tr>'; 
        if (in_array($player['PlayerID'],$takenJ)) {
            echo '<td bgcolor="green"><font color="white">NYJ</font>';
        } elseif (in_array($player['PlayerID'],$takenS)) {
            echo '<td bgcolor="blue"><font color="white">SEA</font>';
        } elseif (in_array($player['PlayerID'],$returned)) {
            echo '<td><strike>' . idToAbbrev($player['TeamID']) . '</strike>';
        } else {
            echo '<td>' . idToAbbrev($player['TeamID']);
        }
        if (in_array($player['PlayerID'],$takenJ) || in_array($player['PlayerID'],$takenS)  || in_array($player['PlayerID'],$returned)) {
            echo '</td><td><strike><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></strike></td><td>';
        } else {
            echo '</td><td><a href="/ptf/player.php?player=' . $player['PlayerID'] . '">' . $player['FullName'] . '</a></td><td>';
        }
        echo $player['Position'] . '</td><td>' .
        $player['Age'] . '</td><td>' .
        floor($player['Height'] / 12) . '\'' . ($player['Height'] % 12) . '"</td><td>' .
        $player['Weight'] . '</td><td>' .
        $player['Overall'] . '</td><td>' .
        $player['Strength'] . '</td><td>' .
        $player['Agility'] . '</td><td>' .
        $player['Arm'] . '</td><td>' .
        $player['Speed'] . '</td><td>' .
        $player['Hands'] . '</td><td>' .
        $player['Intelligence'] . '</td><td>' .
        $player['Accuracy'] . '</td><td>' .
        $player['RunBlocking'] . '</td><td>' .
        $player['PassBlocking'] . '</td><td>' .
        $player['Tackling'] . '</td><td>' .
        $player['Endurance'] . '</td><td>' .
        $player['KickDistance'] . '</td><td>' .
        $player['KickAccuracy'] . '</td><td>' .
        $player['1987'] . '</td><td>' . 
        $player['1988'] . '</td><td>' . 
        $player['1989'] . '</td><td>' . 
        $player['1990'] . '</td><td>' . 
        $player['1991'] . '</td>' . 
        '</tr>';
    }
}

echo '</table><br><br>';