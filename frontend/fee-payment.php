<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fee Payment - I.T.T Group of Education</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .payment-container {
      max-width: 500px;
      margin: 50px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .payment-container h2 {
      color: #002060;
      margin-bottom: 20px;
      border-bottom: 2px solid #004080;
      display: inline-block;
      padding-bottom: 8px;
    }
    .payment-container p {
      font-size: 16px;
      margin: 10px 0 20px;
    }
    .payment-container input[type="text"] {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }
    .payment-container button {
      background: #004080;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
      font-size: 16px;
      margin-top: 20px;
    }
    .payment-container button:hover {
      background: #002060;
    }
  </style>
</head>
<body>
<?php 
  require_once "header.php"; 
  echo getMyHeader();
 ?>
  <div class="payment-container">
    <h2>Fee Payment</h2>
    <p>Please enter your UPI ID to proceed with the payment.</p>
    <input type="text" id="upi-id" placeholder="Enter UPI ID (e.g., username@upi)" required>
    <button onclick="processPayment()">Pay Now</button>
  </div>
  <?php 
    require_once 'footer.php';
    echo getFooter();
  ?>
  <script>
    function processPayment() {
      var upiId = document.getElementById("upi-id").value;
      if (upiId.trim() === "") {
        alert("Please enter a valid UPI ID.");
        return;
      }
      // यहाँ वास्तविक पेमेंट गेटवे API इंटीग्रेशन किया जा सकता है।
      alert("Payment process initiated for UPI ID: " + upiId + "\n(This is a demo. Actual payment integration required.)");
    }
  </script>
</body>
</html>
