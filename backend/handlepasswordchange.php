<?php
// login.php
include_once 'db.php';
include_once 'utils.php';

$msg = "";
$email_or_phone="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $row = [];
    $error = "";
    if(authUser($email_or_phone,$password,$row/*OUT*/,$error))
    {
        if(changePassword($row['ID'],$new_password,$error))
        {
            $msg = "Password Changed Successfully";
        }
    }
    else
    {
        //$msg = $error;//got this from utils.php func
        $msg = "Error - ".$error;
    }

}
?>
