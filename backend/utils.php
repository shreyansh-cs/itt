<?php 
    function getAllClasses()
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME FROM classes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows; 
    }
    function getStreamsForClass($class)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME FROM streams where CLASS_ID=$class";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows;
    }

    function getSubjectsForStream($class,$stream)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID as ID, NAME as NAME from subjects where ID IN(SELECT SUBJECT_ID FROM streamubjectmap where STREAM_ID=$stream)";
        //$sql = "SELECT ID AS ID, NAME as NAME FROM subjects where STREAM_ID=$stream";
        //echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows;
    }

    function getChaptersForSubject($class,$stream,$subject)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME FROM chapters where SUBJECT_ID=$subject";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows;
    }

    function getNotesForChapter($class,$stream,$subject,$chapter)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME, DETAILS AS DETAILS, PDF AS PDF, TEXT AS TEXT FROM notes where CHAPTER_ID=$chapter";
        //echo $sql;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows; 
    }

    function getPDFPathFromNote($note)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT PDF AS PDF, NAME AS NAME FROM notes where ID=$note";
        //echo $sql;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows; 
    }

    function getVideoForChapter($class,$stream,$subject,$chapter)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME, DETAILS AS DETAILS, LINK AS LINK FROM videos where CHAPTER_ID=$chapter";
        //echo $sql;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        return $rows; 
    }

    //Check if this option in dropdown is selected or not
    function checkSelected($thisOptionValue,$selectedValue)
    {
        //must check if there was something selected in last form submit
        if(isset($selectedValue))
        {
            if($thisOptionValue == $selectedValue)
            {
                return "selected";
            }
        }
    }

    function insertNotes($chapter_id,$notes_title,$notesText,$pdf_file_path,&$error/*OUT*/ )
    {
        include 'db.php';
        $ret = false;
        $sql = "INSERT INTO notes(NAME,DETAILS,TEXT,PDF,CHAPTER_ID) VALUES ('$notes_title','','$notesText', '$pdf_file_path',$chapter_id)";
        // Execute the query
        if ($conn->query($sql) === TRUE) 
        {
            $error = "New record created successfully";
            $ret = true;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ret = false;
        } 
        return $ret;
    }

    function deleteNote($noteid,&$error)
    {
        include 'db.php';
        $ret = false;
        $sql = "delete from notes where ID=$noteid";
        if ($conn->query($sql) === TRUE) 
        {
            $error = "Record deleted successfully";
            if($conn->affected_rows == 0)
            {
                $error = "No record updated"; 
            }

            $ret = true;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ret = false;
        } 
        return $ret;
    }

    function deleteVideo($videoid,&$error)
    {
        include 'db.php';
        $ret = false;
        $sql = "delete from videos where ID=$videoid";
        if ($conn->query($sql) === TRUE) 
        {
            $error = "Record deleted successfully";
            if($conn->affected_rows == 0)
            {
                $error = "No record updated"; 
            }

            $ret = true;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ret = false;
        } 
        return $ret;
    }

    //Both param can be email or phone
    function authUser($email_or_phone,$password,&$row/*OUT*/,&$error)
    {
        include 'db.php';
        $sql = "SELECT id as ID, full_name as FULL_NAME, password as PASSWORD, user_type as USER_TYPE, user_class as USER_CLASS from users where email='$email_or_phone' OR phone='$email_or_phone'";
        $ok = false;
        $result = $conn->query($sql);
        if ($result && $result->num_rows == 1) 
        {
            //only one row of user
            $row = $result->fetch_assoc();
            $ok = true;
        }
        else if($result && $result->num_rows == 0)
        {
            $error = "No such user exists"; 
        }
        else
        {
            $error = "Unknown Error ". $conn->error;
        }

        if($ok)
        {
            //check password
            $ok=false; //by default not matched
            echo $row['PASSWORD'];
            echo $password;
            if($row['PASSWORD'] == $password)
            {
                $ok = true;
            }
        }
        return $ok;
    }

    //Both param can be email or phone
    function doesUserAlreadyExist($param1,$param2,&$error)
    {
        include 'db.php';
        $rows = [];
        $sql1 = "SELECT email as EMAIL, phone as PHONE from users where email='$param1' OR phone='$param1'";
        $sql2 = "SELECT email as EMAIL, phone as PHONE from users where email='$param2' OR phone='$param2'";
        $ok = false;
        $result = $conn->query($sql1);
        if ( $result && $result->num_rows > 0) 
        {
                $ok = true;
        }
        
        //param 1 did not match, check for param2
        if(!$ok)
        {
            $result = $conn->query($sql2);
            if ( $result && $result->num_rows > 0) 
            {
                    $ok = true;
            }
        }
    
        return $ok;
    }

    function redirectError($error)
    {
        include '../frontend/session.php';
        $_SESSION['error'] = $error;
        header("Location: /itt/frontend/error.php");
    }

    function redirect($url)
    {
        header("Location: $url");
    }

    function setStatusMsg($msg)
    {
        include '../frontend/session.php';
        $_SESSION['error'] = "";//clear error
        //set msg
        $_SESSION['msg'] = $msg;
    }

    function GetUniqueNumber()
    {
        // Get current timestamp in microseconds
        $timestamp = microtime(true);
        // Convert timestamp to a unique number (combining date/time and microseconds)
        $uniqueNumber = str_replace('.', '', $timestamp);
        return $uniqueNumber;
    }
?>