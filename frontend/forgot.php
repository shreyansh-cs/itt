<?php 
include_once 'session.php';
include_once 'showerror.php';
ob_start();
$title = "Forgot Password";
include_once 'session.php';
include_once '../backend/handleforgot.php';
?>
<div class='message'><?php echo $msg; ?></div>
<!-- Login Form -->
<div class="login-container" id="login-form">
    <form action="" method="POST">
      <table class='login'>
        <tr>
          <!--th class='first'></th-->
          <th class='second'>Recover Password</th>
        </tr>
        <tr>
          <!--td class='first'>Email</td-->
          <td class='second'><input type="text" name="email" <?php echo "value='$email'"; ?> placeholder="Email" required></td>
        </tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'><button type="submit">Recover</button></td>
        </tr>
        <tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'> 
            Already have an account? <a href="login.php" > Login </a>
          </td>
        </tr>
        <tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'>New User? <a href='register.php'>Sign Up</a></td>
        </tr>
      </table>
    </form>
  </div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


