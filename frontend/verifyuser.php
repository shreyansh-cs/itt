<?php 
include_once 'showerror.php';
include_once '../backend/db.php';
include_once '../backend/utils.php';


if(!isset($_GET['verify_key']) || empty(isset($_GET['verify_key'])))
{
    die("Probably you have a tempered link(1)");    
}
$verify_key = trim($_GET['verify_key']);

if(!verifyRegisterFromEmailLink($verify_key,$error))
{
    die("Probably you have a tempered link(2) - {$error}");
}

echo "<script>alert('User is verified, Go to ITT home page'); window.location='/itt/frontend/index.php'; </script>";

?>