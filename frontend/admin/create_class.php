<?php
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../../backend/utils.php';
include_once __DIR__.'/../../backend/public_utils.php';

include_once __DIR__.'/../../frontend/restrictedpage.php';

$title = "Create New Class - Admin Panel";
$msg = "";
$success = false;

// Initialize form values
$class_name = "";
$package_id = "";
$supported = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once __DIR__.'/../../backend/db.php';
    
    $class_name = trim($_POST['class_name']);
    $package_id = intval($_POST['package_id']);
    $supported = isset($_POST['supported']) ? 1 : 0;
    
    // Validate input
    $errors = [];
    
    if (empty($class_name)) {
        $errors[] = "Class name is required.";
    } elseif (strlen($class_name) > 200) {
        $errors[] = "Class name must be 200 characters or less.";
    }
    
    if ($package_id <= 0) {
        $errors[] = "Package ID must be a positive number.";
    }
    
    // Check if class name already exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM classes WHERE NAME = ?");
            $stmt->execute([$class_name]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "A class with this name already exists.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // Insert new class if no errors
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO classes (NAME, IS_COMPUTER, IS_COMPETITION, PACKAGE_ID, SUPPORTED) VALUES (?, 0, 0, ?, ?)");
            $result = $stmt->execute([$class_name, $package_id, $supported]);
            
            if ($result) {
                $success = true;
                $msg = "Class '$class_name' created successfully!";
                // Reset form values
                $class_name = "";
                $package_id = "";
                $supported = "";
            } else {
                $errors[] = "Failed to create class.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // Display errors
    if (!empty($errors)) {
        $msg = implode("<br>", $errors);
    }
}

function checkChecked($value, $current) {
    return $value == $current ? " checked" : "";
}

ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Admin Panel</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Class</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Create New Class
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                            <?php echo $msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="class_name" class="form-label">Class Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="class_name" 
                                   name="class_name" 
                                   value="<?php echo htmlspecialchars($class_name); ?>" 
                                   placeholder="e.g., CLASS 13, NEET, JEE, etc."
                                   maxlength="200"
                                   required>
                            <div class="form-text">Enter the name of the class (max 200 characters)</div>
                        </div>

                        <div class="mb-3">
                            <label for="package_id" class="form-label">Package <span class="text-danger">*</span></label>
                            <select class="form-select" id="package_id" name="package_id" required>
                                <option value="">Select a package...</option>
                                <?php 
                                    $packages = getAllPackages(); 
                                    foreach ($packages as $package) {
                                        $selected = ($package['ID'] == $package_id) ? ' selected' : '';
                                        $price = number_format($package['PRICE'], 2); // Price is already in rupees
                                        echo "<option value='{$package['ID']}'{$selected}>";
                                        echo htmlspecialchars($package['NAME']) . " (₹{$price})";
                                        echo "</option>";
                                    }
                                ?>
                            </select>
                            <div class="form-text">Select the package associated with this class</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="supported" 
                                       name="supported"
                                       <?php echo $supported ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="supported">
                                    Supported/Active
                                </label>
                            </div>
                            <div class="form-text">Check to make this class available to students</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Class</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.form-text {
    font-size: 0.875em;
    color: #6c757d;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}
</style>

<?php
$content = ob_get_clean();
include_once __DIR__.'/../master.php';
?>
