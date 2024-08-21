<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ryythmwave";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $conn->real_escape_string(trim($_POST['fname']));
    $lname = $conn->real_escape_string(trim($_POST['lname']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    $mobile_no= $conn->real_escape_string(trim($_POST['mobileno']));

    // Check if the username or email already exists
    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // Username or email already exists
        echo "<script>alert('Username or Email already exists. Please try again.'); window.location.href='user_registration.html';</script>";
    } else {
        // Insert the user into the database without hashing the password
        $insert_query = "INSERT INTO user_table (first_name, last_name, username, password, email, mobile_no) VALUES ('$fname', '$lname', '$username', '$password', '$email','$mobile_no')";

        if ($conn->query($insert_query) === TRUE) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='login.html';</script>";
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>
