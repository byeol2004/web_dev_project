<?php
include 'config.php'; // Include the database connection

// Fetch all albums from the database
$stmt = $pdo->query("SELECT * FROM albums ORDER BY created_at DESC");
$albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albums - MyGallery</title>
    <link rel="stylesheet" href="albums.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="toggle-sidebar">
        <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    </div>

    <div class="container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">MyGallery</div>
            <ul class="sidebar-links">
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="favorites.html"><i class="fas fa-heart"></i> Favourites</a></li>
                <li><a href="home.html"><i class="fas fa-search"></i> Explore</a></li>
                <li><a href="auth.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="logout.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <header>
                <nav>
                    <ul class="top-nav">
                        <li data-text="Home"><a href="home.html"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li data-text="Upload"><a href="upload.html"><i class="fa-solid fa-cloud-arrow-up"></i></a></li>
                        <li data-text="Gallery"><a href="gallery.php"><i class="fa-solid fa-photo-film"></i></a></li>
                        <li data-text="Albums"><a href="albums.php"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <!-- Create Album Section -->
<section class="create-album">
            <h2>Create New Album</h2>
    <form id="create-album-form" method="POST" action="create_album.php">
        <input type="text" name="album-name" placeholder="Album Name" id="album-name" required>
        <textarea name="album-description" placeholder="Album Description" id="album-description" required></textarea>
        <input type="text" name="cover-photo-url" placeholder="Cover Photo URL (HTTP only)" id="cover-photo-url">
        <button type="submit">Create Album</button>
    </form>
</section>


                <!-- Albums Section -->
<section class="albums">
    <h2>My Albums</h2>
    <div class="album-grid">
        <?php foreach ($albums as $album): ?>
            <div class="album-card">
                <!-- Display cover photo if available, otherwise use placeholder -->
                <img src="<?= htmlspecialchars($album['cover_photo_url']) ?: 'https://via.placeholder.com/150'; ?>" alt="<?= htmlspecialchars($album['album_name']); ?>">
                <div class="album-info">
                    <h3><?= htmlspecialchars($album['album_name']); ?></h3>
                    <p><?= htmlspecialchars($album['album_description']); ?></p>
                    <p>Created on: <?= date('F j, Y', strtotime($album['created_at'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
            </main>

            <footer>
                <p>&copy; 2024 MyGallery. All rights reserved.</p>
            </footer>
        </div>
    </div>
    <script>
        // Sidebar toggle functionality
        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');  // Show/hide the sidebar
            mainContent.classList.toggle('shifted');  // Shift the main content accordingly
        });
    </script>
</body>
</html>
