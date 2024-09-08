<?php
session_start();
include 'config.php'; // Include the database connection

if (isset($_POST['register'])) {
    $username = $_POST['reg_username'];
    $email = $_POST['reg_email'];
    $password = (int)$_POST['reg_password']; // Convert to integer
    $about_me = $_POST['reg_about_me'] ?? '';
    $profile_picture_url = $_POST['reg_profile_picture_url'] ?? '';

    // Validate profile picture URL (optional)
    if (filter_var($profile_picture_url, FILTER_VALIDATE_URL)) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($profile_picture_url, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $profile_picture = $profile_picture_url;
        } else {
            $reg_error = "Invalid profile picture URL. Please use a valid image link (jpg, jpeg, png, gif).";
        }
    } else {
        $reg_error = "Invalid URL format!";
    }

    // Check if user exists
    if (!isset($reg_error)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $reg_error = "User already exists!";
        } else {
            // Insert new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, profile_picture, about_me) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $password, $profile_picture, $about_me])) {
                $success_message = "Registration successful! You can now log in.";
            } else {
                $reg_error = "Registration failed!";
            }
        }
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = (int)$_POST['login_password']; // Convert to integer

    // Retrieve user from the database
    $stmt = $pdo->prepare("SELECT id, username, email, password, profile_picture, about_me FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password and login
    if ($user && $user['password'] == $password) { // Compare integer values
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_picture'] = $user['profile_picture'];
        $_SESSION['about_me'] = $user['about_me'];

        // Redirect to profile page
        header("Location: profile.php");
        exit(); // Ensure no further code execution
    } else {
        $login_error = "Invalid credentials!";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="auth.css"> <!-- Your existing CSS -->
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
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
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
                        <li data-text="Home"><a href="home.html"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li data-text="Upload"><a href="upload.html"><i class="fa-solid fa-cloud-arrow-up"></i></a></li>
                        <li data-text="Gallery"><a href="gallery.php"><i class="fa-solid fa-photo-film"></i></a></li>
                        <li data-text="Albums"><a href="albums.php"><i class="fa-solid fa-folder-plus"></i></a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <section class="auth-section">
                    <div class="auth-tabs">
                        <button class="tab-button active" onclick="showForm('login')">Login</button>
                        <button class="tab-button" onclick="showForm('register')">Register</button>
                    </div>

                    <div class="auth-forms">
                        <!-- Login Form -->
                        <div id="loginForm" class="auth-form">
                            <h2>Login</h2>
                            <?php if (isset($login_error)) { echo "<p style='color:red;'>$login_error</p>"; } ?>
                            <form method="post">
                                <input type="email" name="login_email" placeholder="Email" required>
                                <input type="password" name="login_password" placeholder="Password" required>
                                <button type="submit" name="login">Login</button>
                            </form>
                            <p class="toggle-link" onclick="showForm('register')">Don't have an account? Register here</p>
                        </div>

                        <!-- Registration Form -->
                        <div id="registerForm" class="auth-form" style="display: none;">
                            <h2>Register</h2>
                            <?php if (isset($reg_error)) { echo "<p style='color:red;'>$reg_error</p>"; } ?>
                            <?php if (isset($success_message)) { echo "<p style='color:green;'>$success_message</p>"; } ?>
<form method="post">
    <input type="text" name="reg_username" placeholder="Username" required>
    <input type="email" name="reg_email" placeholder="Email" required>
    <input type="password" name="reg_password" placeholder="Password" required>
    <textarea name="reg_about_me" placeholder="About Me"></textarea>
    <input type="url" name="reg_profile_picture_url" placeholder="Profile Picture URL (http://example.com/image.jpg)" required>
    <button type="submit" name="register">Register</button>
</form>

                            <p class="toggle-link" onclick="showForm('login')">Already have an account? Login here</p>
                        </div>
                    </div>
                </section>
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
        function showForm(formType) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginButton = document.querySelector('.tab-button.active');
            const registerButton = document.querySelector('.tab-button:not(.active)');
    
            if (formType === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                loginButton.classList.add('active');
                registerButton.classList.remove('active');
            } else if (formType === 'register') {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                loginButton.classList.remove('active');
                registerButton.classList.add('active');
            }
        }
    
        // Sidebar toggle logic
        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
    
        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');  // Show/hide the sidebar
            mainContent.classList.toggle('active'); // Adjust content width
        });
    </script>
</body>
</html>

