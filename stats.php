<?php 
include 'header.php';
$link = $_GET['page'];
?>

<script type="text/javascript">
function hijacklinks(iframe){
  var as = iframe.contentDocument.getElementsByTagName('a');
  for(i=0;i<as.length;i++){
    as[i].setAttribute('target','');
  }
}
</script>

<?php include 'nav.php'; ?>
<div class='iframe'>
<iframe src='export/<?=$link; ?>.html' width='100%' height='100%' onload="hijacklinks(this)"></iframe>
</div>