<?php
include 'header.php';

$chart = array();

$depth = $connection->query('SELECT * FROM `ptf_gameplan` WHERE TeamID = ' . $_SESSION['TeamID']);
while($row = $depth->fetch_assoc()) {
    array_push($chart, $row);
}

$opponent = $connection->query('SELECT HomeTeamID, AwayTeamID, Week FROM `ptf_games` WHERE (HomeTeamID = ' . $_SESSION['TeamID'] . ' OR AwayTeamID = ' . $_SESSION['TeamID'] . ') AND Week = ' . $curWeek);
while($opp = $opponent->fetch_assoc()) {
    if($opp['HomeTeamID'] == $_SESSION['TeamID']) {
        $oppId = $opp['AwayTeamID'];
    } else {
        $oppId = $opp['HomeTeamID'];
    }
}

echo '<h1>' . $_SESSION['mascot'] . ' Weekly Game Plan</h1>';
echo '<center><p><b>IMPORTANT NOTICE!!!   If using DEFAULT GAME PLANS, all sections MUST be set to default!!  If NOT using DEFAULT GAME PLANS, all sections must be filled out!!!!</p>';
echo '<br><p>NEXT OPPONENT - ' . idToAbbrev($oppId) . ' </p></b><br>';
echo '<h3>Use Default Game Plan?</h3>';
echo '<form action="submit_strategy.php" method="POST">';
echo '<select name="defaulto" id="defaulto">
<option value="Manual"' . ($chart[0]['defaulto'] == "Manual" ? "Selected" : " ") . ' >Manual Game Plan (Fill out below form)</option>
<option value="HeadCoach"' . ($chart[0]['defaulto'] == "HeadCoach" ? "Selected" : " ") . ' >Head Coach (You Can Submit)</option>
</select></center<<br><br>';
// <option value="Coordinator"' . ($chart[0]['defaulto'] == "Coordinator" ? "Selected" : " ") . ' >Coordinators (Don\'t Use Right Now)</option>
echo '<div id="depthcharts"><table class="depth" id="' . $_SESSION['abbreviation'] . '"><tr><td valign="top">';
echo '<h2>OFFENSE</h2>';

echo '<input type="hidden" id="Team" name="Team" value="' . $_SESSION['mascot'] . '">';
echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
echo '<input type="hidden" id="Time" name="Time" value="' . date('Y-m-d H:i:s') . '">';
echo '<input type="hidden" id="Type" name="Type" value="gameplan">';


echo '<h3>Game Plan</h3>';
echo '<b>Attitude</b>';
echo '<br><i>Affects playcalling, a little more likely to go for it on 4th down and onside kicks.<br> Also affects penalties.</i><br>';
echo '<select name="style" id="style">
<option value="Aggressive"' . ($chart[0]['style'] == "Aggressive" ? "Selected" : " ") . ' >Aggressive</option>
<option value="Conservative"' . ($chart[0]['style'] == "Conservative" ? "Selected" : " ") . ' >Conservative</option>
<option value="Balanced"' . ($chart[0]['style'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select><br><br>';

echo '<b>Focus</b>';
echo '<br><i>Affects success rate of selection AND the inverse.</i><br>';
echo '<select name="focus" id="focus">
<option value="NotSet"' . ($chart[0]['focus'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>
<option value="Run"' . ($chart[0]['focus'] == "Run" ? "Selected" : " ") . ' >Run</option>
<option value="Pass"' . ($chart[0]['focus'] == "Pass" ? "Selected" : " ") . ' >Pass</option>
<option value="Balanced"' . ($chart[0]['focus'] == "Balance" ? "Selected" : " ") . ' >Balance</option>
</select><br><br>';

echo '<b>Tempo</b>';
echo '<br><i>Affects clock consumption.</i><br>';
echo '<select name="tempo" id="tempo">
<option value="Normal"' . ($chart[0]['tempo'] == "Normal" ? "Selected" : " ") . ' >Normal</option>
<option value="VeryFast"' . ($chart[0]['tempo'] == "VeryFast" ? "Selected" : " ") . ' >Very Fast</option>
<option value="Fast"' . ($chart[0]['tempo'] == "Fast" ? "Selected" : " ") . ' >Fast</option>
<option value="Slow"' . ($chart[0]['tempo'] == "Slow" ? "Selected" : " ") . ' >Slow</option>
<option value="VerySlow"' . ($chart[0]['tempo'] == "VerySlow" ? "Selected" : " ") . ' >Very Slow</option>
</select><br><br>';

echo '<b>Pass Preference</b>';
echo '<br><i>Affects success rate for selected range, with minor penalties to the others.</i><br>';
echo '<select name="passPref" id="passPref">
<option value="NotSet"' . ($chart[0]['passPref'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>
<option value="Short"' . ($chart[0]['passPref'] == "Short" ? "Selected" : " ") . ' >Short</option>
<option value="Medium"' . ($chart[0]['passPref'] == "Medium" ? "Selected" : " ") . ' >Medium</option>
<option value="Long"' . ($chart[0]['passPref'] == "Long" ? "Selected" : " ") . ' >Long</option>
<option value="Balanced"' . ($chart[0]['passPref'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select><br><br>';

echo '<b>Pass Target Preference</b>';
echo '<br><i>Affects targeting of players and minor bonus to passing.</i><br>';
echo '<select name="passTargetPref" id="passTargetPref">
<option value="NotSet"' . ($chart[0]['passTargetPref'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>
<option value="Balanced"' . ($chart[0]['passTargetPref'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
<option value="Outside"' . ($chart[0]['passTargetPref'] == "Outside" ? "Selected" : " ") . ' >Outside</option>
<option value="TightEnds"' . ($chart[0]['passTargetPref'] == "TightEnds" ? "Selected" : " ") . ' >Tight Ends</option>
<option value="RunningBacks"' . ($chart[0]['passTargetPref'] == "RunningBacks" ? "Selected" : " ") . ' >Running Backs</option>
<option value="OverMiddle"' . ($chart[0]['passTargetPref'] == "OverMiddle" ? "Selected" : " ") . ' >Over Middle</option>
</select><br><br>';

echo '<b>Primary Receiver</b>';
echo '<br><i>Affects targeting on passes.</i><br>';
echo '<select name="primaryRec" id="primaryRec">';
$playerService = playerService($_SESSION['TeamID'],0,0);
echo '<option value="NotSet"' . ($chart[0]['primaryRec'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>';
foreach ($playerService as $player) {
    if (in_array($player['Position'], array('RB','FB','WR','TE'))) {
        echo '<option value="' . $player['FullName'] . '"' . ($chart[0]['primaryRec'] == $player['FullName']  ? "Selected" : " ") . ' >' . $player['FullName'] . '</option>';
    }
}
echo '</select><br><br>';

echo '<b>Third Down Back</b>';
echo '<br><i>Attempts to insert specified player on third downs.</i><br>';
echo '<select name="thirdDownBack" id="thirdDownBack">';
$playerService = playerService($_SESSION['TeamID'],0,0);
echo '<option value="NotSet"' . ($chart[0]['thirdDownBack'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>';
foreach ($playerService as $player) {
    if (in_array($player['Position'], array('RB','FB'))) {
        echo '<option value="' . $player['FullName'] . '"' . ($chart[0]['thirdDownBack'] == $player['FullName']  ? "Selected" : " ") . ' >' . $player['FullName'] . '</option>';
    }
}
echo '</select><br><br>';

echo '<b>Goal Line Back</b>';
echo '<br><i>Attempts to insert specified player on goal line situations.</i><br>';
echo '<select name="goalLineBack" id="goalLineBack">';
$playerService = playerService($_SESSION['TeamID'],0,0);
echo '<option value="NotSet"' . ($chart[0]['goalLineBack'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>';
foreach ($playerService as $player) {
    if (in_array($player['Position'], array('RB','FB'))) {
        echo '<option value="' . $player['FullName'] . '"' . ($chart[0]['goalLineBack'] == $player['FullName']  ? "Selected" : " ") . ' >' . $player['FullName'] . '</option>';
    }
}
echo '</select><br><br>';

echo '<b>Run Focus</b>';
echo '<br><i>Minor bonus to run type.</i><br>';
echo '<select name="runPref" id="runPref">
<option value="None"' . ($chart[0]['runPref'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Inside"' . ($chart[0]['runPref'] == "Inside" ? "Selected" : " ") . ' >Inside</option>
<option value="Outside"' . ($chart[0]['runPref'] == "Outside" ? "Selected" : " ") . ' >Outside</option>
<option value="Power"' . ($chart[0]['runPref'] == "Power" ? "Selected" : " ") . ' >Power</option>
<option value="From3WRSets"' . ($chart[0]['runPref'] == "From3WRSets" ? "Selected" : " ") . ' >3 WR Set</option>
<option value="Draw"' . ($chart[0]['runPref'] == "Draw" ? "Selected" : " ") . ' >Draw</option>
<option value="Balanced"' . ($chart[0]['runPref'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select><br><br>';

echo '<b>RB Role</b>';
echo '<br><i>Small increase in performance for selected role.</i><br>';
echo '<select name="rbRole" id="rbRole">
<option value="None"' . ($chart[0]['rbRole'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Running"' . ($chart[0]['rbRole'] == "Running" ? "Selected" : " ") . ' >Running</option>
<option value="Blocking"' . ($chart[0]['rbRole'] == "Blocking" ? "Selected" : " ") . ' >Blocking</option>
<option value="Catching"' . ($chart[0]['rbRole'] == "Catching" ? "Selected" : " ") . ' >Catching</option>
<option value="Mixed"' . ($chart[0]['rbRole'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select><br><br>';

echo '<b>Backfield By Committee?</b><br>';
echo '<br><i>Affect distribution of snaps.</i><br>';
echo '<input type="radio" id="bbc_yes" name="backfieldCom" value="bbc_yes" ' . ($chart[0]['backfieldCom'] == "bbc_yes" ? "Checked" : " ") . '>
<label for="bbc_yes">YES</label><br>
<input type="radio" id="bbc_no" name="backfieldCom" value="bbc_no"' . ($chart[0]['backfieldCom'] == "bbc_no" ? "Checked" : " ") . '>
<label for="bbc_no">NO</label><br>
</select><br>';

echo '<b>TE Role</b>';
echo '<br><i>Small increase in performance for selected role.</i><br>';
echo '<select name="teRole" id="teRole">
<option value="None"' . ($chart[0]['teRole'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Blocking"' . ($chart[0]['teRole'] == "Blocking" ? "Selected" : " ") . ' >Blocking</option>
<option value="Catching"' . ($chart[0]['teRole'] == "Catching" ? "Selected" : " ") . ' >Catching</option>
<option value="Mixed"' . ($chart[0]['teRole'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select><br><br>';

echo '<b>QB Tuck and Run</b>';
echo '<br><i>Affects likelihood of QB running on failed pass play.</i><br>';
echo '<select name="qbTuck" id="qbTuck">
<option value="Default"' . ($chart[0]['qbTuck'] == "Default" ? "Selected" : " ") . ' >Default</option>
<option value="Never"' . ($chart[0]['qbTuck'] == "Never" ? "Selected" : " ") . ' >Never</option>
<option value="Rare"' . ($chart[0]['qbTuck'] == "Rare" ? "Selected" : " ") . ' >Rare</option>
<option value="Often"' . ($chart[0]['qbTuck'] == "Often" ? "Selected" : " ") . ' >Often</option>
</select><br><br>';
echo '</td></tr></table><table class="depth" id="' . $_SESSION['abbreviation'] . '"><tr><td valign="top">';



echo '<h2>DEFENSE</h2>';

echo '<input type="hidden" id="defaultd" name="defaultd" value=" ">';

echo '<h3>Game Plan</h3>';
echo '<b>Attitude</b>';
echo '<br><i>Affects tackling, fumbles, missed tackles, interceptions, penalties.</i><br>';
echo '<select name="styled" id="styled">
<option value="Aggressive"' . ($chart[0]['styled'] == "Aggressive" ? "Selected" : " ") . ' >Aggressive</option>
<option value="Conservative"' . ($chart[0]['styled'] == "Conservative" ? "Selected" : " ") . ' >Conservative</option>
<option value="Balanced"' . ($chart[0]['styled'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select><br><br>';

echo '<b>Focus</b>';
echo '<br><i>Minor bonuses/penalties versus play type.</i><br>';
echo '<select name="focusd" id="focusd">
<option value="NotSet"' . ($chart[0]['focusd'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>
<option value="Run"' . ($chart[0]['focusd'] == "Run" ? "Selected" : " ") . ' >Run</option>
<option value="Pass"' . ($chart[0]['focusd'] == "Pass" ? "Selected" : " ") . ' >Pass</option>
<option value="Balanced"' . ($chart[0]['focusd'] == "Balance" ? "Selected" : " ") . ' >Balance</option>
</select><br><br>';

echo '<b>Primary Coverage</b>';
echo '<br><i>Affects performance of players coverage.</i><br>';
echo '<select name="coverPref" id="coverPref">
<option value="Not Set"' . ($chart[0]['coverPref'] == "Not Set" ? "Selected" : " ") . ' >Not Set</option>
<option value="Zone"' . ($chart[0]['coverPref'] == "Zone" ? "Selected" : " ") . ' >Zone</option>
<option value="Man"' . ($chart[0]['coverPref'] == "Man" ? "Selected" : " ") . ' >Man</option>
<option value="Mixed"' . ($chart[0]['coverPref'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select><br><br>';

echo '<b>Defensive Line Role</b>';
echo '<br><i>Small increase in performance for selected role.</i><br>';
echo '<select name="dlUse" id="dlUse">
<option value="None"' . ($chart[0]['dlUse'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="RunSupport"' . ($chart[0]['dlUse'] == "RunSupport" ? "Selected" : " ") . ' >Run Support</option>
<option value="PassRush"' . ($chart[0]['dlUse'] == "PassRush" ? "Selected" : " ") . ' >Pass Rush</option>
<option value="Mixed"' . ($chart[0]['dlUse'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select><br><br>';

echo '<b>Linebacker Role</b>';
echo '<br><i>Small increase in performance for selected role.</i><br>';
echo '<select name="lbUse" id="lbUse">
<option value="None"' . ($chart[0]['lbUse'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="RunSupport"' . ($chart[0]['lbUse'] == "RunSupport" ? "Selected" : " ") . ' >Run Support</option>
<option value="Coverage"' . ($chart[0]['lbUse'] == "Coverage" ? "Selected" : " ") . ' >Coverage</option>
<option value="Mixed"' . ($chart[0]['lbUse'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select><br><br>';

echo '<b>Wear Player Radio</b><br>';
echo '<i>Give player radio to defender you think
best supports <br>both run and pass scenarios
and hasa decently high intelligence<br>
to be able to make improved decisions and inform teammates. <br>Basically helps with recognition of plays
to defend passes better, <br>make better tackles, etc.</i><br>';
echo '<select name="primaryDef" id="primaryDef">';
$playerService = playerService($_SESSION['TeamID'],0,0);
echo '<option value="NotSet"' . ($chart[0]['primaryDef'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>';
foreach ($playerService as $player) {
    if (in_array($player['Position'], array('DT','DE','LB','CB','SS','FS'))) {
        echo '<option value="' . $player['FullName'] . '"' . ($chart[0]['primaryDef'] == $player['FullName']  ? "Selected" : " ") . ' >' . $player['FullName'] . '</option>';
    }
}
echo '</select><br><br>';

echo '<b>Match CBs to WRs?</b><br><br>';
echo '<i>Affects play selection, will attempt to have at least
as many CB<br> on the field as there are WR. (i.e. selecting 335, nickel<br> and dime plays
when offense lines up in shotgun and spread.)</i><br>';
echo '<input type="radio" id="match_yes" name="matchWR" value="match_yes" ' . ($chart[0]['matchWR'] == "match_yes" ? "Checked" : " ") . '>
<label for="match_yes">YES</label><br>
<input type="radio" id="match_no" name="matchWR" value="match_no" ' . ($chart[0]['matchWR'] == "match_no" ? "Checked" : " ") . '>
<label for="match_no">NO</label><br>
</select><br>';

echo '<b>Align Man Coverage</b><br>';
echo '<i>Affects where defenders line up to cover receivers.</i><br>';
echo '<select name="alignMan" id="alignMan">
<option value="None"' . ($chart[0]['alignMan'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Never"' . ($chart[0]['alignMan'] == "Never" ? "Selected" : " ") . ' >Never</option>
<option value="Balanced"' . ($chart[0]['alignMan'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
<option value="Always"' . ($chart[0]['alignMan'] == "Always" ? "Selected" : " ") . ' >Always</option>
</select><br><br>';

echo '<b>Target Player</b><br>';
echo '<i>Allows you to set a defensive focus for extra attention towards an offensive player.</i><br>';
echo '<select name="target" id="target">';
$playerService = playerService($oppId,0,0);
echo '<option value="NotSet"' . ($chart[0]['target'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>';
usort($playerService, fn($a, $b) => $b['Overall'] <=> $a['Overall']);
foreach ($playerService as $player) {
    if (in_array($player['Position'], array('RB','FB','WR','TE'))) {
        echo '<option value="' . $player['FullName'] . '"' . ($chart[0]['target'] == $player['FullName']  ? "Selected" : " ") . ' >' . $player['FullName'] . '</option>';
    }
}
echo '</select><br><br>';
echo '</td></tr></table><table class="depth" id="' . $_SESSION['abbreviation'] . '"><tr><td valign="top">';



echo '<h2>Substitution and End Game</h2>';

echo '<p><b>NOTE!!!   Only fill this out IF you used MANUAL Game Plans!!!!!</b></p>';

/*echo '<b>Use Defaults?</b><br>';
echo '<input type="radio" id="def_yes" name="defaultSub" value="def_yes" ' . ($chart[0]['defaultSub'] == "def_yes" ? "Checked" : " ") . '>
<label for="def_yes">YES (Move On to Counter Strategy)</label><br>
<input type="radio" id="def_no" name="defaultSub" value="def_no" ' . ($chart[0]['defaultSub'] == "def_no" ? "Checked" : " ") . '>
<label for="def_no">NO</label><br>
</select><br>';*/

echo '<br><br><input type="hidden" id="defaultSub" name="defaultSub" value=" ">';
echo '<input type="hidden" id="handlePrep" name="handlePrep" value=" ">';

echo '<b>Point Margin for Backup Package</b><br>';
echo '<i>Uses Backup package once team gets ahead or behind this amount.</i><br>';
echo '<input type="number" min=1 max=70 id="margin" name="margin" value="'.$chart[0]['margin'].'"><br><br>';

echo '<b>Energy Sub - Min (40-70)</b>';
echo '<input type="number" min=40 max=70 id="energyMin" name="energyMin" value="'.$chart[0]['energyMin'].'"><br><br>';

echo '<b>Energy Sub - Max (70-100)</b>';
echo '<input type="number" min=70 max=100 id="energyMax" name="energyMax" value="'.$chart[0]['energyMax'].'">';

echo '<br><i>Once energy goes below Min use backup until energy recovers above Max</i><br><br>';

echo '<b>Endgame Override Winning</b>';
echo '<select name="overrideWin" id="overrideWin">
<option value="None"' . ($chart[0]['overrideWin'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Run More"' . ($chart[0]['overrideWin'] == "RunMore" ? "Selected" : " ") . ' >Run More</option>
<option value="Pass More"' . ($chart[0]['overrideWin'] == "PassMore" ? "Selected" : " ") . ' >Pass More</option>
<option value="Only Run"' . ($chart[0]['overrideWin'] == "OnlyRun" ? "Selected" : " ") . ' >Only Run</option>
<option value="Only Pass"' . ($chart[0]['overrideWin'] == "OnlyPass" ? "Selected" : " ") . ' >Only Pass</option>
</select><br><br>';

echo '<b>Endgame Override Losing</b>';
echo '<select name="overrideLose" id="overrideLose">
<option value="None"' . ($chart[0]['overrideLose'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Run More"' . ($chart[0]['overrideLose'] == "RunMore" ? "Selected" : " ") . ' >Run More</option>
<option value="Pass More"' . ($chart[0]['overrideLose'] == "PassMore" ? "Selected" : " ") . ' >Pass More</option>
<option value="Only Run"' . ($chart[0]['overrideLose'] == "OnlyRun" ? "Selected" : " ") . ' >Only Run</option>
<option value="Only Pass"' . ($chart[0]['overrideLose'] == "OnlyPass" ? "Selected" : " ") . ' >Only Pass</option>
</select><br><br>';

echo '<h2>Counter Strategy</h2>';

echo '<br><b><i>Current League Setting for Counter Prep: Type</b><br>
Coordinators Handle Prep: Delegates the preparation for each game to your Coordinators.<br>
Prepare vs Offense: Get defensive bonuses by preparing against offensive plays.<br>
Prepare vs Defense: Get offensive bonuses by preparing against defensive plays.</i><br><br>';

/*echo '<b>Coordinators Handle Prep?</b><br>';
echo '<input type="radio" id="prep_yes" name="handlePrep" value="prep_yes"  ' . ($chart[0]['handlePrep'] == "prep_yes" ? "Checked" : " ") . '>
<label for="prep_yes">YES (ALL SET!  YOU CAN HIT SUBMIT!)</label><br>
<input type="radio" id="prep_no" name="handlePrep" value="prep_no" ' . ($chart[0]['handlePrep'] == "prep_no" ? "Checked" : " ") . '>
<label for="prep_no">NO</label><br>
</select><br>';*/

echo '<b>Offensive Prep</b>';
echo '<select name="oPrep" id="oPrep">
<option value="ShortPass"' . ($chart[0]['oPrep'] == "ShortPass" ? "Selected" : " ") . ' >Short Passing</option>
<option value="MediumPass"' . ($chart[0]['oPrep'] == "MediumPass" ? "Selected" : " ") . ' >Medium Passing</option>
<option value="LongPass"' . ($chart[0]['oPrep'] == "LongPass" ? "Selected" : " ") . ' >Long Passing</option>
<option value="InsideRun"' . ($chart[0]['oPrep'] == "InsideRun" ? "Selected" : " ") . ' >Inside Running</option>
<option value="OutsideRun"' . ($chart[0]['oPrep'] == "OutsideRun" ? "Selected" : " ") . ' >Outside Running</option>
<option value="PowerRun"' . ($chart[0]['oPrep'] == "PowerRun" ? "Selected" : " ") . ' >Power Running</option>
</select><br><br>';

echo '<b>Defensive Prep</b>';
echo '<select name="dPrep" id="dPrep">
<option value="Zone"' . ($chart[0]['dPrep'] == "Zone" ? "Selected" : " ") . ' >Zone</option>
<option value="Man"' . ($chart[0]['dPrep'] == "Man" ? "Selected" : " ") . ' >Man</option>
</select><br><br>
</td></tr></table></div>


<br><br><center><input type="submit" value="Submit Game Plan"></center>
<br><br>'; 
?>
