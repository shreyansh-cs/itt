<?php
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blank title</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  
  <?php 
   require_once "header.php";
   echo getMyHeader(); 
  ?>
  <section class="hero">
    <h1>Provide content for this page here</h1>
  </section>
  
  <?php 
    require_once 'footer.php';
    echo getFooter();
  ?>
  
  <script src="script.js"></script>
</body>
</html>

