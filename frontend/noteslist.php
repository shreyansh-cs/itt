
<?php 
include_once "showerror.php";
ob_start();
session_start();
$title = "Admin Notes";
?>

<?php 

    //restrict page
    include_once 'restrictedpage.php';
    
    include_once '../backend/utils.php';
    $border = "0";
    include_once 'selection.php';
    echo "<table border='$border'>";
    echo "<tr>";

    if(!empty($chapter))
    {
        echo "<td id='notescolumn'>";
        $rows = getNotesForChapter($class,$stream,$subject,$chapter);
        echo "<table border='$border'><th>Title</th><th>Text</th><th>Notes Download</th><th>Action</th>";//table and headers start
        //Each row
        foreach ($rows as $row) {
            echo "<tr>"; //row start

            echo "<td>";
            echo "<span>".$row['NAME']."</span>";
            echo "</td>";
            
            echo "<td>";
            echo "<p>".$row['TEXT']."</p>";
            echo "</td>";

            echo "<td>";
            echo "<a target='_blank' href='download.php?noteid=".$row['ID']."'>DOWNLOAD PDF</a>";
            echo "</td>";

            echo "<td>";
            echo "<a target='_blank' href='delete.php?class=$class&stream=$stream&subject=$subject&chapter=$chapter&noteid=".$row['ID']."'>Delete</a>";
            echo "</td>";

            echo "</tr>"; //row end
        }
        echo "</table>";//end table
        echo "</td>";
    }
    echo "</tr>";
    if(isAdminLoggedIn())
    {
        echo "<tr><td><a target='_blank' href='uploadnotesvideo.php?class=$class&stream=$stream&subject=$subject&chapter=$chapter'>Add New Notes</tr></td>";
    }
    echo "</table>";
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>