<?php
echo '<table id="tmPage"><tr><td><img src="images/' . $team['Abbrev'] . '_115.png" id="tmHelmet"></td>';
echo '<td><h1>' . $team['City'] . ' </h1></td>';
echo '<td><img src="images/' . $team['Abbrev'] . '_word.png" id="tmLogo"></td></tr></table><br>';
echo '<div class="tmBar" id="' . $team['Abbrev'] . '" align="center">';
echo "
<span><a href='rosters.php?team=" . $team['TeamID'] . "&sort=Jersey&order=asc'>Roster</a></span>  
<span><a href='teamschedule.php?team=" .  $team['TeamID'] . "'>Schedule</a></span>
<span><a href='player_stats.php?team=" . $team['TeamID'] . "'>Stats</a></span>  
<span><a href='stats.php?page=Teams/" . $team['TeamID'] . "'>Sim Page</a></span>";
echo '</div>';

echo '<div class="tmBar2" id="' . $team['Abbrev'] . '" align="center">';
echo "
<span><a href='history.php?team=" . $team['TeamID'] . "'>History</a></span>"; 
echo '</div>';


// MOBILE

echo '<table id="tmPageMob"><tr><td><img src="images/' . $team['Abbrev'] . '_115.png" id="tmHelmet"></td></tr>';
echo '<tr><td><h1>' . $team['City'] . ' </h1></td></tr>';
echo '<tr><td><img src="images/' . $team['Abbrev'] . '_word.png" id="tmLogo"></td></tr></table><br>';


echo '<div class="tmBarMob" id="' . $team['Abbrev'] . '" align="center">';
echo "
<span><a href='rosters.php?team=" . $team['TeamID'] . "&sort=Jersey&order=asc'>Roster</a></span>  <br><hr>
<span><a href='teamschedule.php?team=" .  $team['TeamID'] . "'>Schedule</a></span>   <br><hr>
<span><a href='player_stats.php?team=" . $team['TeamID'] . "'>Stats</a></span>   <br><hr>
<span><a href='stats.php?page=Teams/" . $team['TeamID'] . "'>Sim Page</a></span> <br><hr>
<span><a href='history.php?team=" . $team['TeamID'] . "'>History</a></span> <br>"; 
echo '</div>';

?>