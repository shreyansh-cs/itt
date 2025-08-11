<?php 
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "Manage Tests";
?>

<?php 
require_once __DIR__.'/../../backend/db.php';

// Fetch all tests with additional details
$stmt = $pdo->query("
    SELECT t.test_id, t.title, t.duration_minutes, t.total_questions,
           COUNT(q.question_id) as uploaded_questions,
           COUNT(DISTINCT tcm.chapter_id) as mapped_chapters
    FROM tests t 
    LEFT JOIN questions q ON t.test_id = q.test_id 
    LEFT JOIN test_chapters_map tcm ON t.test_id = tcm.test_id
    GROUP BY t.test_id, t.title, t.duration_minutes, t.total_questions
    ORDER BY t.test_id DESC
");
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="../admin/index.php">Admin Panel</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Tests</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Manage Tests
                    </h4>
                    <a href="create_test.php" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Create New Test
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($tests)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No tests found. <a href="create_test.php" class="alert-link">Create your first test</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Test ID</th>
                                        <th>Title</th>
                                        <th>Duration</th>
                                        <th>Questions</th>
                                        <th>Chapters</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tests as $test): ?>
                                        <tr>
                                            <td><?= $test['test_id'] ?></td>
                                            <td><strong><?= htmlspecialchars($test['title']) ?></strong></td>
                                            <td><?= $test['duration_minutes'] ?> min</td>
                                            <td>
                                                <?= $test['uploaded_questions'] ?>/<?= $test['total_questions'] ?>
                                                <?php if ($test['uploaded_questions'] == $test['total_questions']): ?>
                                                    <span class="badge bg-success ms-1">Complete</span>
                                                <?php elseif ($test['uploaded_questions'] > 0): ?>
                                                    <span class="badge bg-warning ms-1">Partial</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger ms-1">Empty</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($test['mapped_chapters'] > 0): ?>
                                                    <span class="badge bg-info"><?= $test['mapped_chapters'] ?> chapters</span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not mapped</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($test['uploaded_questions'] == $test['total_questions'] && $test['mapped_chapters'] > 0): ?>
                                                    <span class="badge bg-success">Ready</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Setup Required</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="view_test.php?test_id=<?= $test['test_id'] ?>" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="upload_questions.php?test_id=<?= $test['test_id'] ?>" 
                                                       class="btn btn-outline-success btn-sm" 
                                                       title="Upload Questions">
                                                        <i class="fas fa-upload"></i>
                                                    </a>
                                                    <a href="map_test_to_chapter.php?test_id=<?= $test['test_id'] ?>" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="Map to Chapters">
                                                        <i class="fas fa-link"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="deleteTest(<?= $test['test_id'] ?>)" 
                                                            title="Delete Test">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= count($tests) ?></h4>
                            <p class="mb-0">Total Tests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>
                                <?php 
                                $ready_tests = array_filter($tests, function($test) {
                                    return $test['uploaded_questions'] == $test['total_questions'] && $test['mapped_chapters'] > 0;
                                });
                                echo count($ready_tests);
                                ?>
                            </h4>
                            <p class="mb-0">Ready Tests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>
                                <?php 
                                $incomplete_tests = array_filter($tests, function($test) {
                                    return $test['uploaded_questions'] < $test['total_questions'];
                                });
                                echo count($incomplete_tests);
                                ?>
                            </h4>
                            <p class="mb-0">Incomplete Tests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>
                                <?php 
                                $unmapped_tests = array_filter($tests, function($test) {
                                    return $test['mapped_chapters'] == 0;
                                });
                                echo count($unmapped_tests);
                                ?>
                            </h4>
                            <p class="mb-0">Unmapped Tests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-unlink fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteTest(testId) {
    if (confirm('Are you sure you want to delete this test? This will also delete all questions and class mappings associated with it.')) {
        fetch('delete_test.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `test_id=${testId}`
        })
        .then(response => response.text())
        .then(data => {
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    alert('Test deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting test: ' + jsonData.message);
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                alert('Error parsing server response: ' + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting test. Please try again.');
        });
    }
}
</script>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}
</style>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?>
