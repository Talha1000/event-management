<?php

session_start();
include 'config.php';

if (basename(__FILE__) === 'register.php') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $password = hash_password(sanitize_input($_POST['password']));

        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $email, $password);
        if ($stmt->execute()) {
            header('Location: login.php');
            exit;
        } else {
            $error = 'Registration failed: ' . $conn->error;
        }
        $stmt->close();
    }

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<title>Register</title>';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
    echo '</head>';
    echo '<body>';
    echo '<div class="container mt-5">';
    echo '<h1>Register</h1>';
    if (!empty($error)) echo "<p class='text-danger'>$error</p>";
    echo '<form method="POST">';
    echo '<div class="mb-3">';
    echo '<label for="username" class="form-label">Username</label>';
    echo '<input type="text" name="username" id="username" class="form-control" required>'; 
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="email" class="form-label">Email</label>';
    echo '<input type="email" name="email" id="email" class="form-control" required>'; 
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="password" class="form-label">Password</label>';
    echo '<input type="password" name="password" id="password" class="form-control" required>'; 
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Register</button>';
    echo '</form>';
    echo '<p class="mt-3">Already have an account? <a href="login.php">Log in</a></p>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
}
?>