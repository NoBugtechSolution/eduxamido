<?php
$videoFile = "Output.mp4"; // Change this to your actual video file name
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
    <title>Demo </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            /* Light background */
        }

        .video-container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }

        video {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="video-container">
        <h2 class="text-primary">Demo</h2>
        <video controls>
            <source src="<?= htmlspecialchars($videoFile) ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</body>

</html>