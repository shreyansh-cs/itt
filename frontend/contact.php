<?php 
ob_start();
$title = "Contact Us";
?>
<!-- Contact Info Section -->
<div class="contact-info">
    <h3>Our Office</h3>
    <p>Address: Bimal Chowk, Mudipur</p>
    <p>Phone: +91-7349803122</p>
    <p>Email: <a href="mailto:info@ittgroup.com">support@itticon.in</a></p>
</div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


