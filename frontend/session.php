<?php 
if (session_status() == PHP_SESSION_NONE) {
    // Session has not started
    session_start();  // Start the session
  }
?>