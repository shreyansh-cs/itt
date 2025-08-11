<?php 
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "View Test";
?>

<?php
require_once __DIR__.'/../../backend/db.php';
require_once __DIR__.'/../../backend/utils.php';

// Check if test_id provided
if (!isset($_GET['test_id'])) {
    die("Test ID not provided.");
}

$test_id = (int) $_GET['test_id'];

// Fetch test details
$stmt = $pdo->prepare("SELECT * FROM tests WHERE test_id = :test_id");
$stmt->execute([':test_id' => $test_id]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Invalid Test ID.");
}

// Fetch questions for this test
$stmt = $pdo->prepare("SELECT * FROM questions WHERE test_id = :test_id ORDER BY question_id");
$stmt->execute([':test_id' => $test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total questions
$total_questions = count($questions);

// Fetch chapter mappings for this test
$chapterMappings = getChaptersForTest($test_id);
?>

<div class="container-fluid p-0">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= htmlspecialchars($test['title']) ?></h5>
            <div>
                <a href="upload_questions.php?test_id=<?= $test_id ?>" class="btn btn-light btn-sm">Upload Questions</a>
                <a href="map_test_to_chapter.php" class="btn btn-light btn-sm ms-2">Assign to Chapter</a>
                <button class="btn btn-danger btn-sm ms-2" onclick="deleteTest(<?= $test_id ?>)">
                    <i class="fas fa-trash"></i> Delete Test
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Test Details</h6>
                            <p class="mb-1"><strong>Duration:</strong> <?= $test['duration_minutes'] ?> minutes</p>
                            <p class="mb-1"><strong>Total Questions:</strong> <?= $total_questions ?>/<?= $test['total_questions'] ?></p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <?php if ($total_questions == $test['total_questions']): ?>
                                    <span class="badge bg-success">Complete</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Incomplete</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Chapter Assignments</h6>
                            <?php if (empty($chapterMappings)): ?>
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    This test is not assigned to any chapters yet.
                                    <a href="map_test_to_chapter.php" class="alert-link">Assign to chapters</a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($chapterMappings as $mapping): ?>
                                        <div class="col-lg-6 mb-2">
                                            <div class="border rounded p-2 bg-light">
                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($mapping['class_name']) ?> → 
                                                    <?= htmlspecialchars($mapping['stream_name']) ?> → 
                                                    <?= htmlspecialchars($mapping['subject_name']) ?>
                                                </small>
                                                <strong><?= htmlspecialchars($mapping['section_name']) ?> → <?= htmlspecialchars($mapping['chapter_name']) ?></strong>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mt-2">
                                    <a href="edit_test_chapter_map.php" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Manage Mappings
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="mb-3">Questions</h6>
            <?php if (empty($questions)): ?>
                <div class="alert alert-info">
                    No questions uploaded yet. <a href="upload_questions.php?test_id=<?= $test_id ?>">Upload Questions</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Options</th>
                                <th>Correct Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $question): ?>
                                <tr>
                                    <td><?= htmlspecialchars($question['question_text']) ?></td>
                                    <td>
                                        <div>A) <?= htmlspecialchars($question['option_a']) ?></div>
                                        <div>B) <?= htmlspecialchars($question['option_b']) ?></div>
                                        <div>C) <?= htmlspecialchars($question['option_c']) ?></div>
                                        <div>D) <?= htmlspecialchars($question['option_d']) ?></div>
                                    </td>
                                    <td><?= strtoupper($question['correct_option']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteTest(testId) {
    if (confirm('Are you sure you want to delete this test? This will also delete all questions associated with it.')) {
        fetch('delete_test.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `test_id=${testId}`
        })
        .then(response => response.text())
        .then(data => {
            console.log('Raw response:', data);
            //alert('Server response: ' + data);
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    console.log('Test deleted successfully');
                    window.location.href = 'create_test.php';
                } else {
                    alert('Error deleting test: ' + jsonData.message);
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                alert('Error parsing server response. Raw response: ' + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting test. Please try again.');
        });
    }
}
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?> 