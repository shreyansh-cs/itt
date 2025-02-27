<?php
// register.php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $father_name = $_POST['father_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $user_class = $_POST['user_class'];
    echo $user_class;
    
    // Handle file upload for photo
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if file is an image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check === false) {
        die("File is not an image.");
        $uploadOk = 0;
    }
    // Allow only JPG, JPEG, PNG, GIF files
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        $uploadOk = 0;
    }
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user details into the database
    $stmt = $conn->prepare("INSERT INTO users (full_name, father_name, email, phone, dob, photo, password, user_type,user_class) VALUES (?, ?, ?, ?, ?, ?, ?,?,?)");
    $user_type = "student";  
    $stmt->bind_param("sssssssss", $full_name, $father_name, $email, $phone, $dob, $photo_path, $hashed_password, $user_type,$user_class);
    
    if ($stmt->execute()) {
        echo "Registration successful.";
        header("Location: ../frontend/index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
