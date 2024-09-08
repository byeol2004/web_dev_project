<?php
include 'config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photoId = $_POST['photo_id'];
    $albumId = $_POST['album_id'];

    // Insert record into album_photos table
    $stmt = $pdo->prepare("INSERT INTO album_photos (album_id, photo_id) VALUES (:album_id, :photo_id)");
    $stmt->execute([
        ':album_id' => $albumId,
        ':photo_id' => $photoId
    ]);

    // Redirect back to gallery page
    header('Location: gallery.php');
    exit();
}
