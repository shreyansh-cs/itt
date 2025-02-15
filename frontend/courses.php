<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses - I.T.T Group of Education</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .courses-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }
    .course-card {
      background: #fff;
      width: 300px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      padding: 20px;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .course-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    .course-card img {
      width: 100%;
      border-radius: 10px;
    }
    .course-card h3 {
      margin: 15px 0;
      color: #002060;
    }
    .course-card p {
      font-size: 16px;
      color: #555;
    }
    .course-link {
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>
<body>
<?php 
  require_once "header.php"; 
  echo getMyHeader();
 ?>
  <section class="courses-container">
    <a href="notes.php" class="course-link">
      <div class="course-card">
        <!--img src="course1.jpg" alt="6th to 12th All Subjects"-->
        <h3>Notes and Videos</h3>
        <p>Comprehensive courses covering all subjects. Click here to view Notes & Video Lectures.</p>
      </div>
    </a>
    <a href="computer_coaching.php" class="course-link">
      <div class="course-card">
        <!--img src="course2.jpg" alt="Computer Coaching"-->
        <h3>Computer Coaching</h3>
        <p>Specialized computer coaching including CCA, DCA, CFA, DTP, DOA, DCP, ADIT, ADCA, and DHT. Click for Notes.</p>
      </div>
    </a>
    <a href="competitive_exam.php" class="course-link">
      <div class="course-card">
        <!--img src="course3.jpg" alt="Competitive Exam Preparation"-->
        <h3>Competitive Exam Preparation</h3>
        <p>Focused coaching for competitive exams with expert guidance and study materials. Click for Notes & Videos.</p>
      </div>
    </a>
  </section>
  <?php 
    require_once 'footer.php';
    echo getFooter();
  ?>
  <script src="script.js"></script>
</body>
</html>
