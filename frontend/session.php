<?php
include_once __DIR__.'/showerror.php';
include_once __DIR__.'/../backend/jw_utils.php';
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
  
  // Update session_id in database and regenerate JWT after regeneration
  if (isset($_SESSION['token'])) {
    include_once __DIR__.'/../backend/public_utils.php';
    include_once __DIR__.'/../backend/utils.php';
    include_once __DIR__.'/../backend/jw_utils.php';
    
    $payload = getSessionData();
    if (isset($payload['user_id'])) {
      $error = "";
      updateUserSessionId($payload['user_id'], $error);
      
      // Regenerate JWT with updated timestamp for security
      $newPayload = [
        "user_id" => $payload['user_id'],
        "full_name" => $payload['full_name'],
        "user_type" => $payload['user_type'],
        "user_class" => $payload['user_class'],
        "email" => $payload['email'],
        "phone" => $payload['phone'],
        "session_regenerated" => time() // Add timestamp for additional security
      ];
      $newJwt = \Firebase\JWT\JWT::encode($newPayload, $secretKey, 'HS256');
      $_SESSION['token'] = $newJwt; // Update with new JWT
    }
  }
}

include_once __DIR__.'/../backend/public_utils.php';

//Redirect if not logged in
if(isProtectedPage() && !isSessionValid())
{
  header("Location: /itt/frontend/login.php");
}
?>