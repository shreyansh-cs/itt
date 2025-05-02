<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Online Test";
?>




<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php'
?>