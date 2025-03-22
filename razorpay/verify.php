<?php

require('config.php');

if (session_status() == PHP_SESSION_NONE) {
    // Session has not started
    session_start();  // Start the session
  }

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

if (empty($_POST['razorpay_payment_id']) === false)
{
    $success = true;
    $error = "Payment Failed";

    //echo "<pre>".print_r($_SESSION)."</pre>";
    //echo "<pre>".print_r($_POST)."</pre>";

    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }

    if ($success === true)
    {
        $html = "<div class='pay_success'>Your payment was successful - Txn ID: {$_POST['razorpay_payment_id']}</div>";
    }
    else
    {
        $html = "<div class='pay_failure'>Your payment failed - {$error}</div>";
    }

    echo $html;
}
