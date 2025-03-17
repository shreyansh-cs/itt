<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/itt/frontend/css/styles.css">
</head>
<body>
  <?php 
   include_once 'session.php';
   include_once '../backend/utils.php';

   //session is not valid and 
    if(!isSessionValid() && isProtectedPage())
    {
        header("Location: /itt/frontend/login.php");
    }

   $id = "";
   if(isset($_SESSION['user_id']))
   {
        $id = $_SESSION['user_id'];
   }
   
   $full_name = "";
   if(isset($_SESSION['full_name']))
   {
        $full_name = $_SESSION['full_name'];
   }

   $class = "";
   if(isset($_SESSION['user_class']))
   {
        $class = $_SESSION['user_class'];
   }

   $type = "";
   if(isset($_SESSION['user_type']))
   {
        $type = $_SESSION['user_type'];
   }
   
   ?>
    <!-- Header -->
    <header>
        <div class="logo">
        <img src="../images/icon.jpeg"  alt="I.T.T Group of Education Logo">
        <h1>I.T.T. Group of Education</h1>
        </div>
        <nav class="navbar">
            <ul>
            <li><a href="/itt/frontend/index.php">Home</a></li>
            <li><a href="/itt/frontend/about.php">About Us</a></li>
            <li><a href='/itt/frontend/courses.php'>Courses</a></li>
            <li><a href="/itt/frontend/onlinetest.php">Online Test</a></li>
            <li><a href="/itt/frontend/contact.php">Contact Us</a></li> 
            <?php
                //more items of page
                if(!isSessionValid())
                {
                    echo "<li><a href='/itt/frontend/login.php'>Login</a></li>";
                }
                else
                {
                    echo "<li><a href='/itt/backend/logout.php'>Logout ({$full_name})</a></li>";
                    if($type == "admin")
                    {
                        echo "<li><a href='/itt/frontend/noteslist.php'>Admin</a></li>";
                    }
                }
            ?>
            </ul>
        </nav>
        <div class="hamburger" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </header>
    <main>
    <?php echo $content; ?>
    </main>

    <footer>
        <p>&copy; 2025 I.T.T. Group of Education. All Rights Reserved.</p>
    </footer>

    <script src="/itt/frontend/scripts/script.js"></script>
</body>
</html>