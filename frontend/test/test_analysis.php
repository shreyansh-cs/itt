<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Test Analysis";
require __DIR__.'/../../backend/db.php';

$user_id = getUserID();
$test_id = isset($_GET['test_id']) ? (int)$_GET['test_id'] : null;

// Get test details and user's performance
$stmt = $pdo->prepare("
    SELECT 
        t.test_id,
        t.title,
        t.duration_minutes,
        t.total_questions,
        ts.start_time,
        ts.end_time,
        ts.status,
        (SELECT COUNT(*) FROM user_answers ua 
         WHERE ua.test_id = t.test_id 
         AND ua.user_id = :user_id) as questions_attempted,
        (SELECT COUNT(*) FROM user_answers ua 
         INNER JOIN questions q ON ua.question_id = q.question_id 
         WHERE ua.test_id = t.test_id 
         AND ua.user_id = :user_id 
         AND ua.selected_option = q.correct_option) as correct_answers
    FROM test_sessions ts
    INNER JOIN tests t ON ts.test_id = t.test_id
    WHERE ts.user_id = :user_id
    " . ($test_id ? "AND t.test_id = :test_id" : "") . "
    ORDER BY ts.start_time DESC
");

$params = [':user_id' => $user_id];
if ($test_id) {
    $params[':test_id'] = $test_id;
}
$stmt->execute($params);
$test_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Test Analysis</h3>
            <a href="../noteslist.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Tests
            </a>
        </div>
        
        <?php if (!empty($test_history)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Test Title</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Questions</th>
                            <th>Attempted</th>
                            <th>Correct</th>
                            <th>Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($test_history as $test): 
                            $score = $test['total_questions'] > 0 ? 
                                round(($test['correct_answers'] / $test['total_questions']) * 100, 2) : 0;
                            
                            $status_class = match($test['status']) {
                                'completed' => 'bg-success',
                                'expired' => 'bg-danger',
                                'in_progress' => 'bg-warning',
                                default => 'bg-secondary'
                            };
                            
                            $start_date = new DateTime($test['start_time']);
                            $formatted_date = $start_date->format('d M Y, h:i A');
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($test['title']) ?></td>
                                <td><?= $formatted_date ?></td>
                                <td><?= $test['duration_minutes'] ?> minutes</td>
                                <td><?= $test['total_questions'] ?></td>
                                <td><?= $test['questions_attempted'] ?></td>
                                <td><?= $test['correct_answers'] ?></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar <?= $score >= 70 ? 'bg-success' : ($score >= 40 ? 'bg-warning' : 'bg-danger') ?>" 
                                             role="progressbar" 
                                             style="width: <?= $score ?>%"
                                             aria-valuenow="<?= $score ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= $score ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?= $status_class ?>">
                                        <?= ucfirst($test['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <?php if ($test_id): ?>
                    No test history found for this test.
                <?php else: ?>
                    You haven't taken any tests yet. 
                    <a href="../noteslist.php" class="alert-link">Go to Notes & Tests</a> to start taking tests.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?> 