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
    $stmt = $conn->prepare("INSERT INTO playlists (user_id, name, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $name, $created_at);

    // Get form data
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $created_at = date('Y-m-d H:i:s'); // Set the current timestamp

    // Execute statement
    if ($stmt->execute()) {
        echo "<p>Playlist added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}

// Fetch playlists from the database
$conn = new mysqli("localhost", "root", "", "ryythmwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$playlists = $conn->query("SELECT * FROM playlists");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Playlist</title>
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
        .form-group input[type="number"] {
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
            Add New Playlist
        </div>
        <div class="form-container">
            <h2>Add Playlist</h2>
            <form action="add_playlist.php" method="post">
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" id="user_id" name="user_id" required>
                </div>
                <div class="form-group">
                    <label for="name">Playlist Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <button type="submit" class="submit-btn">Add Playlist</button>
            </form>
        </div>

        <div>
            <h2>Existing Playlists</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Playlist Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($playlist = $playlists->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $playlist['id']; ?></td>
                        <td><?php echo $playlist['user_id']; ?></td>
                        <td><?php echo $playlist['name']; ?></td>
                        <td><?php echo $playlist['created_at']; ?></td>
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
