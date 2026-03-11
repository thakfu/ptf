<?php
include 'header.php';
$scenarios = ['First and 10'=>'1st10',
'First and Short'=>'1stSh',
'First and Medium'=>'1stMd',
'First and Long'=>'1stLg',
'Second and Short'=>'2ndSh',
'Second and Medium'=>'2ndMd',
'Second and Long'=>'2ndLg',
'Third and Short'=>'3rdSh',
'Third and Medium'=>'3rdMd',
'Third and Long'=>'3rdLg',
'Fourth and Short'=>'4thSh',
'Fourth and Medium'=>'4thMd',
'Fourth and Long'=>'4thLg',
'Goal Line'=>'GL',
'End of Half or Game (Winning)'=>'EoHW',
'End of Half or Game (Losing)'=>'EoHL'];
$chart = array();

$depth = $connection->query('SELECT * FROM `ptf_playcalling` WHERE TeamID = ' . $_SESSION['TeamID']);
while($row = $depth->fetch_assoc()) {
    array_push($chart, $row);
}
echo "<td valign='top'><h2>STRATEGY</h2>";

echo '<form action="submit_strategy.php" method="POST">';
echo '<input type="hidden" id="Team" name="Team" value="' . $_SESSION['mascot'] . '">';
echo '<input type="hidden" id="TeamID" name="TeamID" value="' . $_SESSION['TeamID'] . '">';
echo '<input type="hidden" id="Time" name="Time" value="' . date('Y-m-d H:i:s') . '">';
echo '<input type="hidden" id="Type" name="Type" value="playcalling">';

echo '<table id="' . $_SESSION['abbreviation'] . '"><tr><td valign="top">';
echo '<h3>Use Coach Settings for Offense?</h3>';
echo '<select name="offUseCoach" id="offUseCoach">
<option value="no"' . ($chart[0]['offUseCoach'] == "no" ? "Selected" : " ") . ' >Manual</option>
<option value="HC"' . ($chart[0]['offUseCoach'] == "HC" ? "Selected" : " ") . ' >Head Coach</option>
</select><br><br>';
// <option value="OC"' . ($chart[0]['offUseCoach'] == "OC" ? "Selected" : " ") . ' >Offensive Coordinator</option>

echo '<h3>Use Coach Settings for Defense?</h3>';
echo '<select name="defUseCoach" id="defUseCoach">
<option value="no"' . ($chart[0]['defUseCoach'] == "no" ? "Selected" : " ") . ' >Manual</option>
<option value="HC"' . ($chart[0]['defUseCoach'] == "HC" ? "Selected" : " ") . ' >Head Coach</option>
</select><br><br>';
// <option value="DC"' . ($chart[0]['defUseCoach'] == "DC" ? "Selected" : " ") . ' >Defensive Coordinator</option>

echo '</td></tr><tr><td>
<h3>Playcalling Help</h3>
<b>Distances</b><br>
Short: 0-3 yards <br>Medium: 4-6 yards <br>Long: 7+ yards<br><br>
<b>Run/Pass</b><br>
Basically, the higher the number the more often a pass will be called. The numbers are NOT percentages.<br>
The numbers are weights and are blended with the configuration settings for your league. Setting your pass <br>
weight to 100 does not mean you will pass 100% of the time. It means you are setting it to maximum pass <br>
percentage allowed by your league.<br>
Also note that run/pass decisions are also adjusted by coaching personalities, game situations, and some element of randomness.<br><br>
<b>Blitzing</b><br>
The higher the number the more blitzing will occur. If a blitz is called the defense will select a blitz from the internal blitzing playbook.<br>
For more control: Setting the blitz number to 0 will make the Al only call plays from the assigned playbook. So make sure your playbook <br>
has an appropriate amount of blitz plays for your desired aggressiveness.<br><br>
</td></tr></table>';

echo '<table id="' . $_SESSION['abbreviation'] . '"><tr><td>';
echo '<h2>Offense Run/Pass Ratio</h2>
<table id="' . $_SESSION['abbreviation'] . '">
    <tr>
        <th>Scenario</th>
        <th>Percent</th>
    </tr>';

    foreach ($scenarios as $scenKey=>$scenValue) {
        echo '<tr><td>' . $scenKey . '</td><td><input type="number" min="1" max="100" value="'. $chart[0]['o'.$scenValue].'" name="o'.$scenValue. '" id="o'.$scenValue. '"></td></tr>';
    }

echo '</table><br>
</td><td>
<h2>Defense Blitz Weight</h2>
<table id="' . $_SESSION['abbreviation'] . '">
    <tr>
        <th>Scenario</th>
        <th>Percent</th>
    </tr>';

    foreach ($scenarios as $scenKey=>$scenValue) {
        echo '<tr><td>' . $scenKey . '</td><td><input type="number" min="1" max="100" value="'. $chart[0]['d'.$scenValue].'" name="d'.$scenValue. '" id="d'.$scenValue. '"></td></tr>';
    }

echo '</table><br>
</td></tr></table>
<br><br><center><input type="submit" value="Submit Playcalling"></center>
<br><br>'; 

?>