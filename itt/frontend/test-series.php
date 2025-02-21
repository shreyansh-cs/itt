<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Test Series - I.T.T Group of Education</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php 
  require_once "header.php"; 
  echo getMyHeader();
 ?>
  
  <div class="container" id="test-series">
    <h2>Online Test Series</h2>
    <form action="#" method="GET">
      <label for="class">Select Class:</label>
      <select id="class" name="class" onchange="updateSubjects()">
        <?php
              session_start();
             $class = $_SESSION['user_class'];
             echo $class;
             echo "<option value=\"{$class}\">Class {$class}</option>";
        ?>
      </select>
      
      <label for="subject">Select Subject:</label>
      <select id="subject" name="subject" onchange="updateChapters()"></select>
      
      <label for="chapter">Select Chapter:</label>
      <select id="chapter" name="chapter"></select>
      
      <button type="submit">Start Test</button>
      <button type="button" onclick="viewResults()">See Results</button>
    </form>
  </div>
  
  <script src="script.js"></script>
</body>
</html>
