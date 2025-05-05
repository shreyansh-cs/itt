<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "View Questions";
require __DIR__.'/../../backend/db.php';

// Fetch all tests for dropdown
$tests = $pdo->query("SELECT test_id, title FROM tests")->fetchAll(PDO::FETCH_ASSOC);

$selected_test_id = $_GET['test_id'] ?? null;
$questions = [];

if ($selected_test_id) {
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE test_id = ?");
    $stmt->execute([$selected_test_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container">
    <div class="card shadow p-4">
        <h3 class="mb-4">View Uploaded Questions</h3>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <label class="input-group-text" for="test_id">Select Test</label>
                <select class="form-select" name="test_id" id="test_id" onchange="this.form.submit()">
                    <option value="">-- Choose Test --</option>
                    <?php foreach ($tests as $test): ?>
                        <option value="<?= $test['test_id'] ?>" <?= ($selected_test_id == $test['test_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($test['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($selected_test_id && count($questions) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>Correct</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $index => $q): ?>
                        <tr id="question-row-<?= $q['question_id'] ?>">
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($q['question_text']) ?></td>
                            <td><?= htmlspecialchars($q['option_a']) ?></td>
                            <td><?= htmlspecialchars($q['option_b']) ?></td>
                            <td><?= htmlspecialchars($q['option_c']) ?></td>
                            <td><?= htmlspecialchars($q['option_d']) ?></td>
                            <td><strong><?= $q['correct_option'] ?></strong></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deleteQuestion(<?= $q['question_id'] ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($selected_test_id): ?>
            <div class="alert alert-warning">No questions found for this test.</div>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question?')) {
        fetch('delete_question.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'question_id=' + questionId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                document.getElementById('question-row-' + questionId).remove();
                alert('Question deleted successfully');
            } else {
                alert(data.message || 'Error deleting question');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting question');
        });
    }
}
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?>