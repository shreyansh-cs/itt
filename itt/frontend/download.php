<?php
include "showerror.php";
include '../backend/utils.php';
if(isset($_GET['noteid']))
{
    $noteid = $_GET['noteid'];//noteid
    if(!empty($noteid))
    {
        $rows = getBlobFromNote($noteid);
        echo $rows[0]['NAME'];
        if(count($rows) == 1)
        {
            $row = $rows[0];//only first row is expected
            $name = $row['NAME'];
            $fileData = $row['PDF'];

            // Set the headers to download the file as a PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $name . '"');
            header('Content-Length: ' . strlen($fileData));

            // Output the file data (binary data)
            echo $fileData;
        }
        else
        {
            die("Note expecting multiple rows from getBlobFromNote");
        }
    }
}


?>