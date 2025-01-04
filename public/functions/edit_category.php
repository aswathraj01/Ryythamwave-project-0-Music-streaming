<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Create database connection
$conn = new mysqli("localhost", "root", "", "ryythmwave");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for updating category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    // Prepare and execute the update query
    $sql = $conn->prepare("UPDATE genres SET name=? WHERE id=?");
    $sql->bind_param("si", $name, $id);

    if ($sql->execute()) {
        // Redirect to admin panel with a success message
        header("Location: admin_panel.php?success=Category updated successfully");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
}

// Fetch the category details from the database
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $result = $conn->query("SELECT * FROM genres WHERE id='$id'");
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        echo "Category not found.";
        exit();
    }
} else {
    echo "Invalid category ID.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
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

        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../../admin_panel.php" class="back-button">Back</a>
            Edit Category
        </div>
        <div class="form-container">
            <h2>Edit Category</h2>
            <form action="edit_category.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>">
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>
                <button type="submit" class="submit-btn">Update Category</button>
            </form>
        </div>
    </div>
</body>
</html>
