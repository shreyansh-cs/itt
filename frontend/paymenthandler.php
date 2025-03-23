<?php 
include_once '../backend/razorpay_config.php';
include_once '../razorpay/razorpay-php/Razorpay.php';
include_once '../backend/txnutils.php';
include_once '../backend/utils.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

function savePaymentDetails($arr,$error)
{
    return savePaymentDetailsDB($arr,$error);
}

function createOrder($params,&$json,$error)
{
    global $keyId,$keySecret,$displayCurrency;
    $api = new Api($keyId, $keySecret);

    $orderData = [
        'receipt'         => $params['receipt'],
        'amount'          => $params['amount'] * 100, // 2000 rupees in paise
        'currency'        => 'INR',
        'payment_capture' => 1 // auto capture
    ];
    
    $razorpayOrder = $api->order->create($orderData);
    
    $razorpayOrderId = $razorpayOrder['id'];

    if(!createNewOrderInDB($razorpayOrderId,$orderData['amount'],$params['receipt'],$error))
    {
        die("Order not created in DB".$error);
    }
    
    $_SESSION['razorpay_order_id'] = $razorpayOrderId;
    
    $displayAmount = $amount = $orderData['amount'];
    
    if ($displayCurrency !== 'INR')
    {
        $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
        $exchange = json_decode(file_get_contents($url), true);
    
        $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
    }
    
    $data = [
        "key"               => $keyId,
        "amount"            => $amount,
        "name"              => "ITT Group of Education",
        "description"       => $params['description'],
        "image"             => $params['image'],
        "prefill"           => [
        "name"              => $params['name'],
        "email"             => $params['email'],
        "contact"           => $params['contact'],
        ],
        "notes"             => [
        "address"           => $params['address'],
        "merchant_order_id" => $params['merchant_order_id'],
        ],
        "theme"             => [
        "color"             => "#F37254"
        ],
        "order_id"          => $razorpayOrderId,
    ];
    
    if ($displayCurrency !== 'INR')
    {
        $data['display_currency']  = $displayCurrency;
        $data['display_amount']    = $displayAmount;
    }
    $json = json_encode($data);
}

function getForm($json)
{
    $arr = json_decode($json,true);
    $order_id = $arr['order_id'];

    $form = "
        <button id='rzp-button1' style='display:none'>Payment</button>
        <script src='https://checkout.razorpay.com/v1/checkout.js'></script>
        <form name='razorpayform' action='' method='POST'>
            <input type='hidden' name='razorpay_payment_id' id='razorpay_payment_id'>
            <input type='hidden' name='razorpay_signature'  id='razorpay_signature' >
        </form>
        <script>
        // Checkout details as a json
        var options = {$json};

        /**
         * The entire list of Checkout fields is available at
         * https://docs.razorpay.com/docs/checkout-form#checkout-fields
         */
        options.handler = function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            console.log('options.handler called after payment popup return')
            document.razorpayform.submit();
        };

        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = false;

        options.modal = {
            ondismiss: function() {
                console.log('This code runs when the popup is closed');
                //Just refresh the page
                window.location='receipts.php';
                //alert('Payment popup is dismissed - {$order_id}');
            },
            // Boolean indicating whether pressing escape key 
            // should close the checkout form. (default: true)
            escape: true,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
        };

        var rzp = new Razorpay(options);

        //this function will be clicked from script in receipts.php
        function clickRazorPayButton()
        {
            document.getElementById('rzp-button1').click();
        }

        //I have hidden the button, so this won't be hit
        document.getElementById('rzp-button1').onclick = function(e){
            rzp.open();
            e.preventDefault();
        }
        </script>
    ";

    return $form;
}

function handlePostPayment($order_id,&$mesg)
{
    global $keyId,$keySecret;
    $success = true;
    $error = "";
    $output = "";

    $api = new Api($keyId, $keySecret);
    $razorpay_order_id = $order_id;
    $razorpay_payment_id = "";
    $razorpay_signature = "";    

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        //$attributes = array(
            //'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            //'razorpay_payment_id' => $_POST['razorpay_payment_id'], // no need, we will fetch the status
            //'razorpay_signature' => $_POST['razorpay_signature'] // no need, we will fetch the status
        //);
        $success = false;

        if(getOrderStatus($razorpay_order_id, $result,$error))
        {
            $arr = json_decode($result,true);
            if(isset($arr['order']) && isset($arr['payment']))
            {
                $order = $arr['order'];
                $razor_receipt_id = $order['receipt']; //our identifier for order id

                //Accept only one payment for one order id
                $output = "{$order['status']}";
                if($arr['payment']['count'] == 1)
                {
                    $payment = $arr['payment']['items'][0];
                    $razorpay_payment_id = $payment['id']; // id that we got from razor pay
                }

                if(isset($payment['status']))
                {
                    $output .= ",{$payment['status']}";
                }

                //echo "checking for order id - {$order['id']}";
                //Perhaps the most important function here
                if(!savePaymentDetails($arr,$error))
                {
                    $success = false;
                    //Most critical step, go for exit
                    die("Unable to save payment details into DB");
                }
                //also verify the order_id and payment_id in session with the api values
                $success = true;
            }
        }
        else
        {
            die("Unable to get payment status".$error);
        }
        //$api->utility->verifyPaymentSignature($attributes);
    }
    //catch(SignatureVerificationError $e)
    catch(Exception $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }

    if ($success === true)
    {
        $mesg = "<div class='pay_success'>Status {$output}</div>";
    }
    else
    {
        $mesg = "<div class='pay_failure'>Failed - Order-ID {$razorpay_order_id}, TxnID {$razorpay_payment_id} </div>";
    }
    return true;
    //echo $html;
}

?>