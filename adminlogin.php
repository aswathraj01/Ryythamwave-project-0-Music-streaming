<?php
session_start();

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Database connection
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "ryythmwave";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admin_table WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin_panel.php");
        exit();
    } else {
        echo "<div style='
		display: flex;
		flex: 1;
		align-items: center;
		position: absolute;
		bottom: 200px;
		z-index:2;>
		<h6>Invalid username or password.</h6>
		</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Login</title>
    <link rel="stylesheet" href="public/assets/css/admin_login.css">
</head>
<body>
    <header class="admin-header">
        <div class="admin-header-div">
            <a href="Login.html"><button type="button">Home</button></a>
            <a href="Login.html"><button type="button">USER Login</button></a>
        </div>
    </header>
    <div class="login-box">
        <div class="key-icon">
            <img src="public/assets/icons/key_icon.png" alt="Key Icon">
        </div>
        <h2>Admin Panel</h2>
        <form action="adminlogin.php" method="post">
            <div class="textbox">
                <input type="text" id="username" name="username" required>
                <label for="username">Username</label>
            </div>
            <div class="textbox">
                <input type="password" id="password" name="password" required>
                <label for="password">Password</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
    <div class="copy">
        <img src="public/assets/icons/toppng.com-copyright-symbol-png-white-copyright-logo-in-white-2000x2000.png" alt="Copyright Symbol">
        <h6>Copyright owned by Helix</h6>
    </div>
</body>
</html>
