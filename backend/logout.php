<?php
    // logout.php
    session_start();
    $_SESSION = [];//clear session vars
    session_destroy();
    // Delete the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_regenerate_id(true); //regenerate id

    header("Location: /itt/frontend/login.php");
?>
