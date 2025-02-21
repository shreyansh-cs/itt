<?php
// login.php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, full_name, password, user_type,user_class FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email_or_phone, $email_or_phone);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $full_name, $hashed_password, $user_type,$user_class);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['user_type'] = $user_type;
            $_SESSION['user_class'] = $user_class;
            header("Location: ../frontend/index.php");
            return;
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "No user found with provided email/phone.";
    }
    
    $stmt->close();
    $conn->close();
}
?>
