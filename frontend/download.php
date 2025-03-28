<?php
include_once 'session.php';
include_once "showerror.php";
include_once '../backend/utils.php';

if(!isSessionValid() || !doesUserHasSubscription($error))
{
    die("What's this, Are you trying without having proper subsciption?");
}

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
            margin: 0px;
            text-align: center;
            width: 100%;
            height:fit-content ;
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
        var url = 'https://itticon.site/itt/frontend/supplypdf.php?noteid=<?php echo $noteid; ?>';

        // Initialize the PDF.js library
        var pdfjsLib = window['pdfjs-dist/build/pdf'];

        // Set the workerSrc (PDF.js requires this to load PDFs properly)
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
        const pdfDoc = pdfDoc_;
        const numPages = pdfDoc.numPages;
        console.log('Number of pages:', numPages);

        // Render all pages
        for (let pageNum = 1; pageNum <= numPages; pageNum++) {
            renderPage(pdfDoc, pageNum);
        }
        });

        function renderPage(pdfDoc, pageNum) {
            pdfDoc.getPage(pageNum).then(function(page) {
            const scale = 2.0; // Set the scale for rendering
            const viewport = page.getViewport({ scale: scale });

            // Prepare the canvas element to render the page
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            document.body.appendChild(canvas); // Add canvas to the body or to your desired container

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            // Render the page onto the canvas
            const renderContext = {
            canvasContext: ctx,
            viewport: viewport
            };
            page.render(renderContext).promise.then(function() {
            console.log('Page ' + pageNum + ' rendered');
            });
        });
        }

    </script>
</body>
</html>
