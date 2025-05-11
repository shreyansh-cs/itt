<?php 
include_once 'session.php';
include_once 'showerror.php';
ob_start();
include_once '../backend/handleregister.php';
include_once '../backend/utils.php';
$title = "Register";
$required = "required";
?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Sign Up</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($msg) && !empty($msg)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="class" id="class">
                                <?php 
                                    $rows = getAllClasses(); 
                                    foreach ($rows as $row) {
                                        echo "<option value='{$row['ID']}'".checkSelected($row['ID'],$user_class). ">{$row['NAME']}</option>";
                                    }
                                ?>
                            </select>
                            <label for="class">Select Class</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                <?php echo "value='$full_name'"; ?> placeholder="Full Name" <?php echo $required; ?>>
                            <label for="full_name">Full Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="father_name" name="father_name" 
                                <?php echo "value='$father_name'"; ?> placeholder="Father's Name" <?php echo $required; ?>>
                            <label for="father_name">Father's Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" 
                                <?php echo "value='$email'"; ?> placeholder="Email" <?php echo $required; ?>>
                            <label for="email">Email</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                <?php echo "value='$phone'"; ?> placeholder="Phone Number" <?php echo $required; ?>>
                            <label for="phone">Phone Number</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="dob" name="dob" 
                                <?php echo "value='$dob'"; ?> placeholder="Date of Birth" <?php echo $required; ?>>
                            <label for="dob">Date of Birth</label>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Upload Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" <?php echo $required; ?>>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" 
                                placeholder="Password" <?php echo $required; ?>>
                            <label for="password">Password</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="showPassword">
                            <label class="form-check-label" for="showPassword">
                                Show Password
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
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
                        <a href="change_password.php" class="text-decoration-none">Change Password</a>
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

.form-floating > .form-control,
.form-floating > .form-select {
    padding: 1rem 0.75rem;
}

.form-floating > label {
    padding: 1rem 0.75rem;
    z-index: 1;
}

.form-floating > .form-select {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}

.form-floating > .form-select ~ label {
    opacity: 0.65;
    transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
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

.form-label {
    font-weight: 500;
    color: #495057;
}

.form-check-label {
    color: #495057;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>

<script>
document.getElementById('showPassword').addEventListener('change', function() {
    const passwordInput = document.getElementById('password');
    passwordInput.type = this.checked ? 'text' : 'password';
});
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>


