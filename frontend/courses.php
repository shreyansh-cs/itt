<?php 
include_once 'session.php';
ob_start();
$title = "Courses";
?>
<div class="card shadow p-4 rounded-0 w-100 m-0">
    <div class="mb-4">
        <a href="noteslist.php" class="btn btn-primary btn-lg w-100 text-start">
            <div class="d-flex justify-content-between align-items-center">
                <span>Notes and Videos</span>
                <i class="fas fa-chevron-right"></i>
            </div>
            <small class="d-block text-white mt-1">Comprehensive courses covering all subjects.</small>
        </a>
    </div>

    <div class="mb-4">
        <a href="computercoaching.php" class="btn btn-primary btn-lg w-100 text-start">
            <div class="d-flex justify-content-between align-items-center">
                <span>Computer Coaching</span>
                <i class="fas fa-chevron-right"></i>
            </div>
            <small class="d-block text-white mt-1">Specialized computer coaching including CCA, DCA, CFA, DTP, DOA, DCP, ADIT, ADCA, and DHT.</small>
        </a>
    </div>

    <div class="mb-4">
        <a href="competitiveexam.php" class="btn btn-primary btn-lg w-100 text-start">
            <div class="d-flex justify-content-between align-items-center">
                <span>Competitive Exam Preparation</span>
                <i class="fas fa-chevron-right"></i>
            </div>
            <small class="d-block text-white mt-1">Focused coaching for competitive exams with expert guidance and study materials.</small>
        </a>
    </div>
</div>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>


