<?php 
include_once 'session.php';
ob_start();
$title = "Courses";
?>
  <table class='courses'>
  <tr>
  <td class='first'>
  <a href="noteslist.php" class="course-link">Notes and Videos</a>
  </td>
  <td class='second'>Comprehensive courses covering all subjects.</td>
  <tr>
  <td class='first'>
  <a href="computercoaching.php" class="course-link">Computer Coaching</a>
  </td>
  <td class='second'>Specialized computer coaching including CCA, DCA, CFA, DTP, DOA, DCP, ADIT, ADCA, and DHT.</td>
  </tr>
  <tr>
  <td class='first'>
  <a href="competitiveexam.php" class="course-link">Competitive Exam Preparation</a>
  </td>
  <td class='second'>Focused coaching for competitive exams with expert guidance and study materials.</td>
  </tr>
  </table>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


