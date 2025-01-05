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

// Fetch track details if an ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM songs WHERE id = $id"); // Correct table name
    if ($result && $result->num_rows > 0) {
        $track = $result->fetch_assoc();
    } else {
        die("Track not found. Please check the ID.");
    }
} else {
    die("No valid track ID specified.");
}

// Update track details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $album = $conn->real_escape_string($_POST['album']);
    $artist = $conn->real_escape_string($_POST['artist']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $path = $conn->real_escape_string($_POST['path']);

    $sql = "UPDATE songs 
            SET title = '$title', album = '$album', artist = '$artist', genre = '$genre', path = '$path' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Track updated successfully');</script>"; // Show success alert
        // Redirect after showing the message
        echo "<script>window.location.href = 'edit_track.php?id=$id';</script>";
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Track</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/logo.png">
    <style>
        /* Existing styles */
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../../admin_panel.php" class="back-button">Back</a>
            Edit Track
        </div>
        <div class="form-container">
            <h2>Edit Track</h2>
            <form action="edit_track.php?id=<?php echo $track['id']; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $track['id']; ?>">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($track['title']); ?>" required>
    </div>
    <div class="form-group">
        <label for="album">Album ID:</label>
        <input type="text" id="album" name="album" value="<?php echo htmlspecialchars($track['album']); ?>" required>
    </div>
    <div class="form-group">
        <label for="artist">Artist ID:</label>
        <input type="text" id="artist" name="artist" value="<?php echo htmlspecialchars($track['artist']); ?>" required>
    </div>
    <div class="form-group">
        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($track['genre']); ?>" required>
    </div>
    <div class="form-group">
        <label for="path">Track Path:</label>
        <input type="text" id="path" name="path" value="<?php echo htmlspecialchars($track['path']); ?>" required>
    </div>
    <button type="submit" class="submit-btn">Update Track</button>
</form>

        </div>
    </div>
</body>
</html>
