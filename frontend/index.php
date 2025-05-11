<?php 
include_once 'session.php';
include_once '../backend/public_utils.php';
ob_start();
$title = "I.T.T Group of Education - Home";
?>
<!-- Banner Section -->
<div class="banner-section">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-start">
                <h1 class="display-4 fw-bold mb-3">Welcome to I.T.T Group of Education</h1>
                <p class="lead mb-4">Your Gateway to Quality Learning</p>
                <p class="mb-4"> 
                We believe in delivering a quality education that goes beyond textbook learning and 
                exam preparation. Our mission is to nurture well-rounded individuals by fostering critical 
                thinking, creativity, and problem-solving abilities. We aim to create an environment 
                where students are not only academically competent but also emotionally intelligent, 
                socially responsible, and equipped with practical skills that empower them to thrive in 
                an ever-changing world. Through a balanced blend of knowledge, real-world application, 
                and personal development, we strive to instill a growth mindset, a strong sense of purpose, 
                and the confidence needed to navigate challenges and seize opportunities throughout life.
                </p>

                <!-- Quick Access Cards -->
                <div class="row mt-4">
                    <!-- Courses Section -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Courses</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="courses.php" class="list-group-item list-group-item-action">
                                        <i class="fas fa-book-open me-2"></i>View Courses
                                    </a>
                                    <a href="noteslist.php" class="list-group-item list-group-item-action">
                                        <i class="fas fa-pencil-alt me-2"></i>Online Tests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes & Video Section -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Notes & Video</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="noteslist.php" class="list-group-item list-group-item-action">
                                        <i class="fas fa-file-alt me-2"></i>View Notes
                                    </a>
                                    <a href="noteslist.php" class="list-group-item list-group-item-action">
                                        <i class="fas fa-download me-2"></i>Download Videos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Packages Section -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Packages</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="receipts.php" class="list-group-item list-group-item-action">
                                        <i class="fas fa-shopping-cart me-2"></i>Transactions
                                    </a>
                                    <a href="receipts.php" class="list-group-item list-group-item-action">
                                        <i class="fas fa-shopping-cart me-2"></i>Buy Package
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="/itt/images/banner.svg" alt="Education Banner" class="img-fluid banner-image">
            </div>
        </div>
    </div>
</div>

<?php if(isAdminLoggedIn()): ?>
<!-- Admin Section -->
<div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Admin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="list-group">
                                <a href="test/admin_take_test.php" class="list-group-item list-group-item-action">
                                    <i class="fas fa-pencil-alt me-2"></i>Admin Test Attempt
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="list-group">
                                <a href="test/create_test.php" class="list-group-item list-group-item-action">
                                    <i class="fas fa-plus-circle me-2"></i>Create Test
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="list-group">
                                <a href="test/upload_questions.php" class="list-group-item list-group-item-action">
                                    <i class="fas fa-upload me-2"></i>Upload Questions
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="list-group">
                                <a href="test/map_test_to_class.php" class="list-group-item list-group-item-action">
                                    <i class="fas fa-link me-2"></i>Map Test to Class
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="list-group">
                                <a href="test/edit_test_map.php" class="list-group-item list-group-item-action">
                                    <i class="fas fa-edit me-2"></i>Edit Test Map
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="list-group">
                                <a href="gettransactions.php" class="list-group-item list-group-item-action">
                                    <i class="fas fa-money-bill-wave me-2"></i>Transactions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.banner-section {
    background: #ffffff;
    padding: 2rem 0;
    position: relative;
    overflow: hidden;
    margin-bottom: 3rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-radius: 20px;
    margin: 0 0 3rem;
    width: 100%;
}

.banner-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('/itt/images/pattern.svg') repeat;
    opacity: 0.05;
    z-index: 0;
}

.banner-image {
    max-width: 100%;
    height: auto;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
    100% {
        transform: translateY(0px);
    }
}

@media (max-width: 991.98px) {
    .banner-section {
        text-align: center;
        padding: 1.5rem 0;
    }
    
    .banner-image {
        margin-top: 1rem;
        max-width: 100%;
    }
    
    .banner-section .btn {
        margin: 0.5rem;
    }
    
    .col-lg-6.d-flex {
        align-items: center;
    }
}

@media (max-width: 767.98px) {
    .banner-section {
        padding: 1rem 0;
    }
    
    .banner-image {
        max-width: 100%;
        margin-top: 0.5rem;
    }
}

.card {
    transition: transform 0.2s;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    height: 100%;
}

.card:hover {
    transform: translateY(-5px);
}

.list-group-item {
    border: none;
    margin-bottom: 5px;
    border-radius: 5px !important;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #eee;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.card-header {
    border-bottom: none;
    border-radius: 10px 10px 0 0 !important;
    padding: 1rem;
}

.card-body {
    padding: 1rem;
}

@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
    
    .list-group-item {
        padding: 0.75rem 1rem;
    }
}
</style>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>