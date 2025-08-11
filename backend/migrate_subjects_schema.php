<?php
/**
 * Database migration script to update subjects table schema
 * This script adds CLASS_ID field to subjects table and migrates existing data
 */

require_once __DIR__.'/db.php';

echo "Starting subjects schema migration...\n";

try {
    // Check if CLASS_ID column already exists in subjects table
    $stmt = $pdo->prepare("SHOW COLUMNS FROM subjects LIKE 'CLASS_ID'");
    $stmt->execute();
    $columnExists = $stmt->fetch() !== false;
    
    if (!$columnExists) {
        echo "Adding CLASS_ID column to subjects table...\n";
        
        // Add CLASS_ID column to subjects table
        $stmt = $pdo->prepare("ALTER TABLE subjects ADD COLUMN CLASS_ID INT NOT NULL DEFAULT 0 AFTER NAME");
        $stmt->execute();
        
        echo "CLASS_ID column added successfully.\n";
        
        // Migrate existing subjects to have proper CLASS_ID based on streamubjectmap
        echo "Migrating existing subject data...\n";
        
        $stmt = $pdo->prepare("
            UPDATE subjects s 
            SET CLASS_ID = (
                SELECT DISTINCT str.CLASS_ID 
                FROM streamubjectmap som 
                JOIN streams str ON som.STREAM_ID = str.ID 
                WHERE som.SUBJECT_ID = s.ID 
                LIMIT 1
            )
            WHERE s.CLASS_ID = 0
        ");
        $stmt->execute();
        
        $affected = $stmt->rowCount();
        echo "Updated {$affected} subjects with proper CLASS_ID.\n";
        
        // Set any remaining subjects with CLASS_ID = 0 to a default class (first available class)
        $stmt = $pdo->prepare("SELECT ID FROM classes ORDER BY ID LIMIT 1");
        $stmt->execute();
        $defaultClass = $stmt->fetchColumn();
        
        if ($defaultClass) {
            $stmt = $pdo->prepare("UPDATE subjects SET CLASS_ID = ? WHERE CLASS_ID = 0");
            $stmt->execute([$defaultClass]);
            
            $affected = $stmt->rowCount();
            if ($affected > 0) {
                echo "Set {$affected} remaining subjects to default class {$defaultClass}.\n";
            }
        }
        
        // Now make CLASS_ID NOT NULL and remove the default
        echo "Making CLASS_ID NOT NULL...\n";
        $stmt = $pdo->prepare("ALTER TABLE subjects MODIFY COLUMN CLASS_ID INT NOT NULL");
        $stmt->execute();
        
        echo "Schema migration completed successfully!\n";
        
    } else {
        echo "CLASS_ID column already exists in subjects table. No migration needed.\n";
    }
    
    // Verify the migration
    echo "\nVerifying migration...\n";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM subjects");
    $stmt->execute();
    $totalSubjects = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as with_class FROM subjects WHERE CLASS_ID > 0");
    $stmt->execute();
    $subjectsWithClass = $stmt->fetch(PDO::FETCH_ASSOC)['with_class'];
    
    echo "Total subjects: {$totalSubjects}\n";
    echo "Subjects with CLASS_ID: {$subjectsWithClass}\n";
    
    if ($totalSubjects == $subjectsWithClass) {
        echo "✅ Migration verification successful!\n";
    } else {
        echo "⚠️  Warning: Some subjects still don't have CLASS_ID set.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nMigration completed.\n";
?>

