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

$title = "Change Password";
include_once '../backend/handlepasswordchange.php';
?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Change Password</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(!empty($msg)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="email_or_phone" name="email_or_phone" 
                                <?php echo "value='$email_or_phone'"; ?> placeholder="Email or Phone" required>
                            <label for="email_or_phone">Email or Phone</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" 
                                placeholder="Old Password" required>
                            <label for="password">Old Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                placeholder="New Password" required>
                            <label for="new_password">New Password</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="showPassword">
                            <label class="form-check-label" for="showPassword">
                                Show Password
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Change Password</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-transparent border-0 text-center py-3">
                    <div class="small mb-2">
                        Already have an account? <a href="login.php" class="text-decoration-none">Login</a>
                    </div>
                    <div class="small mb-2">
                        <a href="forgot.php" class="text-decoration-none">Forgot Password?</a>
                    </div>
                    <div class="small">
                        New User? <a href="register.php" class="text-decoration-none">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    margin-top: 0;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-floating > .form-control {
    padding: 1rem 0.75rem;
}

.form-floating > label {
    padding: 1rem 0.75rem;
}

.btn-primary {
    padding: 0.8rem;
}

.card-footer a {
    color: #0d6efd;
}

.card-footer a:hover {
    color: #0a58ca;
}

.container-fluid {
    padding-top: 0;
    padding-bottom: 0;
}
</style>

<script>
document.getElementById("showPassword").addEventListener("click", function() {
    const passwordInput = document.getElementById("password");
    const passwordInput_new = document.getElementById("new_password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordInput_new.type = "text";
        this.checked = true;
    } else {
        passwordInput.type = "password";
        passwordInput_new.type = "password";
        this.checked = false;
    }
});
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>