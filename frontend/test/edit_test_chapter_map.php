<?php 
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "Manage Test-Chapter Mappings";
?>

<?php
require_once __DIR__.'/../../backend/db.php';

// Handle delete request
$message = '';
$messageType = 'info';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    try {
        $deleteStmt = $pdo->prepare("DELETE FROM test_chapters_map WHERE id = :id");
        $deleteStmt->execute([':id' => $delete_id]);

        if ($deleteStmt->rowCount() > 0) {
            $message = "✅ Assignment deleted successfully!";
            $messageType = 'success';
        } else {
            $message = "❌ Failed to delete assignment.";
            $messageType = 'danger';
        }
    } catch (PDOException $e) {
        $message = "❌ Database error: " . $e->getMessage();
        $messageType = 'danger';
    }
}

// Fetch all test-chapter mappings
$stmt = $pdo->query("
    SELECT tcm.id, t.test_id, t.title AS test_title, 
           c.ID as chapter_id, c.NAME as chapter_name,
           s.NAME as section_name,
           sub.NAME as subject_name,
           str.NAME as stream_name,
           cl.NAME as class_name,
           tcm.created_at,
           (SELECT COUNT(*) FROM questions WHERE test_id = t.test_id) as question_count
    FROM test_chapters_map tcm
    JOIN tests t ON t.test_id = tcm.test_id
    JOIN chapters c ON c.ID = tcm.chapter_id
    JOIN sections s ON c.SECTION_ID = s.ID
    JOIN subjects sub ON s.SUBJECT_ID = sub.ID
    JOIN streamubjectmap som ON sub.ID = som.SUBJECT_ID
    JOIN streams str ON som.STREAM_ID = str.ID
    JOIN classes cl ON str.CLASS_ID = cl.ID
    ORDER BY cl.NAME, str.NAME, sub.NAME, s.NAME, c.NAME, t.title
");
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group assignments by class/stream/subject for better organization
$groupedAssignments = [];
foreach ($assignments as $assignment) {
    $key = $assignment['class_name'] . ' → ' . $assignment['stream_name'] . ' → ' . $assignment['subject_name'];
    $groupedAssignments[$key][] = $assignment;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="../admin/index.php">Admin Panel</a></li>
                    <li class="breadcrumb-item"><a href="list_tests.php">Tests</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Mappings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Manage Test-Chapter Mappings
                    </h4>
                    <div>
                        <a href="map_test_to_chapter.php" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New Mapping
                        </a>
                        <a href="list_tests.php" class="btn btn-outline-light btn-sm ms-2">
                            <i class="fas fa-arrow-left me-1"></i>Back to Tests
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($assignments)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>No Test-Chapter Mappings Found</h5>
                            <p>You haven't created any test-chapter mappings yet.</p>
                            <a href="map_test_to_chapter.php" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create First Mapping
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-muted mb-0">
                                        Total Mappings: <span class="badge bg-primary"><?= count($assignments) ?></span>
                                    </h6>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Search mappings...">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php foreach ($groupedAssignments as $groupName => $groupAssignments): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-folder-open me-2"></i>
                                        <?= htmlspecialchars($groupName) ?>
                                        <span class="badge bg-secondary ms-2"><?= count($groupAssignments) ?></span>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Test</th>
                                                    <th>Section → Chapter</th>
                                                    <th>Questions</th>
                                                    <th>Created</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($groupAssignments as $assignment): ?>
                                                    <tr class="mapping-row">
                                                        <td>
                                                            <strong><?= htmlspecialchars($assignment['test_title']) ?></strong>
                                                            <br>
                                                            <small class="text-muted">ID: <?= $assignment['test_id'] ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted"><?= htmlspecialchars($assignment['section_name']) ?></span>
                                                            →
                                                            <strong><?= htmlspecialchars($assignment['chapter_name']) ?></strong>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?= $assignment['question_count'] > 0 ? 'success' : 'warning' ?>">
                                                                <?= $assignment['question_count'] ?> questions
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small><?= date('M j, Y', strtotime($assignment['created_at'])) ?></small>
                                                            <br>
                                                            <small class="text-muted"><?= date('g:i A', strtotime($assignment['created_at'])) ?></small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="../test/view_test.php?test_id=<?= $assignment['test_id'] ?>" 
                                                                   class="btn btn-outline-primary btn-sm" 
                                                                   title="View Test">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button class="btn btn-outline-danger btn-sm" 
                                                                        onclick="deleteMapping(<?= $assignment['id'] ?>, '<?= addslashes($assignment['test_title']) ?>', '<?= addslashes($assignment['chapter_name']) ?>')"
                                                                        title="Delete Mapping">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMapping(id, testTitle, chapterName) {
    if (confirm(`Are you sure you want to delete the mapping between test "${testTitle}" and chapter "${chapterName}"?`)) {
        window.location.href = `edit_test_chapter_map.php?delete_id=${id}`;
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.mapping-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Hide empty groups
    document.querySelectorAll('.card').forEach(card => {
        const visibleRows = card.querySelectorAll('.mapping-row:not([style*="display: none"])');
        if (visibleRows.length === 0 && searchTerm !== '') {
            card.style.display = 'none';
        } else {
            card.style.display = '';
        }
    });
});
</script>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.btn-group .btn {
    border-radius: 4px !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
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

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?>
