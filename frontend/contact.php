<?php 
ob_start();
$title = "Contact Us - I.T.T Group of Education";
?>
<h1> Coming Soon... </h1>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


