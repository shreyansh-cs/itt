<?php 
include_once "session.php";
include_once "showerror.php";
include_once '../backend/utils.php';

//values from URL get priority
$class = "0"; //default for admin
if(isset($_GET['class']) && !empty($_GET['class']))
{
    $class=$_GET['class'];
}

$stream="";
if(isset($_GET['stream']) && !empty($_GET['stream']))
{
    $stream=$_GET['stream'];
}

$subject = "";
if(isset($_GET['subject']) && !empty($_GET['subject']))
{
    $subject=$_GET['subject'];
}

$section="";
if(isset($_GET['section']) && !empty($_GET['section']))
{
    $section=$_GET['section'];
}

$chapter="";
if(isset($_GET['chapter']) && !empty($_GET['chapter']))
{
    $chapter=$_GET['chapter'];
}

$user_type = getUserType();
$class = getUserClass();

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
<form action="" id="notesForm" name="notesForm" method="get">
    <table>
      <tr>
        <td class='dropdown'> 
          <select id="class" name="class" onchange="submitForm(this,'notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getAllClasses(); 
            foreach ($rows as $row) {
              if(!isAdminLoggedIn())
              {
                if($row['ID'] == $class)
                {
                  echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$class) .">".$row['NAME']."</option>";
                }
              }
              else
              {
                echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$class) .">".$row['NAME']."</option>";
              }
            }
          ?>
          </select>
        </td>
      </tr>
      <tr>
      <td class='dropdown'> 
          <select id="stream" name="stream" onchange="submitForm(this,'notesForm')">
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
          <select id="subject" name="subject" onchange="submitForm(this,'notesForm')">
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
          <select id="section" name="section" onchange="submitForm(this,'notesForm')">
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
          <select id="chapter" name="chapter" onchange="submitForm(this,'notesForm')">
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
  function submitForm(obj,form_id) 
  {
      formObj = document.getElementById(form_id);
      //alert(obj.name);
      if(obj.name == "class")
      {
        //We have to put if checks becuase some of the dropdowns may not be on the DOM yet
        //Like first time when class dropdown is changed, it will throw JS error and form will not be submitted - Check console for errors
        if(formObj.elements['stream'])
          formObj.elements['stream'].selectedIndex = 0;

        if(formObj.elements['subject'])
          formObj.elements['subject'].selectedIndex = 0;

        if(formObj.elements['section'])
          formObj.elements['section'].selectedIndex = 0;

        if(formObj.elements['chapter'])
          formObj.elements['chapter'].selectedIndex = 0;
      }
      if(obj.name == "stream")
      {
        if(formObj.elements['subject'])
          formObj.elements['subject'].selectedIndex = 0;

        if(formObj.elements['section'])
          formObj.elements['section'].selectedIndex = 0;

        if(formObj.elements['chapter'])
          formObj.elements['chapter'].selectedIndex = 0;
      }
      if(obj.name == "subject")
      {
        if(formObj.elements['section'])
          formObj.elements['section'].selectedIndex = 0;

        if(formObj.elements['chapter'])
          formObj.elements['chapter'].selectedIndex = 0;
      }
      if(obj.name == "section")
      {
        if(formObj.elements['chapter'])
          formObj.elements['chapter'].selectedIndex = 0;
      }

      formObj.submit();
  }
</script>



