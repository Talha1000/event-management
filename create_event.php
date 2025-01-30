<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    $event_id = intval(sanitize_input($_POST['event_id']));
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $capacity = intval(sanitize_input($_POST['capacity']));
    $date = sanitize_input($_POST['date']);
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare('INSERT INTO events (id, name, description, capacity, date, created_by) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('issisi', $event_id, $name, $description, $capacity, $date, $created_by);
    if ($stmt->execute()) {
        $success = 'Event created successfully!';
    } else {
        $error = 'Failed to create event: ' . $conn->error;
    }
    $stmt->close();
}

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Create Event</title>';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>';
echo '</head>';
echo '<body onload="AOS.init()">';
echo '<div class="container mt-5">';
echo '<h1 class="text-center mb-4" data-aos="fade-down">Create an Event</h1>';
if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>";
if (!empty($success)) echo "<div class='alert alert-success'>$success</div>";
echo '<form method="POST" data-aos="fade-up">';
echo '<div class="mb-3">';
echo '<label for="event_id" class="form-label">Event ID</label>';
echo '<input type="number" name="event_id" id="event_id" class="form-control" required>'; 
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="name" class="form-label">Event Name</label>';
echo '<input type="text" name="name" id="name" class="form-control" required>'; 
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="description" class="form-label">Event Description</label>';
echo '<textarea name="description" id="description" class="form-control" rows="4" required></textarea>';
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="capacity" class="form-label">Capacity</label>';
echo '<input type="number" name="capacity" id="capacity" class="form-control" required>'; 
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="date" class="form-label">Event Date</label>';
echo '<input type="date" name="date" id="date" class="form-control" required>'; 
echo '</div>';
echo '<button type="submit" name="create_event" class="btn btn-primary">Create Event</button>';
echo '</form>';
echo '</div>';
echo '</body>';
echo '</html>';
?>