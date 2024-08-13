<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ryythmwave";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from the form
$user_username = $_POST['username'];
$user_password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT id, password FROM user_table WHERE username = ?");
$stmt->bind_param("s", $user_username);
$stmt->execute();
$stmt->store_result();

// Check if username exists
if ($stmt->num_rows == 1) {
    $stmt->bind_result($id, $stored_password);
    $stmt->fetch();

    // Verify password
    if ($user_password === $stored_password) {
        // Password is correct
        $_SESSION['user_id'] = $id;
        $_SESSION['user_username'] = $user_username;
        header("Location: index.html"); // Redirect to home
        exit();
    } else {
        // Password is incorrect
        echo "Invalid username or password.";
    }
} else {
    // Username does not exist
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
