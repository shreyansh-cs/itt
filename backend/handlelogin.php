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
    
    //regenerate session before authentication so session_id is consistent
    session_regenerate_id(true);
    if(authUserSingleSession($email_or_phone,$password,$row/*OUT*/,$error))
    {
        include_once 'jw_utils.php';
        $payload = [
            "user_id" => $row['ID'],
            "full_name" =>  $row['FULL_NAME'],
            "user_type" => $row['USER_TYPE'],
            "user_class" => $row['USER_CLASS'],
            "email" => $row['EMAIL'],
            "phone" => $row['PHONE'],
        ];
        $jwt = \Firebase\JWT\JWT::encode($payload, $secretKey, 'HS256');
        $_SESSION['token'] = $jwt; // set it in sessions
        header("Location: /itt/frontend");
    }
    else
    {
        //$msg = $error;//got this from utils.php func
        $msg = "Error login - ".$error;
    }

}
?>
