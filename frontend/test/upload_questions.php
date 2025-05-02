<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Online Test";
include_once __DIR__.'/testshandler.php';
include_once __DIR__.'/../restrictedpage.php'; // only for admin page

$tests = getTests($error);
?>
<?php 
    if(!empty($mesg)){
        echo '<div class="alert alert-success w-50 mx-auto" role="alert">';
        echo $mesg;
        echo "</div>";
    }

    if(!empty($error)){
        echo '<div class="alert alert-danger w-50 mx-auto" role="alert">';
        echo $mesg;
        echo "</div>";
    }
?>
<div class="container-fluid">
    <div class="card shadow p-4">
        <h3 class="mb-4">Add New Question</h3>
        <form action="" method="POST">
            
        <div class="row g-2">
            <div class="col-md-9">
                <select class="form-select" id="test_id" name="test_id" required>
                    <option value="">-- Choose Test --</option>
                    <?php foreach ($tests as $test): ?>
                        <option value="<?= $test['test_id'] ?>" <?= ($test_id == $test['test_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($test['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <a href="#" id="viewLink" class="btn btn-outline-primary w-100">View Questions</a>
            </div>
        </div>

            <div class="mb-3">
                <label for="question_text" class="form-label">Question</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="3" required><?php echo $question; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Options</label>
                <input type="text" class="form-control mb-2" name="option_a" value="<?php echo $optA; ?>" placeholder="Option A" required>
                <input type="text" class="form-control mb-2" name="option_b" value="<?php echo $optB; ?>" placeholder="Option B" required>
                <input type="text" class="form-control mb-2" name="option_c" value="<?php echo $optC; ?>" placeholder="Option C" required>
                <input type="text" class="form-control mb-2" name="option_d" value="<?php echo $optD; ?>" placeholder="Option D" required>
            </div>

            <div class="mb-4">
                <label for="correct_option" class="form-label">Correct Option</label>
                <select class="form-select" id="correct_option" name="correct_option" required>
                    <option value="">-- Select Correct Option --</option>
                    <option value="A" <?php echo selected($correct,'A'); ?>>A</option>
                    <option value="B" <?php echo selected($correct,'B'); ?>>B</option>
                    <option value="C" <?php echo selected($correct,'C'); ?>>C</option>
                    <option value="D" <?php echo selected($correct,'D'); ?>>D</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Upload Question</button>
        </form>
    </div>
</div>
<script>
document.getElementById('test_id').addEventListener('change', function() {
    const testId = this.value;
    const viewLink = document.getElementById('viewLink');
    if (testId) {
        viewLink.href = 'view_questions.php?test_id=' + testId;
        viewLink.classList.remove('disabled');
        viewLink.target = '_blank';
    } else {
        viewLink.href = '#';
        viewLink.classList.add('disabled');
    }
});
</script>
<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?>