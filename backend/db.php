<?php
// db.php
$servername = "localhost";
$username = "root";       // अपने DB username के अनुसार सेट करें
$password = "";           // अपने DB password के अनुसार सेट करें
$dbname = "itt_education";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
