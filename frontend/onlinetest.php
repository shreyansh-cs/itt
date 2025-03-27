<?php 
include_once 'session.php';
ob_start();
$title = "Online Test";
?>
<h1> Coming Soon... </h1>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>