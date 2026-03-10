<?php 
include 'header.php';

$slice = 100000;
echo '<center><br>';
echo '<h3>All Transactions</h3><br>';
echo '<br><a href="ogTransLog.php">CLASSIC VIEW</a><br><br>';

if($_GET['team'] != NULL) {
    echo '<a href="translog.php?team=2&type=all">MIA</a> || ';
    echo '<a href="translog.php?team=8&type=all">SEA</a> || ';
    echo '<a href="translog.php?team=9&type=all">LON</a> || ';
    echo '<a href="translog.php?team=18&type=all">BAL</a> || ';
    echo '<a href="translog.php?team=20&type=all">SD</a> || ';
    echo '<a href="translog.php?team=1&type=all">NYT</a> || ';
    echo '<a href="translog.php?team=4&type=all">OAK</a> || ';
    echo '<a href="translog.php?team=5&type=all">BUF</a> || ';
    echo '<a href="translog.php?team=7&type=all">CIN</a> || ';
    echo '<a href="translog.php?team=19&type=all">WAS</a> || ';
    echo '<a href="translog.php?team=6&type=all">NYG</a> || ';
    echo '<a href="translog.php?team=10&type=all">IND</a> || ';
    echo '<a href="translog.php?team=14&type=all">ATL</a> || ';
    echo '<a href="translog.php?team=16&type=all">TB</a> || ';
    echo '<a href="translog.php?team=17&type=all">CHC</a> || ';
    echo '<a href="translog.php?team=3&type=all">GB</a> || ';
    echo '<a href="translog.php?team=11&type=all">CHI</a> || ';
    echo '<a href="translog.php?team=12&type=all">DET</a> || ';
    echo '<a href="translog.php?team=13&type=all">MIN</a> || ';
    echo '<a href="translog.php?team=15&type=all">SF</a><br><br> ';
    $tteam = $_GET['team'];
    echo  '<a href="translog.php?team='.$tteam.'&type=fasign">Free Agency</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=trade">Trade</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=draft">Draft</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=sign">Waiver Add</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=cut">Waiver Drop</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=extend">Extend</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=change">Pos. Change</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=squad">To PS</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=promote">From PS</a> || ';
    echo  '<a href="translog.php?team='.$tteam.'&type=ir">IR</a><br><br> ';
} 


include 'latest_transactions.php';
echo '</center><br><br>';

?>