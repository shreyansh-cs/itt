<?php 
include_once __DIR__.'/session.php';
include_once __DIR__.'/../backend/public_utils.php';
if(!isAdminLoggedIn())
{
    redirectError("Restricted Page - No Access");
    exit;
}
?>