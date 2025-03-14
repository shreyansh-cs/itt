<?php
// login.php
include_once 'db.php';
include_once 'utils.php';
$msg = "";
$email="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $error = "";
    if(doesEmailExist($email,$username,$password,$error))
    {
        if(sendPasswordResetMail($email,$username,$password,$error))
        {
            $msg = "Password has been sent to your email.";
        }
        else
        {
            $msg = $error;
        }
    }
    else
    {
        $msg = "User does not exist - ".$email;
    }

}
?>
