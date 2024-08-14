<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Change this if needed
$password = ""; // Change this if needed
$dbname = "ryythmwave";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data counts
$tracksCount = $conn->query("SELECT COUNT(*) as count FROM tracks")->fetch_assoc()['count'];
$albumsCount = $conn->query("SELECT COUNT(*) as count FROM albums")->fetch_assoc()['count'];
$artistsCount = $conn->query("SELECT COUNT(*) as count FROM artists")->fetch_assoc()['count'];
$usersCount = $conn->query("SELECT COUNT(*) as count FROM user_table")->fetch_assoc()['count'];

// Fetch data for other sections
$tracks = $conn->query("SELECT * FROM tracks");
$albums = $conn->query("SELECT * FROM albums");
$artists = $conn->query("SELECT * FROM artists");
$users = $conn->query("SELECT * FROM user_table");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="public/assets/css/admin_panel.css">
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(function (section) {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }

        window.onload = function() {
            showSection('dashboard'); // Show the dashboard by default
        }
    </script>
</head>
<body>

<div class="sidebar">
    <div class="profile">
        <h2>Admin Panel</h2>
        <p>Welcome, <?php echo $_SESSION['admin_username']; ?></p>
    </div>
    <ul>
        <li><a href="javascript:void(0);" onclick="showSection('dashboard')">Dashboard</a></li>
        <li><a href="javascript:void(0);" onclick="showSection('tracks')">Tracks</a></li>
        <li><a href="javascript:void(0);" onclick="showSection('albums')">Albums</a></li>
        <li><a href="javascript:void(0);" onclick="showSection('artists')">Artists</a></li>
        <li><a href="javascript:void(0);" onclick="showSection('users')">Users</a></li>
    </ul>
</div>

<div class="main-content">
    <header>
        <div class="header-title">
            <h1>Admin Dashboard</h1>
        </div>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <!-- Dashboard Section -->
    <section id="dashboard" class="section">
        <h2>Dashboard</h2>
        <div class="dashboard-stats">
            <div class="stat">
                <h3>Total Tracks</h3>
                <p><?php echo $tracksCount; ?></p>
            </div>
            <div class="stat">
                <h3>Total Albums</h3>
                <p><?php echo $albumsCount; ?></p>
            </div>
            <div class="stat">
                <h3>Total Artists</h3>
                <p><?php echo $artistsCount; ?></p>
            </div>
            <div class="stat">
                <h3>Total Users</h3>
                <p><?php echo $usersCount; ?></p>
            </div>
        </div>
    </section>

    <!-- Tracks Section -->
    <section id="tracks" class="section" style="display: none;">
        <h2>Manage Tracks</h2>
        <a href="public/fuctions/add_track.php">Add New Track</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Album</th>
                    <th>Artist</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($track = $tracks->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $track['id']; ?></td>
                    <td><?php echo $track['title']; ?></td>
                    <td><?php echo $track['album_id']; ?></td>
                    <td><?php echo $track['artist_id']; ?></td>
                    <td>
                        <a href="edit_track.php?id=<?php echo $track['id']; ?>">Edit</a>
                        <a href="delete_track.php?id=<?php echo $track['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Albums Section -->
    <section id="albums" class="section" style="display: none;">
        <h2>Manage Albums</h2>
        <a href="public/fuctions/add_album.php">Add New Album</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Album Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($album = $albums->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $album['id']; ?></td>
                    <td><?php echo $album['album_name']; ?></td>
                    <td>
                        <a href="edit_album.php?id=<?php echo $album['id']; ?>">Edit</a>
                        <a href="delete_album.php?id=<?php echo $album['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Artists Section -->
    <section id="artists" class="section" style="display: none;">
        <h2>Manage Artists</h2>
        <a href="public/fuctions/add_artist.php">Add New Artist</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Artist Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($artist = $artists->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $artist['id']; ?></td>
                    <td><?php echo $artist['artist_name']; ?></td>
                    <td>
                        <a href="public/fuctions/edit_artist.php?id=<?php echo $artist['id']; ?>">Edit</a>
                        <a href="public/fuctions/delete_artist.php?id=<?php echo $artist['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Users Section -->
    <section id="users" class="section" style="display: none;">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <a href="public/fuctions/edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="public/fuctions/delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

</div>

</body>
</html>

<?php $conn->close(); ?>
