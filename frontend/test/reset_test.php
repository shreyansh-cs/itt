<?php
include_once __DIR__.'/../restrictedpage.php';
require_once __DIR__.'/../../backend/db.php';

header('Content-Type: application/json');

if (!isset($_POST['test_id'])) {
    die(json_encode(['success' => false, 'message' => 'Test ID not provided']));
}

try {
    $test_id = $_POST['test_id'];
    $user_id = getUserID();

    // Start transaction
    $pdo->beginTransaction();

    // Delete user answers for this test
    $stmt = $pdo->prepare("DELETE FROM user_answers WHERE test_id = ? AND user_id = ?");
    $stmt->execute([$test_id, $user_id]);

    // Delete test session for this test
    $stmt = $pdo->prepare("DELETE FROM test_sessions WHERE test_id = ? AND user_id = ?");
    $stmt->execute([$test_id, $user_id]);

    // Commit transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Test reset successfully']);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 