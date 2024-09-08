<?php
session_start();
include 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Get the user ID from the session and the photo ID from the request
$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$photo_id = $data['photo_id'] ?? null;

if ($photo_id) {
    // Insert the favorite into the database
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, photo_id) VALUES (?, ?)");
    if ($stmt->execute([$user_id, $photo_id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid photo ID']);
}
?>
