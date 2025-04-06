<?php 

function redirect($url)
{
    header("Location : ".$url);
}

function redirectError($error)
{
    if (session_status() == PHP_SESSION_NONE) {session_start();}
    $_SESSION['error'] = $error;
    redirect("/itt/frontend/error.php");
}

function setStatusMsg($msg)
{
    if (session_status() == PHP_SESSION_NONE) {session_start();}
    $_SESSION['error'] = "";//clear error
    //set msg
    $_SESSION['msg'] = $msg;
}

function getSessionData()
{
    if (session_status() == PHP_SESSION_NONE) {session_start();}
    include 'jw_utils.php';
    if(!isset($_SESSION['token']))
    {
        return []; //return empty payload
    }
    $jwt = $_SESSION['token'];
    $decoded = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key($secretKey, $jwtAlgo));
    $payload = (array) $decoded;

    return $payload;
}

function getUserID()
{
    $payload = getSessionData();

    if(isset($payload['user_id']))
        return $payload['user_id'];
    
    return "";
}

function getUserName()
{
    $payload = getSessionData();

    if(isset($payload['full_name']))
        return $payload['full_name'];
    
    return "";    
}

function getUserEmail()
{
    $payload = getSessionData();

    if(isset($payload['email']))
        return $payload['email'];
    
    return "";       
}

function getUserMobile()
{
    $payload = getSessionData();

    if(isset($payload['mobile']))
        return $payload['mobile'];
    
    return "";     
}

function getUserClass()
{
    $payload = getSessionData();

    if(isset($payload['user_class']))
        return $payload['user_class'];
    
    return "";     
}

function getUserType()
{
    $payload = getSessionData();

    if(isset($payload['user_type']))
        return $payload['user_type'];
    
    return "";    
}

function isSessionValid()
{
    if (session_status() == PHP_SESSION_NONE) {session_start();}
    if(!isset($_SESSION['token']) || empty($_SESSION['token'])) //jwt token is stored here
    {
        return false;
    }
    
    $payload = getSessionData();

    if(isset($payload['user_id']))
        return true;
    
    return false;
}

function isProtectedPage()
{
    //Page accessible without login
    $protectedURI = [
        "login.php",
        "register.php",
        "index.php",
        "about.php",
        "contact.php",
        "forgot.php",
    ];
    $currentURI = $_SERVER['REQUEST_URI'];
    $protected = true;//default is protected
    foreach ($protectedURI as $uri) 
    {
        if(strpos($currentURI,$uri))
        {
            $protected = false;
            break;
        }   
    }
    //protected page
    return $protected;
}

function isAdminLoggedIn()
{
    if (session_status() == PHP_SESSION_NONE) {session_start();}

    if(!isSessionValid())
        return false;
    
    $user_type = getUserType();

    if($user_type == "admin")
    {
        return true;
    }

    return false;
}

function isTeacherLoggedIn()
{
    if (session_status() == PHP_SESSION_NONE) {session_start();}

    if(!isSessionValid())
        return false;
    
    $user_type = getUserType();

    if($user_type == "teacher")
    {
        return true;
    }

    return false;
}

?>