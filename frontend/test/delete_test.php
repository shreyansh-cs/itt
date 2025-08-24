<?php
require_once __DIR__.'/../../frontend/restrictedpage.php';

if(isset($_POST['test_id'])){
    $test_id = $_POST['test_id'];
}
/*else if(isset($_GET['test_id'])){
    $test_id = $_GET['test_id'];
}*/
else{
    echo json_encode(['success' => false, 'message' => 'Test ID not provided']);
    exit();
}

try {
    include __DIR__."/../../backend/db.php";
    $pdo->beginTransaction();

    // 1. First delete user_answers (child of questions)
    $stmt = $pdo->prepare("DELETE ua FROM user_answers ua 
                          INNER JOIN questions q ON ua.question_id = q.question_id 
                          WHERE q.test_id = ?");
    $stmt->execute([$test_id]);

    // 2. Delete test_sessions (child of tests)
    $stmt = $pdo->prepare("DELETE FROM test_sessions WHERE test_id = ?");
    $stmt->execute([$test_id]);

    // 3. Delete test_chapters_map (child of tests) - chapter-based mapping
    $stmt = $pdo->prepare("DELETE FROM test_chapters_map WHERE test_id = ?");
    $stmt->execute([$test_id]);

    // 4. Delete questions (child of tests)
    $stmt = $pdo->prepare("DELETE FROM questions WHERE test_id = ?");
    $stmt->execute([$test_id]);

    // 5. Finally delete the test itself
    $stmt = $pdo->prepare("DELETE FROM tests WHERE test_id = ?");
    $stmt->execute([$test_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Test deleted successfully']);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error deleting test: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error deleting test: ' . $e->getMessage()]);
}
?> 