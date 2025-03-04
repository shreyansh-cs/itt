<?php 
ob_start();
$title = "I.T.T Group of Education - Home";
?>
<section class="hero">
    <h1>Welcome to I.T.T Group of Education</h1>
    <p>Your Gateway to Quality Learning</p>
    <p> 
      We believe in providing quality education that not only prepares students for exams but also equips 
      them with the skills, mindset, and confidence needed to succeed in the real world. 
      Whether you are looking to crack competitive exams, enhance your skills, or 
      build a strong academic foundation, we are here to guide you every step of the way.
    </p>


    <!-- 'Join Now' बटन के साथ-साथ एक 'Online Test' बटन भी जोड़ा गया है -->
    <a href="courses.php" class="btn">Courses</a>
    <a href="usernoteslist.php" class="btn" style="margin-left: 10px;">Notes</a>
    <a href="uservideolist.php" class="btn" style="margin-left: 10px;">Video Tutorials</a>
    <a href="onlinetest.php" class="btn" style="margin-left: 10px;">Online Tests</a>
  </section>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>