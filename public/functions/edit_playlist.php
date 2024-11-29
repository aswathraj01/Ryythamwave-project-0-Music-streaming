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

// Fetch all playlists to display in the table
$playlists_result = $conn->query("SELECT * FROM playlists");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $user_id = $_POST['owner'];
    $name = $_POST['name'];

    $sql = "UPDATE playlists SET owner='$user_id', name='$name' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Playlist updated successfully</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM playlists WHERE id='$id'");
    $playlist = $result->fetch_assoc();
} else {
    $playlist = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Playlist</title>
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

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        td {
            background-color: #fff;
            color: #555;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        .edit-button {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .edit-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../../admin_panel.php" class="back-button">Back to Admin Panel</a>
            Edit Playlist
        </div>

        <div class="form-container">
            <h2>Edit Playlist</h2>
            <?php if ($playlist): ?>
                <form action="edit_playlist.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($playlist['id']); ?>">
                    <div class="form-group">
                        <label for="user_id">User Name:</label>
                        <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($playlist['owner']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Playlist Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($playlist['name']); ?>" required>
                    </div>
                    <button type="submit" class="submit-btn">Update Playlist</button>
                </form>
            <?php else: ?>
                <p>Select a playlist to edit.</p>
            <?php endif; ?>
        </div>

        <div class="form-container">
            <h2>All Playlists</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Playlist Name</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $playlists_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['owner']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><a href="edit_playlist.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="edit-button">Edit</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
