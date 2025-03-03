<?php 
include_once 'showerror.php';
ob_start();
$title = "Login";
include_once 'session.php';
include_once '../backend/handlelogin.php';
?>
<div style='color:red'><?php echo $msg; ?></div>
<!-- Login Form -->
<div class="container" id="login-form">
    <h2>Login</h2>
    <form action="" method="POST">
      <table>
        <tr>
          <td>Email or Phone</td>
          <td><input type="text" name="email_or_phone" <?php echo "value='$email_or_phone'"; ?> placeholder="Email or Phone" required></td>
        </tr>
        <tr>
          <td>Password</td>
          <td> <input type="password" name="password" placeholder="Password" required></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><button type="submit">Login</button></td>
        </tr>
        <tr>
          <td>
          &nbsp;
          </td>
          <td>
          <a href='forgot.php'>Forgot Password?</a>
          </td>
        </tr>
        <tr>
          <td>
          &nbsp;
          </td>
          <td>
          <a href='register.php'>New User? Sign Up</a>
          </td>
        </tr>
      </table>
    </form>
  </div>
  
  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


