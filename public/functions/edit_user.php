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

$successMessage = "";  // Variable to hold success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the 'id' is passed from the form correctly
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
    } else {
        echo "User ID is missing from the form. Please check the form submission.";
        exit();
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if a new password is provided and update it
    if ($password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id='$id'";
    } else {
        $update_sql = "UPDATE users SET username='$username', email='$email' WHERE id='$id'";
    }

    if ($conn->query($update_sql) === TRUE) {
        $successMessage = "User updated successfully";  // Set success message
        // Redirect to the admin panel after success
        header("Location: ../../admin_panel.php");
        exit();  // Ensure no further code is executed after redirection
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
}

// Fetch user details
if (isset($_GET['id'])) {  // Ensure the id is passed via GET
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id='$id'");
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found. Please check the ID.";
        exit();
    }
} else {
    echo "No user ID provided.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #0c697d;
        }

        .container {
            padding: 20px;
        }

        .header {
            background-color: #2196F3;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 5px;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .submit-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .success-message {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../../admin_panel.php" class="back-button">Back to Admin Panel</a>
            Edit User
        </div>
        <div class="form-container">
            <?php if ($successMessage): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <h2>Edit User</h2>
            <form action="edit_user.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (leave blank to keep current password):</label>
                    <input type="password" id="password" name="password">
                </div>
                <button type="submit" class="submit-btn">Update User</button>
            </form>
        </div>
    </div>
</body>
</html>
