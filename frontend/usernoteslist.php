
<?php 
include_once "showerror.php";
ob_start();
session_start();
$title = "Notes";
?>

<?php 
    include_once '../backend/utils.php';
    $border = "1";
    include_once 'selection.php';
    echo "<table border='$border'>";
    echo "<tr><td>";

    if(!empty($chapter))
    {
        $rows = getNotesForChapter($class,$stream,$subject,$chapter);
        echo "<table border='$border'><th>Title</th><th>Text</th><th>Notes Download</th>";//table and headers start
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
            if(doesUserHasSubscription($error))
            {
                
                echo "<a target='_blank' href='download.php?noteid=".$row['ID']."'>DOWNLOAD PDF</a>";
                
            }
            else
            {
                echo "<a target='_blank' href='receipts.php'>Buy Package</a>";
            }
            echo "</td>";
            echo "</tr>"; //row end
        }
        echo "</table>";//end table
    }
    echo "</td></tr>";
    if(isAdminLoggedIn())
    {
        echo "<tr><td class='bottomlink'><a target='_blank' href='uploadnotesvideo.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter' style='text-decoration:underline'>Add New Notes</a></td></tr>";
    }
    echo "</table>";
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>