<?php
// login.php
include_once '../frontend/showerror.php';
include_once 'db.php';
include_once '../backend/utils.php';
include_once '../frontend/session.php';

//echo "<pre>".print_r($_POST)."</pre>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //common feilds of both notes and video
    $class = $_POST['class'];
    $stream=$_POST['stream'];
    $subject=$_POST['subject'];
    $section=$_POST['section'];
    $chapter=$_POST['chapter'];
    $redirectLink = "/itt/frontend/noteslist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter";

    //upload notes was submitted
    if(isset($_POST['notes_title']))
    {
        // Get the text input
        $notes_title= $_POST['notes_title'];
        $notesText = $_POST['notes_text'];


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
                    redirect($redirectLink);
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
    } //upload notes end

    //upload video start
    else if(isset($_POST['video_title']))
    {
        // Get the text input
        $video_title= trim($_POST['video_title']);
        $video_link = trim($_POST['video_link']);

        if(empty($video_link) || empty($video_title))
        {
            redirectError("Video link and/or title is empty");
        }

        if(!insertVideo($video_title,$video_link,$chapter,$error))
        {
            redirectError("Error uploading video - ".$error);   
        }
        else
        {
            redirect($redirectLink);
        }
    }
}
?>