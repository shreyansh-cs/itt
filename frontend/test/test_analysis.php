<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Test Analysis";
require __DIR__.'/../../backend/db.php';

$test_id = $_GET['test_id'] ?? null;
$user_id = getUserID();

if (!$test_id) {
    die("Test ID not provided.");
}

try {
    // Get test details
    $stmt = $pdo->prepare("SELECT * FROM tests WHERE test_id = ?");
    $stmt->execute([$test_id]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$test) {
        die("Test not found.");
    }

    // Get test session details
    $stmt = $pdo->prepare("SELECT * FROM test_sessions 
                          WHERE test_id = ? AND user_id = ? 
                          ORDER BY start_time DESC LIMIT 1");
    $stmt->execute([$test_id, $user_id]);
    $test_session = $stmt->fetch(PDO::FETCH_ASSOC);

    //debug_print($test_session);

    if (!$test_session) {
        die("No test session found.");
    }

    // Get all questions and user's answers
    $stmt = $pdo->prepare("SELECT q.*, ua.selected_option
                          FROM questions q 
                          JOIN user_answers ua ON q.question_id = ua.question_id 
                          WHERE ua.test_id = ? 
                          AND ua.user_id = ?
                          ORDER BY q.question_id");
    $stmt->execute([$test_id, $user_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total marks and percentage
    $total_marks = 0;
    $obtained_marks = 0;
    $questions_correct = 0;
    foreach ($questions as $question) {
        $total_marks += $question['marks'];
        if ($question['correct_option'] == $question['selected_option']) {
            $obtained_marks += $question['marks'];
            $questions_correct++;
        }
    }
    $percentage = $total_marks > 0 ? ($obtained_marks / $total_marks) * 100 : 0;

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container-fluid p-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Test Analysis: <?= htmlspecialchars($test['title']) ?></h5>
        </div>
        <div class="card-body">
            <!-- Test Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Score</h6>
                            <h3 class="mb-0"><?= $obtained_marks ?>/<?= $total_marks ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Percentage</h6>
                            <h3 class="mb-0"><?= number_format($percentage, 1) ?>%</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Questions Correct</h6>
                            <h3 class="mb-0"><?= $questions_correct ?>/<?= $test['total_questions'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Time Taken</h6>
                            <h3 class="mb-0">
                                <?php
                                $start = strtotime($test_session['start_time']);
                                $end = strtotime($test_session['end_time']);
                                $duration = $end - $start;
                                echo floor($duration / 60) . 'm ' . ($duration % 60) . 's';
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Analysis -->
            <h5 class="mb-3">Question Analysis</h5>
            <?php foreach ($questions as $index => $question): ?>
                <div class="card mb-3 <?= $question['selected_option'] == $question['correct_option'] ? 'border-success' : 'border-danger' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">Question <?= $index + 1 ?></h6>
                            <span class="badge <?= $question['selected_option'] == $question['correct_option'] ? 'bg-success' : 'bg-danger' ?>">
                                <?= $question['selected_option'] == $question['correct_option'] ? 'Correct' : 'Incorrect' ?>
                            </span>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($question['question_text']) ?></p>
                        
                        <div class="options">
                            <?php
                            $options = ['A', 'B', 'C', 'D'];
                            foreach ($options as $option):
                                $option_value = strtolower($option);
                                $is_correct = strtoupper($question['correct_option']) === $option;
                                $is_selected = strtoupper($question['selected_option']) === $option;
                            ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" disabled
                                           <?= $is_selected ? 'checked' : '' ?>>
                                    <label class="form-check-label <?= $is_correct ? 'text-success fw-bold' : '' ?>">
                                        <?= htmlspecialchars($question['option_' . $option_value]) ?>
                                        <?php if ($is_correct): ?>
                                            <i class="fas fa-check text-success"></i>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-2">
                            <small class="text-muted">
                                Your Answer: <?= $question['selected_option'] ? strtoupper($question['selected_option']) : 'Not answered' ?>
                                <?php if ($question['selected_option'] != $question['correct_option']): ?>
                                    <br>Correct Answer: <?= strtoupper($question['correct_option']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?> 