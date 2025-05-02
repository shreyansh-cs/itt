<?php
require_once '../backend/db.php';
require_once '../backend/public_utils.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    die('Access denied. Admin login required.');
}

// Function to generate database dump
function generateDatabaseDump() {
    global $servername, $username, $db_password, $dbname;
    
    $filename = "database_dump_" . date('Y-m-d_H-i-s') . ".sql";
    $command = "mysqldump --host={$servername} --user={$username} --password={$db_password} {$dbname} > {$filename}";
    
    exec($command, $output, $return_var);
    
    if ($return_var === 0) {
        return $filename;
    } else {
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = generateDatabaseDump();
    
    if ($filename) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);
        unlink($filename); // Delete the file after download
        exit;
    } else {
        $error = "Failed to generate database dump. Please check your server configuration.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Dump Generator</title>
</head>
<body>
    <h1>Database Dump Generator</h1>
    
    <?php if (isset($error)): ?>
        <div><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <button type="submit">Generate Database Dump</button>
    </form>
</body>
</html> 