<?php
session_start();
include_once __DIR__.'/../../backend/utils.php';
include_once __DIR__.'/../../backend/public_utils.php';

include_once __DIR__.'/../../frontend/restricted_page.php';

// Check if user is logged in and is admin
if (!isSessionValid()) {
    header("Location: ../login.php");
    exit();
}

$user_type = getUserType();
if ($user_type !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$title = "Admin Panel - I.T.T. Group of Education";

ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Panel</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                    </h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Welcome to the admin panel. Here you can manage classes, subjects, tests, and other administrative tasks.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Class Management Section -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Class Management
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Classes</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="create_class.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Create New Class
                        </a>
                        <a href="manage_classes.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>Manage Classes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Management Section -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Test Management
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Tests</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="../test/create_test.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Create Test
                        </a>
                        <a href="../test/list_tests.php" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-list me-1"></i>View Tests
                        </a>
                        <a href="../test/upload_questions.php" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Questions
                        </a>
                        <a href="../test/admin_take_test.php" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-pencil-alt me-1"></i>Take Test
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Management Section -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Content Management
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Notes & Videos</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="../uploadnotes.php" class="btn btn-info btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Notes
                        </a>
                        <a href="../uploadvideo.php" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-video me-1"></i>Upload Video
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Configuration Section -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Test Configuration
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Chapter Mapping</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="../test/map_test_to_chapter.php" class="btn btn-warning btn-sm">
                            <i class="fas fa-sitemap me-1"></i>Map Test to Chapter
                        </a>
                        <a href="../test/edit_test_chapter_map.php" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Manage Chapter Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Financial Management Section -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Financial Management
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Transactions</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="../gettransactions.php" class="btn btn-danger btn-sm">
                            <i class="fas fa-money-bill-wave me-1"></i>View Transactions
                        </a>
                        <a href="../receipts.php" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-receipt me-1"></i>Payment Receipts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Quick Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <?php
                        try {
                            include_once __DIR__.'/../../backend/db.php';
                            
                            // Get total classes
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM classes");
                            $stmt->execute();
                            $total_classes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                            
                            // Get supported classes
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM classes WHERE SUPPORTED = 1");
                            $stmt->execute();
                            $supported_classes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                            
                            // Get total users
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
                            $stmt->execute();
                            $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                            
                            // Get total tests
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tests");
                            $stmt->execute();
                            $total_tests = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                        ?>
                        
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary"><?php echo $total_classes; ?></h4>
                                <p class="text-muted mb-0">Total Classes</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-success"><?php echo $supported_classes; ?></h4>
                                <p class="text-muted mb-0">Active Classes</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-info"><?php echo $total_users; ?></h4>
                                <p class="text-muted mb-0">Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning"><?php echo $total_tests; ?></h4>
                            <p class="text-muted mb-0">Total Tests</p>
                        </div>
                        
                        <?php
                        } catch (PDOException $e) {
                            echo '<div class="col-12"><p class="text-danger">Error loading statistics: ' . $e->getMessage() . '</p></div>';
                        }
                        ?>
                    </div>
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

.btn {
    border-radius: 5px;
}

.text-xs {
    font-size: 0.7rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
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

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .border-end:last-child {
        border-bottom: none !important;
        margin-bottom: 0;
        padding-bottom: 0;
    }
}
</style>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$content = ob_get_clean();
include_once __DIR__.'/../master.php';
?>
