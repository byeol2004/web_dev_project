<?php
include 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo_url = $_POST['photo_url'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insert the photo into the database
    $stmt = $pdo->prepare("INSERT INTO photos (photo_url, title, description) VALUES (?, ?, ?)");
    $stmt->execute([$photo_url, $title, $description]);

    // Redirect to gallery after successful upload
    header("Location: gallery.html");
    exit();
}
?>
