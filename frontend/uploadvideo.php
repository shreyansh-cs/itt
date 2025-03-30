<?php 
include_once 'session.php';
include_once "showerror.php";
ob_start();
$title = "Upload Video";

include_once 'restrictedpage.php';//restrict page
include_once 'selection.php';
$border = "1";
//Check if we need to show the upload form
echo "<table border='0'>";
echo "<tr><td>";

if(!empty($chapter))
{
?>
    <form action="../backend/upload.php" method="POST" id="uploadvideo" name="uploadvideo" enctype="multipart/form-data" onSubmit="OnSubmit()">
        <table class='upload_container' border="$border">
            <tr>
                <td class='first'>Video Title:</td>
                <td class='second'>
                <input type="text" name="video_title" id="video_title">
                </td>
            </tr>
            <tr>
                <td class='first'>Video Link</td>
                <td class='second'>
                <textarea name="video_link" id="video_link" rows="2" cols="50"></textarea>
                <!-- Hidden items for additional info about it -->
                <input type="hidden" id="class" name="class" value="<?php echo $class; ?>"/>
                <input type="hidden" id="stream" name="stream" value="<?php echo $stream; ?>"/>
                <input type="hidden" id="subject" name="subject" value="<?php echo $subject; ?>"/>
                <input type="hidden" id="section" name="section" value="<?php echo $section; ?>"/>
                <input type="hidden" id="chapter" name="chapter" value="<?php echo $chapter; ?>"/>
                </td>
            </tr>
            <tr>
                <!--td class='first'>&nbsp;</td-->
                <td colspan="2"> 
                    <input class='upload_button' type="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>
<?php 
}
echo "</tr></td>";
echo "<tr><td class='td_bottomlink'><a class='notes_link' target='_blank' href='noteslist.php?class=$class&stream=$stream&subject=$subject&section=$section&chapter=$chapter'>Notes & Video</a></td></tr>";
echo "</table>";
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