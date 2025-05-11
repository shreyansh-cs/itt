<?php 
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "Admin Test Attempt";
?>

<?php
require_once __DIR__.'/../../backend/db.php';

// Fetch all tests
$stmt = $pdo->prepare("
    SELECT t.*, 
           COUNT(q.question_id) as questions_uploaded,
           GROUP_CONCAT(DISTINCT c.name) as assigned_classes
    FROM tests t
    LEFT JOIN questions q ON t.test_id = q.test_id
    LEFT JOIN test_classes_map tcm ON t.test_id = tcm.test_id
    LEFT JOIN classes c ON tcm.class_id = c.id
    GROUP BY t.test_id
    ORDER BY t.created_at DESC
");
$stmt->execute();
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Select Test to Attempt</h5>
            <a href="../index.php" class="btn btn-light btn-sm">Back to Home</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Test Title</th>
                            <th>Duration</th>
                            <th>Total Questions</th>
                            <th>Questions Uploaded</th>
                            <th>Assigned Classes</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tests as $test): ?>
                            <tr>
                                <td><?= htmlspecialchars($test['title']) ?></td>
                                <td><?= $test['duration_minutes'] ?> minutes</td>
                                <td><?= $test['total_questions'] ?></td>
                                <td>
                                    <?php if ($test['questions_uploaded'] == $test['total_questions']): ?>
                                        <span class="badge bg-success"><?= $test['questions_uploaded'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><?= $test['questions_uploaded'] ?>/<?= $test['total_questions'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($test['assigned_classes']): ?>
                                        <?= htmlspecialchars($test['assigned_classes']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($test['created_at'])) ?></td>
                                <td>
                                    <?php if ($test['questions_uploaded'] > 0): ?>
                                        <a href="take_test.php?test_id=<?= $test['test_id'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-pencil-alt me-1"></i>Attempt Test
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-exclamation-circle me-1"></i>No Questions
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.9em;
    padding: 0.5em 0.8em;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}
</style>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?> 