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
    $album_name = $_POST['album_name'];

    $sql = "UPDATE albums SET album_name='$album_name' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Album updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM albums WHERE id='$id'");
$album = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
</head>
<body>
    <h2>Edit Album</h2>
    <form action="edit_album.php" method="post">
        <input type="hidden" name="id" value="<?php echo $album['id']; ?>">
        <label for="album_name">Album Name:</label>
        <input type="text" id="album_name" name="album_name" value="<?php echo $album['album_name']; ?>" required><br>
        <button type="submit">Update Album</button>
    </form>
</body>
</html>
