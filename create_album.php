<?php
include 'config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $albumName = $_POST['album-name'];
    $albumDescription = $_POST['album-description'];
    $coverPhotoUrl = $_POST['cover-photo-url'];

    // Insert album into the database
    $stmt = $pdo->prepare("INSERT INTO albums (album_name, album_description, cover_photo_url) VALUES (:album_name, :album_description, :cover_photo_url)");
    $stmt->execute([
        ':album_name' => $albumName,
        ':album_description' => $albumDescription,
        ':cover_photo_url' => $coverPhotoUrl
    ]);

    // Redirect back to the albums page
    header('Location: albums.php');
    exit();
}
