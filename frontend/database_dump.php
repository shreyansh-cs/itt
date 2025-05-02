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
</html> 