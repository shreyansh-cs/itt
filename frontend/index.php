<?php 
include_once 'session.php';
include_once '../backend/public_utils.php';
ob_start();
$title = "I.T.T Group of Education - Home";
?>
<div class="home">
    <h1>Welcome to I.T.T Group of Education</h1>
    <p>Your Gateway to Quality Learning</p>
    <p> 
      We believe in providing quality education that not only prepares students for exams but also equips 
      them with the skills, mindset, and confidence needed to succeed in the real world. 
      Whether you are looking to crack competitive exams, enhance your skills, or 
      build a strong academic foundation, we are here to guide you every step of the way.
    </p>

    <a href="courses.php" class="home_link">Courses</a>
    <a href="noteslist.php" class="home_link">Notes & Video</a>
    <a href="test/onlinetest.php" class="home_link">Online Tests</a>
    <a href="receipts.php" class="home_link">Buy Package</a>
    <?php if(isAdminLoggedIn()) { 
      echo "<a href='gettransactions.php' class='home_link'>Transactions</a>"; 
      echo "<a href='test/create_test.php' class='home_link'>Create Test</a>"; 
      echo "<a href='test/upload_questions.php' class='home_link'>Upload Questions</a>";
      echo "<a href='test/map_test_to_class.php' class='home_link'>Map test to Class</a>";
      echo "<a href='database_dump.php' class='home_link'>Database Dump</a>";  
      echo "<a href='test/edit_test_map.php' class='home_link'>Edit Test Map</a>";
    }
    ?>
</div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>