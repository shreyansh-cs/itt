<?php
    function getMyHeader()
    {
        session_start();
        $id = $_SESSION['user_id'];
        $full_name = $_SESSION['full_name'];
        $class = $_SESSION['user_class'];
        
        $text = <<<EOD
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
            <li><a href="courses.php">Courses</a></li>
            <li><a href="test-series.php">Online Test</a></li>
            <li><a href="contact.php">Contact</a></li> 
EOD;
               
            if(!isset($id))
            {
                $text.="<li><a href=\"login.php\">Login</a></li>";
            }
            else
            {
                $text.="<li><a href=\"../backend/logout.php\">Logout (Class {$class})</a></li>";
            }

            $text .= "
                </ul>
            </nav>
            ";

        return $text;
    }
  ?>