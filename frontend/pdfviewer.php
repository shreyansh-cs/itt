<?php
include_once "showerror.php";
include_once '../backend/utils.php';
$noteid = "";
if(isset($_GET['noteid']))
{
    $noteid = $_GET['noteid'];//noteid
}
else
{
    die("Are you trying to mess with url, Please Don't do this !!!!!!!!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PDF Viewer</title>
    <style>
        /* Style for the PDF viewer */
        #pdf-viewer {
            width: 100%;
            height: auto;
            /*overflow: hidden;*/
            overflow: scroll;
        }

        canvas {
            width: 100%;
            height: auto;
        }

        /* Prevent right-click on the PDF viewer */
        #pdf-viewer {
            pointer-events: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
</head>
<body>
    <div id="pdf-viewer">
        <canvas id="pdf-canvas"></canvas>
    </div>

    <script>
        //var url = 'http://127.0.0.1/uploads/notes/17410493989725.pdf';  // Path to your PDF file
        var url = 'https://itticon.site/itt/frontend/download.php?noteid=<?php echo $noteid; ?>';

        // Initialize the PDF.js library
        var pdfjsLib = window['pdfjs-dist/build/pdf'];

        // Set the workerSrc (PDF.js requires this to load PDFs properly)
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

        // Function to render the PDF
        function renderPDF(url) {
            var loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');
                
                // Fetch the first page
                pdf.getPage(1).then(function(page) {
                    var scale = 1.5;  // Set scale for zoom
                    var viewport = page.getViewport({ scale: scale });

                    var canvas = document.getElementById('pdf-canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render the page into the canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext).then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(error) {
                console.log('Error loading PDF: ' + error);
            });
        }

        renderPDF(url);  // Render the PDF from the URL
    </script>
</body>
</html>
