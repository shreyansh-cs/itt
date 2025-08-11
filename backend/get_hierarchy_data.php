<?php
/**
 * AJAX endpoint for fetching hierarchical data (streams, subjects, sections, chapters)
 */

require_once __DIR__.'/db.php';
require_once __DIR__.'/utils.php';

header('Content-Type: application/json');

// Check if action is provided
if (!isset($_GET['action'])) {
    echo json_encode(['error' => 'No action specified']);
    exit;
}

$action = $_GET['action'];

try {
    switch ($action) {
        case 'get_classes':
            $classes = getAllClasses();
            echo json_encode(['success' => true, 'data' => $classes]);
            break;
            
        case 'get_streams':
            if (!isset($_GET['class_id'])) {
                echo json_encode(['error' => 'Class ID required']);
                exit;
            }
            $streams = getStreamsForClass($_GET['class_id']);
            echo json_encode(['success' => true, 'data' => $streams]);
            break;
            
        case 'get_subjects':
            if (!isset($_GET['class_id']) || !isset($_GET['stream_id'])) {
                echo json_encode(['error' => 'Class ID and Stream ID required']);
                exit;
            }
            $subjects = getSubjectsForStream($_GET['class_id'], $_GET['stream_id']);
            echo json_encode(['success' => true, 'data' => $subjects]);
            break;
            
        case 'get_sections':
            if (!isset($_GET['class_id']) || !isset($_GET['stream_id']) || !isset($_GET['subject_id'])) {
                echo json_encode(['error' => 'Class ID, Stream ID, and Subject ID required']);
                exit;
            }
            $sections = getSectionsForSubject($_GET['class_id'], $_GET['stream_id'], $_GET['subject_id']);
            echo json_encode(['success' => true, 'data' => $sections]);
            break;
            
        case 'get_chapters':
            if (!isset($_GET['class_id']) || !isset($_GET['stream_id']) || !isset($_GET['subject_id']) || !isset($_GET['section_id'])) {
                echo json_encode(['error' => 'Class ID, Stream ID, Subject ID, and Section ID required']);
                exit;
            }
            $chapters = getChaptersForSection($_GET['class_id'], $_GET['stream_id'], $_GET['subject_id'], $_GET['section_id']);
            echo json_encode(['success' => true, 'data' => $chapters]);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
