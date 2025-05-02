<?php
require_once '../backend/db.php';
require_once '../backend/public_utils.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    die('Access denied. Admin login required.');
}

// Function to generate database dump
function generateDatabaseDump() {
    global $pdo;
    
    $filename = "database_dump_" . date('Y-m-d_H-i-s') . ".sql";
    $sql_content = "-- Database Dump\n";
    $sql_content .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Disable foreign key checks at the beginning
    $sql_content .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    try {
        // Get all tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $create_statements = [];
        $data_statements = [];
        $fk_constraints = [];
        
        foreach ($tables as $table) {
            // Get table structure
            $create_table = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
            $create_sql = $create_table['Create Table'];
            
            // Extract foreign key constraints
            if (preg_match_all('/CONSTRAINT `([^`]+)` FOREIGN KEY \(`([^`]+)`\) REFERENCES `([^`]+)` \(`([^`]+)`\)(?: ON DELETE (RESTRICT|CASCADE|SET NULL|NO ACTION|SET DEFAULT))?(?: ON UPDATE (RESTRICT|CASCADE|SET NULL|NO ACTION|SET DEFAULT))?/', $create_sql, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $constraint_name = $match[1];
                    $column = $match[2];
                    $ref_table = $match[3];
                    $ref_column = $match[4];
                    $on_delete = isset($match[5]) ? " ON DELETE " . $match[5] : "";
                    $on_update = isset($match[6]) ? " ON UPDATE " . $match[6] : "";
                    
                    $fk_constraints[] = "ALTER TABLE `$table` ADD CONSTRAINT `$constraint_name` FOREIGN KEY (`$column`) REFERENCES `$ref_table` (`$ref_column`)$on_delete$on_update;";
                }
                
                // Remove foreign key constraints from create statement
                $create_sql = preg_replace('/,\s*CONSTRAINT `[^`]+` FOREIGN KEY \(`[^`]+`\) REFERENCES `[^`]+` \(`[^`]+`\)(?: ON DELETE (?:RESTRICT|CASCADE|SET NULL|NO ACTION|SET DEFAULT))?(?: ON UPDATE (?:RESTRICT|CASCADE|SET NULL|NO ACTION|SET DEFAULT))?/', '', $create_sql);
            }
            
            $create_statements[] = "-- Table structure for table `$table`\n";
            $create_statements[] = "DROP TABLE IF EXISTS `$table`;\n";
            $create_statements[] = $create_sql . ";\n\n";
            
            // Get table data
            $data_statements[] = "-- Dumping data for table `$table`\n";
            $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($rows)) {
                $columns = array_keys($rows[0]);
                $data_statements[] = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";
                
                $values = [];
                foreach ($rows as $row) {
                    $row_values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $row_values[] = 'NULL';
                        } else {
                            $row_values[] = $pdo->quote($value);
                        }
                    }
                    $values[] = "(" . implode(', ', $row_values) . ")";
                }
                $data_statements[] = implode(",\n", $values) . ";\n\n";
            }
        }
        
        // Combine all statements in the correct order
        $sql_content .= implode("", $create_statements);
        $sql_content .= implode("", $data_statements);
        
        // Add foreign key constraints at the end
        if (!empty($fk_constraints)) {
            $sql_content .= "-- Adding foreign key constraints\n";
            $sql_content .= implode("\n", $fk_constraints) . "\n\n";
        }
        
        // Re-enable foreign key checks
        $sql_content .= "SET FOREIGN_KEY_CHECKS=1;\n";
        
        // Write to file
        if (file_put_contents($filename, $sql_content) !== false) {
            return $filename;
        }
    } catch (PDOException $e) {
        return false;
    }
    
    return false;
}

// Function to create uploads folder backup
function createUploadsBackup() {
    $uploads_dir = "../../uploads";
    $backup_dir = "uploads_backup_" . date('Y-m-d_H-i-s');
    
    if (!is_dir($uploads_dir)) {
        return false;
    }
    
    // Create backup directory
    if (!mkdir($backup_dir)) {
        return false;
    }
    
    // Copy all files and directories recursively
    $dir = new RecursiveDirectoryIterator($uploads_dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($files as $file) {
        $target = $backup_dir . DIRECTORY_SEPARATOR . $files->getSubPathName();
        if ($file->isDir()) {
            mkdir($target);
        } else {
            copy($file, $target);
        }
    }
    
    // Create zip file
    $zip = new ZipArchive();
    $zipname = $backup_dir . '.zip';
    
    if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($backup_dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($backup_dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        
        $zip->close();
        
        // Clean up the temporary directory
        array_map('unlink', glob("$backup_dir/*.*"));
        rmdir($backup_dir);
        
        return $zipname;
    }
    
    return false;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $download_files = [];
    
    // Generate database dump if requested
    if (isset($_POST['dump_database']) && $_POST['dump_database'] === '1') {
        $db_file = generateDatabaseDump();
        if ($db_file) {
            $download_files[] = $db_file;
        } else {
            $error = "Failed to generate database dump.";
        }
    }
    
    // Create uploads backup if requested
    if (isset($_POST['dump_uploads']) && $_POST['dump_uploads'] === '1') {
        $uploads_file = createUploadsBackup();
        if ($uploads_file) {
            $download_files[] = $uploads_file;
        } else {
            $error = "Failed to create uploads backup.";
        }
    }
    
    // If only one file, download it directly
    if (count($download_files) === 1) {
        $file = $download_files[0];
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($file);
        unlink($file);
        exit;
    }
    // If multiple files, create a zip
    elseif (count($download_files) > 1) {
        $zipname = "backup_" . date('Y-m-d_H-i-s') . ".zip";
        $zip = new ZipArchive();
        
        if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
            foreach ($download_files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
            
            // Download the zip file
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipname . '"');
            readfile($zipname);
            
            // Clean up
            unlink($zipname);
            foreach ($download_files as $file) {
                unlink($file);
            }
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Backup Generator</title>
</head>
<body>
    <h1>Backup Generator</h1>
    
    <?php if (isset($error)): ?>
        <div><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <input type="checkbox" id="dump_database" name="dump_database" value="1" checked>
            <label for="dump_database">Dump Database</label>
        </div>
        <div>
            <input type="checkbox" id="dump_uploads" name="dump_uploads" value="1">
            <label for="dump_uploads">Backup Uploads Folder</label>
        </div>
        <button type="submit">Generate Backup</button>
    </form>
</body>
</html> 