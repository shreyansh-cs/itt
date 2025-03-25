<?php
include_once 'utils.php';

$keyId = "";
$keySecret = "";
if(!getAPIToken("stage",$keyId,$keySecret,$error))
{
    die("Unable to get API - ".$error);
}
$displayCurrency = 'INR';

?>
