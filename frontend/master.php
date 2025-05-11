<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/itt/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px; /* Add padding to account for fixed navbar */
        }
        .navbar-brand h1 {
            font-size: 24px;
            margin: 0;
            padding-top: 10px;
        }
        .navbar-brand img {
            width: 50px;
            height: 50px;
        }
        .nav-link {
            color: white !important;
        }
        .navbar-toggler {
            border-color: white !important;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }
        @media (max-width: 768px) {
            .navbar-brand h1 {
                font-size: 18px;
            }
            .navbar-brand img {
                width: 35px;
                height: 35px;
            }
            .navbar-collapse {
                background-color: #007bff;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 1rem;
            }
            .navbar-toggler {
                margin-left: auto;
                order: 2;
            }
            .navbar-brand {
                order: 1;
            }
            .navbar-collapse {
                order: 3;
            }
        }
    </style>
</head>
<body>
  <?php 
   include_once __DIR__.'/../backend/utils.php';
   include_once __DIR__.'/../backend/public_utils.php';

   $id = getUserID();
   $full_name = getUserName();
   $class = getUserClass();
   $type = getUserType();
   ?>
   
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href='/itt/frontend/index.php'>
                <img src="/itt/images/icon.png" alt="I.T.T Group of Education Logo" class="me-2">
                <h1 class="text-white">I.T.T. Group of Education</h1>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/itt/frontend/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/itt/frontend/about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href='/itt/frontend/courses.php'>Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/itt/frontend/noteslist.php">Notes & Video</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/itt/frontend/noteslist.php">Online Test</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/itt/frontend/contact.php">Contact Us</a>
                    </li>
                    <?php
                        if(!isSessionValid()) {
                            echo '<li class="nav-item"><a class="nav-link" href="/itt/frontend/login.php">Login</a></li>';
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="/itt/backend/logout.php">Logout (' . htmlspecialchars($full_name) . ')</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-4 mt-4">
        <?php echo $content; ?>
    </main>

    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2025 I.T.T. Group of Education. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/itt/frontend/scripts/script.js<?php echo "?no-cache=".time(); ?>"></script>
</body>
</html>