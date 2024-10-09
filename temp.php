<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = "localhost"; // Your database host
$user = "root"; // Your database username
$password = ""; // Your database password
$database = "ryythmwave"; // Your database name

// Create a connection
$connection = new mysqli($host, $user, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

?>


<!-- Category Section -->
<section id="category" class="section" style="display: none;">
    <h2>Manage Category</h2>
    <a href="public/functions/add_artist.php">Add New Category</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = $categories->fetch_assoc()): ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td>
                    <a href="public/functions/edit_artist.php?id=<?php echo htmlspecialchars($category['id']); ?>">Edit</a>
                    <a href="public/functions/delete_artist.php?id=<?php echo htmlspecialchars($category['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<?php
// Close the connection when done
$connection->close();
?>
