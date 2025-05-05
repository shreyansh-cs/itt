<?php
include_once __DIR__.'/../session.php';
require_once __DIR__.'/../../backend/db.php';

header('Content-Type: application/json');

if (!isset($_POST['question_id'])) {
    echo json_encode(['success' => false, 'message' => 'Question ID is required']);
    exit;
}

$question_id = (int)$_POST['question_id'];

try {
    // Delete the question
    $stmt = $pdo->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->execute([$question_id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Question deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Question not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 