<?php
session_start();
include 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$profile_picture = $_SESSION['profile_picture'];
$about_me = $_SESSION['about_me'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>About Me: <?php echo htmlspecialchars($about_me); ?></p>

        <?php if ($profile_picture): ?>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width:150px;height:150px;">
        <?php else: ?>
            <p>No profile picture uploaded.</p>
        <?php endif; ?>

        <h2>Your Favorites</h2>
        <div class="gallery-grid">
            <?php foreach ($favorites as $favorite): ?>
                <div class="gallery-item">
                    <img src="<?= htmlspecialchars($favorite['photo_url']); ?>" alt="Favorite Image">
                    <div class="image-info">
                        <p><?= htmlspecialchars($favorite['title']); ?></p>
                        <p>Uploaded on: <?= htmlspecialchars(date('F j, Y', strtotime($favorite['upload_date']))); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
