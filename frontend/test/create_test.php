<?php 
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "Create New Test";
?>
<?php 
require_once __DIR__.'/../../backend/db.php';

$message = '';
$title = '';
$duration_minutes = '';
$total_questions = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $duration_minutes = $_POST['duration_minutes'];
    $total_questions = $_POST['total_questions'];
    $ok = true;
    //validate title
    if (empty($title)) {
        $message = "❌ Please provide a test title.";
        $ok = false;
    }

    //validate duration
    if ($ok and (empty($duration_minutes) or !is_numeric($duration_minutes) or $duration_minutes < 1)) {
        $message = "❌ Please provide a valid duration.";
        $ok = false;
    }

    //validate total questions
    if ($ok and (empty($total_questions) or !is_numeric($total_questions) or $total_questions < 1)) {
        $message = "❌ Please provide a valid total questions.";
        $ok = false;
    }   

    if ($ok) {
        $insert = "INSERT INTO tests (title, duration_minutes, total_questions) 
                  VALUES (:title, :duration_minutes, :total_questions)";
        $stmt = $pdo->prepare($insert);
        $success = $stmt->execute([
            ':title' => $title,
            ':duration_minutes' => $duration_minutes,
            ':total_questions' => $total_questions
        ]);

        if ($success) {
            $message = "✅ Test created successfully!";
        } else {
            $message = "❌ Failed to create test.";
        }
    } 
}
?>

<div class="container-fluid w-50 p-0">
    <div class="card shadow p-4">
        <h2 class="mb-4">Create New Test</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Test Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($title) ?>" >
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Duration (in minutes)</label>
                <input type="number" class="form-control" id="duration" name="duration_minutes" min="1" value="<?= htmlspecialchars($duration_minutes) ?>">
            </div>

            <div class="mb-3">
                <label for="total_questions" class="form-label">Total Questions</label>
                <input type="number" class="form-control" id="total_questions" name="total_questions" min="1" value="<?= htmlspecialchars($total_questions) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Create Test</button>
        </form>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?> 