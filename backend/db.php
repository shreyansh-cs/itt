<?php
include __DIR__.'/../../secrets.php'; // Use include instead of include_once, otherwise variables will not be available in some scropes
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pdo = null; 
try 
{
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>
