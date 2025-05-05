<?php 
include_once 'session.php';
include_once '../backend/public_utils.php';
ob_start();
$title = "I.T.T Group of Education - Home";
?>
<div class="container text-center">
    <h1 class="mb-4">Welcome to I.T.T Group of Education</h1>
    <p class="lead mb-4">Your Gateway to Quality Learning</p>
    <p class="mb-5"> 
      We believe in providing quality education that not only prepares students for exams but also equips 
      them with the skills, mindset, and confidence needed to succeed in the real world. 
      Whether you are looking to crack competitive exams, enhance your skills, or 
      build a strong academic foundation, we are here to guide you every step of the way.
    </p>

    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
        <a href="courses.php" class="btn btn-primary rounded-pill px-4">Courses</a>
        <a href="noteslist.php" class="btn btn-primary rounded-pill px-4">Notes & Video</a>
        <a href="test/onlinetest.php" class="btn btn-primary rounded-pill px-4">Online Tests</a>
        <a href="receipts.php" class="btn btn-primary rounded-pill px-4">Buy Package</a>
        <?php if(isAdminLoggedIn()) { 
            echo '<a href="gettransactions.php" class="btn btn-primary rounded-pill px-4">Transactions</a>';
            echo '<a href="test/create_test.php" class="btn btn-primary rounded-pill px-4">Create Test</a>';
            echo '<a href="test/upload_questions.php" class="btn btn-primary rounded-pill px-4">Upload Questions</a>';
            echo '<a href="test/map_test_to_class.php" class="btn btn-primary rounded-pill px-4">Map test to Class</a>';
            //echo '<a href="database_dump.php" class="btn btn-primary rounded-pill px-4">Database Dump</a>';
            echo '<a href="test/edit_test_map.php" class="btn btn-primary rounded-pill px-4">Edit Test Map</a>';
        }
        ?>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>