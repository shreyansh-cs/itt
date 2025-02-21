<?php 
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

    function getBlobFromNote($note)
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
?>