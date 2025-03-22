<?php 
include 'showerror.php';
ob_start();
$title = "Buy Package";
?>

<?php
include_once 'session.php';
include_once 'restrictedpage.php'; //restricted page
include_once '../backend/utils.php';

$class = "";
if(isset($_SESSION['user_class']))
{
    $class = $_SESSION['user_class'];
}

$error = "";
if(getPackageDetails($class,$row,$error))
{
    $package_id = $row['ID'];
    $package_name = $row['NAME'];
    $package_price = $row['PRICE'];
    echo "<div class='payment'>Selected Package - <b>{$package_name}</b> - Rs <b>{$package_price}</b></div>";

    //Values for pay.php
    $_amount = $package_price;
    $_description = $package_name;
    $_image = "https://" . $_SERVER['HTTP_HOST'] . "/itt/images/icon.jpeg";
    $_email = $_SESSION['email'];
    $_contact = $_SESSION['phone'];

    $_receiptID = "23456"; //to be filled and stored in DB
    $_merchangeOrderID = "12345"; //to be filled and stored in DB
    
    $_name = $_SESSION['full_name'];
    $_address = "Bimal Chowk";
    $_redirectPage = "";//we want to redirect to this page only, it will be used in razorpay/checkout/manual.php
    include_once '../razorpay/pay.php';

    include_once '../razorpay/verify.php';
}
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>