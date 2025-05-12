<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "View Questions";
require __DIR__.'/../../backend/db.php';
require_once __DIR__.'/../../frontend/restrictedpage.php';

// Fetch all tests for dropdown
$tests = $pdo->query("SELECT test_id, title FROM tests")->fetchAll(PDO::FETCH_ASSOC);

$selected_test_id = $_GET['test_id'] ?? null;
$questions = [];
$test = [];

if ($selected_test_id) {
    // Get test details including total questions allowed
    $stmt = $pdo->prepare("SELECT t.test_id, t.title, t.total_questions, COUNT(q.question_id) as questions_uploaded
                          FROM tests t 
                          LEFT JOIN questions q ON t.test_id = q.test_id 
                          WHERE t.test_id = ?
                          GROUP BY t.test_id, t.title, t.total_questions");
    //echo $stmt->queryString;
    $stmt->execute([$selected_test_id]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get questions
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE test_id = ?");
    $stmt->execute([$selected_test_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//echo print_r($test, true);
?>

<div class="container">
    <div class="card shadow p-4">
        <h3 class="mb-4">View Uploaded Questions</h3>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <label class="input-group-text" for="test_id">Select Test</label>
                <select class="form-select" name="test_id" id="test_id" onchange="this.form.submit()">
                    <option value="">-- Choose Test --</option>
                    <?php foreach ($tests as $one_test): ?>
                        <option value="<?= $one_test['test_id'] ?>" <?= ($selected_test_id == $one_test['test_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($one_test['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
            
        <?php if ($selected_test_id && $test): ?>
            <div class="alert <?= $test['questions_uploaded'] >= $test['total_questions'] ? 'alert-success' : 'alert-warning' ?> mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Questions Status:</strong> 
                        <?= $test['questions_uploaded'] ?> / <?= $test['total_questions'] ?> questions uploaded
                    </div>
                    <?php if ($test['questions_uploaded'] < $test['total_questions']): ?>
                        <a href="upload_questions.php?test_id=<?= $selected_test_id ?>" class="btn btn-primary btn-sm">
                            Add More Questions
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (count($questions) > 0): ?>
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