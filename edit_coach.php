<?php
include 'header.php';

$stmt = $connection->query('SELECT * FROM ptf_coaches c LEFT JOIN ptf_coaches_export e ON c.CoachID = e.ID WHERE (c.Job = "HeadCoach" or c.Job = "Head Coach") AND c.TeamID = ' . $_SESSION['TeamID']);
$coach = array();

$coach = $stmt->fetch_assoc();

$stmt2 = $connection->query("SHOW TABLE STATUS LIKE 'ptf_coaches'");
$lastid = $stmt2->fetch_assoc();
$nextID = $lastid['Auto_increment'];

if(!$coach['CoachID']) {
    $coachID = $nextID;
} else {
    $coachID = $coach['CoachID'];
}

echo '<h1>Create / Edit Your Coach!</h1>';
echo '<form action="submit_coach.php" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" id="User" name="User" value="' . $_SESSION['user_id'] . '">';
echo '<input type="hidden" id="CoachID" name="CoachID" value="' . $coachID . '">';

echo '<h3 align="center">Identity</h3>';
echo '<table></tr><th>Attribute</th><th>Rating</th></tr>';

echo '<tr><th>First Name</th>';
echo '<td><input type="text" id="firstname" name="firstname" value="'.$coach['FirstName'].'"></td></tr>';
echo '<tr><th>Last Name</th>';
echo '<td><input type="text" id="lastname" name="lastname" value="'.$coach['LastName'].'"></td></tr>';

echo '<tr><th>Age</th>';
echo '<td><input type="text" id="age" name="age" value="'.$coach['Age'].'"></td></tr>';

//echo '<tr><th>(Optional) Image File</th>';
//echo '<td><input type="file" name="fileToUpload" id="fileToUpload"></td></tr>';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Base Attributes</h3>';
echo '<table></tr><th>Attribute</th><th>Rating</th></tr>';

echo '<tr><th>Team</th>';
if(!$_SESSION['TeamID']) {
    $team = 0;
} else {
    $team = $_SESSION['TeamID'];
}
echo '<td>'.idToAbbrev($team).'('. $team .')</td></tr>';
echo '<input type="hidden" id="team" name="team" value="' . $team . '">';

echo '<tr><th>Job</th>';
if(!$coach['Job']) {
    $job = 'HeadCoach';
} else {
    $job= $coach['Job'];
}
echo '<td>'.$job.'</td></tr>';
echo '<input type="hidden" id="job" name="job" value="' . $job . '">';

echo '<tr><th>Experience</th>';
if(!$coach['Experience']) {
    $exp = 0;
} else {
    $exp= $coach['Experience'];
}
echo '<td>'.$exp.'</td></tr>';
echo '<input type="hidden" id="experience" name="experience" value="' . $exp . '">';

echo '<tr><th>Reputation</th>';
if(!$coach['Reputation']) {
    $rep = 75;
} else {
    $rep= $coach['Reputation'];
}
echo '<td>'.$rep.'</td></tr>';
echo '<input type="hidden" id="reputation" name="reputation" value="' . $rep . '">';

echo '<tr><th>Salary</th>';
if(!$coach['Salary']) {
    $sal = 10;
    $years = 20;
} else {
    $sal = $coach['Salary'];
    $years = $coach['years'];
}
echo '<td>'.$years.' Years - $'.$sal.',000,000 per/year</td></tr>';
echo '<input type="hidden" id="years" name="years" value="' . $years . '">';
echo '<input type="hidden" id="salary" name="salary" value="' . $sal . '">';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Style Attributes</h3>';
echo '<table></tr><th>Attribute</th><th>Rating</th></tr>';

echo '<tr><th>Style</th>';
echo '<td> <select name="style" id="style">
<option value="Aggressive"' . ($coach['Style'] == "Aggressive" ? "Selected" : " ") . ' >Aggressive</option>
<option value="Conservative"' . ($coach['Style'] == "Conservative" ? "Selected" : " ") . ' >Conservative</option>
<option value="Balanced"' . ($coach['Style'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select></td></tr>';

echo '<tr><th>Attitude</th>';
echo '<td><select name="attitude" id="attitude">
<option value="Relaxed"' . ($coach['Attitude'] == "Relaxed" ? "Selected" : " ") . ' >Relaxed</option>
<option value="Stern"' . ($coach['Attitude'] == "Stern" ? "Selected" : " ") . ' >Stern</option>
<option value="Silent"' . ($coach['Attitude'] == "Silent" ? "Selected" : " ") . ' > Silent</option>
<option value="Professional"' . ($coach['Attitude'] == "Professional" ? "Selected" : " ") . ' >Professional</option>
<option value="Enthusiastic"' . ($coach['Attitude'] == "Enthusiastic" ? "Selected" : " ") . ' >Enthusiastic</option>
<option value="Volatile"' . ($coach['Attitude'] == "Volatile" ? "Selected" : " ") . ' >Volatile</option>
</select></td></tr>';

echo '<tr><th>Background</th>';
echo '<td><select name="background" id="background">
<option value="None"' . ($coach['Background'] == "None" ? "Selected" : " ") . ' >None</option>
<option value="Player"' . ($coach['Background'] == "Player" ? "Selected" : " ") . ' >Player</option>
<option value="College Coach"' . ($coach['Background'] == "College Coach" ? "Selected" : " ") . ' >College Coach</option>
<option value="Position Coach"' . ($coach['Background'] == "Position Coach" ? "Selected" : " ") . ' >Position Coach</option>
<option value="Pro Coach"' . ($coach['Background'] == "Pro Coach" ? "Selected" : " ") . ' >Pro Coach</option>
</select></td></tr>';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Training Attributes</h3>';
echo '<p align="center"><i>These should add up to 40 points total!</i></p>';
echo '<table></tr><th>Attribute</th><th>Rating</th><th>Usage</th></tr>';

echo '<tr><th>Development</th>';
echo '<td><input type="number" min=0 max=10 id="development" name="development" value="'.$coach['Development'].'"></td>
<td>affects all training</td></tr>';

echo '<tr><th>Detail</th>';
echo '<td><input type="number" min=0 max=10 id="detail" name="detail" value="'.$coach['AttentionToDetail'].'"></td>
<td>affect training of elite players</td></tr>';

echo '<tr><th>Youngsters</th>';
echo '<td><input type="number" min=0 max=10 id="youngsters" name="youngsters" value="'.$coach['Youngsters'].'"></td>
<td>affect training on young players</td></tr>';

echo '<tr><th>Physical</th>';
echo '<td><input type="number" min=0 max=10 id="physical" name="physical" value="'.$coach['PhysicalTraining'].'"></td>
<td>affects Physical and athletic training
</td></tr>';

echo '<tr><th>QBs</th>';
echo '<td><input type="number" min=0 max=10 id="trainqb" name="trainqb" value="'.$coach['TrainQB'].'"></td>
<td>affects training of the specific position</td></tr>';

echo '<tr><th>Skill Positions</th>';
echo '<td><input type="number" min=0 max=10 id="trainskill" name="trainskill" value="'.$coach['TrainOffSkill'].'"></td>
<td>affects training of the specific position</td></tr>';

echo '<tr><th>Special Teams</th>';
echo '<td><input type="number" min=0 max=10 id="trainst" name="trainst" value="'.$coach['TrainSTS'].'"></td>
<td>affects training of the specific position</td></tr>';

echo '<tr><th>Offensive Line</th>';
echo '<td><input type="number" min=0 max=10 id="trainol" name="trainol" value="'.$coach['TrainOL'].'"></td>
<td>affects training of the specific position</td></tr>';

echo '<tr><th>Defensive Line</th>';
echo '<td><input type="number" min=0 max=10 id="traindl" name="traindl" value="'.$coach['TrainDLine'].'"></td>
<td>affects training of the specific position</td></tr>';

echo '<tr><th>Linebackers</th>';
echo '<td><input type="number" min=0 max=10 id="trainlb" name="trainlb" value="'.$coach['TrainLBs'].'"></td>
<td>affects training of the specific position</td></tr>';

echo '<tr><th>Defensive Backs</th>';
echo '<td><input type="number" min=0 max=10 id="traindb" name="traindb" value="'.$coach['TrainDBs'].'"></td>
<td>affects training of the specific position</td></tr>';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Strategy Attributes</h3>';
echo '<p align="center"><i>These should add up to 15 points total!</i></p>';
echo '<table></tr><th>Attribute</th><th>Rating</th><th>Usage</th></tr>';

echo '<tr><th>Coach Offense</th>';
echo '<td><input type="number" min=0 max=10 id="coachoff" name="coachoff" value="'.$coach['CoachingOff'].'"></td>
<td>affects playcalling, training</td></tr>';

echo '<tr><th>Coach Defense</th>';
echo '<td><input type="number" min=0 max=10 id="coachdef" name="coachdef" value="'.$coach['CoachingDef'].'"></td>
<td>affects playcalling, training</td></tr>';

echo '<tr><th>Preparation</th>';
echo '<td><input type="number" min=0 max=10 id="prep" name="prep" value="'.$coach['Prep'].'"></td>
<td>affects playcalling and strategy</td></tr>';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Personality Attributes</h3>';
echo '<p align="center"><i>These should add up to 30 points total!</i></p>';
echo '<table></tr><th>Attribute</th><th>Rating</th><th>Usage</th></tr>';

echo '<tr><th>Intelligence</th>';
echo '<td><input type="number" min=0 max=10 id="intelligence" name="intelligence" value="'.$coach['Intelligence'].'"></td>
<td>affects for a range of things including scouting, training, and playcalling</td></tr>';

echo '<tr><th>Flexibility</th>';
echo '<td><input type="number" min=0 max=10 id="flexibility" name="flexibility" value="'.$coach['Flexibility'].'"></td>
<td>affects efficiency when forced to use other schemes than his preferred one</td></tr>';

echo '<tr><th>Discipline</th>';
echo '<td><input type="number" min=0 max=10 id="discipline" name="discipline" value="'.$coach['Discipline'].'"></td>
<td>Matters for dealing with players with low morale and low work ethic, affects tolerance of players with negative traits</td></tr>';

echo '<tr><th>Motivating</th>';
echo '<td><input type="number" min=0 max=10 id="motivating" name="motivating" value="'.$coach['Motivating'].'"></td>
<td>Matters for morale and on field performance</td></tr>';

echo '<tr><th>Charisma</th>';
echo '<td><input type="number" min=0 max=10 id="charisma" name="charisma" value="'.$coach['Charisma'].'"></td>
<td>affects chance to sign players and morale</td></tr>';

echo '<tr><th>Leadership</th>';
echo '<td><input type="number" min=0 max=10 id="leadership" name="leadership" value="'.$coach['Leadership'].'"></td>
<td>matters for on field performance, morale</td></tr>';

echo '<tr><th>Loyalty</th>';
echo '<td><input type="number" min=0 max=10 id="loyalty" name="loyalty" value="'.$coach['Loyalty'].'"></td>
<td>Matters for contract extension and for the likelihood he will accept job offers from other teams (if he is a coordinator)</td></tr>';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Offense Preferences</h3>';
echo '<table></tr><th>Attribute</th><th>Rating</th></tr>';

echo '<tr><th>Focus</th>';
echo '<td> <select name="focus" id="focus">
<option value="Run"' . ($coach['Focus'] == "Run" ? "Selected" : " ") . ' >Run</option>
<option value="Pass"' . ($coach['Focus'] == "Pass" ? "Selected" : " ") . ' >Pass</option>
<option value="Balanced"' . ($coach['Focus'] == "Balance" ? "Selected" : " ") . ' >Balance</option>
</select></td></tr>';

echo '<tr><th>RB Role</th>';
echo '<td><select name="RBRole" id="RBRole">
<option value="Running"' . ($coach['RBRole'] == "Running" ? "Selected" : " ") . ' >Running</option>
<option value="Blocking"' . ($coach['RBRole'] == "Blocking" ? "Selected" : " ") . ' >Blocking</option>
<option value="Catching"' . ($coach['RBRole'] == "Catching" ? "Selected" : " ") . ' >Catching</option>
<option value="Mixed"' . ($coach['RBRole'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';

echo '<tr><th>FB Role</th>';
echo '<td><select name="FBRole" id="FBRole">
<option value="Running"' . ($coach['FBRole'] == "Running" ? "Selected" : " ") . ' >Running</option>
<option value="Blocking"' . ($coach['FBRole'] == "Blocking" ? "Selected" : " ") . ' >Blocking</option>
<option value="Catching"' . ($coach['FBRole'] == "Catching" ? "Selected" : " ") . ' >Catching</option>
<option value="Mixed"' . ($coach['FBRole'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';

echo '<tr><th>TE Role</th>';
echo '<td><select name="TERole" id="TERole">
<option value="Blocking"' . ($coach['TERole'] == "Blocking" ? "Selected" : " ") . ' >Blocking</option>
<option value="Catching"' . ($coach['TERole'] == "Catching" ? "Selected" : " ") . ' >Catching</option>
<option value="Mixed"' . ($coach['TERole'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';

echo '<tr><th>QB Preference</th>';
echo '<td><select name="QBPref" id="QBPref">
<option value="Mobile"' . ($coach['QBPref'] == "Mobile" ? "Selected" : " ") . ' >Mobile</option>
<option value="Pocket"' . ($coach['QBPref'] == "Pocket" ? "Selected" : " ") . ' >Pocket</option>
<option value="None"' . ($coach['QBPref'] == "None" ? "Selected" : " ") . ' >None</option>
</select></td></tr>';

echo '<tr><th>Offensive Scheme</th>';
echo '<td><select name="OffScheme" id="OffScheme">
<option value="WestCoast"' . ($coach['OffScheme'] == "WestCoast" ? "Selected" : " ") . ' >West Coast</option>
<option value="Power"' . ($coach['OffScheme'] == "Power" ? "Selected" : " ") . ' >Power</option>
<option value="Spread"' . ($coach['OffScheme'] == "Spread" ? "Selected" : " ") . ' >Spread</option>
<option value="ProStyle"' . ($coach['OffScheme'] == "ProStyle" ? "Selected" : " ") . ' >Pro Style</option>
<option value="Vertical"' . ($coach['OffScheme'] == "Vertical" ? "Selected" : " ") . ' >Vertical</option>
<option value="WestCoastHybrid"' . ($coach['OffScheme'] == "WestCoastHybrid" ? "Selected" : " ") . ' >West Coast Hybrid</option>
<option value="PowerHybrid"' . ($coach['OffScheme'] == "PowerHybrid" ? "Selected" : " ") . ' >Power Hybrid</option>
<option value="SpreadHybrid"' . ($coach['OffScheme'] == "SpreadHybrid" ? "Selected" : " ") . ' >Spread Hybrid</option>
<option value="ProStyleHybrid"' . ($coach['OffScheme'] == "Pro StyleHybrid" ? "Selected" : " ") . ' >Pro Style Hybrid</option>
<option value="VerticalHybrid"' . ($coach['OffScheme'] == "VerticalHybrid" ? "Selected" : " ") . ' >Vertical Hybrid</option>
<option value="SmashMouthHybrid"' . ($coach['OffScheme'] == "SmashMouthHybrid" ? "Selected" : " ") . ' >Smash Mouth Hybrid</option>
</select></td></tr>';

echo '<tr><th>Run Preference</th>';
echo '<td><select name="RunPref" id="RunPref">
<option value="Inside"' . ($coach['RunPref'] == "Inside" ? "Selected" : " ") . ' >Inside</option>
<option value="Outside"' . ($coach['RunPref'] == "Outside" ? "Selected" : " ") . ' >Outside</option>
<option value="Power"' . ($coach['RunPref'] == "Power" ? "Selected" : " ") . ' >Power</option>
<option value="From3WRSets"' . ($coach['RunPref'] == "From3WRSets" ? "Selected" : " ") . ' >3 WR Set</option>
<option value="Draw"' . ($coach['RunPref'] == "Draw" ? "Selected" : " ") . ' >Draw</option>
<option value="Balanced"' . ($coach['RunPref'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select></td></tr>';

echo '<tr><th>Pass Preference</th>';
echo '<td><select name="PassPref" id="PassPref">
<option value="Short"' . ($coach['PassPref'] == "Short" ? "Selected" : " ") . ' >Short</option>
<option value="Medium"' . ($coach['PassPref'] == "Medium" ? "Selected" : " ") . ' >Medium</option>
<option value="Long"' . ($coach['PassPref'] == "Long" ? "Selected" : " ") . ' >Long</option>
<option value="Balanced"' . ($coach['PassPref'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
</select></td></tr>';

echo '<tr><th>Pass Target Preference</th>';
echo '<td><select name="PassTargetPref" id="PassTargetPref">
<option value="Balanced"' . ($coach['PassTargetPref'] == "Balanced" ? "Selected" : " ") . ' >Balanced</option>
<option value="Outside"' . ($coach['PassTargetPref'] == "Outside" ? "Selected" : " ") . ' >Outside</option>
<option value="TightEnds"' . ($coach['PassTargetPref'] == "TightEnds" ? "Selected" : " ") . ' >Tight Ends</option>
<option value="RunningBacks"' . ($coach['PassTargetPref'] == "RunningBacks" ? "Selected" : " ") . ' >Running Backs</option>
<option value="OverMiddle"' . ($coach['PassTargetPref'] == "OverMiddle" ? "Selected" : " ") . ' >Over Middle</option>
</select></td></tr>';

echo '<tr><th>WR Preference</th>';
echo '<td><select name="WRPref" id="WRPref">
<option value="Big"' . ($coach['WRPref'] == "Big" ? "Selected" : " ") . ' >Big</option>
<option value="Fast"' . ($coach['WRPref'] == "Fast" ? "Selected" : " ") . ' >Fast</option>
<option value="Flexible"' . ($coach['WRPref'] == "Flexible" ? "Selected" : " ") . ' >Flexible</option>
<option value="Hands"' . ($coach['WRPref'] == "Hands" ? "Selected" : " ") . ' >Hands</option>
<option value="Mixed"' . ($coach['WRPref'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';
echo '</table>';

echo '<hr>';
echo '<h3 align="center">Defense Preferences</h3>';
echo '<table></tr><th>Attribute</th><th>Rating</th></tr>';

echo '<tr><th>Defensive Scheme</th>';
echo '<td><select name="DefScheme" id="DefScheme">
<option value="D34"' . ($coach['DefScheme'] == "D34" ? "Selected" : " ") . ' >3-4 </option>
<option value="D43"' . ($coach['DefScheme'] == "D43" ? "Selected" : " ") . ' >4-3</option>
<option value="D335"' . ($coach['DefScheme'] == "D335" ? "Selected" : " ") . ' >3-3-5</option>
<option value="D34Hybrid"' . ($coach['DefScheme'] == "D34Hybrid" ? "Selected" : " ") . ' >3-4 Hybrid</option>
<option value="D43Hybrid"' . ($coach['DefScheme'] == "D43Hybrid" ? "Selected" : " ") . ' >4-3 Hybrid</option>
<option value="D335Hybrid"' . ($coach['DefScheme'] == "D335Hybrid" ? "Selected" : " ") . ' >3-3-5 Hybrid</option>
<option value="D52"' . ($coach['DefScheme'] == "D52" ? "Selected" : " ") . ' >52</option>
<option value="D52Hybrid"' . ($coach['DefScheme'] == "D52Hybrid" ? "Selected" : " ") . ' 52 Hybrid</option>
</select></td></tr>';

echo '<tr><th>Key Defensive Position</th>';
echo '<td><select name="KeyDefPos" id="KeyDefPos">
<option value="DL"' . ($coach['KeyDefPos'] == "DL" ? "Selected" : " ") . ' >DL</option>
<option value="LB"' . ($coach['KeyDefPos'] == "LB" ? "Selected" : " ") . ' >LB</option>
<option value="CB"' . ($coach['KeyDefPos'] == "CB" ? "Selected" : " ") . ' >CB</option>
<option value="S"' . ($coach['KeyDefPos'] == "S" ? "Selected" : " ") . ' >S</option>
<option value="NotSet"' . ($coach['KeyDefPos'] == "NotSet" ? "Selected" : " ") . ' >Not Set</option>
</select></td></tr>';

echo '<tr><th>Defensive Line Use</th>';
echo '<td><select name="DLUse" id="DLUse">
<option value="RunSupport"' . ($coach['DLUse'] == "RunSupport" ? "Selected" : " ") . ' >Run Support</option>
<option value="PassRush"' . ($coach['DLUse'] == "PassRush" ? "Selected" : " ") . ' >Pass Rush</option>
<option value="Mixed"' . ($coach['DLUse'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';

echo '<tr><th>Linebacker Use</th>';
echo '<td><select name="LBUse" id="LBUse">
<option value="RunSupport"' . ($coach['LBUse'] == "RunSupport" ? "Selected" : " ") . ' >Run Support</option>
<option value="Coverage"' . ($coach['LBUse'] == "Coverage" ? "Selected" : " ") . ' >Coverage</option>
<option value="Mixed"' . ($coach['LBUse'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';

echo '<tr><th>Cover Preference</th>';
echo '<td><select name="CoverPref" id="CoverPref">
<option value="Zone"' . ($coach['CoverPref'] == "Zone" ? "Selected" : " ") . ' >Zone</option>
<option value="Man"' . ($coach['CoverPref'] == "Man" ? "Selected" : " ") . ' >Man</option>
<option value="Mixed"' . ($coach['CoverPref'] == "Mixed" ? "Selected" : " ") . ' >Mixed</option>
</select></td></tr>';
echo '</table>';

echo '<br><br><p align="center"><input type="submit" value="Submit Coach"></p>';

?>