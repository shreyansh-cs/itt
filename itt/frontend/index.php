<?php 
ob_start();
$title = "I.T.T Group of Education - Home";
?>
<section class="hero">
    <h1>Welcome to I.T.T Group of Education</h1>
    <p>Your Gateway to Quality Learning</p>
    <!-- 'Join Now' बटन के साथ-साथ एक 'Online Test' बटन भी जोड़ा गया है -->
    <a href="courses.php" class="btn">Join Now</a>
    <a href="test-series.php" class="btn" style="margin-left: 10px;">Online Test</a>
  </section>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>