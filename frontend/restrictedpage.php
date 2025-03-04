<?php 
include_once '../backend/utils.php';
if(!isAdminLoggedIn())
{
    redirectError("Restricted Page - No Access");
    exit;
}
?>