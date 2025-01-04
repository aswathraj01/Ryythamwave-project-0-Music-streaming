<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $album = $_POST['album'];
    $artist = $_POST['artist'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $path = $_POST['path'];

    $conn = new mysqli("localhost", "root", "", "ryythmwave");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO songs (title, album, artist, genre, path) VALUES ('$title', '$album', '$artist', '$genre', '$path', '$duration')";

    if ($conn->query($sql) === TRUE) {
        echo "New track added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Fetch songs from the database
$conn = new mysqli("localhost", "root", "", "ryythmwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$songs = $conn->query("SELECT * FROM songs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Track</title>
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

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .overtable{
            height: 200px;
            overflow-y: auto; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../../admin_panel.php" class="back-button">Back</a>
            Add New Track
        </div>
        <div class="form-container">
            <h2>Add Track</h2>
            <form action="add_track.php" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="album">Album ID:</label>
                    <input type="text" id="album" name="album" required>
                </div>
                <div class="form-group">
                    <label for="artist">Artist ID:</label>
                    <input type="text" id="artist" name="artist" required>
                </div>
                <div class="form-group">
                    <label for="artist">Duration :</label>
                    <input type="text" id="duration" name="duration" placeholder="Example: 3:41" required>
                </div>
                <div class="form-group">
                    <label for="artist">Genre :</label>
                    <input type="text" id="genre" name="genre" required>
                </div>
                <div class="form-group">
                    <label for="path">Track Path:</label>
                    <input type="text" id="path" name="path" required>
                </div>
                <button type="submit" class="submit-btn">Add Track</button>
            </form>
        </div>

        <h2>Existing songs</h2>
        <div class="overtable">
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Album ID</th>
                        <th>Artist ID</th>
                        <th>Genre</th>
                        <th>Path</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($track = $songs->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $track['id']; ?></td>
                        <td><?php echo $track['title']; ?></td>
                        <td><?php echo $track['album']; ?></td>
                        <td><?php echo $track['artist']; ?></td>
                        <td><?php echo $track['genre']; ?></td>
                        <td><?php echo $track['path']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
