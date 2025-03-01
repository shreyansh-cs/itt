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
       // SQL query without prepared statement
        $sql = "INSERT INTO notes(NAME,DETAILS,TEXT,PDF,CHAPTER_ID) VALUES ('$notes_title','','$notesText', '$pdf_file_path',$chapter_id)";
        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $error = "<br/>New record created successfully";
            return true;
        } else {
            $error = "<br/>Error: " . $sql . "<br>" . $conn->error;
            return false;
        } 
    }
?>