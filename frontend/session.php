<?php
include_once '../backend/jw_utils.php';
$logoutHours = 2;
//Ensure this file has no dependency
session_set_cookie_params([
  'lifetime' => 0,           // Expires when the browser closes
  'path' => '/',             
  'domain' => '',            // Default domain (same as the site)
  'secure' => true,          // Only send over HTTPS
  'httponly' => true,        // Prevent JavaScript access (XSS protection)
  'samesite' => 'Strict'     // Prevent CSRF attacks
]);

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
  // Session has not started
  session_start();  // Start the session
}

//Regenerate token after 2 hours
if (!isset($_SESSION['last_regeneration'])) 
{
  $_SESSION['last_regeneration'] = time();
} 
elseif (time() - $_SESSION['last_regeneration'] > 60*60*$logoutHours) 
{ 
  session_regenerate_id(true);
  $_SESSION['last_regeneration'] = time();
}

include_once '../backend/public_utils.php';

//Redirect if not logged in
if(isProtectedPage() && !isSessionValid())
{
  header("Location: /itt/frontend/login.php");
}
?>