<?php
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>I.T.T Group of Education - Home</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* अतिरिक्त CSS यदि आवश्यक हो */
    .hero {
      color: #000000;
      padding: 100px 20px;
      text-align: center;
    }
    .hero h1 {
      font-size: 50px;
      margin-bottom: 20px;
    }
    .hero p {
      font-size: 26px;
      margin-bottom: 30px;
    }
    .btn {
      background: #ff9800;
      color: #fff;
      padding: 15px 30px;
      text-decoration: none;
      font-size: 22px;
      border-radius: 5px;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #e68900;
    }
  </style>
</head>
<body>
  
  <?php 
   require_once "header.php";
   echo getMyHeader(); 
  ?>
  <section class="hero">
    <h1>Welcome to I.T.T Group of Education</h1>
    <p>Your Gateway to Quality Learning</p>
    <!-- 'Join Now' बटन के साथ-साथ एक 'Online Test' बटन भी जोड़ा गया है -->
    <a href="courses.php" class="btn">Join Now</a>
    <a href="test-series.php" class="btn" style="margin-left: 10px;">Online Test</a>
  </section>
  
  <?php 
    require_once 'footer.php';
    echo getFooter();

  ?>
  
  <script src="script.js"></script>
</body>
</html>

