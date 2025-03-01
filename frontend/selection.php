<?php 
include "showerror.php";
include '../backend/utils.php';
include "session.php";

$type = $_SESSION['user_type'];
$debug = 1;

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

if(isset($_GET['chapter']) && !empty($_GET['chapter']))
{
    $chapter=$_GET['chapter'];
}

//Not look for POST values if any
if(!isset($class))
  $class = "0";

if(isset($_POST['classes']))
{
  $class = $_POST['classes'];
  if($debug)
    echo $class.",";
}

if(!isset($stream))
  $stream = "";
if(isset($_POST['streams']))
{
  $stream = $_POST['streams'];
  if($debug)
    echo $stream.",";
}

if(!isset($subject))
  $subject = "";
if(isset($_POST['subjects']))
{
  $subject = $_POST['subjects'];
  if($debug)
    echo $subject.",";
}

if(!isset($chapter))
  $chapter = "";
if(isset($_POST['chapters']))
{
  $chapter = $_POST['chapters'];
  if($debug)
    echo $chapter.",";
}
?>
<form action="" id="notesForm" name="notesForm" method="post">
    <table>
      <tr>
        <td> 
          <select id="classes" name="classes" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getAllClasses(); 
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$class) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
        </td>
      <td> 
          <select id="streams" name="streams" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getStreamsForClass($class); 
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$stream) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
      </td>
      <?php
      if(!empty($stream))
      { 
      ?>
        <td> 
          <select id="subjects" name="subjects" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getSubjectsForStream($class,$stream);
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$subject) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
        </td>
      <?php 
      }
      ?>
      <?php
      if(!empty($subject))
      { 
      ?>
          <td> 
          <select id="chapters" name="chapters" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getChaptersForSubject($class,$stream,$subject);
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$chapter) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
          </td>
      <?php 
      }
      ?>
  </tr>
  </table>
</form>
<script>
  function submitForm(id) 
  {
      // Trigger form submission
      //document.getElementById('notescolumn').remove();
      //document.getElementById('videocolumn').remove();
      document.getElementById(id).submit();
  }
</script>



