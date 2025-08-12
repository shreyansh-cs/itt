<?php
include_once __DIR__.'/../session.php';
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
                <div class="card-footer bg-light p-0">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified hierarchy-tabs" id="hierarchyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="creation-tab" data-bs-toggle="tab" data-bs-target="#creation" type="button" role="tab" aria-controls="creation" aria-selected="true" style="color: #0d6efd !important;">
                                <i class="fas fa-plus-circle me-1"></i>Class Hierarchy Creation
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button" role="tab" aria-controls="management" aria-selected="false" style="color: #495057 !important;">
                                <i class="fas fa-cogs me-1"></i>Class Hierarchy Management
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content" id="hierarchyTabContent">
                        <!-- Creation Tab -->
                        <div class="tab-pane fade show active" id="creation" role="tabpanel" aria-labelledby="creation-tab">
                            <div class="p-3">
                                <div class="d-grid gap-2">
                                    <a href="create_class.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>Create New Class
                                    </a>
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#streamModal">
                                        <i class="fas fa-stream me-1"></i>Create Stream
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#subjectModal">
                                        <i class="fas fa-book me-1"></i>Create Subject
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#sectionModal">
                                        <i class="fas fa-list me-1"></i>Create Section
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#chapterModal">
                                        <i class="fas fa-bookmark me-1"></i>Create Chapter
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Management Tab -->
                        <div class="tab-pane fade" id="management" role="tabpanel" aria-labelledby="management-tab">
                            <div class="p-3">
                                <div class="d-grid gap-2">
                                    <a href="manage_classes.php" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Manage Classes
                                    </a>
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#manageHierarchyModal">
                                        <i class="fas fa-tasks me-1"></i>Manage Streams
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#manageSubjectsModal">
                                        <i class="fas fa-books me-1"></i>Manage Subjects
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#manageSectionsModal">
                                        <i class="fas fa-layer-group me-1"></i>Manage Sections
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#manageChaptersModal">
                                        <i class="fas fa-folder-open me-1"></i>Manage Chapters
                                    </button>
                                </div>
                            </div>
                        </div>
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

/* Custom styling for hierarchy tabs */
.hierarchy-tabs {
    border-bottom: none !important;
    background-color: #f8f9fa !important;
}

.hierarchy-tabs .nav-link {
    color: #495057 !important;
    background-color: transparent !important;
    border: 1px solid transparent !important;
    border-bottom: none !important;
    font-weight: 500 !important;
    padding: 12px 16px !important;
    transition: all 0.3s ease !important;
}

.hierarchy-tabs .nav-link:hover {
    color: #0d6efd !important;
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
}

.hierarchy-tabs .nav-link.active {
    color: #0d6efd !important;
    background-color: #ffffff !important;
    border-color: #dee2e6 #dee2e6 #ffffff !important;
    border-bottom: 1px solid #ffffff !important;
    position: relative !important;
    z-index: 1 !important;
}

.hierarchy-tabs .nav-link.active::after {
    content: '' !important;
    position: absolute !important;
    bottom: -1px !important;
    left: 0 !important;
    right: 0 !important;
    height: 2px !important;
    background-color: #0d6efd !important;
}

#hierarchyTabs .nav-link {
    color: #495057 !important;
}

#hierarchyTabs .nav-link.active {
    color: #0d6efd !important;
}

#hierarchyTabs .nav-link:hover {
    color: #0d6efd !important;
}

.tab-content {
    background-color: #ffffff !important;
    border: 1px solid #dee2e6 !important;
    border-top: none !important;
}
</style>

<!-- Stream Creation Modal -->
<div class="modal fade" id="streamModal" tabindex="-1" aria-labelledby="streamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="streamModalLabel">
                    <i class="fas fa-stream me-2"></i>Create Stream
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="streamForm">
                <div class="modal-body">
                    <div id="streamMessages"></div>
                    
                    <div class="mb-3">
                        <label for="stream_class_select" class="form-label">Select Class</label>
                        <select class="form-select" id="stream_class_select" required>
                            <option value="">-- Select Class --</option>
                            <?php
                            try {
                                include '../../backend/db.php';
                                $stmt = $pdo->prepare("SELECT ID, NAME FROM classes ORDER BY NAME");
                                $stmt->execute();
                                $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($classes as $class):
                            ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php 
                                endforeach;
                            } catch (PDOException $e) {
                                echo '<option value="">Error loading classes</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stream_name_input" class="form-label">Stream Name</label>
                        <input type="text" class="form-control" id="stream_name_input" placeholder="e.g., Science, Commerce, Arts" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-plus me-1"></i>Create Stream
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Subject Creation Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="subjectModalLabel">
                    <i class="fas fa-book me-2"></i>Create Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="subjectForm">
                <div class="modal-body">
                    <div id="subjectMessages"></div>
                    
                    <div class="mb-3">
                        <label for="subject_class_select" class="form-label">Select Class</label>
                        <select class="form-select" id="subject_class_select" required>
                            <option value="">-- Select Class --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_stream_select" class="form-label">Select Stream</label>
                        <select class="form-select" id="subject_stream_select" required disabled>
                            <option value="">-- Select Stream --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_name_input" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name_input" placeholder="e.g., Physics, Mathematics, Chemistry" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Section Creation Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="sectionModalLabel">
                    <i class="fas fa-list me-2"></i>Create Section
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sectionForm">
                <div class="modal-body">
                    <div id="sectionMessages"></div>
                    
                    <div class="mb-3">
                        <label for="section_class_select" class="form-label">Select Class</label>
                        <select class="form-select" id="section_class_select" required>
                            <option value="">-- Select Class --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="section_stream_select" class="form-label">Select Stream</label>
                        <select class="form-select" id="section_stream_select" required disabled>
                            <option value="">-- Select Stream --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="section_subject_select" class="form-label">Select Subject</label>
                        <select class="form-select" id="section_subject_select" required disabled>
                            <option value="">-- Select Subject --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="section_name_input" class="form-label">Section Name</label>
                        <input type="text" class="form-control" id="section_name_input" placeholder="e.g., Mechanics, Algebra, Organic Chemistry" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-plus me-1"></i>Create Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Chapter Creation Modal -->
<div class="modal fade" id="chapterModal" tabindex="-1" aria-labelledby="chapterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="chapterModalLabel">
                    <i class="fas fa-bookmark me-2"></i>Create Chapter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="chapterForm">
                <div class="modal-body">
                    <div id="chapterMessages"></div>
                    
                    <div class="mb-3">
                        <label for="chapter_class_select" class="form-label">Select Class</label>
                        <select class="form-select" id="chapter_class_select" required>
                            <option value="">-- Select Class --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="chapter_stream_select" class="form-label">Select Stream</label>
                        <select class="form-select" id="chapter_stream_select" required disabled>
                            <option value="">-- Select Stream --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="chapter_subject_select" class="form-label">Select Subject</label>
                        <select class="form-select" id="chapter_subject_select" required disabled>
                            <option value="">-- Select Subject --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="chapter_section_select" class="form-label">Select Section</label>
                        <select class="form-select" id="chapter_section_select" required disabled>
                            <option value="">-- Select Section --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="chapter_name_input" class="form-label">Chapter Name</label>
                        <input type="text" class="form-control" id="chapter_name_input" placeholder="e.g., Newton's Laws, Linear Equations, Alkanes" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-plus me-1"></i>Create Chapter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Management Modals -->

<!-- Manage Streams Modal -->
<div class="modal fade" id="manageHierarchyModal" tabindex="-1" aria-labelledby="manageHierarchyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="manageHierarchyModalLabel">
                    <i class="fas fa-tasks me-2"></i>Manage Streams
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="streamsManageMessages"></div>
                
                <div class="mb-3">
                    <label for="manage_streams_class_select" class="form-label">Select Class to View Streams</label>
                    <select class="form-select" id="manage_streams_class_select">
                        <option value="">-- Select Class --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div id="streamsList" class="mt-3">
                    <p class="text-muted">Select a class to view its streams</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Subjects Modal -->
<div class="modal fade" id="manageSubjectsModal" tabindex="-1" aria-labelledby="manageSubjectsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="manageSubjectsModalLabel">
                    <i class="fas fa-books me-2"></i>Manage Subjects
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="subjectsManageMessages"></div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="manage_subjects_class_select" class="form-label">Select Class</label>
                        <select class="form-select" id="manage_subjects_class_select">
                            <option value="">-- Select Class --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="manage_subjects_stream_select" class="form-label">Select Stream</label>
                        <select class="form-select" id="manage_subjects_stream_select" disabled>
                            <option value="">-- Select Stream --</option>
                        </select>
                    </div>
                </div>
                
                <div id="subjectsList" class="mt-3">
                    <p class="text-muted">Select class and stream to view subjects</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Sections Modal -->
<div class="modal fade" id="manageSectionsModal" tabindex="-1" aria-labelledby="manageSectionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="manageSectionsModalLabel">
                    <i class="fas fa-layer-group me-2"></i>Manage Sections
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="sectionsManageMessages"></div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="manage_sections_class_select" class="form-label">Select Class</label>
                        <select class="form-select" id="manage_sections_class_select">
                            <option value="">-- Select Class --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="manage_sections_stream_select" class="form-label">Select Stream</label>
                        <select class="form-select" id="manage_sections_stream_select" disabled>
                            <option value="">-- Select Stream --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="manage_sections_subject_select" class="form-label">Select Subject</label>
                        <select class="form-select" id="manage_sections_subject_select" disabled>
                            <option value="">-- Select Subject --</option>
                        </select>
                    </div>
                </div>
                
                <div id="sectionsList" class="mt-3">
                    <p class="text-muted">Select class, stream, and subject to view sections</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Chapters Modal -->
<div class="modal fade" id="manageChaptersModal" tabindex="-1" aria-labelledby="manageChaptersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="manageChaptersModalLabel">
                    <i class="fas fa-folder-open me-2"></i>Manage Chapters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="chaptersManageMessages"></div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="manage_chapters_class_select" class="form-label">Class</label>
                        <select class="form-select" id="manage_chapters_class_select">
                            <option value="">-- Select Class --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="manage_chapters_stream_select" class="form-label">Stream</label>
                        <select class="form-select" id="manage_chapters_stream_select" disabled>
                            <option value="">-- Select Stream --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="manage_chapters_subject_select" class="form-label">Subject</label>
                        <select class="form-select" id="manage_chapters_subject_select" disabled>
                            <option value="">-- Select Subject --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="manage_chapters_section_select" class="form-label">Section</label>
                        <select class="form-select" id="manage_chapters_section_select" disabled>
                            <option value="">-- Select Section --</option>
                        </select>
                    </div>
                </div>
                
                <div id="chaptersList" class="mt-3">
                    <p class="text-muted">Select class, stream, subject, and section to view chapters</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="hierarchyLoadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Processing...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Global functions that need to be accessible from onclick handlers
function deleteItem(event, type, id, name) {
    // Special handling for streams with cascade delete warning
    let confirmMessage = `Are you sure you want to delete the ${type} "${name}"?`;
    
    // Hierarchical deletion warnings
    if (type === 'stream') {
        confirmMessage = `⚠️ WARNING: Deleting stream "${name}" will also delete:\n\n` +
                       `• All subjects in this stream\n` +
                       `• All sections in those subjects\n` +
                       `• All chapters in those sections\n` +
                       `• All notes and videos in those chapters\n` +
                       `• Test-chapter mappings (tests themselves will be preserved)\n\n` +
                       `ℹ️ Tests, questions, and user data will NOT be deleted\n` +
                       `Tests will remain available and can be remapped to other chapters\n\n` +
                       `This action CANNOT be undone!\n\n` +
                       `Are you absolutely sure you want to delete stream "${name}"?`;
    } else if (type === 'subject') {
        confirmMessage = `⚠️ WARNING: Deleting subject "${name}" will also delete:\n\n` +
                       `• All sections in this subject\n` +
                       `• All chapters in those sections\n` +
                       `• All notes and videos in those chapters\n` +
                       `• Test-chapter mappings (tests themselves will be preserved)\n\n` +
                       `ℹ️ Tests, questions, and user data will NOT be deleted\n` +
                       `Tests will remain available and can be remapped to other chapters\n\n` +
                       `This action CANNOT be undone!\n\n` +
                       `Are you absolutely sure you want to delete subject "${name}"?`;
    } else if (type === 'section') {
        confirmMessage = `⚠️ WARNING: Deleting section "${name}" will also delete:\n\n` +
                       `• All chapters in this section\n` +
                       `• All notes and videos in those chapters\n` +
                       `• Test-chapter mappings (tests themselves will be preserved)\n\n` +
                       `ℹ️ Tests, questions, and user data will NOT be deleted\n` +
                       `Tests will remain available and can be remapped to other chapters\n\n` +
                       `This action CANNOT be undone!\n\n` +
                       `Are you absolutely sure you want to delete section "${name}"?`;
    } else if (type === 'chapter') {
        confirmMessage = `⚠️ WARNING: Deleting chapter "${name}" will also delete:\n\n` +
                       `• All notes and videos in this chapter\n` +
                       `• Test-chapter mappings for this chapter\n\n` +
                       `ℹ️ Tests, questions, and user data will NOT be deleted\n` +
                       `Tests will remain available and can be remapped to other chapters\n\n` +
                       `This action CANNOT be undone!\n\n` +
                       `Are you absolutely sure you want to delete chapter "${name}"?`;
    }
    
    if (confirm(confirmMessage)) {
        // Show loading state
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        button.disabled = true;
        
        // Prepare form data
        const formData = new FormData();
        formData.append('action', `delete_${type}`);
        formData.append(`${type}_id`, id);
        
        // Send delete request
        fetch('../../backend/manage_hierarchy.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Determine the appropriate message container
                const messageContainers = {
                    'stream': 'streamsManageMessages',
                    'subject': 'subjectsManageMessages', 
                    'section': 'sectionsManageMessages',
                    'chapter': 'chaptersManageMessages'
                };
                
                const messageContainer = messageContainers[type] || 'streamsManageMessages';
                
                // Show success message
                showMessage(messageContainer, data.message, 'success');
                
                // Refresh the appropriate list
                if (type === 'stream') {
                    const classId = document.getElementById('manage_streams_class_select').value;
                    if (classId) {
                        loadManageStreams(classId);
                    }
                } else if (type === 'subject') {
                    const classId = document.getElementById('manage_subjects_class_select').value;
                    const streamId = document.getElementById('manage_subjects_stream_select').value;
                    if (classId && streamId) {
                        loadManageSubjects(classId, streamId);
                    }
                } else if (type === 'section') {
                    const classId = document.getElementById('manage_sections_class_select').value;
                    const streamId = document.getElementById('manage_sections_stream_select').value;
                    const subjectId = document.getElementById('manage_sections_subject_select').value;
                    if (classId && streamId && subjectId) {
                        loadManageSections(classId, streamId, subjectId);
                    }
                } else if (type === 'chapter') {
                    const classId = document.getElementById('manage_chapters_class_select').value;
                    const streamId = document.getElementById('manage_chapters_stream_select').value;
                    const subjectId = document.getElementById('manage_chapters_subject_select').value;
                    const sectionId = document.getElementById('manage_chapters_section_select').value;
                    if (classId && streamId && subjectId && sectionId) {
                        loadManageChapters(classId, streamId, subjectId, sectionId);
                    }
                }
            } else {
                const messageContainers = {
                    'stream': 'streamsManageMessages',
                    'subject': 'subjectsManageMessages', 
                    'section': 'sectionsManageMessages',
                    'chapter': 'chaptersManageMessages'
                };
                
                const messageContainer = messageContainers[type] || 'streamsManageMessages';
                showMessage(messageContainer, data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            const messageContainers = {
                'stream': 'streamsManageMessages',
                'subject': 'subjectsManageMessages', 
                'section': 'sectionsManageMessages',
                'chapter': 'chaptersManageMessages'
            };
            
            const messageContainer = messageContainers[type] || 'streamsManageMessages';
            showMessage(messageContainer, 'Error deleting ' + type + ': ' + error.message, 'danger');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Global utility functions
function showMessage(containerId, message, type) {
    const container = document.getElementById(containerId);
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    container.innerHTML = '';
    container.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Global hierarchy loading functions
let globalLoadingModal = null;
const SHOW_LOADING_MODAL = false; // Set to false to disable loading modal

function showHierarchyLoading() {
    if (!SHOW_LOADING_MODAL) {
        console.log('Loading modal disabled');
        return;
    }
    
    console.log('showHierarchyLoading called');
    const modalElement = document.getElementById('hierarchyLoadingModal');
    console.log('modalElement found:', !!modalElement);
    
    if (modalElement && typeof bootstrap !== 'undefined') {
        // Get existing instance or create new one
        globalLoadingModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        globalLoadingModal.show();
        console.log('Loading modal shown');
    } else {
        console.log('Bootstrap not available or modal element not found');
    }
}

function hideHierarchyLoading() {
    if (!SHOW_LOADING_MODAL) {
        console.log('Loading modal disabled - nothing to hide');
        return;
    }
    
    console.log('hideHierarchyLoading called');
    
    // Always force hide to ensure it disappears
    const modalElement = document.getElementById('hierarchyLoadingModal');
    if (modalElement) {
        // Try Bootstrap method first
        if (globalLoadingModal) {
            try {
                globalLoadingModal.hide();
                console.log('Loading modal hidden via globalLoadingModal');
            } catch (e) {
                console.log('Error hiding via globalLoadingModal:', e);
            }
        }
        
        // Also try getInstance method
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            try {
                modalInstance.hide();
                console.log('Loading modal hidden via getInstance');
            } catch (e) {
                console.log('Error hiding via getInstance:', e);
            }
        }
        
        // Force hide with timeout to ensure it works
        setTimeout(() => {
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Remove all backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            console.log('Loading modal force hidden with cleanup');
        }, 100);
        
    } else {
        console.log('Modal element not found');
    }
}

// Global management functions
function displayManageList(containerId, items, type, typeName) {
    const container = document.getElementById(containerId);
    if (items.length === 0) {
        container.innerHTML = `<div class="alert alert-info">No ${type}s found</div>`;
        return;
    }
    
    let html = `<div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>${typeName} Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>`;
    
    items.forEach(item => {
        html += `
            <tr>
                <td>${item.NAME}</td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(event, '${type}', ${item.ID}, '${item.NAME}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>`;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function loadManageStreams(classId) {
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_streams&class_id=${classId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayManageList('streamsList', data.data, 'stream', 'Stream');
            } else {
                document.getElementById('streamsList').innerHTML = '<div class="alert alert-warning">No streams found for this class</div>';
            }
        })
        .catch(error => {
            document.getElementById('streamsList').innerHTML = '<div class="alert alert-danger">Error loading streams</div>';
        })
        .finally(() => hideHierarchyLoading());
}

function loadManageSubjects(classId, streamId) {
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_subjects&class_id=${classId}&stream_id=${streamId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayManageList('subjectsList', data.data, 'subject', 'Subject');
            } else {
                document.getElementById('subjectsList').innerHTML = '<div class="alert alert-warning">No subjects found for this stream</div>';
            }
        })
        .catch(error => {
            document.getElementById('subjectsList').innerHTML = '<div class="alert alert-danger">Error loading subjects</div>';
        })
        .finally(() => hideHierarchyLoading());
}

function loadManageSections(classId, streamId, subjectId) {
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_sections&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayManageList('sectionsList', data.data, 'section', 'Section');
            } else {
                document.getElementById('sectionsList').innerHTML = '<div class="alert alert-warning">No sections found for this subject</div>';
            }
        })
        .catch(error => {
            document.getElementById('sectionsList').innerHTML = '<div class="alert alert-danger">Error loading sections</div>';
        })
        .finally(() => hideHierarchyLoading());
}

function loadManageChapters(classId, streamId, subjectId, sectionId) {
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_chapters&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}&section_id=${sectionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayManageList('chaptersList', data.data, 'chapter', 'Chapter');
            } else {
                document.getElementById('chaptersList').innerHTML = '<div class="alert alert-warning">No chapters found for this section</div>';
            }
        })
        .catch(error => {
            document.getElementById('chaptersList').innerHTML = '<div class="alert alert-danger">Error loading chapters</div>';
        })
        .finally(() => hideHierarchyLoading());
}

function resetManageDisplay(containerId, message) {
    document.getElementById(containerId).innerHTML = `<p class="text-muted">${message}</p>`;
}

// Global dropdown management functions
function loadStreams(classId, targetSelectId) {
    console.log('loadStreams called with:', classId, targetSelectId);
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_streams&class_id=${classId}`)
        .then(response => {
            console.log('loadStreams response received');
            return response.json();
        })
        .then(data => {
            console.log('loadStreams data:', data);
            if (data.success) {
                populateDropdown(targetSelectId, data.data, 'ID', 'NAME');
                document.getElementById(targetSelectId).disabled = false;
                console.log('Dropdown populated successfully');
            } else {
                console.log('loadStreams failed:', data);
            }
        })
        .catch(error => {
            console.error('Error loading streams:', error);
        })
        .finally(() => {
            console.log('loadStreams completed');
            hideHierarchyLoading();
        });
}

function loadSubjects(classId, streamId, targetSelectId) {
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_subjects&class_id=${classId}&stream_id=${streamId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateDropdown(targetSelectId, data.data, 'ID', 'NAME');
                document.getElementById(targetSelectId).disabled = false;
            }
        })
        .catch(error => console.error('Error loading subjects:', error))
        .finally(() => hideHierarchyLoading());
}

function loadSections(classId, streamId, subjectId, targetSelectId) {
    showHierarchyLoading();
    fetch(`../../backend/get_hierarchy_data.php?action=get_sections&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateDropdown(targetSelectId, data.data, 'ID', 'NAME');
                document.getElementById(targetSelectId).disabled = false;
            }
        })
        .catch(error => console.error('Error loading sections:', error))
        .finally(() => hideHierarchyLoading());
}

function populateDropdown(selectId, data, valueField, textField) {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">-- Select --</option>';
    
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item[valueField];
        option.textContent = item[textField];
        select.appendChild(option);
    });
}

function resetDropdown(selectId) {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">-- Select --</option>';
    select.disabled = true;
}

function createHierarchyItem(type, data, modalId, messagesId) {
    console.log('createHierarchyItem called with:', {type, data, modalId, messagesId});
    showHierarchyLoading();
    
    const formData = new FormData();
    formData.append('action', 'create_' + type);
    for (const key in data) {
        console.log('Appending to FormData:', key, '=', data[key]);
        formData.append(key, data[key]);
    }
    
    fetch('../../backend/manage_hierarchy.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showMessage(messagesId, result.message, 'success');
            document.getElementById(type + 'Form').reset();
            resetFormDropdowns(modalId);
            // Close modal after a short delay
            setTimeout(() => {
                const modalElement = document.getElementById(modalId);
                if (modalElement && typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }, 1500);
        } else {
            showMessage(messagesId, result.error, 'danger');
        }
    })
    .catch(error => {
        showMessage(messagesId, 'Error: ' + error.message, 'danger');
    })
    .finally(() => hideHierarchyLoading());
}

function resetFormDropdowns(modalId) {
    const modal = document.getElementById(modalId);
    const selects = modal.querySelectorAll('select');
    selects.forEach((select, index) => {
        if (index > 0) { // Skip the first select (class)
            resetDropdown(select.id);
        }
        });
}

function setupClassChangeHandler(classSelectId, streamSelectId, subjectSelectId, sectionSelectId) {
    console.log('Setting up class change handler for:', classSelectId);
    const classSelect = document.getElementById(classSelectId);
    if (!classSelect) {
        console.error('Class select element not found:', classSelectId);
        return;
    }
    
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        console.log('Class changed:', classId, 'for', classSelectId);
        
        if (classId) {
            loadStreams(classId, streamSelectId);
        } else {
            resetDropdown(streamSelectId);
            if (subjectSelectId) resetDropdown(subjectSelectId);
            if (sectionSelectId) resetDropdown(sectionSelectId);
        }
    });
    
    if (streamSelectId && subjectSelectId) {
        document.getElementById(streamSelectId).addEventListener('change', function() {
            const streamId = this.value;
            const classId = document.getElementById(classSelectId).value;
            
            if (streamId && classId) {
                loadSubjects(classId, streamId, subjectSelectId);
                if (sectionSelectId) resetDropdown(sectionSelectId);
            } else {
                resetDropdown(subjectSelectId);
                if (sectionSelectId) resetDropdown(sectionSelectId);
            }
        });
    }
    
    if (subjectSelectId && sectionSelectId) {
        document.getElementById(subjectSelectId).addEventListener('change', function() {
            const subjectId = this.value;
            const streamId = document.getElementById(streamSelectId).value;
            const classId = document.getElementById(classSelectId).value;
            
            if (subjectId && streamId && classId) {
                loadSections(classId, streamId, subjectId, sectionSelectId);
            } else {
                resetDropdown(sectionSelectId);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    let hierarchyLoadingModal = null;
    
    // Initialize class change handlers for creation modals
    setupClassChangeHandler('subject_class_select', 'subject_stream_select', null, null);
    setupClassChangeHandler('section_class_select', 'section_stream_select', 'section_subject_select', null);
    setupClassChangeHandler('chapter_class_select', 'chapter_stream_select', 'chapter_subject_select', 'chapter_section_select');
    
    // Initialize management modals handlers
    setupManagementHandlers();
    
    // Fix tab colors
    setupTabColors();
    
    // Form submission handlers
    document.getElementById('streamForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const classId = document.getElementById('stream_class_select').value;
        const streamName = document.getElementById('stream_name_input').value.trim();
        
        console.log('Form submission - classId:', classId, 'streamName:', streamName);
        
        if (!classId || classId === '') {
            showMessage('streamMessages', 'Please select a class', 'danger');
            return;
        }
        
        if (!streamName || streamName === '') {
            showMessage('streamMessages', 'Please enter a stream name', 'danger');
            return;
        }
        
        createHierarchyItem('stream', {class_id: classId, stream_name: streamName}, 'streamModal', 'streamMessages');
    });
    
    document.getElementById('subjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const streamId = document.getElementById('subject_stream_select').value;
        const subjectName = document.getElementById('subject_name_input').value.trim();
        
        console.log('Subject form submission - streamId:', streamId, 'subjectName:', subjectName);
        
        if (!streamId || streamId === '') {
            showMessage('subjectMessages', 'Please select a stream', 'danger');
            return;
        }
        
        if (!subjectName || subjectName === '') {
            showMessage('subjectMessages', 'Please enter a subject name', 'danger');
            return;
        }
        
        createHierarchyItem('subject', {stream_id: streamId, subject_name: subjectName}, 'subjectModal', 'subjectMessages');
    });
    
    document.getElementById('sectionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const subjectId = document.getElementById('section_subject_select').value;
        const sectionName = document.getElementById('section_name_input').value.trim();
        
        if (subjectId && sectionName) {
            createHierarchyItem('section', {subject_id: subjectId, section_name: sectionName}, 'sectionModal', 'sectionMessages');
        }
    });
    
    document.getElementById('chapterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const sectionId = document.getElementById('chapter_section_select').value;
        const chapterName = document.getElementById('chapter_name_input').value.trim();
        
        if (sectionId && chapterName) {
            createHierarchyItem('chapter', {section_id: sectionId, chapter_name: chapterName}, 'chapterModal', 'chapterMessages');
        }
    });
    
    // Removed broken function remnants
    /*
    if (!classSelect) {
        console.error('Class select element not found:', classSelectId);
        return;
    }
    
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        console.log('Class changed:', classId, 'for', classSelectId);
        const streamSelect = document.getElementById(streamSelectId);
        
        if (classId) {
            loadStreams(classId, streamSelectId);
        } else {
            resetDropdown(streamSelectId);
            if (subjectSelectId) resetDropdown(subjectSelectId);
            if (sectionSelectId) resetDropdown(sectionSelectId);
        }
    });
        
        if (streamSelectId && subjectSelectId) {
            document.getElementById(streamSelectId).addEventListener('change', function() {
                const streamId = this.value;
                const classId = document.getElementById(classSelectId).value;
                
                if (streamId && classId) {
                    loadSubjects(classId, streamId, subjectSelectId);
                } else {
                    resetDropdown(subjectSelectId);
                    if (sectionSelectId) resetDropdown(sectionSelectId);
                }
            });
        }
        
        if (subjectSelectId && sectionSelectId) {
            document.getElementById(subjectSelectId).addEventListener('change', function() {
                const subjectId = this.value;
                const streamId = document.getElementById(streamSelectId).value;
                const classId = document.getElementById(classSelectId).value;
                
                if (subjectId && streamId && classId) {
                    loadSections(classId, streamId, subjectId, sectionSelectId);
                } else {
                    resetDropdown(sectionSelectId);
                }
            });
        }
    }
    */
    
    function setupManagementHandlers() {
        // Manage Streams
        document.getElementById('manage_streams_class_select').addEventListener('change', function() {
            const classId = this.value;
            if (classId) {
                loadManageStreams(classId);
            } else {
                document.getElementById('streamsList').innerHTML = '<p class="text-muted">Select a class to view its streams</p>';
            }
        });
        
        // Manage Subjects
        document.getElementById('manage_subjects_class_select').addEventListener('change', function() {
            const classId = this.value;
            if (classId) {
                loadStreams(classId, 'manage_subjects_stream_select');
                resetManageDisplay('subjectsList', 'Select class and stream to view subjects');
            } else {
                resetDropdown('manage_subjects_stream_select');
                resetManageDisplay('subjectsList', 'Select class and stream to view subjects');
            }
        });
        
        document.getElementById('manage_subjects_stream_select').addEventListener('change', function() {
            const streamId = this.value;
            const classId = document.getElementById('manage_subjects_class_select').value;
            if (streamId && classId) {
                loadManageSubjects(classId, streamId);
            } else {
                resetManageDisplay('subjectsList', 'Select class and stream to view subjects');
            }
        });
        
        // Manage Sections
        document.getElementById('manage_sections_class_select').addEventListener('change', function() {
            const classId = this.value;
            if (classId) {
                loadStreams(classId, 'manage_sections_stream_select');
                resetDropdown('manage_sections_subject_select');
                resetManageDisplay('sectionsList', 'Select class, stream, and subject to view sections');
            } else {
                resetDropdown('manage_sections_stream_select');
                resetDropdown('manage_sections_subject_select');
                resetManageDisplay('sectionsList', 'Select class, stream, and subject to view sections');
            }
        });
        
        document.getElementById('manage_sections_stream_select').addEventListener('change', function() {
            const streamId = this.value;
            const classId = document.getElementById('manage_sections_class_select').value;
            if (streamId && classId) {
                loadSubjects(classId, streamId, 'manage_sections_subject_select');
                resetManageDisplay('sectionsList', 'Select class, stream, and subject to view sections');
            } else {
                resetDropdown('manage_sections_subject_select');
                resetManageDisplay('sectionsList', 'Select class, stream, and subject to view sections');
            }
        });
        
        document.getElementById('manage_sections_subject_select').addEventListener('change', function() {
            const subjectId = this.value;
            const streamId = document.getElementById('manage_sections_stream_select').value;
            const classId = document.getElementById('manage_sections_class_select').value;
            if (subjectId && streamId && classId) {
                loadManageSections(classId, streamId, subjectId);
            } else {
                resetManageDisplay('sectionsList', 'Select class, stream, and subject to view sections');
            }
        });
        
        // Manage Chapters
        document.getElementById('manage_chapters_class_select').addEventListener('change', function() {
            const classId = this.value;
            if (classId) {
                loadStreams(classId, 'manage_chapters_stream_select');
                resetDropdown('manage_chapters_subject_select');
                resetDropdown('manage_chapters_section_select');
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            } else {
                resetDropdown('manage_chapters_stream_select');
                resetDropdown('manage_chapters_subject_select');
                resetDropdown('manage_chapters_section_select');
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            }
        });
        
        document.getElementById('manage_chapters_stream_select').addEventListener('change', function() {
            const streamId = this.value;
            const classId = document.getElementById('manage_chapters_class_select').value;
            if (streamId && classId) {
                loadSubjects(classId, streamId, 'manage_chapters_subject_select');
                resetDropdown('manage_chapters_section_select');
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            } else {
                resetDropdown('manage_chapters_subject_select');
                resetDropdown('manage_chapters_section_select');
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            }
        });
        
        document.getElementById('manage_chapters_subject_select').addEventListener('change', function() {
            const subjectId = this.value;
            const streamId = document.getElementById('manage_chapters_stream_select').value;
            const classId = document.getElementById('manage_chapters_class_select').value;
            if (subjectId && streamId && classId) {
                loadSections(classId, streamId, subjectId, 'manage_chapters_section_select');
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            } else {
                resetDropdown('manage_chapters_section_select');
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            }
        });
        
        document.getElementById('manage_chapters_section_select').addEventListener('change', function() {
            const sectionId = this.value;
            const subjectId = document.getElementById('manage_chapters_subject_select').value;
            const streamId = document.getElementById('manage_chapters_stream_select').value;
            const classId = document.getElementById('manage_chapters_class_select').value;
            if (sectionId && subjectId && streamId && classId) {
                loadManageChapters(classId, streamId, subjectId, sectionId);
            } else {
                resetManageDisplay('chaptersList', 'Select class, stream, subject, and section to view chapters');
            }
        });
    }
    
    function setupTabColors() {
        // Set up tab color handling
        const creationTab = document.getElementById('creation-tab');
        const managementTab = document.getElementById('management-tab');
        
        // Initial colors
        creationTab.style.setProperty('color', '#0d6efd', 'important');
        managementTab.style.setProperty('color', '#495057', 'important');
        
        // Tab click handlers
        creationTab.addEventListener('click', function() {
            this.style.setProperty('color', '#0d6efd', 'important');
            managementTab.style.setProperty('color', '#495057', 'important');
        });
        
        managementTab.addEventListener('click', function() {
            this.style.setProperty('color', '#0d6efd', 'important');
            creationTab.style.setProperty('color', '#495057', 'important');
        });
        
        // Bootstrap tab events
        document.addEventListener('shown.bs.tab', function (e) {
            const allTabs = document.querySelectorAll('#hierarchyTabs .nav-link');
            allTabs.forEach(tab => {
                if (tab.classList.contains('active')) {
                    tab.style.setProperty('color', '#0d6efd', 'important');
                } else {
                    tab.style.setProperty('color', '#495057', 'important');
                }
            });
        });
    }
});
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$content = ob_get_clean();
include_once __DIR__.'/../master.php';
?>
