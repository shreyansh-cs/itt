<?php 
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "Map test to a class";
?>
<?php 
require_once __DIR__.'/../../backend/db.php'; // adjust this path
// Fetch Tests
$stmt = $pdo->query("SELECT test_id, title FROM tests");
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Classes
$stmt = $pdo->query("SELECT ID, NAME FROM classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id = $_POST['test_id'];
    $class_id = $_POST['class_id'];

    $insert = "INSERT INTO test_classes_map (test_id, class_id) VALUES (:test_id, :class_id)";
    $stmt = $pdo->prepare($insert);
    $success = $stmt->execute([
        ':test_id' => $test_id,
        ':class_id' => $class_id
    ]);

    if ($success) {
        $message = "✅ Test successfully assigned to class!";
    } else {
        $message = "❌ Failed to assign test.";
    }
}
?>

<div class="container-fluid w-50 p-0">
    <div class="card shadow p-4">
        <h2 class="mb-4">Assign Test to Class</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="test_id" class="form-label">Select Test</label>
                <select class="form-select" id="test_id" name="test_id" required>
                    <option value="">-- Choose Test --</option>
                    <?php foreach ($tests as $test): ?>
                        <option value="<?= htmlspecialchars($test['test_id']) ?>">
                            <?= htmlspecialchars($test['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="class_id" class="form-label">Select Class</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">-- Choose Class --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= htmlspecialchars($class['ID']) ?>">
                            <?= htmlspecialchars($class['NAME']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?>