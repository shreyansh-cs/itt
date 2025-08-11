<?php 
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../restrictedpage.php';
ob_start();
$title = "Map Test to Chapter";
?>
<?php 
require_once __DIR__.'/../../backend/db.php';
require_once __DIR__.'/../../backend/utils.php';

// Fetch Tests
$stmt = $pdo->query("SELECT test_id, title FROM tests ORDER BY title");
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
$message = '';
$messageType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id = $_POST['test_id'];
    $chapter_id = $_POST['chapter_id'];

    try {
        // Check if mapping already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM test_chapters_map WHERE test_id = :test_id AND chapter_id = :chapter_id");
        $checkStmt->execute([':test_id' => $test_id, ':chapter_id' => $chapter_id]);
        
        if ($checkStmt->fetchColumn() > 0) {
            $message = "❌ This test is already mapped to the selected chapter!";
            $messageType = 'warning';
        } else {
            $insert = "INSERT INTO test_chapters_map (test_id, chapter_id) VALUES (:test_id, :chapter_id)";
            $stmt = $pdo->prepare($insert);
            $success = $stmt->execute([
                ':test_id' => $test_id,
                ':chapter_id' => $chapter_id
            ]);

            if ($success) {
                $message = "✅ Test successfully assigned to chapter!";
                $messageType = 'success';
            } else {
                $message = "❌ Failed to assign test.";
                $messageType = 'danger';
            }
        }
    } catch (PDOException $e) {
        $message = "❌ Database error: " . $e->getMessage();
        $messageType = 'danger';
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="../admin/index.php">Admin Panel</a></li>
                    <li class="breadcrumb-item"><a href="list_tests.php">Tests</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Map to Chapter</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-sitemap me-2"></i>Map Test to Chapter
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="mappingForm">
                        <!-- Test Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Step 1: Select Test</h6>
                                    </div>
                                    <div class="card-body">
                                        <label for="test_id" class="form-label">Choose Test <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg" id="test_id" name="test_id" required>
                                            <option value="">-- Select a Test --</option>
                                            <?php foreach ($tests as $test): ?>
                                                <option value="<?= htmlspecialchars($test['test_id']) ?>">
                                                    <?= htmlspecialchars($test['title']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hierarchical Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Step 2: Navigate to Chapter</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- Class Selection -->
                                            <div class="col-md-6 col-lg-4">
                                                <label for="class_id" class="form-label">
                                                    <i class="fas fa-school me-1"></i>Class <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="class_id" required>
                                                    <option value="">-- Select Class --</option>
                                                    <?php
                                                    $classes = getAllClasses();
                                                    foreach ($classes as $class_row) {
                                                        echo "<option value='{$class_row['ID']}'>" . htmlspecialchars($class_row['NAME']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <div class="form-text">Choose the class level</div>
                                            </div>

                                            <!-- Stream Selection -->
                                            <div class="col-md-6 col-lg-4">
                                                <label for="stream_id" class="form-label">
                                                    <i class="fas fa-stream me-1"></i>Stream <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="stream_id" disabled required>
                                                    <option value="">-- Select Stream --</option>
                                                </select>
                                                <div class="form-text">Choose the stream</div>
                                            </div>

                                            <!-- Subject Selection -->
                                            <div class="col-md-6 col-lg-4">
                                                <label for="subject_id" class="form-label">
                                                    <i class="fas fa-book me-1"></i>Subject <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="subject_id" disabled required>
                                                    <option value="">-- Select Subject --</option>
                                                </select>
                                                <div class="form-text">Choose the subject</div>
                                            </div>

                                            <!-- Section Selection -->
                                            <div class="col-md-6 col-lg-4">
                                                <label for="section_id" class="form-label">
                                                    <i class="fas fa-bookmark me-1"></i>Section <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="section_id" disabled required>
                                                    <option value="">-- Select Section --</option>
                                                </select>
                                                <div class="form-text">Choose the section</div>
                                            </div>

                                            <!-- Chapter Selection -->
                                            <div class="col-md-6 col-lg-4">
                                                <label for="chapter_id" class="form-label">
                                                    <i class="fas fa-file-alt me-1"></i>Chapter <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="chapter_id" name="chapter_id" disabled required>
                                                    <option value="">-- Select Chapter --</option>
                                                </select>
                                                <div class="form-text">Choose the final chapter</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selection Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Selection Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="selectionPath" class="alert alert-light mb-0">
                                            <i class="fas fa-arrow-right me-2"></i>
                                            <span class="text-muted">Please make your selections above to see the path...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="list_tests.php" class="btn btn-secondary btn-lg me-md-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-link me-1"></i>Map Test to Chapter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Mappings -->
    <div class="row justify-content-center mt-4">
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Test-Chapter Mappings</h5>
                    <a href="edit_test_chapter_map.php" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i>Manage All Mappings
                    </a>
                </div>
                <div class="card-body">
                    <?php
                    // Fetch recent mappings for display
                    $stmt = $pdo->query("
                        SELECT tcm.id, t.title AS test_title, 
                               c.NAME as chapter_name,
                               s.NAME as section_name,
                               sub.NAME as subject_name,
                               str.NAME as stream_name,
                               cl.NAME as class_name,
                               tcm.created_at
                        FROM test_chapters_map tcm
                        JOIN tests t ON t.test_id = tcm.test_id
                        JOIN chapters c ON c.ID = tcm.chapter_id
                        JOIN sections s ON c.SECTION_ID = s.ID
                        JOIN subjects sub ON s.SUBJECT_ID = sub.ID
                        JOIN streams str ON sub.STREAM_ID = str.ID
                        JOIN classes cl ON str.CLASS_ID = cl.ID
                        ORDER BY tcm.created_at DESC
                        LIMIT 5
                    ");
                    $mappings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    
                    <?php if (empty($mappings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No test-chapter mappings found. Create your first mapping above.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Test</th>
                                        <th>Chapter Path</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mappings as $mapping): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($mapping['test_title']) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($mapping['class_name']) ?> → 
                                                    <?= htmlspecialchars($mapping['stream_name']) ?> → 
                                                    <?= htmlspecialchars($mapping['subject_name']) ?> → 
                                                    <?= htmlspecialchars($mapping['section_name']) ?> → 
                                                    <strong><?= htmlspecialchars($mapping['chapter_name']) ?></strong>
                                                </small>
                                            </td>
                                            <td>
                                                <small><?= date('M j, Y g:i A', strtotime($mapping['created_at'])) ?></small>
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

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Selection tracking
    let selections = {
        test: '',
        class: '',
        stream: '',
        subject: '',
        section: '',
        chapter: ''
    };
    
    // Event listeners
    document.getElementById('class_id').addEventListener('change', function() {
        const classId = this.value;
        selections.class = this.options[this.selectedIndex].text;
        
        if (classId) {
            loadStreams(classId);
            resetDropdowns(['stream', 'subject', 'section', 'chapter']);
        } else {
            resetDropdowns(['stream', 'subject', 'section', 'chapter']);
        }
        updateSelectionPath();
    });
    
    document.getElementById('stream_id').addEventListener('change', function() {
        const streamId = this.value;
        const classId = document.getElementById('class_id').value;
        selections.stream = this.options[this.selectedIndex].text;
        
        if (streamId && classId) {
            loadSubjects(classId, streamId);
            resetDropdowns(['subject', 'section', 'chapter']);
        } else {
            resetDropdowns(['subject', 'section', 'chapter']);
        }
        updateSelectionPath();
    });
    
    document.getElementById('subject_id').addEventListener('change', function() {
        const subjectId = this.value;
        const classId = document.getElementById('class_id').value;
        const streamId = document.getElementById('stream_id').value;
        selections.subject = this.options[this.selectedIndex].text;
        
        if (subjectId && classId && streamId) {
            loadSections(classId, streamId, subjectId);
            resetDropdowns(['section', 'chapter']);
        } else {
            resetDropdowns(['section', 'chapter']);
        }
        updateSelectionPath();
    });
    
    document.getElementById('section_id').addEventListener('change', function() {
        const sectionId = this.value;
        const classId = document.getElementById('class_id').value;
        const streamId = document.getElementById('stream_id').value;
        const subjectId = document.getElementById('subject_id').value;
        selections.section = this.options[this.selectedIndex].text;
        
        if (sectionId && classId && streamId && subjectId) {
            loadChapters(classId, streamId, subjectId, sectionId);
            resetDropdowns(['chapter']);
        } else {
            resetDropdowns(['chapter']);
        }
        updateSelectionPath();
    });
    
    document.getElementById('chapter_id').addEventListener('change', function() {
        selections.chapter = this.options[this.selectedIndex].text;
        updateSelectionPath();
        updateSubmitButton();
    });
    
    document.getElementById('test_id').addEventListener('change', function() {
        selections.test = this.options[this.selectedIndex].text;
        
        // Reset all hierarchy dropdowns when a different test is selected
        resetAllHierarchyDropdowns();
        
        updateSelectionPath();
        updateSubmitButton();
    });
    
    // Load functions
    
    function loadStreams(classId) {
        showLoading();
        const url = `../../backend/get_hierarchy_data.php?action=get_streams&class_id=${classId}`;
        console.log('Fetching URL:', url);
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Streams data received:', data);
                if (data.success) {
                    populateDropdown('stream_id', data.data, 'ID', 'NAME');
                    document.getElementById('stream_id').disabled = false;
                } else {
                    showError('Failed to load streams: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showError('Failed to load streams: ' + error.message);
            })
            .finally(() => {
                console.log('loadStreams finally block executing - about to hide loading');
                hideLoading();
                console.log('loadStreams finally block completed');
            });
    }
    
    function loadSubjects(classId, streamId) {
        showLoading();
        fetch(`../../backend/get_hierarchy_data.php?action=get_subjects&class_id=${classId}&stream_id=${streamId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('subject_id', data.data, 'ID', 'NAME');
                    document.getElementById('subject_id').disabled = false;
                } else {
                    showError('Failed to load subjects: ' + data.error);
                }
            })
            .catch(error => {
                showError('Failed to load subjects');
                console.error('Error:', error);
            })
            .finally(() => {
                hideLoading();
            });
    }
    
    function loadSections(classId, streamId, subjectId) {
        showLoading();
        fetch(`../../backend/get_hierarchy_data.php?action=get_sections&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('section_id', data.data, 'ID', 'NAME');
                    document.getElementById('section_id').disabled = false;
                } else {
                    showError('Failed to load sections: ' + data.error);
                }
            })
            .catch(error => {
                showError('Failed to load sections');
                console.error('Error:', error);
            })
            .finally(() => {
                hideLoading();
            });
    }
    
    function loadChapters(classId, streamId, subjectId, sectionId) {
        showLoading();
        fetch(`../../backend/get_hierarchy_data.php?action=get_chapters&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}&section_id=${sectionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('chapter_id', data.data, 'ID', 'NAME');
                    document.getElementById('chapter_id').disabled = false;
                } else {
                    showError('Failed to load chapters: ' + data.error);
                }
            })
            .catch(error => {
                showError('Failed to load chapters');
                console.error('Error:', error);
            })
            .finally(() => {
                hideLoading();
            });
    }
    
    // Utility functions
    function populateDropdown(elementId, data, valueField, textField) {
        console.log(`Populating dropdown ${elementId} with data:`, data);
        const dropdown = document.getElementById(elementId);
        if (!dropdown) {
            console.error(`Dropdown element ${elementId} not found!`);
            return;
        }
        
        dropdown.innerHTML = '<option value="">-- Select --</option>';
        
        data.forEach(function(item) {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];
            dropdown.appendChild(option);
        });
        
        console.log(`Successfully populated ${elementId} with ${data.length} items`);
    }
    
    function resetDropdowns(dropdowns) {
        dropdowns.forEach(function(name) {
            const dropdown = document.getElementById(name + '_id');
            dropdown.innerHTML = '<option value="">-- Select --</option>';
            dropdown.disabled = true;
            selections[name] = '';
        });
        updateSubmitButton();
    }
    
    function updateSelectionPath() {
        let path = [];
        
        if (selections.test) path.push(`<strong>Test:</strong> ${selections.test}`);
        if (selections.class) path.push(`<strong>Class:</strong> ${selections.class}`);
        if (selections.stream) path.push(`<strong>Stream:</strong> ${selections.stream}`);
        if (selections.subject) path.push(`<strong>Subject:</strong> ${selections.subject}`);
        if (selections.section) path.push(`<strong>Section:</strong> ${selections.section}`);
        if (selections.chapter) path.push(`<strong>Chapter:</strong> ${selections.chapter}`);
        
        const selectionPath = document.getElementById('selectionPath');
        if (path.length > 0) {
            selectionPath.innerHTML = '<i class="fas fa-arrow-right me-2"></i>' + path.join(' → ');
        } else {
            selectionPath.innerHTML = '<i class="fas fa-arrow-right me-2"></i><span class="text-muted">Please make your selections above to see the path...</span>';
        }
    }
    
    function updateSubmitButton() {
        const testSelected = document.getElementById('test_id').value;
        const chapterSelected = document.getElementById('chapter_id').value;
        const submitBtn = document.getElementById('submitBtn');
        
        if (testSelected && chapterSelected) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    function resetAllHierarchyDropdowns() {
        console.log('Resetting all hierarchy dropdowns due to test change');
        
        // Reset dependent dropdowns only (not class dropdown as it should keep its options)
        const dependentDropdowns = ['stream_id', 'subject_id', 'section_id', 'chapter_id'];
        dependentDropdowns.forEach(function(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.innerHTML = '<option value="">-- Select --</option>';
            dropdown.disabled = true;
        });
        
        // Reset class dropdown selection but keep its options
        const classDropdown = document.getElementById('class_id');
        classDropdown.value = '';
        classDropdown.disabled = false;
        
        // Reset selections object
        selections.class = '';
        selections.stream = '';
        selections.subject = '';
        selections.section = '';
        selections.chapter = '';
        
        console.log('All hierarchy dropdowns reset');
    }
    
    let currentLoadingModal = null;
    
    function showLoading() {
        console.log('Showing loading modal...');
        const modalElement = document.getElementById('loadingModal');
        if (modalElement) {
            currentLoadingModal = new bootstrap.Modal(modalElement);
            currentLoadingModal.show();
            console.log('Loading modal shown');
        } else {
            console.error('Loading modal element not found!');
        }
    }
    
    function hideLoading() {
        console.log('Hiding loading modal...');
        
        // First hide via Bootstrap modal
        if (currentLoadingModal) {
            currentLoadingModal.hide();
            console.log('Bootstrap modal.hide() called');
        } else {
            // Fallback: try to get existing instance
            const modalElement = document.getElementById('loadingModal');
            if (modalElement) {
                const loadingModal = bootstrap.Modal.getInstance(modalElement);
                if (loadingModal) {
                    loadingModal.hide();
                    console.log('Bootstrap modal.hide() called via getInstance');
                }
            }
        }
        
        // Force cleanup after a short delay to ensure everything is removed
        setTimeout(() => {
            const modalElement = document.getElementById('loadingModal');
            if (modalElement) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                modalElement.setAttribute('aria-hidden', 'true');
                modalElement.removeAttribute('aria-modal');
            }
            
            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Remove any leftover backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Reset modal reference
            currentLoadingModal = null;
            
            console.log('Loading modal force cleaned up');
        }, 150);
    }
    
    function showError(message) {
        console.error('Error:', message);
        // Create and show an alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            <strong>Error:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of the form
        const form = document.getElementById('mappingForm');
        form.insertBefore(alertDiv, form.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
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

.form-select:disabled {
    background-color: #f8f9fa;
    opacity: 0.65;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once __DIR__.'/../master.php';
?>