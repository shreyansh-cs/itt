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
           COUNT(DISTINCT tcm.chapter_id) as assigned_chapters
    FROM tests t
    LEFT JOIN questions q ON t.test_id = q.test_id
    LEFT JOIN test_chapters_map tcm ON t.test_id = tcm.test_id
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
                            <th>S.No</th>
                            <th>Test ID</th>
                            <th>Test Title</th>
                            <th>Duration</th>
                            <th>Total Questions</th>
                            <th>Questions Uploaded</th>
                            <th>Assigned Classes</th>
                            <th>Created Date</th>
                            <th>Action</th>
                            <th>Reset</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $serial = 1;
                        foreach ($tests as $test): 
                        ?>
                            <tr>
                                <td><?= $serial++ ?></td>
                                <td>
                                    <a href="view_test.php?test_id=<?= $test['test_id'] ?>" class="text-decoration-none">
                                        <?= $test['test_id'] ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="view_test.php?test_id=<?= $test['test_id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($test['title']) ?>
                                    </a>
                                </td>
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
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-pencil-alt me-1"></i>Attempt Test
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-exclamation-circle me-1"></i>No Questions
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($test['questions_uploaded'] > 0): ?>
                                        <button class="btn btn-warning btn-sm" 
                                                onclick="resetTest(<?= $test['test_id'] ?>)"
                                                title="Reset Test">
                                            <i class="fas fa-redo-alt me-1"></i>Reset Test
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-redo-alt me-1"></i>Reset Test
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

<script>
function resetTest(testId) {
    if (confirm('Are you sure you want to reset this test? This will delete all your answers and test session data.')) {
        fetch('reset_test.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `test_id=${testId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test reset successfully!');
                location.reload();
            } else {
                alert('Error resetting test: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error resetting test. Please try again.');
        });
    }
}
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?> 