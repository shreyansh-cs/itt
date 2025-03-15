<?php 
include_once 'showerror.php';
ob_start();
$title = "Login";
include_once 'session.php';
include_once '../backend/handlelogin.php';
?>
<div style='color:red'><?php echo $msg; ?></div>
<!-- Login Form -->
<div class="login-container" id="login-form">
    <form action="" method="POST">
      <table class='login'>
        <tr>
          <!--th></th-->
          <th>Login</th>
        </tr>
        <tr>
          <!--td class='first'>Email or Phone</td-->
          <td class='second'><input type="text" name="email_or_phone" <?php echo "value='$email_or_phone'"; ?> placeholder="Email or Phone" required></td>
        </tr>
        <tr>
          <!--td class='first'>Password</td-->
          <td class='second'> <input type="password" name="password" placeholder="Password" required></td>
        </tr>
        <tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'><button type="submit">Login</button></td>
        </tr>
        <tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'>
          <a href='forgot.php'>Forgot Password?</a>
          </td>
        </tr>
        <tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'>
          New User? <a href='register.php'>Sign Up</a>
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


