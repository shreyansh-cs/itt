<?php
include_once __DIR__.'/../session.php';
require_once __DIR__.'/../../backend/db.php';

// Check if required parameters are provided
if (!isset($_POST['test_id']) || !isset($_POST['question_id']) || !isset($_POST['selected_option'])) {
    die(json_encode(['success' => false, 'message' => 'Missing required parameters']));
}

$test_id = (int)$_POST['test_id'];
$question_id = (int)$_POST['question_id'];
$selected_option = $_POST['selected_option'];
$user_id = getUserID();

try {
    // First check if there's an active test session
    $stmt = $pdo->prepare("SELECT test_session_id FROM test_sessions 
                          WHERE user_id = :user_id 
                          AND test_id = :test_id 
                          AND status = 'in_progress'");
    $stmt->execute([':user_id' => $user_id, ':test_id' => $test_id]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$session) {
        die(json_encode(['success' => false, 'message' => 'No active test session found']));
    }

    // Check if answer already exists
    $stmt = $pdo->prepare("SELECT * FROM user_answers 
                          WHERE user_id = :user_id 
                          AND test_id = :test_id 
                          AND question_id = :question_id");
    $stmt->execute([
        ':user_id' => $user_id,
        ':test_id' => $test_id,
        ':question_id' => $question_id
    ]);
    $existing_answer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_answer) {
        // Update existing answer
        $stmt = $pdo->prepare("UPDATE user_answers 
                              SET selected_option = :selected_option,
                                  answered_at = NOW()
                              WHERE user_id = :user_id 
                              AND test_id = :test_id 
                              AND question_id = :question_id");
    } else {
        // Insert new answer
        $stmt = $pdo->prepare("INSERT INTO user_answers 
                              (user_id, test_id, question_id, selected_option, answered_at) 
                              VALUES (:user_id, :test_id, :question_id, :selected_option, NOW())");
    }

    $success = $stmt->execute([
        ':user_id' => $user_id,
        ':test_id' => $test_id,
        ':question_id' => $question_id,
        ':selected_option' => $selected_option
    ]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Answer saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save answer']);
    }

} catch (PDOException $e) {
    $error = $e->getMessage().' '.$test_id.' '.$question_id.' '.$selected_option;
    error_log($error);
    echo json_encode(['success' => false, 'message' => $error]);
}
?> 