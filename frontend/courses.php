<?php 
ob_start();
$title = "Courses";
?>
  <table>
  <tr>
  <td class='td_courses'>
  <a href="usernoteslist.php" class="course-link">
      <div class="course-card">
        <!--img src="course1.jpg" alt="6th to 12th All Subjects"-->
        <h3>Notes and Videos</h3>
        <p>Comprehensive courses covering all subjects. Click here to view Notes & Video Lectures.</p>
      </div>
    </a>
  </td>
  <tr>
  <td class='td_courses'>
  <a href="computercoaching.php" class="course-link">
      <div class="course-card">
        <!--img src="course2.jpg" alt="Computer Coaching"-->
        <h3>Computer Coaching</h3>
        <p>Specialized computer coaching including CCA, DCA, CFA, DTP, DOA, DCP, ADIT, ADCA, and DHT. Click for Notes.</p>
      </div>
    </a>
  </td>
  </tr>
  <tr>
  <td class='td_courses'>
  <a href="competitiveexam.php" class="course-link">
      <div class="course-card">
        <!--img src="course3.jpg" alt="Competitive Exam Preparation"-->
        <h3>Competitive Exam Preparation</h3>
        <p>Focused coaching for competitive exams with expert guidance and study materials. Click for Notes & Videos.</p>
      </div>
    </a>
  </td>
  </tr>
  </table>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


