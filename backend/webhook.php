<?php
include '../../secrets.php';
//$webhook_secret will be present in secrets.php
// Read the incoming JSON data
$payload = @file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE']; // Get the signature from headers

// Verify the signature
$expected_signature = hash_hmac('sha256', $payload, $webhook_secret);

if ($signature === $expected_signature) {
    $data = json_decode($payload, true); //json to array
    $json = json_encode($data, JSON_PRETTY_PRINT); // this is same as $payload which is already json

    // Write JSON data to a file
    file_put_contents('../../payment_log.txt', $json, FILE_APPEND);

    //decode anything you want from $data array
    if ($data['event'] == 'payment.captured') {
        $payment_id = $data['payload']['payment']['entity']['id'];
        $amount = $data['payload']['payment']['entity']['amount'];
        $customer_email = $data['payload']['payment']['entity']['email'];

        // Process payment (e.g., update database, send confirmation email)
        // Example:
        //file_put_contents('../../payment_log.txt', "Payment ID: $payment_id, Amount: $amount, Email: $customer_email\n", FILE_APPEND);

        http_response_code(200); // Acknowledge the webhook
    } else {
        http_response_code(200); // For other events
    }
} else {
    // Invalid signature
    http_response_code(400);
    echo 'Invalid signature';
}
?>
