<?php 
include_once 'session.php';
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
    <a href="onlinetest.php" class="home_link">Online Tests</a>
    <a href="receipts.php" class="home_link">Buy Package</a>
</div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>