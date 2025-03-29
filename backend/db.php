<?php
include '../../db_cred.php'; // Use include instead of include_once, otherwise variables will not be available in some scropes
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
