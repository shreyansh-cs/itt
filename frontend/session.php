<?php 
session_set_cookie_params([
  'lifetime' => 0,           // Expires when the browser closes
  'path' => '/',             
  'domain' => '',            // Default domain (same as the site)
  'secure' => true,          // Only send over HTTPS
  'httponly' => true,        // Prevent JavaScript access (XSS protection)
  'samesite' => 'Strict'     // Prevent CSRF attacks
]);

if (session_status() == PHP_SESSION_NONE) {
  // Session has not started
  session_start();  // Start the session
}


//Regenerate token after 2 hours
if (!isset($_SESSION['last_regeneration'])) 
{
  $_SESSION['last_regeneration'] = time();
} 
elseif (time() - $_SESSION['last_regeneration'] > 60*60*2) 
{ // 2 hours
  session_regenerate_id(true);
  $_SESSION['last_regeneration'] = time();
}

?>