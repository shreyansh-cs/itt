<?php 
include_once 'showerror.php';
ob_start();
include_once '../backend/handleregister.php';
$title = "Register";
?>
  <div id="msg" style='color:red'>
    <?php if(isset($msg)) echo $msg; ?>
  </div>
  <!-- Sign Up Form -->
  <div class="container" id="signup-form">
    <h2>Sign Up</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>
                    <select name='class' id='class'>
                        <?php 
                            for($i=5;$i<13;$i++)
                            {
                                $selected = "";
                                if($i == $user_class)
                                {
                                    $selected = "selected";
                                }
                                echo "<option value='$i' $selected>CLASS $i</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="full_name" <?php echo "value='$full_name'"; ?> placeholder="Full Name" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="father_name" <?php echo "value='$father_name'"; ?> placeholder="Father's Name" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="email" name="email" <?php echo "value='$email'"; ?> placeholder="Email" required> 
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="phone" <?php echo "value='$phone'"; ?> placeholder="Phone Number" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="date" name="dob" <?php echo "value='$dob'"; ?> placeholder="Date of Birth" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Upload Photo:</label>
                    <input type="file" name="photo" accept="image/*" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="password" name="password" placeholder="Password" required>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit">Sign Up</button>
                </td>
            </tr>
            <tr>
                <td>
                <div class="links">
                    <p>Already have an account? <a href="login.php" > Login </a></p>
                    </div>
                    <div class="links">
                    <p>Forgot Password? <a href="forgot.php"> Reset Password </a></p>
                </div>
                </td>
            </tr>
        </table>
        </table> 
    </form>
  </div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


