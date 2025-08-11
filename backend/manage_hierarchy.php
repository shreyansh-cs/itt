<?php
/**
 * Backend endpoint for managing hierarchy creation (streams, subjects, sections, chapters)
 */

require_once __DIR__.'/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST method allowed']);
    exit;
}

if (!isset($_POST['action'])) {
    echo json_encode(['error' => 'No action specified']);
    exit;
}

$action = $_POST['action'];

try {
    switch ($action) {
        case 'create_stream':
            if (!isset($_POST['class_id']) || !isset($_POST['stream_name'])) {
                echo json_encode(['error' => 'Class ID and stream name required']);
                exit;
            }
            
            $class_id = trim($_POST['class_id']);
            $stream_name = trim($_POST['stream_name']);
            
            if (empty($class_id) || $class_id === '' || $class_id === '0') {
                echo json_encode(['error' => 'Please select a valid class']);
                exit;
            }
            
            if (empty($stream_name)) {
                echo json_encode(['error' => 'Stream name cannot be empty']);
                exit;
            }
            
            // Validate that class_id is a valid integer
            if (!filter_var($class_id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                echo json_encode(['error' => 'Invalid class ID provided']);
                exit;
            }
            
            // Check if stream already exists for this class
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM streams WHERE NAME = ? AND CLASS_ID = ?");
            $stmt->execute([$stream_name, $class_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Stream already exists for this class']);
                exit;
            }
            
            // Final validation before insert
            $class_id = (int) $class_id;
            if ($class_id <= 0) {
                echo json_encode(['error' => 'Invalid class ID: must be a positive integer']);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO streams (NAME, CLASS_ID) VALUES (?, ?)");
            $stmt->execute([$stream_name, $class_id]);
            
            echo json_encode(['success' => true, 'message' => "Stream '$stream_name' created successfully!"]);
            break;
            
        case 'create_subject':
            if (!isset($_POST['class_id']) || !isset($_POST['subject_name'])) {
                echo json_encode(['error' => 'Class ID and subject name required']);
                exit;
            }
            
            $class_id = trim($_POST['class_id']);
            $subject_name = trim($_POST['subject_name']);
            
            if (empty($class_id) || $class_id === '' || $class_id === '0') {
                echo json_encode(['error' => 'Please select a valid class']);
                exit;
            }
            
            if (empty($subject_name)) {
                echo json_encode(['error' => 'Subject name cannot be empty']);
                exit;
            }
            
            // Validate that class_id is a valid integer
            if (!filter_var($class_id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                echo json_encode(['error' => 'Invalid class ID provided']);
                exit;
            }
            
            // Check if subject already exists for this class
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE NAME = ? AND CLASS_ID = ?");
            $stmt->execute([$subject_name, $class_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Subject already exists for this class']);
                exit;
            }
            
            // Final validation before insert
            $class_id = (int) $class_id;
            if ($class_id <= 0) {
                echo json_encode(['error' => 'Invalid class ID: must be a positive integer']);
                exit;
            }
            
            // Create the subject with class_id
            $stmt = $pdo->prepare("INSERT INTO subjects (NAME, CLASS_ID) VALUES (?, ?)");
            $stmt->execute([$subject_name, $class_id]);
            
            echo json_encode(['success' => true, 'message' => "Subject '$subject_name' created successfully!"]);
            break;
            
        case 'create_section':
            if (!isset($_POST['subject_id']) || !isset($_POST['section_name'])) {
                echo json_encode(['error' => 'Subject ID and section name required']);
                exit;
            }
            
            $subject_id = $_POST['subject_id'];
            $section_name = trim($_POST['section_name']);
            
            if (empty($section_name)) {
                echo json_encode(['error' => 'Section name cannot be empty']);
                exit;
            }
            
            // Check if section already exists for this subject
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM sections WHERE NAME = ? AND SUBJECT_ID = ?");
            $stmt->execute([$section_name, $subject_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Section already exists for this subject']);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO sections (NAME, SUBJECT_ID) VALUES (?, ?)");
            $stmt->execute([$section_name, $subject_id]);
            
            echo json_encode(['success' => true, 'message' => "Section '$section_name' created successfully!"]);
            break;
            
        case 'create_chapter':
            if (!isset($_POST['section_id']) || !isset($_POST['chapter_name'])) {
                echo json_encode(['error' => 'Section ID and chapter name required']);
                exit;
            }
            
            $section_id = $_POST['section_id'];
            $chapter_name = trim($_POST['chapter_name']);
            
            if (empty($chapter_name)) {
                echo json_encode(['error' => 'Chapter name cannot be empty']);
                exit;
            }
            
            // Check if chapter already exists for this section
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM chapters WHERE NAME = ? AND SECTION_ID = ?");
            $stmt->execute([$chapter_name, $section_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Chapter already exists for this section']);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO chapters (NAME, SECTION_ID) VALUES (?, ?)");
            $stmt->execute([$chapter_name, $section_id]);
            
            echo json_encode(['success' => true, 'message' => "Chapter '$chapter_name' created successfully!"]);
            break;
            
        case 'map_stream_subject':
            if (!isset($_POST['stream_id']) || !isset($_POST['subject_id'])) {
                echo json_encode(['error' => 'Stream ID and Subject ID required']);
                exit;
            }
            
            $stream_id = trim($_POST['stream_id']);
            $subject_id = trim($_POST['subject_id']);
            
            // Validate inputs
            if (!filter_var($stream_id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ||
                !filter_var($subject_id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                echo json_encode(['error' => 'Invalid stream or subject ID']);
                exit;
            }
            
            // Check if mapping already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM streamubjectmap WHERE STREAM_ID = ? AND SUBJECT_ID = ?");
            $stmt->execute([$stream_id, $subject_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Stream-Subject mapping already exists']);
                exit;
            }
            
            // Verify that stream and subject belong to the same class
            $stmt = $pdo->prepare("
                SELECT s.CLASS_ID as stream_class, sub.CLASS_ID as subject_class
                FROM streams s, subjects sub 
                WHERE s.ID = ? AND sub.ID = ?
            ");
            $stmt->execute([$stream_id, $subject_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result || $result['stream_class'] != $result['subject_class']) {
                echo json_encode(['error' => 'Stream and subject must belong to the same class']);
                exit;
            }
            
            // Create the mapping
            $stmt = $pdo->prepare("INSERT INTO streamubjectmap (STREAM_ID, SUBJECT_ID) VALUES (?, ?)");
            $stmt->execute([$stream_id, $subject_id]);
            
            echo json_encode(['success' => true, 'message' => 'Stream-Subject mapping created successfully!']);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
