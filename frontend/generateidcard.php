<?php 
ob_start();
$title = "Generate ID Card";
?>
<h1> Coming Soon... </h1>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>