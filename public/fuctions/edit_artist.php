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
    $artist_name = $_POST['artist_name'];

    $sql = "UPDATE artists SET artist_name='$artist_name' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Artist updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM artists WHERE id='$id'");
$artist = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artist</title>
</head>
<body>
    <h2>Edit Artist</h2>
    <form action="edit_artist.php" method="post">
        <input type="hidden" name="id" value="<?php echo $artist['id']; ?>">
        <label for="artist_name">Artist Name:</label>
        <input type="text" id="artist_name" name="artist_name" value="<?php echo $artist['artist_name']; ?>" required><br>
        <button type="submit">Update Artist</button>
    </form>
</body>
</html>
