<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

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

// Fetch admin details
$adminId = $_SESSION['admin_id'] ?? null;
$adminUsername = $_SESSION['admin_username'] ?? '';

$adminProfilePicture = "default_profile.png"; // default image if no profile picture is found
if ($adminId) {
    $profileQuery = "SELECT setting_value FROM admin_settings WHERE admin_id = ? AND setting_key = 'profile_picture'";
    $stmt = $conn->prepare($profileQuery);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $profileRow = $result->fetch_assoc();
        $adminProfilePicture = $profileRow['setting_value'] ?: "default_profile.png"; // use default if empty
    }
    $stmt->close();
}

// Ensure the profile picture path is correct
$adminProfilePicturePath = "public/assets/images/profile-pics" . $adminProfilePicture;
if (!file_exists($adminProfilePicturePath) || empty($adminProfilePicture)) {
    $adminProfilePicturePath = "public/assets/images/profile-pics/default_profile.png"; // Default profile picture
}

// Fetch data counts
$songsCount = $conn->query("SELECT COUNT(*) as count FROM songs")->fetch_assoc()['count'];
$albumsCount = $conn->query("SELECT COUNT(*) as count FROM albums")->fetch_assoc()['count'];
$artistsCount = $conn->query("SELECT COUNT(*) as count FROM artists")->fetch_assoc()['count'];
$usersCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Fetch playlists
$playlists = $conn->query("SELECT * FROM playlists");

// Fetch data for other sections
$songs = $conn->query("SELECT * FROM songs");
$albums = $conn->query("SELECT * FROM albums");
$artists = $conn->query("SELECT * FROM artists");
$users = $conn->query("SELECT * FROM users");

// Fetch number of users registered per month
$user_registrations_query = "SELECT MONTHNAME(signUpDate) as month, COUNT(id) as count
                            FROM users
                            GROUP BY MONTH(signUpDate)
                            ORDER BY MONTH(signUpDate)";
$user_registrations_result = $conn->query($user_registrations_query);

$monthly_user_data = [];
$monthly_user_labels = [];

while ($row = $user_registrations_result->fetch_assoc()) {
    $monthly_user_labels[] = $row['month'];
    $monthly_user_data[] = $row['count'];
}

// Fetch monthly traffic data
$traffic_query = "SELECT MONTHNAME(date) as month, COUNT(*) as count
                  FROM traffic
                  GROUP BY MONTH(date)
                  ORDER BY MONTH(date)";
$traffic_result = $conn->query($traffic_query);

$monthly_traffic_data = [];
$monthly_traffic_labels = [];

while ($row = $traffic_result->fetch_assoc()) {
    $monthly_traffic_labels[] = $row['month'];
    $monthly_traffic_data[] = $row['count'];
}

$genres = $conn->query("SELECT * FROM genres");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="public/assets/css/admin_panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(function (section) {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }

        window.onload = function() {
            showSection('dashboard');

            // Chart for songs, Albums, Artists, and Users
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['songs', 'Albums', 'Artists', 'Users'],
                    datasets: [{
                        label: '# of Entries',
                        data: [<?php echo $songsCount; ?>, <?php echo $albumsCount; ?>, <?php echo $artistsCount; ?>, <?php echo $usersCount; ?>],
                        backgroundColor: [
                            'rgba(255, 0, 0, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(0, 0, 0, 0.2)',
                            'rgba(255, 0, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 0, 0, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(0, 0, 0, 1)',
                            'rgba(255, 0, 255, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Monthly User Registrations
            const userCtx = document.getElementById('userChart').getContext('2d');
            const userChart = new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($monthly_user_labels); ?>, // Months from PHP
                    datasets: [{
                        label: 'Users Registered',
                        data: <?php echo json_encode($monthly_user_data); ?>, // User count from PHP
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Monthly Traffic
            const trafficCtx = document.getElementById('trafficChart').getContext('2d');
            const trafficChart = new Chart(trafficCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($monthly_traffic_labels); ?>, // Months from PHP
                    datasets: [{
                        label: 'Page Traffic',
                        data: <?php echo json_encode($monthly_traffic_data); ?>, // Traffic data from PHP
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar collapsed">
<div class="profile">
        <h2>Admin Panel</h2>
        <img src="<?php echo htmlspecialchars($adminProfilePicturePath); ?>" alt="Admin Profile" draggable="false" style="width: 100px; height: 100px; border-radius: 50%;">
        <p>Welcome, <?php echo htmlspecialchars($adminUsername); ?></p>
    </div>
    <hr>
    <ul>
        <li><a href="javascript:void(0);" onclick="showSection('dashboard')"><img id="dash" src="public/assets/icons/dashboard.svg"><span class="text">Dashboard</span></a></li>
        <li><a href="javascript:void(0);" onclick="showSection('songs')"><img src="public/assets/icons/file-music.svg"><span class="text">songs</span></a></li>
        <li><a href="javascript:void(0);" onclick="showSection('albums')"><img src="public/assets/icons/journal-album.svg"><span class="text">Albums</span></a></li>
        <li><a href="javascript:void(0);" onclick="showSection('artists')"><img src="public/assets/icons/disc.svg"><span class="text">Artists</span></a></li>
        <li><a href="javascript:void(0);" onclick="showSection('playlists')"><img src="public/assets/icons/music-note-list.svg"><span class="text">Playlists</span></a></li>
        <li><a href="javascript:void(0);" onclick="showSection('category')"><img src="public/assets/icons/collection-play.svg"><span class="text">Category</span></a></li>
        <li><a href="javascript:void(0);" onclick="showSection('users')"><img src="public/assets/icons/people.svg"><span class="text">Users</span></a></li>
        <div class="settings">
            <li><a href="javascript:void(0);" onclick="showSection('edit-profile')"><img src="public/assets/icons/Sliders.svg"></a></li>
        </div>
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
    <section id="dashboard" class="section active">
        <h2>Dashboard</h2>
        <div class="dashboard-stats">
            <div class="stat">
                <h3>Total songs</h3>
                <p><?php echo $songsCount; ?></p>
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

        <!-- Graph Section -->
        <div class="graph-container">
            <div class="chart-box">
                <canvas id="myChart"></canvas>
            </div>
            <div class="chart-box">
                <canvas id="userChart"></canvas>
            </div>
            <div class="chart-box">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

    </section>

    <!-- songs Section -->
    <section id="songs" class="section" style="display: none;">
        <h2>Manage songs</h2>
        <a href="public/functions/add_track.php">Add New Track</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Genre</th>
                    <th>Duration</th>
                    <th>File Path</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php while ($track = $songs->fetch_assoc()): ?>
                <tr>
                <td><?php echo isset($track['id']) ? htmlspecialchars($track['id']) : 'N/A'; ?></td>
                <td><?php echo isset($track['title']) ? htmlspecialchars($track['title']) : 'N/A'; ?></td>
                <td><?php echo isset($track['artist']) ? htmlspecialchars($track['artist']) : 'N/A'; ?></td>
                <td><?php echo isset($track['album']) ? htmlspecialchars($track['album']) : 'N/A'; ?></td>
                <td><?php echo isset($track['genre']) ? htmlspecialchars($track['genre']) : 'N/A'; ?></td>
                <td><?php echo isset($track['duration']) ? htmlspecialchars($track['duration']) : 'N/A'; ?></td>
                <td><?php echo isset($track['file_path']) ? htmlspecialchars($track['file_path']) : 'N/A'; ?></td>
                <td>
                    <a href="public/functions/edit_track.php?id=<?php echo htmlspecialchars($track['id']); ?>">Edit</a>
                    <a href="public/functions/delete_track.php?id=<?php echo htmlspecialchars($track['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        </table>
    </section>

    <!-- Albums Section -->
    <section id="albums" class="section" style="display: none;">
        <h2>Manage Albums</h2>
        <a href="public/functions/add_album.php">Add New Album</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Release Date</th>
                    <th>Album Cover</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($album = $albums->fetch_assoc()): ?>
                <tr>
                <td><?php echo $album['id']; ?></td>
                <td><?php echo $album['album_name']; ?></td>
                <td><?php echo $album['artist_name']; ?></td>
                <td><?php echo $album['release_date']; ?></td>
                <td><img src="<?php echo $album['album_cover']; ?>" alt="<?php echo $album['album_name']; ?>" style="width: 100px; height: auto;"></td>
                <td>
                    <a href="public/functions/edit_album.php?id=<?php echo htmlspecialchars($album['id']); ?>">Edit</a>
                    <a href="public/functions/delete_album.php?id=<?php echo htmlspecialchars($album['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        </table>
    </section>

    <!-- Artists Section -->
    <section id="artists" class="section" style="display: none;">
        <h2>Manage Artists</h2>
        <a href="public/functions/add_artist.php">Add New Artist</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($artist = $artists->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $artist['id']; ?></td>
                    <td><?php echo $artist['artist_name']; ?></td>
                    <td>
                        <a href="public/functions/edit_artist.php?id=<?php echo htmlspecialchars($artist['id']); ?>">Edit</a>
                        <a href="public/functions/delete_artist.php?id=<?php echo htmlspecialchars($artist['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Playlists Section -->
    <section id="playlists" class="section">
    <h2>Manage Playlists</h2>
    <a href="public/functions/add_playlist.php">Add New Playlist</a>
    <table>
        <thead>
            <tr>
                <th>Playlist ID</th>
                <th>Playlist Name</th>
                <th>User ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($playlist = $playlists->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($playlist['id']); ?></td>
                    <td><?php echo htmlspecialchars($playlist['name']); ?></td>
                    <td><?php echo htmlspecialchars($playlist['owner']); ?></td>
                    <td>
                        <a href="public/functions/edit_playlist.php?id=<?php echo $playlist['id']; ?>">Edit</a> |
                        <a href="public/functions/delete_playlist.php?id=<?php echo $playlist['id']; ?>">Delete</a>
                    </td>
                </tr>
                <tr class="track-list" style="display: none;">
                    <td colspan="4">
                        <strong>songs in Playlist:</strong>
                        <ul>
                            <?php
                            // Fetch songs for the current playlist
                            $playlist_id = $playlist['id'];
                            $track_query = "SELECT t.* FROM songs t 
                                            JOIN playlistsongs pt ON t.id = pt.songId 
                                            WHERE pt.playlistId = ?";
                            $stmt = $conn->prepare($track_query);
                            $stmt->bind_param("i", $playlist_id);
                            $stmt->execute();
                            $track_result = $stmt->get_result();

                            if ($track_result && $track_result->num_rows > 0) {
                                while ($track = $track_result->fetch_assoc()) {
                                    echo '<li>' . htmlspecialchars($track['title']) . '</li>';
                                }
                            } else {
                                echo '<li>No songs found for this playlist.</li>';
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<script>
    // Toggle track list visibility
    document.querySelectorAll('table tbody tr').forEach(function(row) {
        row.addEventListener('click', function() {
            const nextRow = this.nextElementSibling;
            if (nextRow && nextRow.classList.contains('track-list')) {
                nextRow.style.display = nextRow.style.display === 'none' ? '' : 'none';
            }
        });
    });
</script>

    <!-- Category Section -->
<section id="category" class="section" style="display: none;">
    <h2>Manage Category</h2>
    <a href="public/functions/add_category.php">Add New Category</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = $genres->fetch_assoc()): ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td>
                    <a href="public/functions/edit_category.php?id=<?php echo htmlspecialchars($category['id']); ?>">Edit</a>
                    <a href="public/functions/delete_category.php?id=<?php echo urlencode(htmlspecialchars($category['id'])); ?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

    <!-- Users Section -->
    <section id="users" class="section" style="display: none;">
        <h2>Manage Users</h2>
        <a href="public/functions/add_user.php">Add New User</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['signUpDate']); ?></td>
                    <td>
                        <a href="public/functions/edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Edit</a>
                        <a href="public/functions/delete_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Edit Profile Section -->
    <section id="edit-profile" class="section" style="display: none;">
        <h2>Edit Profile</h2>
        <form action="public/functions/update_profile.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['admin_username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['admin_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="from-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>
            <button type="submit">Update Profile</button>
        </form>
        
    </section>
</div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
