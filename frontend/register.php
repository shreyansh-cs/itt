<?php 
include_once 'session.php';
include_once 'showerror.php';
ob_start();
include_once '../backend/handleregister.php';
include_once '../backend/utils.php';
$title = "Register";
$required = "required";
?>
  <div id="msg" style='color:red;text-align:left;margin-bottom:10px;text-decoration:underline'>
    <?php if(isset($msg)) echo $msg; ?>
  </div>
  <!-- Sign Up Form -->
  <div class="login-container" id="signup-form">
    <form action="" method="POST" enctype="multipart/form-data">
        <table class='login'>
            <tr>
            <th class='second'>Sign Up</th>
            </tr>
            <tr>
            <tr>
                <td class='second'>
                    <select name='class' id='class'>
                        <?php 
                            $rows = getAllClasses(); 
                            foreach ($rows as $row)
                            {
                                echo "<option value='{$row['ID']}'".checkSelected($row['ID'],$user_class). ">{$row['NAME']}</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <input type="text" name="full_name" <?php echo "value='$full_name'"; ?> placeholder="Full Name" <?php echo $required; ?>>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <input type="text" name="father_name" <?php echo "value='$father_name'"; ?> placeholder="Father's Name" <?php echo $required; ?>>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <input type="email" name="email" <?php echo "value='$email'"; ?> placeholder="Email" <?php echo $required; ?>> 
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <input type="text" name="phone" <?php echo "value='$phone'"; ?> placeholder="Phone Number" <?php echo $required; ?>>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <input type="date" name="dob" <?php echo "value='$dob'"; ?> placeholder="Date of Birth" <?php echo $required; ?>>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <!--label>Upload Photo:</label-->
                    <input type="file" name="photo" accept="image/*" <?php echo $required; ?>>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <input type="password" name="password" placeholder="Password" <?php echo $required; ?>>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <button type="submit">Sign Up</button>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    Already have an account? <a href="login.php" > Login </a>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    Forgot Password? <a href="forgot.php"> Reset Password </a>
                </td>
            </tr>
            <tr>
                <td class='second'>
                    <a href='change_password.php'>Change Password</a>
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


