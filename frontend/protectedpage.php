<?php
      function isSessionValid()
      {
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']))
        {
          return true;
        }
        return false;
      }

      function isProtectedPage()
      {
        //Page accessible without login
        $protectedURI = [
            "login.php",
            "register.php",
            "index.php",
            "about.php",
            "contact.php",
        ];
        $currentURI = $_SERVER['REQUEST_URI'];
        $protected = true;//default is protected
        foreach ($protectedURI as $uri) 
        {
            if(strpos($currentURI,$uri))
            {
                $protected = false;
                break;
            }   
        }
        //protected page
        return $protected;
      }

?>