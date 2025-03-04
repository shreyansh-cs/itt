<?php
// login.php
include_once 'db.php';
include_once '../backend/utils.php';
session_start();

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
        // Define the target directory - itt/uploads/
        $target_dir="../../uploads/notes/";
        if (!file_exists($target_dir)) 
        {
            if (!mkdir($target_dir, 0777, true)) 
            {
                redirectError("<br/>Failed to create directory. Error: " . error_get_last()['message']);
            }
        }

        $pdf_file = $_FILES['pdf_file']['name'];
        $pdf_temp_name = $_FILES['pdf_file']['tmp_name'];

        // Check if the uploaded file is a valid PDF
        $file_ext = strtolower(pathinfo($pdf_file, PATHINFO_EXTENSION));
        $pdf_file_path = $target_dir . GetUniqueNumber(). "." .$file_ext;

        if($debug)
        {
            echo "<br/>UPLOAD PATH = ".$pdf_file_path;
        }
        //Do you want to limit any extension, do it here
        if (strtoupper($file_ext) != 'PDF') {
            redirectError("Only PDF allowed");
        }

        // Move the uploaded PDF to the target directory
        if (move_uploaded_file($pdf_temp_name, $pdf_file_path)) 
        {
            $error = "";
            if(insertNotes($chapter,$notes_title,$notesText,$pdf_file_path,$error)) 
            {
                //success - Enable this if you want to see return from insertNotes
                //echo $error; 
                header("Location: ../frontend/noteslist.php?class=$class&stream=$stream&subject=$subject&chapter=$chapter");
            } 
            else 
            {
                redirectError($error);
            }
        } 
        else 
        {
            redirectError("Error uploading the file.");
        }
    } 
    else 
    {
        redirectError("Please upload a valid PDF file.");
    }
}
?>