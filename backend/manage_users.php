<?php
include_once 'db.php';
include_once 'utils.php';

// Set JSON content type for API responses
header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if user is admin using proper session validation
include_once 'public_utils.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin using the proper validation functions
if (!isAdminLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Access denied. Admin privileges required.']);
    exit();
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'get_users':
            handleGetUsers();
            break;
        case 'get_user_details':
            handleGetUserDetails();
            break;
        case 'get_user_status':
            handleGetUserStatus();
            break;
        case 'get_statistics':
            handleGetStatistics();
            break;
        case 'toggle_verification':
            handleToggleVerification();
            break;
        case 'delete_user':
            handleDeleteUser();
            break;
        case 'bulk_verify':
            handleBulkVerify();
            break;
        case 'bulk_delete':
            handleBulkDelete();
            break;
        case 'export_users':
            handleExportUsers();
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action specified.']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}

function handleGetUsers() {
    global $pdo;
    
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(100, intval($_GET['limit'] ?? 10))); // Limit between 1-100
    $offset = ($page - 1) * $limit;
    
    $search = trim($_GET['search'] ?? '');
    $userType = trim($_GET['user_type'] ?? '');
    $classFilter = trim($_GET['class'] ?? '');
    $verifiedFilter = trim($_GET['verified'] ?? '');
    
    // Build the WHERE clause
    $whereConditions = [];
    $params = [];
    
    if (!empty($search)) {
        $whereConditions[] = "(u.full_name LIKE :search OR u.email LIKE :search OR u.phone LIKE :search OR u.father_name LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    if (!empty($userType)) {
        $whereConditions[] = "u.user_type = :user_type";
        $params[':user_type'] = $userType;
    }
    
    if (!empty($classFilter)) {
        $whereConditions[] = "u.user_class = :class_filter";
        $params[':class_filter'] = $classFilter;
    }
    
    if ($verifiedFilter !== '') {
        $whereConditions[] = "u.verified = :verified";
        $params[':verified'] = $verifiedFilter;
    }
    
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM users u $whereClause";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get users with pagination
    $sql = "            SELECT 
                u.id,
                u.full_name,
                u.father_name,
                u.email,
                u.phone,
                u.dob,
                u.photo,
                u.user_type,
                u.verified,
                'N/A' as created_at,
                c.NAME as class_name
            FROM users u
            LEFT JOIN classes c ON u.user_class = c.ID
            $whereClause
            ORDER BY u.id DESC
            LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics for current filters
    $statistics = getFilteredStatistics($whereClause, $params);
    
    echo json_encode([
        'success' => true,
        'users' => $users,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'statistics' => $statistics
    ]);
}

function handleGetUserDetails() {
    global $pdo;
    
    $userId = intval($_GET['user_id'] ?? 0);
    
    if ($userId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid user ID.']);
        return;
    }
    
    $sql = "SELECT 
                u.id,
                u.full_name,
                u.father_name,
                u.email,
                u.phone,
                u.dob,
                u.photo,
                u.user_type,
                u.verified,
                'N/A' as created_at,
                c.NAME as class_name
            FROM users u
            LEFT JOIN classes c ON u.user_class = c.ID
            WHERE u.id = :user_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'User not found.']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
}

function handleGetUserStatus() {
    global $pdo;
    
    $userId = intval($_GET['user_id'] ?? 0);
    
    if ($userId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid user ID.']);
        return;
    }
    
    $sql = "SELECT verified FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        echo json_encode(['success' => false, 'error' => 'User not found.']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'verified' => $result['verified']
    ]);
}

function handleGetStatistics() {
    global $pdo;
    
    $statistics = getUserStatistics();
    
    echo json_encode([
        'success' => true,
        'statistics' => $statistics
    ]);
}

function handleToggleVerification() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required.']);
        return;
    }
    
    $userId = intval($_POST['user_id'] ?? 0);
    $status = intval($_POST['status'] ?? 0);
    
    if ($userId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid user ID.']);
        return;
    }
    
    if ($status !== 0 && $status !== 1) {
        echo json_encode(['success' => false, 'error' => 'Invalid status value.']);
        return;
    }
    
    // Don't allow changing admin verification status
    $checkSql = "SELECT user_type FROM users WHERE id = :user_id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $checkStmt->execute();
    $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'User not found.']);
        return;
    }
    
    if ($user['user_type'] === 'admin') {
        echo json_encode(['success' => false, 'error' => 'Cannot modify admin verification status.']);
        return;
    }
    
    $sql = "UPDATE users SET verified = :status WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $action = $status ? 'verified' : 'unverified';
        echo json_encode([
            'success' => true,
            'message' => "User has been $action successfully."
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update user status.']);
    }
}

function handleDeleteUser() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required.']);
        return;
    }
    
    $userId = intval($_POST['user_id'] ?? 0);
    
    if ($userId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid user ID.']);
        return;
    }
    
    // Check if user exists and get user type
    $checkSql = "SELECT user_type, photo FROM users WHERE id = :user_id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $checkStmt->execute();
    $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'User not found.']);
        return;
    }
    
    // Don't allow deleting admin users
    if ($user['user_type'] === 'admin') {
        echo json_encode(['success' => false, 'error' => 'Cannot delete admin users.']);
        return;
    }
    
    // Don't allow deleting yourself
    $currentUserId = getUserID();
    if ($userId == $currentUserId) {
        echo json_encode(['success' => false, 'error' => 'Cannot delete your own account.']);
        return;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Delete user from database
        $sql = "DELETE FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Delete user photo if exists
        if (!empty($user['photo']) && file_exists($user['photo'])) {
            @unlink($user['photo']);
        }
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'Failed to delete user: ' . $e->getMessage()]);
    }
}

function handleBulkVerify() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required.']);
        return;
    }
    
    $userIds = $_POST['user_ids'] ?? '';
    
    if (empty($userIds)) {
        echo json_encode(['success' => false, 'error' => 'No users selected.']);
        return;
    }
    
    $userIdsArray = array_map('intval', explode(',', $userIds));
    $userIdsArray = array_filter($userIdsArray, function($id) { return $id > 0; });
    
    if (empty($userIdsArray)) {
        echo json_encode(['success' => false, 'error' => 'Invalid user IDs.']);
        return;
    }
    
    try {
        $placeholders = str_repeat('?,', count($userIdsArray) - 1) . '?';
        
        // Only verify non-admin users
        $sql = "UPDATE users SET verified = 1 WHERE id IN ($placeholders) AND user_type != 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($userIdsArray);
        
        $affectedRows = $stmt->rowCount();
        
        echo json_encode([
            'success' => true,
            'message' => "$affectedRows user(s) verified successfully."
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to verify users: ' . $e->getMessage()]);
    }
}

function handleBulkDelete() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required.']);
        return;
    }
    
    $userIds = $_POST['user_ids'] ?? '';
    
    if (empty($userIds)) {
        echo json_encode(['success' => false, 'error' => 'No users selected.']);
        return;
    }
    
    $userIdsArray = array_map('intval', explode(',', $userIds));
    $userIdsArray = array_filter($userIdsArray, function($id) { return $id > 0; });
    
    if (empty($userIdsArray)) {
        echo json_encode(['success' => false, 'error' => 'Invalid user IDs.']);
        return;
    }
    
    // Don't allow deleting yourself
    $currentUserId = getUserID();
    if (in_array($currentUserId, $userIdsArray)) {
        echo json_encode(['success' => false, 'error' => 'Cannot delete your own account.']);
        return;
    }
    
    try {
        $pdo->beginTransaction();
        
        $placeholders = str_repeat('?,', count($userIdsArray) - 1) . '?';
        
        // Get photos to delete
        $photoSql = "SELECT photo FROM users WHERE id IN ($placeholders) AND user_type != 'admin' AND photo IS NOT NULL AND photo != ''";
        $photoStmt = $pdo->prepare($photoSql);
        $photoStmt->execute($userIdsArray);
        $photos = $photoStmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Delete users (excluding admins)
        $sql = "DELETE FROM users WHERE id IN ($placeholders) AND user_type != 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($userIdsArray);
        
        $affectedRows = $stmt->rowCount();
        
        // Delete photos
        foreach ($photos as $photo) {
            if (file_exists($photo)) {
                @unlink($photo);
            }
        }
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => "$affectedRows user(s) deleted successfully."
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'Failed to delete users: ' . $e->getMessage()]);
    }
}

function handleExportUsers() {
    global $pdo;
    
    $search = trim($_GET['search'] ?? '');
    $userType = trim($_GET['user_type'] ?? '');
    $classFilter = trim($_GET['class'] ?? '');
    $verifiedFilter = trim($_GET['verified'] ?? '');
    
    // Build the WHERE clause (same as in handleGetUsers)
    $whereConditions = [];
    $params = [];
    
    if (!empty($search)) {
        $whereConditions[] = "(u.full_name LIKE :search OR u.email LIKE :search OR u.phone LIKE :search OR u.father_name LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    if (!empty($userType)) {
        $whereConditions[] = "u.user_type = :user_type";
        $params[':user_type'] = $userType;
    }
    
    if (!empty($classFilter)) {
        $whereConditions[] = "u.user_class = :class_filter";
        $params[':class_filter'] = $classFilter;
    }
    
    if ($verifiedFilter !== '') {
        $whereConditions[] = "u.verified = :verified";
        $params[':verified'] = $verifiedFilter;
    }
    
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    $sql = "SELECT 
                u.id,
                u.full_name,
                u.father_name,
                u.email,
                u.phone,
                u.dob,
                u.user_type,
                CASE WHEN u.verified = 1 THEN 'Verified' ELSE 'Unverified' END as verification_status,
                'N/A' as created_at,
                c.NAME as class_name
            FROM users u
            LEFT JOIN classes c ON u.user_class = c.ID
            $whereClause
            ORDER BY u.id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Set CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d_H-i-s') . '.csv"');
    
    // Create CSV output
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, [
        'ID',
        'Full Name',
        'Father Name',
        'Email',
        'Phone',
        'Date of Birth',
        'Class',
        'User Type',
        'Verification Status',
        'Registration Date'
    ]);
    
    // Add user data
    foreach ($users as $user) {
        fputcsv($output, [
            $user['id'],
            $user['full_name'],
            $user['father_name'],
            $user['email'],
            $user['phone'],
            $user['dob'],
            $user['class_name'] ?: 'N/A',
            ucfirst($user['user_type']),
            $user['verification_status'],
            $user['created_at']
        ]);
    }
    
    fclose($output);
    exit(); // Don't return JSON for CSV export
}

function getUserStatistics() {
    global $pdo;
    
    try {
        // Total users
        $totalStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
        $totalStmt->execute();
        $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Students count
        $studentsStmt = $pdo->prepare("SELECT COUNT(*) as students FROM users WHERE user_type = 'student'");
        $studentsStmt->execute();
        $students = $studentsStmt->fetch(PDO::FETCH_ASSOC)['students'];
        
        // Verified users
        $verifiedStmt = $pdo->prepare("SELECT COUNT(*) as verified FROM users WHERE verified = 1");
        $verifiedStmt->execute();
        $verified = $verifiedStmt->fetch(PDO::FETCH_ASSOC)['verified'];
        
        // Unverified users
        $unverifiedStmt = $pdo->prepare("SELECT COUNT(*) as unverified FROM users WHERE verified = 0");
        $unverifiedStmt->execute();
        $unverified = $unverifiedStmt->fetch(PDO::FETCH_ASSOC)['unverified'];
        
        return [
            'total' => $total,
            'students' => $students,
            'verified' => $verified,
            'unverified' => $unverified
        ];
        
    } catch (Exception $e) {
        return [
            'total' => 0,
            'students' => 0,
            'verified' => 0,
            'unverified' => 0
        ];
    }
}

function getFilteredStatistics($whereClause, $params) {
    global $pdo;
    
    try {
        $baseQuery = "FROM users u $whereClause";
        
        // Total with filters
        $totalStmt = $pdo->prepare("SELECT COUNT(*) as total $baseQuery");
        $totalStmt->execute($params);
        $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Students with filters
        $studentsParams = $params;
        $studentsWhere = $whereClause;
        if (empty($studentsWhere)) {
            $studentsWhere = "WHERE u.user_type = 'student'";
        } else {
            $studentsWhere .= " AND u.user_type = 'student'";
        }
        $studentsStmt = $pdo->prepare("SELECT COUNT(*) as students FROM users u $studentsWhere");
        $studentsStmt->execute($studentsParams);
        $students = $studentsStmt->fetch(PDO::FETCH_ASSOC)['students'];
        
        // Verified with filters
        $verifiedParams = $params;
        $verifiedWhere = $whereClause;
        if (empty($verifiedWhere)) {
            $verifiedWhere = "WHERE u.verified = 1";
        } else {
            $verifiedWhere .= " AND u.verified = 1";
        }
        $verifiedStmt = $pdo->prepare("SELECT COUNT(*) as verified FROM users u $verifiedWhere");
        $verifiedStmt->execute($verifiedParams);
        $verified = $verifiedStmt->fetch(PDO::FETCH_ASSOC)['verified'];
        
        // Unverified with filters
        $unverifiedParams = $params;
        $unverifiedWhere = $whereClause;
        if (empty($unverifiedWhere)) {
            $unverifiedWhere = "WHERE u.verified = 0";
        } else {
            $unverifiedWhere .= " AND u.verified = 0";
        }
        $unverifiedStmt = $pdo->prepare("SELECT COUNT(*) as unverified FROM users u $unverifiedWhere");
        $unverifiedStmt->execute($unverifiedParams);
        $unverified = $unverifiedStmt->fetch(PDO::FETCH_ASSOC)['unverified'];
        
        return [
            'total' => $total,
            'students' => $students,
            'verified' => $verified,
            'unverified' => $unverified
        ];
        
    } catch (Exception $e) {
        return [
            'total' => 0,
            'students' => 0,
            'verified' => 0,
            'unverified' => 0
        ];
    }
}
?>
