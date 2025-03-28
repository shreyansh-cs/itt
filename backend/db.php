<?php
// db.php
$servername = "localhost";
$username = "u760896062_root";       // अपने DB username के अनुसार सेट करें
$db_password = "Normaxin@321";           // अपने DB password के अनुसार सेट करें
$dbname = "u760896062_itt_education";
//$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
