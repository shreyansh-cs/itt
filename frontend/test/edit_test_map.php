<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Edit test associations";
?>

<?php
require_once __DIR__.'/../../backend/db.php'; // adjust this path

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $deleteStmt = $pdo->prepare("DELETE FROM test_classes_map WHERE id = :id");
    $deleteStmt->execute([':id' => $delete_id]);

    $message = $deleteStmt->rowCount() > 0 ? "✅ Assignment deleted successfully!" : "❌ Failed to delete assignment.";
}

// Fetch all assignments (test_id, test title, class name)
$stmt = $pdo->query("
    SELECT tcm.id, t.title AS test_title, c.name AS class_name
    FROM test_classes_map tcm
    JOIN tests t ON t.test_id = tcm.test_id
    JOIN classes c ON c.ID = tcm.class_id
");
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container py-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Test-Class Assignments</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Test Title</th>
                    <th>Class Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($assignments) > 0): ?>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?= htmlspecialchars($assignment['test_title']) ?></td>
                            <td><?= htmlspecialchars($assignment['class_name']) ?></td>
                            <td>
                                <a href="edit_test_map.php?delete_id=<?= $assignment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this assignment?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No assignments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php'
?>
