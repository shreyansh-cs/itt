<?php 
ob_start();
$title = "Login";
?>
<script>
    function showSignUp() {
      document.getElementById('login-form').style.display = 'none';
      document.getElementById('signup-form').style.display = 'block';
      document.getElementById('forgot-form').style.display = 'none';
    }
    function showLogin() {
      document.getElementById('signup-form').style.display = 'none';
      document.getElementById('login-form').style.display = 'block';
      document.getElementById('forgot-form').style.display = 'none';
    }
    function showForgotPassword() {
      document.getElementById('login-form').style.display = 'none';
      document.getElementById('signup-form').style.display = 'none';
      document.getElementById('forgot-form').style.display = 'block';
    }
  </script>
<!-- Login Form -->
<div class="container" id="login-form">
    <h2>Login</h2>
    <form action="../backend/authenticate.php" method="POST">
      <table>
        <tr>
          <td>Email or Phone</td>
          <td><input type="text" name="email_or_phone" placeholder="Email or Phone" required></td>
        </tr>
        <tr>
          <td>Password</td>
          <td> <input type="password" name="password" placeholder="Password" required></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><button type="submit">Login</button></td>
        </tr>
        <!--tr>
          <td>
          &nbsp;
          </td>
          <td>
          <a onclick="showForgotPassword()">Forgot Password?</a>
          <a onclick="showSignUp()">New User? Sign Up</a>
          </td>
        </tr-->
      </table>
    </form>
  </div>
  
  <!-- Sign Up Form -->
  <!--div class="container" id="signup-form" style="display: none;">
    <h2>Sign Up</h2>
    <form action="../backend/register.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="text" name="father_name" placeholder="Father's Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <input type="date" name="dob" placeholder="Date of Birth" required>
      <input type="text" name="user_class" placeholder="" required>
      <label>Upload Photo:</label>
      <input type="file" name="photo" accept="image/*" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Sign Up</button>
    </form>
    <div class="links">
      <p>Already have an account? <a onclick="showLogin()">Login</a></p>
    </div>
  </div>
  
  <div class="container" id="forgot-form" style="display: none;">
    <h2>Forgot Password</h2>
    <form>
      <input type="text" placeholder="Enter Email or Phone" required>
      <button type="submit">Reset Password</button>
    </form>
    <div class="links">
      <p><a onclick="showLogin()">Back to Login</a></p>
    </div>
  </div-->

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


