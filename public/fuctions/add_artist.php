<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $artist_name = $_POST['artist_name'];

    $conn = new mysqli("localhost", "root", "", "ryythmwave");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO artists (artist_name) VALUES ('$artist_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New artist added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Artist</title>
</head>
<body>
    <h2>Add New Artist</h2>
    <form action="add_artist.php" method="post">
        <label for="artist_name">Artist Name:</label>
        <input type="text" id="artist_name" name="artist_name" required><br>
        <button type="submit">Add Artist</button>
    </form>
</body>
</html>
