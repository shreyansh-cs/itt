<?php 
ob_start();
$title = "Register";
?>
  <div class="container" id="forgot-form" style="display: none;">
    <h2>Forgot Password</h2>
    <form action='../backend/handleforgot.php'>
      <input type="text" placeholder="Enter Email or Phone" required>
      <button type="submit">Reset Password</button>
    </form>
    <div class="links">
      <p><a href='login.php'>Back to Login</a></p>
    </div>
  </div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


