<?php 
include_once 'session.php';
include_once '../backend/public_utils.php';
ob_start();

//If already logged in , go to index
if(isSessionValid())
{
  header("Location: /itt/index.php");
  exit;
}

$title = "Login";
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
          <td class='second'> 
          <input id='password' type="password" name="password" placeholder="Password" required>
          </td>
        </tr>
        <tr>
          <td class='second'> 
            <span id='showPassword'>Show Password</span>
          </td>
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
        <tr>
          <!--td class='first'>&nbsp;</td-->
          <td class='second'>
          <a href='change_password.php'>Change Password</a>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <script>
    const passwordInput = document.getElementById("password");
    const showPasswordCheckbox = document.getElementById("showPassword");

    showPasswordCheckbox.addEventListener("click", function () {
      if(passwordInput.type == "password"){
        passwordInput.type = "text";
        showPasswordCheckbox.innerHTML = "Hide Password";
      }
      else {
        passwordInput.type = "password";
        showPasswordCheckbox.innerHTML = "Show Password"
      }
    });
  </script>
  
  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


