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
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $update_sql = "UPDATE user_table SET username='$username', email='$email' WHERE id='$id'";
    if ($password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE user_table SET username='$username', email='$email', password='$password' WHERE id='$id'";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "User updated successfully";
    } else {
        echo "Error: " . $update_sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM user_table WHERE id='$id'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form action="edit_user.php" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br>
        <label for="password">Password (leave blank to keep current password):</label>
        <input type="password" id="password" name="password"><br>
        <button type="submit">Update User</button>
    </form>
</body>
</html>
