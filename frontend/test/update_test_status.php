<?php
include_once __DIR__.'/../session.php';
require_once __DIR__.'/../../backend/db.php';

header('Content-Type: application/json');

if (!isset($_POST['test_id'])) {
    die(json_encode(['success' => false, 'message' => 'Test ID not provided']));
}

try {
    $test_id = $_POST['test_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $user_id = getUserID();

    if (!$test_id || !$status) {
        throw new Exception('Missing required parameters');
    }

    // Validate status
    $valid_statuses = ['in_progress', 'completed', 'expired'];
    if (!in_array($status, $valid_statuses)) {
        throw new Exception('Invalid status');
    }

    // Get test session details
    $stmt = $pdo->prepare("SELECT ts.*, t.duration_minutes 
                          FROM test_sessions ts 
                          JOIN tests t ON ts.test_id = t.test_id 
                          WHERE ts.test_id = ? AND ts.user_id = ?");
    $stmt->execute([$test_id, $user_id]);
    $test_session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$test_session) {
        throw new Exception('No test session found');
    }

    // Calculate end time based on start time and duration
    $start_time = strtotime($test_session['start_time']);
    $duration_seconds = $test_session['duration_minutes'] * 60;
    $calculated_end_time = date('Y-m-d H:i:s', $start_time + $duration_seconds);
    $current_time = date('Y-m-d H:i:s');

    // If current time is past the calculated end time, force status to expired
    if (strtotime($current_time) > strtotime($calculated_end_time)) {
        $status = 'expired';
        $end_time = $current_time;
    } else {
        // If status is expired or completed, use the calculated end time
        $end_time = ($status === 'in_progress') ? null : $calculated_end_time;
    }

    // Update test session status
    $stmt = $pdo->prepare("UPDATE test_sessions 
        SET status = ?, 
            end_time = ?
        WHERE test_id = ? AND user_id = ?");
    
    $stmt->execute([$status, $end_time, $test_id, $user_id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Failed to update test status');
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 