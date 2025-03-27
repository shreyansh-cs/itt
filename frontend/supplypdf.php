<?php
include_once "session.php";
include_once "showerror.php";
include_once '../backend/utils.php';
if(isset($_GET['noteid']))
{
    $noteid = $_GET['noteid'];//noteid
    if(!empty($noteid))
    {
        $rows = getPDFPathFromNote($noteid);
        echo $rows[0]['NAME'];
        if(count($rows) == 1)
        {
            $row = $rows[0];//only first row is expected
            $name = $row['NAME'];

            //Give a default name if not there in DB
            if(empty($name))
            {
                $name = "download.pdf";
            }
            $path = $row['PDF'];

            $fileData = file_get_contents($path);

            // Set the headers to download the file as a PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $name . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
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