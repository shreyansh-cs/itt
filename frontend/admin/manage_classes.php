<?php
session_start();
include_once __DIR__.'/../../backend/utils.php';
include_once __DIR__.'/../../backend/public_utils.php';

include_once __DIR__.'/../../frontend/restrictedpage.php';

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

$title = "Manage Classes - Admin Panel";
$msg = "";
$success = false;

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    include_once __DIR__.'/../../backend/db.php';
    
    if ($_POST['action'] === 'toggle_support') {
        $class_id = intval($_POST['class_id']);
        $new_status = intval($_POST['new_status']);
        
        try {
            $stmt = $pdo->prepare("UPDATE classes SET SUPPORTED = ? WHERE ID = ?");
            $result = $stmt->execute([$new_status, $class_id]);
            
            if ($result) {
                $status_text = $new_status ? 'enabled' : 'disabled';
                $msg = "Class status successfully $status_text.";
                $success = true;
            } else {
                $msg = "Failed to update class status.";
            }
        } catch (PDOException $e) {
            $msg = "Database error: " . $e->getMessage();
        }
    }
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
                    <li class="breadcrumb-item active" aria-current="page">Manage Classes</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Manage Classes
                    </h4>
                    <a href="create_class.php" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New Class
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                            <?php echo $msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Class Name</th>
                                    <th>Package</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    include_once __DIR__.'/../../backend/db.php';
                                    
                                    $stmt = $pdo->prepare("SELECT c.*, p.NAME as PACKAGE_NAME, p.PRICE as PACKAGE_PRICE FROM classes c LEFT JOIN packages p ON c.PACKAGE_ID = p.ID ORDER BY c.ID ASC");
                                    $stmt->execute();
                                    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if (count($classes) > 0) {
                                        foreach ($classes as $class) {
                                                                                    $status_badge = $class['SUPPORTED'] ? 
                                            '<span class="badge bg-success">Active</span>' : 
                                            '<span class="badge bg-secondary">Inactive</span>';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $class['ID'] . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($class['NAME']) . "</strong></td>";
                                        
                                        // Display package name with price
                                        $package_display = $class['PACKAGE_NAME'] ? 
                                            htmlspecialchars($class['PACKAGE_NAME']) . " (₹" . number_format($class['PACKAGE_PRICE'], 2) . ")" : 
                                            'Package ID: ' . $class['PACKAGE_ID'];
                                        echo "<td>" . $package_display . "</td>";
                                        
                                        echo "<td>" . $status_badge . "</td>";
                                        echo "<td>";
                                            
                                            // Toggle status button
                                            if ($class['SUPPORTED']) {
                                                echo '<form method="POST" style="display: inline;" onsubmit="return confirm(\'Are you sure you want to deactivate this class?\');">';
                                                echo '<input type="hidden" name="action" value="toggle_support">';
                                                echo '<input type="hidden" name="class_id" value="' . $class['ID'] . '">';
                                                echo '<input type="hidden" name="new_status" value="0">';
                                                echo '<button type="submit" class="btn btn-outline-warning btn-sm me-1">';
                                                echo '<i class="fas fa-pause me-1"></i>Deactivate';
                                                echo '</button>';
                                                echo '</form>';
                                            } else {
                                                echo '<form method="POST" style="display: inline;" onsubmit="return confirm(\'Are you sure you want to activate this class?\');">';
                                                echo '<input type="hidden" name="action" value="toggle_support">';
                                                echo '<input type="hidden" name="class_id" value="' . $class['ID'] . '">';
                                                echo '<input type="hidden" name="new_status" value="1">';
                                                echo '<button type="submit" class="btn btn-outline-success btn-sm me-1">';
                                                echo '<i class="fas fa-play me-1"></i>Activate';
                                                echo '</button>';
                                                echo '</form>';
                                            }
                                            
                                            // Edit button (placeholder)
                                            echo '<a href="edit_class.php?id=' . $class['ID'] . '" class="btn btn-outline-primary btn-sm">';
                                            echo '<i class="fas fa-edit me-1"></i>Edit';
                                            echo '</a>';
                                            
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">No classes found. <a href="create_class.php">Create the first class</a>.</td></tr>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="5" class="text-center text-danger">Error loading classes: ' . $e->getMessage() . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>
                                <?php 
                                try {
                                    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM classes");
                                    $stmt->execute();
                                    echo $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                } catch (PDOException $e) {
                                    echo "N/A";
                                }
                                ?>
                            </h4>
                            <p class="mb-0">Total Classes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>
                                <?php 
                                try {
                                    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM classes WHERE SUPPORTED = 1");
                                    $stmt->execute();
                                    echo $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                } catch (PDOException $e) {
                                    echo "N/A";
                                }
                                ?>
                            </h4>
                            <p class="mb-0">Active Classes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>
                                <?php 
                                try {
                                    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM classes WHERE SUPPORTED = 0");
                                    $stmt->execute();
                                    echo $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                } catch (PDOException $e) {
                                    echo "N/A";
                                }
                                ?>
                            </h4>
                            <p class="mb-0">Inactive Classes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-pause-circle fa-2x"></i>
                        </div>
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

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
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

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}
</style>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$content = ob_get_clean();
include_once __DIR__.'/../master.php';
?>
