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
else
{
  $class = getUserClass();
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
    <div class='container-fluid'>
        <div class='row g-3'>
            <div class='col-12'>
                <div class='form-group'>
                    <label class='form-label'>Class</label>
                    <select class='form-select' name='class' onchange="submitForm(this,'notesForm')">
                        <option value=''>-- Choose Class --</option>
                        <?php
                        //Get all classes
                        $rows = getAllClasses();
                        foreach ($rows as $row) {
                            if(!isAdminLoggedIn() && !isTeacherLoggedIn()) {
                                if($row['ID'] == $class) {
                                    echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$class).">".$row['NAME']."</option>";
                                }
                            } else {
                                echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$class).">".$row['NAME']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php
            if(!empty($class))
            {
                //Get all streams
                $rows = getStreamsForClass($class);
                echo "<div class='col-12'>";
                echo "<div class='form-group'>";
                echo "<label class='form-label'>Stream</label>";
                echo "<select class='form-select' name='stream' onchange=\"submitForm(this,'notesForm')\">";
                echo "<option value=''>-- Choose Stream --</option>";
                foreach ($rows as $row) {
                    echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$stream).">".$row['NAME']."</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <?php
            if(!empty($stream))
            {
                //Get all subjects
                $rows = getSubjectsForStream($class,$stream);
                echo "<div class='col-12'>";
                echo "<div class='form-group'>";
                echo "<label class='form-label'>Subject</label>";
                echo "<select class='form-select' name='subject' onchange=\"submitForm(this,'notesForm')\">";
                echo "<option value=''>-- Choose Subject --</option>";
                foreach ($rows as $row) {
                    echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$subject).">".$row['NAME']."</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <?php
            if(!empty($subject))
            {
                //Get all sections
                $rows = getSectionsForSubject($class,$stream,$subject);
                echo "<div class='col-12'>";
                echo "<div class='form-group'>";
                echo "<label class='form-label'>Section</label>";
                echo "<select class='form-select' name='section' onchange=\"submitForm(this,'notesForm')\">";
                echo "<option value=''>-- Choose Section --</option>";
                foreach ($rows as $row) {
                    echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$section).">".$row['NAME']."</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <?php
            if(!empty($section))
            {
                //Get all chapters
                $rows = getChaptersForSection($class,$stream,$subject,$section);
                echo "<div class='col-12'>";
                echo "<div class='form-group'>";
                echo "<label class='form-label'>Chapter</label>";
                echo "<select class='form-select' name='chapter' onchange=\"submitForm(this,'notesForm')\">";
                echo "<option value=''>-- Choose Chapter --</option>";
                foreach ($rows as $row) {
                    echo "<option value='".$row['ID']."' ".checkSelected($row['ID'],$chapter).">".$row['NAME']."</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
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



