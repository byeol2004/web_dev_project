<?php
include 'config.php'; // Include the database connection

// Fetch all photos from the database
$stmt = $pdo->query("SELECT * FROM photos ORDER BY upload_date DESC");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="gallery.css">
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
                <li><a href="profile.html"><i class="fas fa-user"></i> Profile</a></li>
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
                        <li><a href="home.html"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li><a href="upload.html"><i class="fa-solid fa-cloud-arrow-up"></i></a></li>
                        <li><a href="gallery.php"><i class="fa-solid fa-photo-film"></i></a></li>
                        <li><a href="albums.php"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <h1>Gallery</h1>

                <?php
                // Group photos by upload date
                $groupedPhotos = [];
                foreach ($photos as $photo) {
                    $date = date('F j, Y', strtotime($photo['upload_date']));
                    if (!isset($groupedPhotos[$date])) {
                        $groupedPhotos[$date] = [];
                    }
                    $groupedPhotos[$date][] = $photo;
                }

                // Display photos grouped by date
                foreach ($groupedPhotos as $date => $photosOnDate): ?>
                    <section class="gallery-date">
                        <h2>Uploaded on: <?= htmlspecialchars($date); ?></h2>
                        <div class="gallery-grid">
                            <?php foreach ($photosOnDate as $photo): ?>
                                <div class="gallery-item" data-photo-id="<?= htmlspecialchars($photo['id']); ?>">
                                    <img src="<?= htmlspecialchars($photo['photo_url']); ?>" alt="Gallery Image">
                                    <div class="overlay">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="image-info">
                                        <p><?= htmlspecialchars($photo['title']); ?></p>
                                        <p>Uploaded on: <?= htmlspecialchars($date); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </main>

            <footer>
                <div class="footer-content">
                    <p>&copy; 2024 MyGallery. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Functionality to like a photo and send to the backend
        document.querySelectorAll('.gallery-item .overlay i').forEach(icon => {
            icon.addEventListener('click', function() {
                const photoId = this.closest('.gallery-item').getAttribute('data-photo-id');

                // Make an AJAX request to add the photo to the user's favorites
                fetch('add_favorite.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ photo_id: photoId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Photo added to your favorites!');
                    } else {
                        alert('Failed to add favorite. Try again.');
                    }
                });
            });
        });

        // Sidebar toggle functionality
        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('shifted');
        });
    </script>
</body>
</html>
