<?php
// register.php
include_once '../frontend/showerror.php';
include_once 'db.php';
include_once 'utils.php';

$msg = ""; //show message to the user if any
//so that i can refill the register form in case of error popup
$user_class = "";
$full_name = "";
$father_name = "";
$email ="";
$phone = "";
$dob = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $debug = 0;
    $user_class = $_POST['class'];
    $full_name = $_POST['full_name'];
    $father_name = $_POST['father_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $ok = true;
    $error = "";

    //validate the feilds
    if($ok && !validateRegister($full_name,$father_name,$email,$phone,$error))
    {
        $msg = $error; //show list of errors
        $ok=false;
    }

    //Check if user already exist
    if($ok && doesUserAlreadyExist($email,$phone,$error))
    {
        $msg = "User Already Exists with this Phone / Email";
        $ok = false;
    }


    if($ok)
    {
        //We will have admin created directly into DB, do allow admin to register through UI
        $user_type = "student";
        // Handle file upload for photo
        if($debug)
            echo "<br/>CWD=".getcwd();

        $target_dir = "../../uploads/images/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $upload_file = $target_dir . GetUniqueNumber().".".$imageFileType;

        if($debug)
            echo "<br/>".$upload_file;
        
        // Check if file is an image
        $size = $_FILES["photo"]["size"]/ (1024*1024);
        if($size >  10) 
        {
            $msg = "Uploaded image > 10 MB";
            $ok = false;
        }
        // Allow only JPG, JPEG, PNG, GIF files
        if($ok && (strtoupper($imageFileType) != "JPG" && strtoupper($imageFileType) != "PNG" && strtoupper($imageFileType) != "JPEG")) {
            $msg = "Sorry, only JPG, JPEG & PNG  files are allowed.";
            $ok = false;
        }
        if ($ok) 
        {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_file)) 
            {
                $photo_path = $target_file;
            } 
            else 
            {
                $msg = "Sorry, there was an error uploading your file.";
            }
        }
        
        if ($ok)
        {
            // Hash the password
            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
            //No hashed as of now
            $verify_key = hash('sha256', microtime(true)); // unique key to verify users from email
            $hashed_password = $password;
            $sql = "INSERT INTO users (full_name, father_name, email, phone, dob, photo, password, user_type, user_class,verify_key) 
                        VALUES ('$full_name','$father_name','$email','$phone','$dob','$photo_path','$password','$user_type',$user_class,'$verify_key')";

            if($debug)
                echo "<br/>".$sql;

            // Execute the query
            if ($conn->query($sql) === TRUE) 
            {
                
            } 
            else 
            {
                $ok = false;
                $msg = "Error in creating new user";
            }
            $conn->close();
        }

        if($ok && sendWelcomeMail($full_name,$email,$phone,$password,$verify_key,$error))
        {
            //redirect to home page
            redirect("/itt/frontend/login.php");
        }
        else
        {
            $ok = false;
            $msg = "SendMailError-".$error;
        }
    }
}
?>
