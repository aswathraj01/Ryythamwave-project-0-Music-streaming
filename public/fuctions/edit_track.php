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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $album_id = $_POST['album_id'];
    $artist_id = $_POST['artist_id'];

    $sql = "UPDATE tracks SET title='$title', album_id='$album_id', artist_id='$artist_id' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Track updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tracks WHERE id='$id'");
$track = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Track</title>
</head>
<body>
    <h2>Edit Track</h2>
    <form action="edit_track.php" method="post">
        <input type="hidden" name="id" value="<?php echo $track['id']; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $track['title']; ?>" required><br>
        <label for="album_id">Album ID:</label>
        <input type="text" id="album_id" name="album_id" value="<?php echo $track['album_id']; ?>" required><br>
        <label for="artist_id">Artist ID:</label>
        <input type="text" id="artist_id" name="artist_id" value="<?php echo $track['artist_id']; ?>" required><br>
        <button type="submit">Update Track</button>
    </form>
</body>
</html>
