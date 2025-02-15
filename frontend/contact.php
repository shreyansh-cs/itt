<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - I.T.T Group of Education</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php 
  require_once "header.php";
  echo getMyHeader();
 ?>
  <div class="container">
    <h2>Contact Us</h2>
    <form>
      <input type="text" placeholder="Your Name" required>
      <input type="email" placeholder="Your Email" required>
      <input type="text" placeholder="Subject" required>
      <textarea rows="4" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
  <?php 
    require_once 'footer.php';
    echo getFooter();
  ?>
  <script src="script.js"></script>
</body>
</html>
