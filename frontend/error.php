<?php 
//include_once 'showerror.php';
ob_start();
$title = "Error";
?>

<?php 
include_once 'session.php';
echo "<h1>OOPS..</h1";
$error = $_SESSION['error'];
if(isset($error))
{
    echo "<br/><h3>$error</h3>";
}
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>