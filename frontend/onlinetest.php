<?php 
ob_start();
$title = "I.T.T Group of Education - Home";
?>
<h1> Coming Soon... </h1>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>