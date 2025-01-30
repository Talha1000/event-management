<?php
session_start();
include 'config.php';

if (basename(__FILE__) === 'login.php') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitize_input($_POST['username']);
        $password = sanitize_input($_POST['password']);

        $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashedPassword);
        if ($stmt->fetch() && verify_password($password, $hashedPassword)) {
            $_SESSION['user_id'] = $user_id;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<title>Login</title>';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
    echo '</head>';
    echo '<body>';
    echo '<div class="container mt-5">';
    echo '<h1>Login</h1>';
    if (!empty($error)) echo "<p class='text-danger'>$error</p>";
    echo '<form method="POST">';
    echo '<div class="mb-3">';
    echo '<label for="username" class="form-label">Username</label>';
    echo '<input type="text" name="username" id="username" class="form-control" required>'; 
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="password" class="form-label">Password</label>';
    echo '<input type="password" name="password" id="password" class="form-control" required>'; 
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Login</button>';
    echo '</form>';
    echo '<p class="mt-3">New user? <a href="register.php">Create an account</a></p>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
}

?>