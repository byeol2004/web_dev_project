<?php
include 'config.php'; // Include the database connection
session_start();

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit();
}

$userId = $_SESSION['user_id']; // Fetch user ID from session

// Handle adding a photo to favorites
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favorites'])) {
    $photoId = $_POST['photo_id'];
    
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, photo_id) VALUES (:user_id, :photo_id)");
    $success = $stmt->execute([':user_id' => $userId, ':photo_id' => $photoId]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Photo added to favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Unable to add to favorites']);
    }
    exit();
}

// Handle removing a photo from favorites
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_favorites'])) {
    $photoId = $_POST['photo_id'];
    
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND photo_id = :photo_id");
    $success = $stmt->execute([':user_id' => $userId, ':photo_id' => $photoId]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Photo removed from favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Unable to remove from favorites']);
    }
    exit();
}

// Fetch the user's favorite photos
$stmt = $pdo->prepare("SELECT photos.id, photos.title, photos.photo_url, photos.upload_date
    FROM photos 
    JOIN favorites ON photos.id = favorites.photo_id 
    WHERE favorites.user_id = :user_id
    ORDER BY upload_date DESC");
$stmt->execute([':user_id' => $userId]);

$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the favorite photos as JSON
echo json_encode([
    'success' => true,
    'favorites' => $favorites
]);
