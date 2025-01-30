<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<link rel="stylesheet" href="assets/css/styles.css">';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css">';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>';
    echo '<script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>';
    echo '<title>Event Management</title>';
    echo '</head>';
    echo '<body onload="AOS.init()">';
    echo '<div class="container text-center mt-5" data-aos="fade-up">';
    echo '<h1>Welcome to the Event Management System</h1>';
    echo '<p class="mt-3">Register or Login to access the features</p>';
    echo '<a href="register.php" class="btn btn-primary mx-2">Register</a>';
    echo '<a href="login.php" class="btn btn-secondary mx-2">Login</a>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
} else {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<link rel="stylesheet" href="assets/css/styles.css">';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css">';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>';
    echo '<script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>';
    echo '<title>Dashboard</title>';
    echo '</head>';
    echo '<body onload="AOS.init()">';
    echo '<div class="container text-center mt-5">';
    echo '<h1 class="text-center" data-aos="fade-down">Welcome back, User!</h1>';
    echo '<div class="row mt-4">';

    echo '<div class="col-md-4" data-aos="fade-up">';
    echo '<div class="card text-center">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">Go to Dashboard</h5>';
    echo '<p class="card-text">Manage your events and activities seamlessly.</p>';
    echo '<a href="dashboard.php" class="btn btn-success">Dashboard</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="200">';
    echo '<div class="card text-center">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">Create an Event</h5>';
    echo '<p class="card-text">Organize and manage your own events seamlessly.</p>';
    echo '<a href="create_event.php" class="btn btn-primary">Create Event</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="400">';
    echo '<div class="card text-center">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">Logout</h5>';
    echo '<p class="card-text">Securely log out from your account.</p>';
    echo '<a href="logout.php" class="btn btn-danger">Logout</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '</div>'; // Row end
    echo '</div>'; // Container end

    echo '</body>';
    echo '</html>';
}
?>