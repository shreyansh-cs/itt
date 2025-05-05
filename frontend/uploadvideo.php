<?php 
include_once 'session.php';
include_once "showerror.php";
ob_start();
$title = "Upload Video";

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
    <form action="../backend/upload.php" method="POST" id="uploadvideo" name="uploadvideo" enctype="multipart/form-data" onSubmit="OnSubmit()">
        <div class="card shadow p-4">
            <div class="mb-3">
                <label for="video_title" class="form-label">Video Title:</label>
                <input type="text" class="form-control" name="video_title" id="video_title">
            </div>
            <div class="mb-3">
                <label for="video_link" class="form-label">Video Link:</label>
                <textarea class="form-control" name="video_link" id="video_link" rows="2"></textarea>
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
echo "<a class='btn btn-primary btn-sm rounded-pill px-3' target='_blank' href='noteslist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Notes & Video</a>";
echo "</div>";
echo "</div></div></div>";
?>
<script>
function OnSubmit() 
{
    document.getElementById("uploadvideo").submit();//submit the form
}
</script>
<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>