<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/itt/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding-top: 40px; /* Padding for redirect banner */
        }
        .redirect-banner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #ffc107;
            color: #000;
            text-align: center;
            padding: 10px;
            z-index: 1030;
            font-weight: 500;
        }
        .redirect-banner a {
            color: #000;
            text-decoration: underline;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Redirect Banner -->
    <div class="redirect-banner">
        🚀 We're moving to <a href="https://itticon.in" target="_blank">itticon.in</a>! Please update your bookmarks.
    </div>

    <main class="container mt-4">
        <?php /*echo $content;*/ ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>