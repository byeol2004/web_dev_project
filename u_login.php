<?php
// Display PHP errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$db = 'gallery_db';  // Ensure this matches your actual database name
$user = 'root';      // Your database username
$pass = '';          // Your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];

// Basic validation
if (empty($username) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

// Prepare SQL statement
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed: ' . $conn->error]);
    exit();
}

// Bind parameters and execute statement
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
