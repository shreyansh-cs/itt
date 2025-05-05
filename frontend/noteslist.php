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
    echo "<div class='container text-start mt-4'>";
    echo "<div class='table-responsive'>";

    if(!empty($chapter))
    {
        $rows = getNotesForChapter($class,$stream,$subject,$chapter);
        echo "<table class='table table-bordered table-hover'>";
        echo "<thead class='table-primary'>";
        echo "<tr><th style='width: 50%'>Title</th><th style='width: 25%'>Notes Download</th>";
        //Action column is only for admin
        if(isAdminLoggedIn())
        {
            echo "<th style='width: 25%'>Action</th>";//table and headers start
        }
        echo "</tr></thead><tbody>";
        //Each row
        foreach ($rows as $row) {
            echo "<tr>"; //row start

            echo "<td>";
            echo "<span>".$row['NAME']."</span>";
            echo "</td>";
            
            echo "<td>";
            if(isAdminLoggedIn() || isTeacherLoggedIn() || doesUserHasSubscription($error))
            { 
                echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='download.php?noteid=".$row['ID']."'>DOWNLOAD PDF</a>";   
            }
            else
            {
                echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='receipts.php'>Buy Package</a>";
            }
            echo "</td>";

            if(isAdminLoggedIn())
            {
                echo "<td>";
                echo "<a class='btn btn-danger btn-sm rounded-pill px-3' href='delete_note.php?noteid=".$row['ID']."'>Delete</a>";
                echo "</td>";
            }

            echo "</tr>"; //row end
        }
        echo "</tbody></table>";

        echo "<br/>";

        //Start video section
        echo "<div class='table-responsive'>";
        $rows = getVideoForChapter($class,$stream,$subject,$chapter);
        echo "<table class='table table-bordered table-hover'>";
        echo "<thead class='table-primary'>";
        echo "<tr><th style='width: 50%'>Video Title</th><th style='width: 25%'>Link</th>";
        
        //action only for admin
        if(isAdminLoggedIn())
        {
            echo "<th style='width: 25%'>Action</th>";
        }
        echo "</tr></thead><tbody>";
        foreach ($rows as $row) {
            echo "<tr>";

            echo "<td>";
            echo "<span>".$row['NAME']."</span>";
            echo "</td>";

            echo "<td>";
            if(isAdminLoggedIn() || isTeacherLoggedIn() || doesUserHasSubscription($error))
            { 
                $link = $row['LINK'];
                echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='$link'>Video</a>"; 
            }
            else
            {
                echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='receipts.php'>Buy Package</a>";
            }
            echo "</td>";

            //action only for admin
            if(isAdminLoggedIn())
            {
                echo "<td>";
                echo "<a class='btn btn-danger btn-sm rounded-pill px-3' href='delete.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter&videoid=".$row['ID']."'>Delete</a>";
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
        //End Video section

        // Start Tests section
        echo "<div class='table-responsive mt-4'>";
        echo "<h4 class='mb-3'>Available Tests</h4>";
        
        // Get tests mapped to this class
        include __DIR__.'/../backend/db.php';
        $stmt = $pdo->prepare("
            SELECT t.test_id, t.title, t.duration_minutes, t.total_questions,
                   (SELECT COUNT(*) FROM questions WHERE test_id = t.test_id) as questions_added
            FROM tests t
            INNER JOIN test_classes_map tcm ON t.test_id = tcm.test_id
            WHERE tcm.class_id = ?
        ");
        $stmt->execute([$class]);
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($tests)) {
            echo "<table class='table table-bordered table-hover'>";
            echo "<thead class='table-primary'>";
            echo "<tr>
                    <th>Test Title</th>
                    <th>Duration</th>
                    <th>Questions</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>";
            echo "</thead><tbody>";
            
            foreach ($tests as $test) {
                $status = $test['questions_added'] == $test['total_questions'] ? 
                    '<span class="badge bg-success">Complete</span>' : 
                    '<span class="badge bg-warning">Incomplete</span>';
                
                echo "<tr>";
                echo "<td>" . htmlspecialchars($test['title']) . "</td>";
                echo "<td>" . $test['duration_minutes'] . " minutes</td>";
                echo "<td>" . $test['questions_added'] . "/" . $test['total_questions'] . "</td>";
                echo "<td>" . $status . "</td>";
                echo "<td>";
                if (isAdminLoggedIn() || isTeacherLoggedIn() || doesUserHasSubscription($error)) {
                    echo "<a href='test/take_test.php?test_id=" . $test['test_id'] . "' class='btn btn-primary btn-sm rounded-pill px-3'>Take Test</a>";
                } else {
                    echo "<a href='receipts.php' class='btn btn-primary btn-sm rounded-pill px-3'>Buy Package</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info'>No tests available for this class.</div>";
        }
        echo "</div>";
        // End Tests section
    }
    echo "</div></div>";
    if(isAdminLoggedIn())
    {
        echo "<div class='container text-start mt-4'>";
        echo "<div class='d-flex flex-wrap gap-3'>";
        echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='uploadnotes.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Add New Notes</a>";
        echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='uploadvideo.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Add New Video</a>";
        echo "</div></div>";
    }
?>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>