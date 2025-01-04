<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "ryythmwave";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO albums (title, artworkPath, artist, genre) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $album_name, $artworkPath, $artist_name, $genre);

    // Get form data
    $album_name = $_POST['title'];
    $artist_name = $_POST['name'];
    $genre = $_POST['genre'];

    // Handle file upload
    $artworkPath = '';
    if (isset($_FILES['artworkPath']) && $_FILES['artworkPath']['error'] == 0) {
        $upload_dir = '../../assets/images/artwork/';

        // Create uploads directory if it does not exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_file = $upload_dir . basename($_FILES['artworkPath']['name']);
        
        // Move uploaded file to the desired directory
        if (move_uploaded_file($_FILES['artworkPath']['tmp_name'], $upload_file)) {
            $artworkPath = $upload_file;
        } else {
            echo "<p>Error uploading file.</p>";
            exit();
        }
    } else {
        echo "<p>No file uploaded or there was an error.</p>";
        exit();
    }

    // Execute statement
    if ($stmt->execute()) {
        echo "<p>Album added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}

// Fetch albums from the database
$conn = new mysqli("localhost", "root", "", "ryythmwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$albums = $conn->query("SELECT * FROM albums");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Album</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo1.png">
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
        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group input[type="date"] {
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
        .back-button {
            position: absolute;
            left: 25px;
            top: 25px;
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
            Add New Album
        </div>
        <div class="form-container">
            <h2>Add Album</h2>
            <form action="add_album.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="album_name">Album Name</label>
                    <input type="text" id="album_name" name="title" required>
                </div>
                <div class="form-group">
                    <label for="artist_name">Artist Name</label>
                    <input type="text" id="artist_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="release_date">Genre</label>
                    <input type="text" id="genre" name="genre" required>
                </div>
                <div class="form-group">
                    <label for="artworkPath">Album Cover</label>
                    <input type="file" id="artworkPath" name="artworkPath" accept="image/*" required>
                </div>
                <button type="submit" class="submit-btn">Add Album</button>
            </form>
        </div>

        <div>
            <h2>Existing Albums</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Album Name</th>
                        <th>Artist Name</th>
                        <th>Genre</th>
                        <th>Album Cover</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($album = $albums->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $album['id']; ?></td>
                        <td><?php echo $album['title']; ?></td>
                        <td><?php echo $album['artist']; ?></td>
                        <td><?php echo $album['genre']; ?></td>
                        <td><img src="<?php echo $album['artworkPath']; ?>" alt="<?php echo $album['title']; ?>" style="width: 100px; height: auto;"></td>
                    </tr>
                    <?php endwhile; ?>
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
