<?php
require_once '../../config/database.php';
require_once '../../config/session.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../../login.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: take_test.php');
    exit();
}

try {
    // Get test session ID and test ID
    $test_session_id = $_POST['test_session_id'] ?? null;
    $test_id = $_POST['test_id'] ?? null;

    if (!$test_session_id || !$test_id) {
        throw new Exception('Missing required parameters');
    }

    // Start transaction
    $pdo->beginTransaction();

    // Get test details
    $stmt = $pdo->prepare("SELECT * FROM tests WHERE test_id = ?");
    $stmt->execute([$test_id]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$test) {
        throw new Exception('Test not found');
    }

    // Get all questions for this test
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE test_id = ?");
    $stmt->execute([$test_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_questions = count($questions);
    $correct_answers = 0;
    $total_score = 0;

    // Process each answer
    foreach ($questions as $question) {
        $question_id = $question['question_id'];
        $user_answer = $_POST['answer_' . $question_id] ?? null;
        $is_correct = false;

        // Check if answer is correct
        if ($user_answer !== null) {
            $is_correct = strtolower(trim($user_answer)) === strtolower(trim($question['correct_answer']));
            if ($is_correct) {
                $correct_answers++;
                $total_score += $question['marks'];
            }
        }

        // Save or update the answer
        $stmt = $pdo->prepare("INSERT INTO user_answers 
            (test_session_id, question_id, user_answer, is_correct, marks_obtained) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            user_answer = VALUES(user_answer),
            is_correct = VALUES(is_correct),
            marks_obtained = VALUES(marks_obtained)");
        
        $marks_obtained = $is_correct ? $question['marks'] : 0;
        $stmt->execute([
            $test_session_id,
            $question_id,
            $user_answer,
            $is_correct,
            $marks_obtained
        ]);
    }

    // Calculate percentage
    $percentage = ($total_score / $test['total_marks']) * 100;

    // Update test session
    $stmt = $pdo->prepare("UPDATE test_sessions 
        SET status = 'completed',
            end_time = NOW(),
            score = ?,
            percentage = ?,
            questions_attempted = ?,
            questions_correct = ?
        WHERE test_session_id = ?");
    
    $stmt->execute([
        $total_score,
        $percentage,
        count(array_filter($_POST, function($key) {
            return strpos($key, 'answer_') === 0;
        }, ARRAY_FILTER_USE_KEY)),
        $correct_answers,
        $test_session_id
    ]);

    // Commit transaction
    $pdo->commit();

    // Redirect to results page
    header('Location: test_analysis.php?test_id=' . $test_id);
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log error
    error_log("Error in submit_test.php: " . $e->getMessage());
    
    // Redirect with error
    header('Location: take_test.php?error=' . urlencode('Failed to submit test. Please try again.'));
    exit();
}
?> 