<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Edit test associations";
?>

<?php
require_once __DIR__.'/../../backend/db.php'; // Adjust your db connection file

// Check if test_id provided
if (!isset($_GET['test_id'])) {
    die("Test ID not provided.");
}

$test_id = (int) $_GET['test_id'];

// Fetch test title
$stmt = $pdo->prepare("SELECT title FROM tests WHERE test_id = :test_id");
$stmt->execute([':test_id' => $test_id]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Invalid Test ID.");
}

// Fetch Questions
$stmt = $pdo->prepare("SELECT question_id as id, question_text, option_a, option_b, option_c, option_d FROM questions WHERE test_id = :test_id");
$stmt->execute([':test_id' => $test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container py-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Test: <?= htmlspecialchars($test['title']) ?></h2>

        <form action="submit_test.php" method="POST">
            <input type="hidden" name="test_id" value="<?= $test_id ?>">

            <?php foreach ($questions as $index => $question): ?>
                <div class="mb-5">
                    <h5 class="mb-3"><?= ($index + 1) ?>. <?= htmlspecialchars($question['question_text']) ?></h5>

                    <div class="ms-4">
                        <div class="form-check d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="radio" name="answers[<?= $question['id'] ?>]" value="A" id="q<?= $question['id'] ?>a" required>
                            <label class="form-check-label" for="q<?= $question['id'] ?>a">
                                <?= htmlspecialchars($question['option_a']) ?>
                            </label>
                        </div>

                        <div class="form-check d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="radio" name="answers[<?= $question['id'] ?>]" value="B" id="q<?= $question['id'] ?>b" required>
                            <label class="form-check-label" for="q<?= $question['id'] ?>b">
                                <?= htmlspecialchars($question['option_b']) ?>
                            </label>
                        </div>

                        <div class="form-check d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="radio" name="answers[<?= $question['id'] ?>]" value="C" id="q<?= $question['id'] ?>c" required>
                            <label class="form-check-label" for="q<?= $question['id'] ?>c">
                                <?= htmlspecialchars($question['option_c']) ?>
                            </label>
                        </div>

                        <div class="form-check d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="radio" name="answers[<?= $question['id'] ?>]" value="D" id="q<?= $question['id'] ?>d" required>
                            <label class="form-check-label" for="q<?= $question['id'] ?>d">
                                <?= htmlspecialchars($question['option_d']) ?>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5">Submit Test</button>
            </div>
        </form>

    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php'
?>
