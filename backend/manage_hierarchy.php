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
            if (!isset($_POST['stream_id']) || !isset($_POST['subject_name'])) {
                echo json_encode(['error' => 'Stream ID and subject name required']);
                exit;
            }
            
            $stream_id = trim($_POST['stream_id']);
            $subject_name = trim($_POST['subject_name']);
            
            if (empty($stream_id) || $stream_id === '' || $stream_id === '0') {
                echo json_encode(['error' => 'Please select a valid stream']);
                exit;
            }
            
            if (empty($subject_name)) {
                echo json_encode(['error' => 'Subject name cannot be empty']);
                exit;
            }
            
            // Validate that stream_id is a valid integer
            if (!filter_var($stream_id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                echo json_encode(['error' => 'Invalid stream ID provided']);
                exit;
            }
            
            // Check if subject already exists for this stream
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE NAME = ? AND STREAM_ID = ?");
            $stmt->execute([$subject_name, $stream_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Subject already exists for this stream']);
                exit;
            }
            
            // Final validation before insert
            $stream_id = (int) $stream_id;
            if ($stream_id <= 0) {
                echo json_encode(['error' => 'Invalid stream ID: must be a positive integer']);
                exit;
            }
            
            // Create the subject with stream_id
            $stmt = $pdo->prepare("INSERT INTO subjects (NAME, STREAM_ID) VALUES (?, ?)");
            $stmt->execute([$subject_name, $stream_id]);
            
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
            
        case 'delete_stream':
        case 'delete_subject':
        case 'delete_section':
        case 'delete_chapter':
            // Generic hierarchical deletion handler
            $type = str_replace('delete_', '', $_POST['action']);
            $id_field = $type . '_id';
            
            if (!isset($_POST[$id_field])) {
                echo json_encode(['error' => ucfirst($type) . ' ID required']);
                exit;
            }
            
            $id = trim($_POST[$id_field]);
            
            if (empty($id) || $id === '' || $id === '0') {
                echo json_encode(['error' => 'Please provide a valid ' . $type . ' ID']);
                exit;
            }
            
            // Validate that ID is a valid integer
            if (!filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                echo json_encode(['error' => 'Invalid ' . $type . ' ID provided']);
                exit;
            }
            
            $id = (int) $id;
            
            // Check if item exists and get its name
            $table_map = [
                'stream' => 'streams',
                'subject' => 'subjects', 
                'section' => 'sections',
                'chapter' => 'chapters'
            ];
            
            $table = $table_map[$type];
            $stmt = $pdo->prepare("SELECT NAME FROM $table WHERE ID = ?");
            $stmt->execute([$id]);
            $item_name = $stmt->fetchColumn();
            
            if (!$item_name) {
                echo json_encode(['error' => ucfirst($type) . ' not found']);
                exit;
            }
            
            // Include utils.php for the deletion functions
            require_once __DIR__.'/utils.php';
            
            $error = '';
            if (deleteHierarchyItem($type, $id, $error)) {
                echo json_encode(['success' => true, 'message' => $error]);
            } else {
                echo json_encode(['error' => $error]);
            }
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
