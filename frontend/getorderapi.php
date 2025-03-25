<?php
include_once 'session.php';
include_once '../backend/razorpay_config.php';
include_once '../backend/utils.php';
include_once '../backend/txnutils.php';

if(!isSessionValid())
{
    echo json_encode(array("error" => "No Access"));
    exit;
}

$order_id = "";
$payment_id="";

if(isset($_GET['order_id']))
{
    $order_id = $_GET['order_id'];

}

if(isset($_GET['payment_id']))
{
    $payment_id = $_GET['payment_id'];
}


if(empty($order_id) && empty($payment_id))
{
    //echo json with error
    echo json_encode(array("error" => "No order id/payment_id given"));
    exit;
}

$url = "";
$result = json_encode("");
if(!empty($payment_id))
{
    if(getPaymentStatus($payment_id,$result,$error))
    {
        //json
        echo $result;
        //array
        //print_r(json_decode($result, true));
    }
}
else if(!empty($order_id))
{
    if(getOrderStatus($order_id,$result,$error))
    {
        //json
        echo $result;
        //array
        //print_r(json_decode($result, true));
    }
}

?>
