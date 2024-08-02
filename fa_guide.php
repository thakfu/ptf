<?php
?>
<h1>Free Agency Cheat Sheet</h1>
<p>
    <b>Demand(D) - <i>($$)</i></b> The amount of money a player demands. Meeting this value triggers a successful offer.  This decreases daily.<br><br>
    Decrease per day = 10%... This percentage is decreased by 1% for every attribute point above 90 that a particular player has!  For instance, a player with a Money demand of 94 will decrease by only 6% per day!<br><br>
    On the last day of free agency a player will accept any amount of money EXCEPT for players with a money rating great than 90.  Those players will accept only amounts above the depreciated demand.<br><br>
    Players with a money demand of 100 will accept 75% of their demand ONLY on the last day!  Otherwise their demand does not change!<br><br>
    A player's demanded amount is based on their overall rating (or a rating based on prior year performance, whichever is higher) and their money attribute combined.  Based on this number they will ask for an amount in line with other players pay at their given position. A maxed out player will demand to be the highest paid player at his position with a randomized amount added on top of the current highest paid.  Other potential demands are Top 5 Average, Position Average or a set dollar amount.<br><br>
    <a href='highestpaid.php'>Refer to this page for the highest paid players at each position.</a>
</p>
<p>
    <b>Money(M) - <i>(70-100)</i></b> A Player will always value more money.  This is foundation of any free agency offer.  Players with higher money will get a bigger multiplier for the higher contract.<br><br>
    Money Offered(m) refers to AVERAGE salary per year.
</p>
<p>
    <b>Security(S) - <i>(30-50)</i></b> A Player with higher security values a longer contract.<br><br>
    Security Multiplier (Sm)  
    <table border=1>
        <tr><th>(Sm)</th><th colspan=6>Years Offered (s)</th></tr>
        <tr><th>Value (S)</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th></tr>   
        <tr><td>30-35:</td><td>1.15</td><td>1.1</td><td>1</td><td>0.9</td><td>0.9</td><td>0.85</td></tr>
        <tr><td>36-40:</td><td>1.1</td><td>1.1</td><td>1</td><td>1</td><td>0.9</td><td>0.9</td></tr>
        <tr><td>41-45:</td><td>0.9</td><td>0.9</td><td>1</td><td>1</td><td>1.1</td><td>1.1</td></tr>
        <tr><td>46-50:</td><td>0.85</td><td>0.9</td><td>0.9</td><td>1</td><td>1.1</td><td>1.15</td></tr>
    </table>
</p>
<p>
    <b>Loyalty(L) - <i>(30-50)</i></b> A Player will higher loyalty will lean towards staying with their current team.<br><br>
    Loyalty bonus (l) is ONLY given to resigning teams. <br>
    30-32 = 1.05<br>
    33-39 = 1.1<br>
    40-47 = 1.15<br>
    48-50 = 1.2
</p>
<p>
    <b>Winning(W) - <i>(30-70)</i></b> A Player with high winning will prefer to play for a team that wins more.   In this league winning is counted over a 3 season period, weighted towards the most recent season.<br><br>
    w = (Wins(current year) * 1.5 + Wins(last year) * 1 + Wins(2 years ago) * 0.5) / 21 (MAXIMUM VALUE IS 1.2, anything above that is lowered to 1.2)
</p>
<p>
    <b>Playing Time(P) - <i>(70-100)</i></b> A Player with high playing time will value being the highest rated player on his team at his position.<br><br>
    IF TRUE: p = P/100+0.2<br>
    IF FALSE: p = P/100+0.1
</p>
<p>
    <b>Home(H) - <i>(30-50)</i></b> A Player with higher home wants to play nearer to his home.  Home is defined by the state in which he attended college.  There is a data table in relation to nearness to a state.<br><br>
    This (h) will be either 0.5, 0.6 or 0.7 + H/100 based on the results of the chart.    
</p>
<p>
    <b>Market(K) - <i>(30-70)</i></b> A Player with higher market will want to play for a big market team, where-as a player with a lower value wants to play for a smaller market.
    Market Multiplier (Km)  
    <table border=1>
        <tr><th>(Km)</th><th colspan=6>Team Market (k)</th></tr>
        <tr><th>Value (K)</th><th>1</th><th>2</th><th>3</th><th>4</th></tr>   
        <tr><td>30-40:</td><td>1.15</td><td>1.1</td><td>0.9</td><td>0.85</td></tr>
        <tr><td>41-50:</td><td>1.1</td><td>1</td><td>1</td><td>0.9</td></tr>
        <tr><td>51-60:</td><td>0.9</td><td>1</td><td>1</td><td>1.1</td></tr>
        <tr><td>61-70:</td><td>0.85</td><td>0.9</td><td>1.1</td><td>1.15</td></tr>
    </table>
    <table>
        <tr><th>Team</th><th>Market</th></tr>
        <tr><td>Atlanta</td><td>3</td></tr>
        <tr><td>Buffalo</td><td>1</td></tr>
        <tr><td>Chicago</td><td>4</td></tr>
        <tr><td>Green Bay</td><td>1</td></tr>
        <tr><td>Los Angeles</td><td>4</td></tr>
        <tr><td>Miami</td><td>2</td></tr>
        <tr><td>New England</td><td>2</td></tr>
        <tr><td>New York</td><td>4</td></tr>
        <tr><td>San Francisco</td><td>3</td></tr>
        <tr><td>Washington</td><td>3</td></tr>
</table>
</p>
<p>
    Contract Offer Valuation Formula -  Capital letters represent a PLAYER's attribute, Lowerclass letters represent the TEAM's value. <br><br>
    ((m*(M-70)/100+1)*Sm)*l*(w + W/1000))*p*h*Km = Offer Value<br>
</p>







<?php
?>