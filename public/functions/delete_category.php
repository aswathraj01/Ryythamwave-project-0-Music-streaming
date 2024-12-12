<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../adminlogin.php");
    exit();
}

// Get the category ID from the URL and sanitize it
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    // If no valid ID is provided, redirect with an error
    header("Location: ../../admin_panel.php?error=Invalid ID");
    exit();
}

// Create a new database connection
$conn = new mysqli("localhost", "root", "", "ryythmwave");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query using a prepared statement
$sql = $conn->prepare("DELETE FROM genres WHERE id = ?");
$sql->bind_param("i", $id);  // 'i' for integer

// Execute the query
if ($sql->execute()) {
    // If the category is deleted, redirect with a success message
    header("Location: ../../admin_panel.php?success=Category deleted successfully");
} else {
    // If an error occurs, redirect with an error message
    header("Location: ../../admin_panel.php?error=Failed to delete category");
}

// Close the connection
$conn->close();

exit();
?>
