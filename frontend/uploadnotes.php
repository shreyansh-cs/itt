<?php 
include_once 'session.php';
include_once "showerror.php";
ob_start();
$title = "Upload Notes";

include_once 'restrictedpage.php';//restrict page
include_once 'selection.php';
$border = "1";
//Check if we need to show the upload form
echo "<table border='{$border}'>";
echo "<tr><td>";

if(!empty($chapter))
{
?>
    <form action="../backend/upload.php" method="POST" id="uploadnotes" name="uploadnotes" enctype="multipart/form-data" onSubmit="OnSubmit()">
        <table class='upload' border="<?php echo $border; ?>">
            <tr>
                <td class='first'>Notes Title:</td>
                <td class='second'>
                <input type="text" name="notes_title" id="notes_title">
                </td>
            </tr>
            <tr>
                <td class='first'>Enter Text:</td>
                <td class='second'>
                <textarea name="notes_text" id="notes_text" rows="4" cols="50"></textarea>
                </td>
            </tr>
            <tr>
                <td class='first'>Upload PDF</td>
                <td class='second'>
                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf">
                <input type="hidden" id="class" name="class" value="<?php echo $class; ?>"/>
                <input type="hidden" id="stream" name="stream" value="<?php echo $stream; ?>"/>
                <input type="hidden" id="subject" name="subject" value="<?php echo $subject; ?>"/>
                <input type="hidden" id="section" name="section" value="<?php echo $section; ?>"/>
                <input type="hidden" id="chapter" name="chapter" value="<?php echo $chapter; ?>"/>
                </td>
                </td>
            </tr>
            <tr>
                <td class='first'>&nbsp;</td>
                <td class='second'> 
                    <input type="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>
<?php 
}
echo "</tr></td>";
echo "<tr><td class='bottomlink'><a target='_blank' href='noteslist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Notes List</a></td></tr>";
echo "</table>";
?>
<script>
function OnSubmit() 
  {
      //var selectedChapter = document.getElementById("chapters").value;
      //alert("Chapter="+selectedChapter);
      //var chapterhidden = document.getElementById("chapter");
      //chapterhidden.value = selectedChapter;
      //alert("hidden - " + chapterhidden.value);
      document.getElementById("uploadnotes").submit();//submit the form
  }
</script>
<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>