<?php 
include_once "showerror.php";
ob_start();
session_start();
$title = "I.T.T Group of Education - Home";

include_once 'selection.php';

//Check if we need to show the upload form
if(!empty($chapter))
{
?>
    <form action="../backend/upload.php" method="POST" id="uploadnotes" name="uploadnotes" enctype="multipart/form-data" onSubmit="OnSubmit()">
        <table>
            <tr>
                <td>Notes Title:</td>
                <td>
                <input type="text" name="notes_title" id="notes_title">
                </td>
            </tr>
            <tr>
                <td>Enter Text:</td>
                <td>
                <textarea name="notes_text" id="notes_text" rows="4" cols="50"></textarea>
                </td>
            </tr>
            <tr>
                <td>Upload PDF</td>
                <td>
                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf">
                <input type="hidden" id="class" name="class" value="<?php echo $class; ?>"/>
                <input type="hidden" id="stream" name="stream" value="<?php echo $stream; ?>"/>
                <input type="hidden" id="subject" name="subject" value="<?php echo $subject; ?>"/>
                <input type="hidden" id="chapter" name="chapter" value="<?php echo $chapter; ?>"/>
                </td>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td> 
                    <input type="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>
<?php 
}
echo "<br/><br/><h2><a target='_blank' href='noteslist.php?class=$class&stream=$stream&subject=$subject&chapter=$chapter' style='text-decoration:underline'>Notes List</a></h2>";
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