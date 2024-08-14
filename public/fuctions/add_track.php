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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Track</title>
</head>
<body>
    <h2>Add New Track</h2>
    <form action="add_track.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="album_id">Album ID:</label>
        <input type="text" id="album_id" name="album_id" required><br>
        <label for="artist_id">Artist ID:</label>
        <input type="text" id="artist_id" name="artist_id" required><br>
        <button type="submit">Add Track</button>
    </form>
</body>
</html>
