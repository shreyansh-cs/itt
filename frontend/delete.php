<?php 
include_once 'showerror.php';
include_once '../frontend/session.php';
include_once '../backend/utils.php';

$id = $_SESSION['user_id'];
$fullname = $_SESSION['full_name'];
$user_type = $_SESSION['user_type'];
$user_class = $_SESSION['user_class'];

if(!isset($id) || empty($id))
{
    redirectError("Invalid Session");
}

if(!isset($user_type) || empty(($user_type)))
{
    redirectError("Invalid User Type");
}

//Check if it's admin
if($user_type != "admin")
{
    redirectError("Invalid User Type - 2"); 
}

$noteid = "";
if(isset($_GET['noteid']))
{
    $noteid = $_GET['noteid'];
}
$videoid="";
if(isset($_GET['videoid']))
{
    $videoid = $_GET['videoid'];
}

if(empty($noteid) && empty($videoid))
{
    redirectError("Note / Video not provided in request");  
}

$class="";
$stream="";
$subject="";
$section="";
$chapter="";

if(!empty($noteid) || !empty($videoid))
{
    $class=$_GET['class'];
    $stream=$_GET['stream'];
    $subject=$_GET['subject'];
    $section=$_GET['section'];
    $chapter=$_GET['chapter'];
}

if(!empty($noteid))
{
    $error = "";

    if(deleteNote($noteid,$error))
    {
        setStatusMsg($error);
        header("Location: /itt/frontend/noteslist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter");
    }
    else
    {
        redirectError($error); 
    }
}

if(!empty($videoid))
{
    $error = "";
    if(deleteVideo($videoid,$error))
    {
        setStatusMsg($error);
        header("Location: /itt/frontend/videolist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter");
    }
    else
    {
        redirectError($error); 
    }
}


?>