<?php 
include_once "showerror.php";
include_once '../backend/utils.php';
include_once "session.php";

$debug = 0;

//values from URL get priority
if(isset($_GET['class']) && !empty($_GET['class']))
{
    $class=$_GET['class'];
}

if(isset($_GET['stream']) && !empty($_GET['stream']))
{
    $stream=$_GET['stream'];
}

if(isset($_GET['subject']) && !empty($_GET['subject']))
{
    $subject=$_GET['subject'];
}

if(isset($_GET['section']) && !empty($_GET['section']))
{
    $section=$_GET['section'];
}

if(isset($_GET['chapter']) && !empty($_GET['chapter']))
{
    $chapter=$_GET['chapter'];
}

$user_type = "";
if(isset($_SESSION['user_type']))
{ 
  $user_type = $_SESSION['user_type'];
  if($user_type == "student")
  {
    //extract class from session
    $class = $_SESSION['user_class'];
  }
}

//Not look for POST values if any
if(!isset($class))
  $class = "0";

if(isset($_POST['class']))
{
  $class = $_POST['class'];
  if($debug)
    echo $class.",";
}

if(!isset($stream))
  $stream = "";
if(isset($_POST['stream']))
{
  $stream = $_POST['stream'];
  if($debug)
    echo $stream.",";
}

if(!isset($subject))
  $subject = "";
if(isset($_POST['subject']))
{
  $subject = $_POST['subject'];
  if($debug)
    echo $subject.",";
}

if(!isset($section))
  $section = "";
if(isset($_POST['section']))
{
  $section = $_POST['section'];
  if($debug)
    echo $section.",";
}

if(!isset($chapter))
  $chapter = "";
if(isset($_POST['chapter']))
{
  $chapter = $_POST['chapter'];
  if($debug)
    echo $chapter.",";
}

$msg = "";
if(isset($_SESSION['msg']))
{
  $msg = $_SESSION['msg'];
  //clear after showing
  $_SESSION['msg']="";
}
if(!empty($msg))
{
  echo "<div style='color:red'>$msg</div>";
}
?>
<form action="" id="notesForm" name="notesForm" method="post">
    <table>
      <tr>
        <td class='dropdown'> 
          <select id="class" name="class" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getAllClasses(); 
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$class) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
        </td>
      </tr>
      <tr>
      <td class='dropdown'> 
          <select id="stream" name="stream" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getStreamsForClass($class); 
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$stream) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
      </td>
      </tr>
      <?php
      if(!empty($stream))
      { 
      ?>
        <tr>
        <td class='dropdown'> 
          <select id="subject" name="subject" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getSubjectsForStream($class,$stream);
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$subject) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
        </td>
        </tr>
      <?php 
      }
      ?>
      <?php
      if(!empty($subject))
      { 
      ?>
        <tr>
        <td class='dropdown'> 
          <select id="section" name="section" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getSectionsForSubject($class,$stream,$subject);
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$section) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
        </td>
        </tr>
      <?php 
      }
      ?>
      <?php
      if(!empty($section))
      { 
      ?>
          <tr>
          <td class='dropdown'> 
          <select id="chapter" name="chapter" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getChaptersForSection($class,$stream,$subject,$section);
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$chapter) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
          </td>
          </tr>
      <?php 
      }
      ?>
  </table>
</form>
<script>
  function submitForm(id) 
  {
      document.getElementById(id).submit();
  }
</script>



