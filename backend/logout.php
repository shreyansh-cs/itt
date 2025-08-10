<?php
    // logout.php
    session_start();
    
    // Clear session_id from database before destroying session
    if (isset($_SESSION['token'])) {
        include_once 'public_utils.php';
        include_once 'utils.php';
        $payload = getSessionData();
        
        if (isset($payload['user_id'])) {
            $error = "";
            if (!clearUserSessionId($payload['user_id'], $error)) {
                // Log error but continue with logout process
                error_log("Failed to clear session_id during logout: " . $error);
            }
        }
    }
    
    $_SESSION = [];//clear session vars
    session_destroy();
    // Delete the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_regenerate_id(true); //regenerate id

    header("Location: /itt/frontend/login.php");
?>
