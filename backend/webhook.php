<?php
// Razorpay API keys
$razorpay_secret = 'f3b8e2c9d4a6b9c1d8f4a3e7e5d1c9b2'; // Get this from the dashboard

// Read the incoming JSON data
$payload = @file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE']; // Get the signature from headers

// Verify the signature
$expected_signature = hash_hmac('sha256', $payload, $razorpay_secret);

if ($signature === $expected_signature) {
    $data = json_decode($payload, true); // Decode JSON payload

    //echo $data;
    file_put_contents('../../payment_log.txt', "-----{$data}----", FILE_APPEND);
    // Handle the event
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
