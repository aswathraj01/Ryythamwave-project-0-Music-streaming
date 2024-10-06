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
    $stmt = $conn->prepare("INSERT INTO albums (album_name, album_cover, artist_name, release_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $album_name, $album_cover, $artist_name, $release_date);

    // Get form data
    $album_name = $_POST['album_name'];
    $artist_name = $_POST['artist_name'];
    $release_date = $_POST['release_date'];

    // Handle file upload
    $album_cover = '';
    if (isset($_FILES['album_cover']) && $_FILES['album_cover']['error'] == 0) {
        $upload_dir = '../../uploads/'; // Adjust this path based on your directory structure

        // Create uploads directory if it does not exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_file = $upload_dir . basename($_FILES['album_cover']['name']);
        
        // Move uploaded file to the desired directory
        if (move_uploaded_file($_FILES['album_cover']['tmp_name'], $upload_file)) {
            $album_cover = $upload_file;
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

// Fetch artists from the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$artists = $conn->query("SELECT id, artist_name FROM artists"); // Assume there's an artists table


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
        .form-group select,
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="file"]:focus,
        .form-group select:focus,
        .form-group input[type="date"]:focus {
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

        img {
            width: 100px;
            height: auto;
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
                    <input type="text" id="album_name" name="album_name" required>
                </div>
                <div class="form-group">
                    <label for="artist_id">Artist</label>
                    <select id="artist_id" name="artist_id" required>
                        <option value="">Select Artist</option>
                        <?php while ($artist = $artists->fetch_assoc()) { ?>
                            <option value="<?php echo $artist['id']; ?>"><?php echo htmlspecialchars($artist['artist_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="release_date">Release Date</label>
                    <input type="date" id="release_date" name="release_date" required>
                </div>
                <div class="form-group">
                    <label for="album_cover">Album Cover</label>
                    <input type="file" id="album_cover" name="album_cover" accept="image/*" required>
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
                        <th>Release Date</th>
                        <th>Album Cover</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($album = $albums->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $album['id']; ?></td>
                        <td><?php echo $album['album_name']; ?></td>
                        <td><?php echo $album['artist_name']; ?></td>
                        <td><?php echo $album['release_date']; ?></td>
                        <td><img src="<?php echo $album['album_cover']; ?>" alt="<?php echo $album['album_name']; ?>" style="width: 100px; height: auto;"></td>
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
