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
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // You may want to hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE admin_table SET username='$username', password='$hashed_password', email='$email' WHERE id='{$_SESSION['admin_id']}'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully";
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_email'] = $email;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
header("Location: ../../admin_panel.php");
exit();
?>
