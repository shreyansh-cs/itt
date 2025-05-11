<?php 
include_once 'session.php';
include_once 'showerror.php';
ob_start();
$title = "Forgot Password";
include_once 'session.php';
include_once '../backend/handleforgot.php';
?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Recover Password</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(!empty($msg)): ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" 
                                <?php echo "value='$email'"; ?> placeholder="Email" required>
                            <label for="email">Email</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Recover Password</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-transparent border-0 text-center py-3">
                    <div class="small mb-2">
                        Already have an account? <a href="login.php" class="text-decoration-none">Login</a>
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

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>


