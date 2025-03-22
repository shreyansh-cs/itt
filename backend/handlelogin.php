<?php
// login.php
include_once 'db.php';
include_once 'utils.php';
$msg = "";
$email_or_phone="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];
    $row = [];
    $error = "";
    if(authUser($email_or_phone,$password,$row/*OUT*/,$error))
    {
        $_SESSION['user_id'] = $row['ID'];
        $_SESSION['full_name'] = $row['FULL_NAME'];
        $_SESSION['user_type'] = $row['USER_TYPE'];
        $_SESSION['user_class'] = $row['USER_CLASS'];
        $_SESSION['email'] = $row['EMAIL'];
        $_SESSION['phone'] = $row['PHONE'];
        $_SESSION['api_token'] = $row['TOKEN'];

        //echo "<pre>".print_r($row)."</pre>";
        redirect("/itt/frontend");
    }
    else
    {
        //$msg = $error;//got this from utils.php func
        $msg = "Error login - ".$error;
    }

}
?>
