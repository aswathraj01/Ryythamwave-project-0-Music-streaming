<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../adminlogin.php");
    exit();
}

$id = $_GET['id'];

$conn = new mysqli("localhost", "root", "", "ryythmwave");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM user_table WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "User deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("Location: ../../admin_panel.php");
exit();
?>
