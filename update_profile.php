<?php
session_start();
$servername = "localhost";
$username = "root"; // Your DB username
$password = ""; // Your DB password
$dbname = "gallery_db"; // Your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $new_username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Optionally hash the password if updated
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$new_username', email='$email', password='$password' WHERE username='$username'";
    } else {
        $sql = "UPDATE users SET username='$new_username', email='$email' WHERE username='$username'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $new_username;
        echo "Profile updated successfully";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
$conn->close();
?>
