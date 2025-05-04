<?php 
include_once 'session.php';
include_once "showerror.php";
ob_start();
$title = "Upload Notes";

include_once 'restrictedpage.php';//restrict page
include_once 'selection.php';
$border = "1";

//Check if we need to show the upload form
echo "<div class='container-fluid mt-4'>";
echo "<div class='row'>";
echo "<div class='col-12'>";

if(!empty($chapter))
{
?>
    <form action="../backend/upload.php" method="POST" id="uploadnotes" name="uploadnotes" enctype="multipart/form-data" onSubmit="OnSubmit()">
        <div class="card shadow p-4">
            <div class="mb-3">
                <label for="notes_title" class="form-label">Notes Title:</label>
                <input type="text" class="form-control" name="notes_title" id="notes_title">
            </div>
            <div class="mb-3">
                <label for="notes_text" class="form-label">Enter Text:</label>
                <textarea class="form-control" name="notes_text" id="notes_text" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="pdf_file" class="form-label">Upload PDF</label>
                <input type="file" class="form-control" name="pdf_file" id="pdf_file" accept="application/pdf">
                <input type="hidden" id="class" name="class" value="<?php echo $class; ?>"/>
                <input type="hidden" id="stream" name="stream" value="<?php echo $stream; ?>"/>
                <input type="hidden" id="subject" name="subject" value="<?php echo $subject; ?>"/>
                <input type="hidden" id="section" name="section" value="<?php echo $section; ?>"/>
                <input type="hidden" id="chapter" name="chapter" value="<?php echo $chapter; ?>"/>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
<?php 
}
echo "<div class='mt-3'>";
echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='noteslist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Notes List</a>";
echo "</div>";
echo "</div></div></div>";
?>
<script>
function OnSubmit() 
{
    document.getElementById("uploadnotes").submit();//submit the form
}
</script>
<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>