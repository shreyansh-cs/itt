<?php 
include_once 'session.php';
include_once "showerror.php";
ob_start();
$title = "Notes & Video";
?>

<?php 
    include_once '../backend/utils.php';
    $border = "1";
    include_once 'selection.php';
    echo "<div class='container mt-4'>";
    echo "<div class='table-responsive'>";

    if(!empty($chapter))
    {
        $rows = getNotesForChapter($class,$stream,$subject,$chapter);
        echo "<table class='table table-bordered table-hover'>";
        echo "<thead class='table-primary'>";
        echo "<tr><th>Title</th><th>Notes Download</th>";
        //Action column is only for admin
        if(isAdminLoggedIn())
        {
            echo "<th>Action</th>";//table and headers start
        }
        echo "</tr></thead><tbody>";
        //Each row
        foreach ($rows as $row) {
            echo "<tr>"; //row start

            echo "<td>";
            echo "<span>".$row['NAME']."</span>";
            echo "</td>";
            
            // echo "<td>";
            // echo "<p>".$row['TEXT']."</p>";
            // echo "</td>";

            echo "<td>";

            if(isAdminLoggedIn() || isTeacherLoggedIn() || doesUserHasSubscription($error))
            { 
                echo "<a class='btn btn-primary rounded-pill px-4' target='_blank' href='download.php?noteid=".$row['ID']."'>DOWNLOAD PDF</a>";   
            }
            else
            {
                echo "<a class='btn btn-primary rounded-pill px-4' target='_blank' href='receipts.php'>Buy Package</a>";
            }
            echo "</td>";

            if(isAdminLoggedIn())
            {
                echo "<td>";
                echo "<a class='btn btn-danger rounded-pill px-4' href='delete_note.php?noteid=".$row['ID']."'>Delete</a>";
                echo "</td>";
            }

            echo "</tr>"; //row end
        }
        echo "</tbody></table>";

        echo "<br/>";

        //Start video section
        echo "<table  border='0'>";
        echo "<tr>";
        echo "<td id='videocolumn'>";
        $rows = getVideoForChapter($class,$stream,$subject,$chapter);
        echo "<table  border='$border' class='video_data'><th>Video Title</th><th>Link</th>";
        
        //action only for admin
        if(isAdminLoggedIn())
        {
            echo "<th>Action</th>";
        }
        foreach ($rows as $row) {
            echo "<tr>";

            echo "<td>";
            echo "<h3>".$row['NAME']."</h3>";
            echo "</td>";

            echo "<td>";
            if(isAdminLoggedIn() || isTeacherLoggedIn() || doesUserHasSubscription($error))
            { 
                $link = $row['LINK'];
                echo "<a class='notes_link' target='_blank' href='$link'>Video</a>"; 
            }
            else
            {
                echo "<a class='notes_link' target='_blank' href='receipts.php'>Buy Package</a>";
            }
            echo "</td>";

            //action only for admin
            if(isAdminLoggedIn())
            {
                echo "<td>";
                echo "<a class='notes_link' target='_blank' href='delete.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter&videoid=".$row['ID']."'>Delete</a>";
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        //End Video section
    }
    echo "</div></div>";
    if(isAdminLoggedIn())
    {
        echo "<div class='container text-start mt-4'>";
        echo "<div class='d-flex flex-wrap gap-3'>";
        echo "<a class='btn btn-primary rounded-pill px-4' target='_blank' href='uploadnotes.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Add New Notes</a>";
        echo "<a class='btn btn-primary rounded-pill px-4' target='_blank' href='uploadvideo.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Add New Video</a>";
        echo "</div></div>";
    }
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>