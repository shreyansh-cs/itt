<?php 
include "showerror.php";
include '../backend/utils.php';
ob_start();
session_start();
$title = "I.T.T Group of Education - Home";
$debug=0;

$type = $_SESSION['user_type'];

$class = "";
if(isset($_POST['classes']))
{
  $class = $_POST['classes'];
  if($debug)
    echo $class.",";
}

$stream = "";
if(isset($_POST['streams']))
{
  $stream = $_POST['streams'];
  if($debug)
    echo $stream.",";
}
$subject = "";
if(isset($_POST['subjects']))
{
  $subject = $_POST['subjects'];
  if($debug)
    echo $subject.",";
}
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
        
        <?php 
        //if no classes are selected, show all classes
        //if(empty($class))
        {
        ?>
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
        <?php 
        } 
        ?>
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
        <td > 
          <select id="subjects" name="subjects" onchange="submitForm('notesForm')">
            <option value='0'>Select</option>
          <?php
            $rows = getSubjectsForStream($class,$stream);
            echo $rows;
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
            echo $rows;
            foreach ($rows as $row) {
              echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$chapter) .">".$row['NAME']."</option>";
            }
          ?>
          </select>
          </td>
    </tr>
    </table>
      <?php 
      }
      ?>
      <?php
      if(!empty($chapter))
      { 
      ?>
      <br/>
      <table>
        <tr>
        <td id="notescolumn"> 
          <?php
            $rows = getNotesForChapter($class,$stream,$subject,$chapter);
            foreach ($rows as $row) {
              echo "<div  id='notescontent'>";
              echo "<h1>".$row['NAME']."</h1>";
              //echo "<h2>".$row['DETAILS']."</h2>";
              echo "<p>".$row['TEXT']."</p>";
              echo "<a target='_blank' href='download.php?noteid=".$row['ID']."'>DOWNLOAD PDF</a>";
              echo "</div>";
            }
          ?>
        </td>
      <?php 
      }
      ?>
      <?php
      if(!empty($chapter))
      { 
      ?>
        <td id="videocolumn"> 
          <?php
            $rows = getVideoForChapter($class,$stream,$subject,$chapter);
            foreach ($rows as $row) {
              echo "<div id='videocontent'>";
              echo "<h1>".$row['NAME']."</h1>";
              //echo "<h2>".$row['DETAILS']."</h2>";
              $link = $row['LINK'];
              echo "<a target='_blank' href='$link'>".$row['NAME']."</a>"; 
              //echo "<iframe width='560' height='315' src='$link' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
              echo "</div>";
            }

          ?>
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
<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>



