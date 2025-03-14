<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" href="styles.css">
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
    <header>
        <div class="logo">
        <img src="../images/icon.jpeg" alt="I.T.T Group of Education Logo">
        <h1>I.T.T Group of Education</h1>
        </div>
    </header>
   
    <nav>
       <ul>
       <li><a href="index.php">Home</a></li>
       <li><a href="about.php">About Us</a></li>
       <li><a href='courses.php'>Courses</a></li>
       <li><a href="onlinetest.php">Online Test</a></li>
       <li><a href="contact.php">Contact Us</a></li> 
    <?php
       if(!isSessionValid())
       {
           echo "<li><a href=\"login.php\">Login</a></li>";
       }
       else
       {
           echo "<li><a href='../backend/logout.php'>Logout (Class {$class})</a></li>";
           if($type == "admin")
           {
            echo "<li><a href='../frontend/noteslist.php'>Admin</a></li>";
           }
       }
    ?>
        </ul>
    </nav>
    <div>
        <?php echo $content; ?>
    </div>
  
    <footer>
        <p>&copy; 2025 I.T.T Group of Education. All Rights Reserved.</p>
    </footer>

</body>
</html>