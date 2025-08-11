<?php 
include_once "session.php";
include_once "showerror.php";
include_once '../backend/utils.php';

//values from URL get priority
$class = "0"; //default for admin
if(isset($_GET['class']) && !empty($_GET['class']))
{
    $class=$_GET['class'];
}
else
{
  $class = getUserClass();
}

$stream="";
if(isset($_GET['stream']) && !empty($_GET['stream']))
{
    $stream=$_GET['stream'];
}

$subject = "";
if(isset($_GET['subject']) && !empty($_GET['subject']))
{
    $subject=$_GET['subject'];
}

$section="";
if(isset($_GET['section']) && !empty($_GET['section']))
{
    $section=$_GET['section'];
}

$chapter="";
if(isset($_GET['chapter']) && !empty($_GET['chapter']))
{
    $chapter=$_GET['chapter'];
}

$user_type = getUserType();

$msg = "";
if(isset($_SESSION['msg']))
{
  $msg = $_SESSION['msg'];
  //clear after showing
  $_SESSION['msg']="";
}
if(!empty($msg))
{
  echo "<div class='alert alert-danger'>$msg</div>";
}
?>
<form action="" id="notesForm" name="notesForm" method="get">
    <div class='container-fluid'>
        <div class='row g-3'>
            <div class='col-12'>
                <div class='form-group'>
                    <label class='form-label'>
                        <i class="fas fa-school me-1"></i>Class
                    </label>
                    <select class='form-select' name='class' id='class_select'>
                        <option value=''>-- Choose Class --</option>
                        <?php
                        //Get all classes
                        $rows = getAllClasses();
                        foreach ($rows as $row) {
                            if(!isAdminLoggedIn() && !isTeacherLoggedIn()) {
                                if($row['ID'] == $class) {
                                    echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$class).">".$row['NAME']."</option>";
                                }
                            } else {
                                echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$class).">".$row['NAME']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- Stream Selection -->
            <div class='col-12' id='stream_container' <?= empty($class) ? 'style="display:none;"' : '' ?>>
                <div class='form-group'>
                    <label class='form-label'>
                        <i class="fas fa-stream me-1"></i>Stream
                    </label>
                    <select class='form-select' name='stream' id='stream_select'>
                        <option value=''>-- Choose Stream --</option>
                        <?php if (!empty($class)): ?>
                            <?php 
                            $rows = getStreamsForClass($class);
                            foreach ($rows as $row) {
                                echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$stream).">".$row['NAME']."</option>";
                            }
                            ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Subject Selection -->
            <div class='col-12' id='subject_container' <?= empty($stream) ? 'style="display:none;"' : '' ?>>
                <div class='form-group'>
                    <label class='form-label'>
                        <i class="fas fa-book me-1"></i>Subject
                    </label>
                    <select class='form-select' name='subject' id='subject_select'>
                        <option value=''>-- Choose Subject --</option>
                        <?php if (!empty($stream)): ?>
                            <?php 
                            $rows = getSubjectsForStream($class,$stream);
                            foreach ($rows as $row) {
                                echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$subject).">".$row['NAME']."</option>";
                            }
                            ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Section Selection -->
            <div class='col-12' id='section_container' <?= empty($subject) ? 'style="display:none;"' : '' ?>>
                <div class='form-group'>
                    <label class='form-label'>
                        <i class="fas fa-bookmark me-1"></i>Section
                    </label>
                    <select class='form-select' name='section' id='section_select'>
                        <option value=''>-- Choose Section --</option>
                        <?php if (!empty($subject)): ?>
                            <?php 
                            $rows = getSectionsForSubject($class,$stream,$subject);
                            foreach ($rows as $row) {
                                echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$section).">".$row['NAME']."</option>";
                            }
                            ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Chapter Selection -->
            <div class='col-12' id='chapter_container' <?= empty($section) ? 'style="display:none;"' : '' ?>>
                <div class='form-group'>
                    <label class='form-label'>
                        <i class="fas fa-file-alt me-1"></i>Chapter
                    </label>
                    <select class='form-select' name='chapter' id='chapter_select'>
                        <option value=''>-- Choose Chapter --</option>
                        <?php if (!empty($section)): ?>
                            <?php 
                            $rows = getChaptersForSection($class,$stream,$subject,$section);
                            foreach ($rows as $row) {
                                echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$chapter).">".$row['NAME']."</option>";
                            }
                            ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Loading indicator -->
<div id="loading-indicator" style="display:none;" class="text-center mt-2">
    <div class="spinner-border spinner-border-sm text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <small class="text-muted ms-2">Loading...</small>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners for AJAX-based hierarchical loading
    document.getElementById('class_select').addEventListener('change', function() {
        const classId = this.value;
        resetFormParameters(['stream', 'subject', 'section', 'chapter']);
        
        if (classId) {
            resetAndHideContainers(['subject', 'section', 'chapter']);
            loadStreams(classId);
        } else {
            resetAndHideContainers(['stream', 'subject', 'section', 'chapter']);
        }
        submitForm();
    });

    document.getElementById('stream_select').addEventListener('change', function() {
        const streamId = this.value;
        const classId = document.getElementById('class_select').value;
        resetFormParameters(['subject', 'section', 'chapter']);
        
        if (streamId && classId) {
            resetAndHideContainers(['section', 'chapter']);
            loadSubjects(classId, streamId);
        } else {
            resetAndHideContainers(['subject', 'section', 'chapter']);
        }
        submitForm();
    });

    document.getElementById('subject_select').addEventListener('change', function() {
        const subjectId = this.value;
        const classId = document.getElementById('class_select').value;
        const streamId = document.getElementById('stream_select').value;
        resetFormParameters(['section', 'chapter']);
        
        if (subjectId && classId && streamId) {
            resetAndHideContainers(['chapter']);
            loadSections(classId, streamId, subjectId);
        } else {
            resetAndHideContainers(['section', 'chapter']);
        }
        submitForm();
    });

    document.getElementById('section_select').addEventListener('change', function() {
        const sectionId = this.value;
        const classId = document.getElementById('class_select').value;
        const streamId = document.getElementById('stream_select').value;
        const subjectId = document.getElementById('subject_select').value;
        resetFormParameters(['chapter']);
        
        if (sectionId && classId && streamId && subjectId) {
            loadChapters(classId, streamId, subjectId, sectionId);
        } else {
            resetAndHideContainers(['chapter']);
        }
        submitForm();
    });

    document.getElementById('chapter_select').addEventListener('change', function() {
        submitForm();
    });

    // AJAX load functions
    function loadStreams(classId) {
        showLoading();
        fetch(`../backend/get_hierarchy_data.php?action=get_streams&class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('stream_select', data.data, 'ID', 'NAME');
                    showContainer('stream');
                } else {
                    console.error('Failed to load streams:', data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => hideLoading());
    }

    function loadSubjects(classId, streamId) {
        showLoading();
        fetch(`../backend/get_hierarchy_data.php?action=get_subjects&class_id=${classId}&stream_id=${streamId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('subject_select', data.data, 'ID', 'NAME');
                    showContainer('subject');
                } else {
                    console.error('Failed to load subjects:', data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => hideLoading());
    }

    function loadSections(classId, streamId, subjectId) {
        showLoading();
        fetch(`../backend/get_hierarchy_data.php?action=get_sections&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('section_select', data.data, 'ID', 'NAME');
                    showContainer('section');
                } else {
                    console.error('Failed to load sections:', data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => hideLoading());
    }

    function loadChapters(classId, streamId, subjectId, sectionId) {
        showLoading();
        fetch(`../backend/get_hierarchy_data.php?action=get_chapters&class_id=${classId}&stream_id=${streamId}&subject_id=${subjectId}&section_id=${sectionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateDropdown('chapter_select', data.data, 'ID', 'NAME');
                    showContainer('chapter');
                } else {
                    console.error('Failed to load chapters:', data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => hideLoading());
    }

    // Utility functions
    function populateDropdown(elementId, data, valueField, textField) {
        const dropdown = document.getElementById(elementId);
        dropdown.innerHTML = '<option value="">-- Choose --</option>';
        
        data.forEach(function(item) {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];
            dropdown.appendChild(option);
        });
    }

    function resetAndHideContainers(containers) {
        containers.forEach(function(name) {
            const dropdown = document.getElementById(name + '_select');
            const container = document.getElementById(name + '_container');
            
            dropdown.innerHTML = '<option value="">-- Choose --</option>';
            container.style.display = 'none';
        });
    }
    
    function resetFormParameters(parameters) {
        parameters.forEach(function(name) {
            const dropdown = document.getElementById(name + '_select');
            dropdown.value = '';
        });
    }

    function showContainer(name) {
        document.getElementById(name + '_container').style.display = 'block';
    }

    function submitForm() {
        // Show loading indicator while form submits
        showLoading();
        document.getElementById('notesForm').submit();
    }

    function showLoading() {
        document.getElementById('loading-indicator').style.display = 'block';
    }

    function hideLoading() {
        document.getElementById('loading-indicator').style.display = 'none';
    }
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

#loading-indicator {
    padding: 10px;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
