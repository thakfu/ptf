<?php 
include 'header.php';

include 'nav.php';

echo '<div class="ptf">';
echo '<h1>' . $leagueName . '</h1><br>';
//echo '<br><b><p>The current league date is ' . $gamedate  . '. </p></b><br>';

echo '<table><tr><td><img src = "basketball.jpg" width="30px"></td><td valign="bottom"><p>Proud Partner of IBL!  <a href="https://www.iblhoops.net/ibl5/index.php"  target="_blank">FIND OUT WHERE WTF HAPPENS!!</a></td><td><img src = "basketball.jpg" width="30px"></td></tr></table></p>';

$slice = 10;
echo '<div id="transmain">';
include 'latest_transactions.php';
echo '</div>';
echo '</div>';

