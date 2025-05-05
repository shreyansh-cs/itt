<?php 
include_once __DIR__.'/../session.php';
ob_start();
$title = "Take Test";
?>

<?php
require_once __DIR__.'/../../backend/db.php';

// Check if test_id provided
if (!isset($_GET['test_id'])) {
    die("Test ID not provided.");
}

$test_id = (int) $_GET['test_id'];
$user_id = getUserID(); // Get current user's ID

// Fetch test details first
$stmt = $pdo->prepare("SELECT title, duration_minutes, total_questions FROM tests WHERE test_id = :test_id");
$stmt->execute([':test_id' => $test_id]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Invalid Test ID.");
}

// Check if user has already started the test
$stmt = $pdo->prepare("SELECT * FROM test_sessions WHERE user_id = :user_id AND test_id = :test_id LIMIT 1");
$stmt->execute([':user_id' => $user_id, ':test_id' => $test_id]);
$existing_test = $stmt->fetch(PDO::FETCH_ASSOC);

$total_duration = $test['duration_minutes'] * 60; // Total duration in seconds

// If test not started, create initial record in test_sessions
if (!$existing_test) {
    $current_time = time();
    $formatted_time = date('Y-m-d H:i:s', $current_time);
    $stmt = $pdo->prepare("INSERT INTO test_sessions (user_id, test_id, start_time, status) VALUES (:user_id, :test_id, :start_time, 'in_progress')");
    $stmt->execute([
        ':user_id' => $user_id,
        ':test_id' => $test_id,
        ':start_time' => $formatted_time
    ]);
    $start_time = $formatted_time;
} else {
    // Check if test is already completed or expired
    if ($existing_test['status'] !== 'in_progress') {
        die("This test has already been completed or expired.");
    }
    $start_time = $existing_test['start_time'];
}

// Calculate remaining time based on start_time
$start_timestamp = strtotime($start_time);
$current_timestamp = time();
$elapsed_seconds = $current_timestamp - $start_timestamp;
$time_remaining = max(0, $total_duration - $elapsed_seconds);

// If time has expired, update status
if ($time_remaining <= 0) {
    // Update status to expired using update_test_status.php
    $ch = curl_init('update_test_status.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'test_id' => $test_id,
        'status' => 'expired'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    die("Test time has expired.");
}

// Pass PHP timestamps to JavaScript
$start_timestamp_ms = $start_timestamp * 1000; // Convert to milliseconds for JavaScript
$current_timestamp_ms = $current_timestamp * 1000; // Convert to milliseconds for JavaScript

// Fetch Questions
$stmt = $pdo->prepare("SELECT question_id as id, question_text, option_a, option_b, option_c, option_d FROM questions WHERE test_id = :test_id LIMIT :total_questions");
$stmt->bindValue(':test_id', $test_id, PDO::PARAM_INT);
$stmt->bindValue(':total_questions', $test['total_questions'], PDO::PARAM_INT);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_questions = $test['total_questions'];
$duration = $test['duration_minutes'] ?? 60; // Default to 60 minutes if not set

// Fetch existing answers
$stmt = $pdo->prepare("SELECT question_id, selected_option FROM user_answers WHERE user_id = :user_id AND test_id = :test_id");
$stmt->execute([':user_id' => $user_id, ':test_id' => $test_id]);
$existing_answers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="container-fluid p-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= htmlspecialchars($test['title']) ?></h5>
            <div class="timer-container">
                <span class="badge bg-danger p-2">
                    <i class="fas fa-clock me-1"></i>
                    <span id="timer">00:00:00</span>
                </span>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Test Instructions -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading">Test Instructions:</h6>
                <ul class="mb-0">
                    <li>Total Duration: <?= $duration ?> minutes</li>
                    <li>Total Questions: <?= $total_questions ?></li>
                    <li>Each question carries equal marks</li>
                    <li>No negative marking</li>
                    <li>You cannot go back to previous questions</li>
                </ul>
            </div>

            <!-- Question Navigation -->
            <div class="question-navigation mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <?php for($i = 1; $i <= $total_questions; $i++): ?>
                        <button class="btn btn-outline-primary question-btn" data-question="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Question Container -->
            <form action="submit_test.php" method="POST" id="testForm">
                <input type="hidden" name="test_id" value="<?= $test_id ?>">
                
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question mb-4" id="question-<?= $index + 1 ?>" style="display: <?= $index === 0 ? 'block' : 'none' ?>">
                        <h5 class="question-text"><?= ($index + 1) ?>. <?= htmlspecialchars($question['question_text']) ?></h5>
                        <div class="options mt-3">
                            <?php
                            $options = ['A', 'B', 'C', 'D'];
                            foreach ($options as $option):
                                $option_value = strtolower($option);
                                $is_checked = isset($existing_answers[$question['id']]) && $existing_answers[$question['id']] === $option;
                            ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" 
                                       name="answers[<?= $question['id'] ?>]" 
                                       value="<?= $option ?>" 
                                       id="q<?= $question['id'] ?><?= $option_value ?>"
                                       <?= $is_checked ? 'checked' : '' ?>
                                       onchange="saveAnswer(<?= $question['id'] ?>, '<?= $option ?>')">
                                <label class="form-check-label" for="q<?= $question['id'] ?><?= $option_value ?>">
                                    <?= htmlspecialchars($question['option_' . $option_value]) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="prevBtn" disabled>
                        <i class="fas fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn">
                        Next <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Submit Button -->
        <div class="card-footer">
            <button class="btn btn-success w-100" id="submitBtn" data-bs-toggle="modal" data-bs-target="#submitModal">
                <i class="fas fa-check-circle me-1"></i> Submit Test
            </button>
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit the test? You won't be able to make any changes after submission.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <span id="unansweredCount">0</span> questions are unanswered.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmit">Submit Test</button>
            </div>
        </div>
    </div>
</div>

<script>
// Timer functionality
const timerElement = document.getElementById('timer');
const serverStartTime = <?= $start_timestamp_ms ?>; // PHP timestamp in milliseconds
const serverCurrentTime = <?= $current_timestamp_ms ?>; // PHP timestamp in milliseconds
const totalDuration = <?= $total_duration ?> * 1000; // Convert to milliseconds
let timeLeft = Math.max(0, totalDuration - (serverCurrentTime - serverStartTime));

// Function to save all answers
function saveAllAnswers() {
    const answers = {};
    document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
        const questionId = input.name.match(/\[(\d+)\]/)[1];
        answers[questionId] = input.value;
    });

    // Save answers to server
    fetch('save_answer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            test_id: <?= $test_id ?>,
            answers: answers
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Error saving answers:', data.message);
        }
    })
    .catch(error => {
        console.error('Error saving answers:', error);
    });
}

// Save answers every 30 seconds
setInterval(saveAllAnswers, 30000);

// Save answers when user leaves the page
window.addEventListener('beforeunload', function(e) {
    if (timeLeft > 0) {
        e.preventDefault();
        e.returnValue = '';
        saveAllAnswers();
    }
});

function updateTimer() {
    const now = new Date().getTime();
    const elapsed = now - serverStartTime;
    timeLeft = Math.max(0, totalDuration - elapsed);
    
    const hours = Math.floor(timeLeft / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    
    timerElement.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        // Save all answers one final time before submitting
        saveAllAnswers();
        // Update status to expired
        fetch('update_test_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `test_id=<?= $test_id ?>&status=expired`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                submitTest();
            } else {
                alert('Error updating test status: ' + data.message);
            }
        });
    }
}

const timerInterval = setInterval(updateTimer, 1000);

// Save answer to database
function saveAnswer(questionId, option) {
    fetch('save_answer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `test_id=<?= $test_id ?>&question_id=${questionId}&selected_option=${option}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Error saving answer:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Question navigation
const questionBtns = document.querySelectorAll('.question-btn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
let currentQuestion = 1;

function showQuestion(num) {
    document.querySelectorAll('.question').forEach(q => q.style.display = 'none');
    document.getElementById(`question-${num}`).style.display = 'block';
}

function updateNavigation() {
    prevBtn.disabled = currentQuestion === 1;
    nextBtn.disabled = currentQuestion === <?= $total_questions ?>;
    
    questionBtns.forEach(btn => {
        const questionNum = parseInt(btn.dataset.question);
        btn.classList.remove('btn-primary', 'btn-outline-primary');
        if (questionNum === currentQuestion) {
            btn.classList.add('btn-primary');
        } else {
            btn.classList.add('btn-outline-primary');
        }
    });

    showQuestion(currentQuestion);
}

questionBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        currentQuestion = parseInt(btn.dataset.question);
        updateNavigation();
    });
});

prevBtn.addEventListener('click', () => {
    if (currentQuestion > 1) {
        currentQuestion--;
        updateNavigation();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentQuestion < <?= $total_questions ?>) {
        currentQuestion++;
        updateNavigation();
    }
});

// Submit functionality
function submitTest() {
    // Update test session status to completed
    fetch('update_test_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `test_id=<?= $test_id ?>&status=completed`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'test_analysis.php?test_id=<?= $test_id ?>';
        } else {
            alert('Error submitting test: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting test. Please try again.');
    });
}

document.getElementById('confirmSubmit').addEventListener('click', submitTest);

// Update unanswered count
function updateUnansweredCount() {
    const questions = document.querySelectorAll('.question');
    let unanswered = 0;
    
    questions.forEach(question => {
        const checkedOption = question.querySelector('input[type="radio"]:checked');
        if (!checkedOption) {
            unanswered++;
        }
    });
    
    document.getElementById('unansweredCount').textContent = unanswered;
}

// Update count when modal is shown
document.getElementById('submitModal').addEventListener('show.bs.modal', updateUnansweredCount);

// Initialize navigation
updateNavigation();
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php'
?>
