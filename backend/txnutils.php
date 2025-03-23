<?php 

$base_url = "https://api.razorpay.com/v1";

function getAPIResponse($url,&$result)
{
    global $keyId;//from razorpay_config.php
    global $keySecret;

    $resp = "";
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // To get the response as a string
    curl_setopt($ch, CURLOPT_HTTPGET, true);         // Use GET method
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);  // Basic Authentication (key_id:key_secret)

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check if any error occurred
    if ($response === false) {
        $result = json_encode(array('Error' => curl_error($ch)));
        return false;
    } else {
        // You can process the response here
        $result = $response;
        //echo $result;
        return true;
    }
}

function getOrderStatus($order_id,&$result,&$error)
{
    global $base_url;
    $url = "{$base_url}/orders/{$order_id}";
    if(!getAPIResponse($url,$json1))
    {
        $error = "getOrderStatus(1) - Unable to get order status";
        return false;
    }

    $url = "{$base_url}/orders/{$order_id}/payments";
    if(!getAPIResponse($url,$json2))
    {
        $error = "getOrderStatus(2) - Unable to get order status";
        return false;
    }

    // Decode JSON into PHP associative arrays
    $array1 = json_decode($json1, true);
    $array2 = json_decode($json2, true);

    // Merge the arrays (second array will overwrite values from the first)
    $mergedArray = array_merge(["order"=> $array1], ["payment" => $array2]);

    // Encode the merged array back into JSON
    $result = json_encode($mergedArray, JSON_PRETTY_PRINT);
    //echo $result;

    return true;
}

function getPaymentStatus($payment_id,&$result,&$error)
{
    global $base_url;
    $url = "{$base_url}/payments/{$payment_id}";
    $result = array();
    $json = array();
    if(!getAPIResponse($url,$json))
    {
        $error = "[getPaymentStatus]Unable to get payment status";
        return false;
    }
    $result["payment"] = $json;
    return true;
}
?>