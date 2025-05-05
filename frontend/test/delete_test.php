<?php
include_once __DIR__.'/../session.php';
require_once __DIR__.'/../../backend/db.php';

header('Content-Type: application/json');

if (!isset($_POST['test_id'])) {
    die(json_encode(['success' => false, 'message' => 'Test ID not provided']));
}

$test_id = (int)$_POST['test_id'];
$user_id = getUserID();

try {
    $pdo->beginTransaction();

    // Delete all questions for this test
    $stmt = $pdo->prepare("DELETE FROM questions WHERE test_id = :test_id");
    $stmt->execute([':test_id' => $test_id]);

    // Delete all test sessions
    $stmt = $pdo->prepare("DELETE FROM test_sessions WHERE test_id = :test_id");
    $stmt->execute([':test_id' => $test_id]);

    // Delete all user answers
    $stmt = $pdo->prepare("DELETE FROM user_answers WHERE test_id = :test_id");
    $stmt->execute([':test_id' => $test_id]);

    // Delete test-class mappings
    $stmt = $pdo->prepare("DELETE FROM test_classes_map WHERE test_id = :test_id");
    $stmt->execute([':test_id' => $test_id]);

    // Finally, delete the test itself
    $stmt = $pdo->prepare("DELETE FROM tests WHERE test_id = :test_id");
    $success = $stmt->execute([':test_id' => $test_id]);

    if ($success) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Test deleted successfully']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete test']);
    }

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?> 