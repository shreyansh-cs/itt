<?php
include_once __DIR__.'/../session.php';
require_once __DIR__.'/../../backend/db.php';

header('Content-Type: application/json');

if (!isset($_POST['test_id'])) {
    die(json_encode(['success' => false, 'message' => 'Test ID not provided']));
}

$test_id = (int)$_POST['test_id'];
$user_id = getUserID();
$status = $_POST['status'] ?? 'completed'; // Default to completed if not specified

try {
    $stmt = $pdo->prepare("UPDATE test_sessions 
                          SET status = :status, 
                              end_time = NOW() 
                          WHERE user_id = :user_id 
                          AND test_id = :test_id 
                          AND status = 'in_progress'");
    
    $success = $stmt->execute([
        ':status' => $status,
        ':user_id' => $user_id,
        ':test_id' => $test_id
    ]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Test status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update test status']);
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?> 