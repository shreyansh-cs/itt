<?php 
include_once 'session.php';
include_once '../backend/public_utils.php';
if(!isAdminLoggedIn())
{
    redirectError("Restricted Page - No Access");
    exit;
}
?>