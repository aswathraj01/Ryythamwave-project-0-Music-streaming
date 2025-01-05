<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ryythmwave");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the album ID is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch album data from the database
    $result = $conn->query("SELECT * FROM albums WHERE id='$id'");
    if ($result && $result->num_rows > 0) {
        $album = $result->fetch_assoc();
    } else {
        echo "No album found.";
        exit();
    }

    // If the form is submitted, update the album information
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $genre = $_POST['genre'];
        $artworkPath = "";

        // If an image is uploaded, handle the file upload
        if (isset($_FILES['artwork']) && $_FILES['artwork']['error'] == 0) {
            $targetDir = "../../assets/images/artwork/";
            $targetFile = $targetDir . basename($_FILES["artwork"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an image
            if (getimagesize($_FILES["artwork"]["tmp_name"]) === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size (limit to 5MB)
            if ($_FILES["artwork"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if the file already exists
            if (file_exists($targetFile)) {
                echo "Sorry, the file already exists.";
                $uploadOk = 0;
            }

            // Attempt to upload the file
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["artwork"]["tmp_name"], $targetFile)) {
                    $artworkPath = $targetFile; // Save the path to the uploaded image
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Update the album information in the database
        $sql = "UPDATE albums SET title='$title', artist='$artist', genre='$genre'";

        // If a new artwork was uploaded, update the artwork path
        if ($artworkPath != "") {
            $sql .= ", artworkPath='$artworkPath'";
        }

        $sql .= " WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Album updated successfully</p>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Redirect after update to avoid form resubmission
        header("Location: edit_album.php?id=$id");
        exit();
    }
} else {
    echo "No album id specified.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #0c697d;
        }

        .container {
            padding: 20px;
        }

        .header {
            background-color: #2196F3;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 5px;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .submit-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../../admin_panel.php" class="back-button">Back to Admin Panel</a>
            Edit Album
        </div>
        <div class="form-container">
            <h2>Edit Album</h2>
            <form action="edit_album.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($album['id']); ?>">
                <div class="form-group">
                    <label for="title">Album Name:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($album['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="artist">Artist:</label>
                    <input type="text" id="artist" name="artist" value="<?php echo htmlspecialchars($album['artist']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="genre">Genre:</label>
                    <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($album['genre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="artwork">Artwork:</label>
                    <input type="file" name="artwork" id="artwork">
                </div>
                <button type="submit" class="submit-btn">Update Album</button>
            </form>
        </div>
    </div>
</body>
</html>
