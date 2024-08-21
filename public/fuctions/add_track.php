<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $album_id = $_POST['album_id'];
    $artist_id = $_POST['artist_id'];

    $conn = new mysqli("localhost", "root", "", "ryythmwave");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO tracks (title, album_id, artist_id) VALUES ('$title', '$album_id', '$artist_id')";

    if ($conn->query($sql) === TRUE) {
        echo "New track added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Fetch tracks from the database
$conn = new mysqli("localhost", "root", "", "ryythmwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tracks = $conn->query("SELECT * FROM tracks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Track</title>
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
                    <label for="album_id">Album ID:</label>
                    <input type="text" id="album_id" name="album_id" required>
                </div>
                <div class="form-group">
                    <label for="artist_id">Artist ID:</label>
                    <input type="text" id="artist_id" name="artist_id" required>
                </div>
                <button type="submit" class="submit-btn">Add Track</button>
            </form>
        </div>

        <div>
            <h2>Existing Tracks</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Album ID</th>
                        <th>Artist ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($track = $tracks->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $track['id']; ?></td>
                        <td><?php echo $track['title']; ?></td>
                        <td><?php echo $track['album_id']; ?></td>
                        <td><?php echo $track['artist_id']; ?></td>
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
