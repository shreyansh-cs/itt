
<?php 
include "showerror.php";
ob_start();
session_start();
$title = "I.T.T Group of Education - Home";
?>

<?php 
    $border = "2";
    include_once 'selection.php';
    echo "<table  border='$border'>";
    echo "<tr>";

    if(!empty($chapter))
    {
        echo "<td id='videocolumn'>";
        $rows = getVideoForChapter($class,$stream,$subject,$chapter);
        echo "<table  border='$border'><th>Video Title</th><th>Link</th>";
        foreach ($rows as $row) {
            echo "<tr>";

            echo "<td>";
            echo "<h3>".$row['NAME']."</h3>";
            echo "</td>";

            echo "<td>";
            $link = $row['LINK'];
            echo "<a target='_blank' href='$link'>".$row['NAME']."</a>"; 
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
        echo "</td>";
    }

    echo "</tr>";
    echo "</table>";
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>