<?php
// login.php
include 'db.php';
include '../backend/utils.php';
session_start();

function GetUniqueNumber()
{
    // Get current timestamp in microseconds
    $timestamp = microtime(true);
    // Convert timestamp to a unique number (combining date/time and microseconds)
    $uniqueNumber = str_replace('.', '', $timestamp);
    return $uniqueNumber;
}

$debug = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the text input
    $notes_title= $_POST['notes_title'];
    $notesText = $_POST['notes_text'];
    $class = $_POST['class'];
    $stream=$_POST['stream'];
    $subject=$_POST['subject'];
    $chapter=$_POST['chapter'];


    if($debug)
    {
        echo "<br/>CWD=".getcwd();
        echo "<br/>".$notesText;
        echo "<br/>".$chapter;
        echo "<br/>".$_FILES['pdf_file']['name'];
        echo "<br/>".$_FILES['pdf_file']['tmp_name'];
    }

    // Handle the PDF file upload
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        // Define the target directory
        //$target_dir = dirname(getcwd())."/uploads";
        $target_dir="../uploads/";
        if (!file_exists($target_dir)) 
        {
            if (!mkdir($target_dir, 0777, true)) 
            {
                die("<br/>Failed to create directory. Error: " . error_get_last()['message']);
            }
        }

        $pdf_file = $_FILES['pdf_file']['name'];
        $pdf_temp_name = $_FILES['pdf_file']['tmp_name'];

        // Check if the uploaded file is a valid PDF
        $file_ext = strtolower(pathinfo($pdf_file, PATHINFO_EXTENSION));
        //$pdf_file_path = $target_dir . basename($pdf_file);
        $pdf_file_path = $target_dir . GetUniqueNumber(). "." .$file_ext;

        if($debug)
        {
            echo "<br/>UPLOAD PATH = ".$pdf_file_path;
        }
        //Do you want to limit any extension, do it here
        if (strtoupper($file_ext) != 'PDF') {
            die("Only PDF files are allowed.");
        }

        // Move the uploaded PDF to the target directory
        if (move_uploaded_file($pdf_temp_name, $pdf_file_path)) 
        {
            $error = "";
            if(insertNotes($chapter,$notes_title,$notesText,$pdf_file_path,$error)) 
            {
                //success
                header("Location: ../frontend/noteslist.php?class=$class&stream=$stream&subject=$subject&chapter=$chapter");
                echo $error;
            } 
            else 
            {
                echo $error;
            }
        } 
        else 
        {
            echo "<br/>Error uploading the file.";
        }
    } 
    else 
    {
        echo "Please upload a valid PDF file.";
    }
}
$conn->close();

?>