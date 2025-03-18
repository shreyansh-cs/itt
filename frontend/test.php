<?php
//date_default_timezone_set("Asia/Kolkata");
// Create a new DateTime object for the current time
$now = new DateTime();

// Add 1 hour to the current time
$now->modify('+1 hours');

// Format the DateTime object as a MySQL-compatible DATETIME string (YYYY-MM-DD HH:MM:SS)
$formattedDate = $now->format('Y-m-d H:i:s');

// Output the result
echo "One hour from now: " . $formattedDate;
?>