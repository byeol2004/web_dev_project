<?php
// Display PHP errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$db = 'gallery_db';  // Make sure this matches your actual database name
$user = 'root';      // Your database username
$pass = '';          // Your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Retrieve form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Basic validation
if (empty($username) || empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL statement
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed: ' . $conn->error]);
    exit();
}

// Bind parameters and execute statement
$stmt->bind_param('sss', $username, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
