
<div class='power'>
    <h3>Standings</h3>
    <?php include 'standings.php'; ?>
    <hr>
    <h3>Power Rankings</h3>
    <table id='powRank' border='1'>
        <tr><th>#</th><th>Team</th><th>Score</th></tr>
        <?php
            $power = array();
            $rank = 1;
            $stmt = $connection->query("SELECT t.TeamID, t.City, t.Mascot, t.Abbrev, t.PowerRanking, d.color_1, d.color_2 FROM ptf_teams t LEFT JOIN ptf_teams_data d ON d.TeamID = t.TeamID WHERE t.TeamID < 50 ORDER by t.PowerRanking DESC");
            while($row = $stmt->fetch_assoc()) {
                array_push($power, $row);
            }
            foreach ($power as $pow) {
                echo '<tr style="background-color:#'.$pow['color_1']. ';color:#'.$pow['color_2'].';"><th>' . $rank . '</th><th><a href="team.php?team=' . $pow['TeamID'] . '">' . $pow['City'] . '</a></th><th>' . number_format(round($pow['PowerRanking'],2),2) . '</th></tr>';
                $rank++;
            }
        ?>
    </table><br>
</div>
