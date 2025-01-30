<?php

session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND created_by = ?");
    $stmt->bind_param('ii', $event_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
        echo '<title>Edit Event</title>';
        echo '</head>';
        echo '<body>'; 
        echo '<div class="container mt-5">';
        echo '<h1>Edit Event</h1>';
        echo '<form method="POST" action="edit_event.php">';
        echo '<input type="hidden" name="event_id" value="' . $event_id . '">';
        echo '<div class="mb-3">';
        echo '<label class="form-label">Event Name</label>';
        echo '<input type="text" class="form-control" name="name" value="' . htmlspecialchars($row['name']) . '" required>'; 
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label class="form-label">Description</label>';
        echo '<textarea class="form-control" name="description" required>' . htmlspecialchars($row['description']) . '</textarea>';
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label class="form-label">Capacity</label>';
        echo '<input type="number" class="form-control" name="capacity" value="' . htmlspecialchars($row['capacity']) . '" required>'; 
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label class="form-label">Date</label>';
        echo '<input type="date" class="form-control" name="date" value="' . htmlspecialchars($row['date']) . '" required>'; 
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Update Event</button>';
        echo '</form>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    } else {
        echo 'Event not found or you are not authorized to edit it.';
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $capacity = intval(sanitize_input($_POST['capacity']));
    $date = sanitize_input($_POST['date']);

    $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, capacity = ?, date = ? WHERE id = ? AND created_by = ?");
    $stmt->bind_param('ssisii', $name, $description, $capacity, $date, $event_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo 'Event updated successfully.';
    } else {
        echo 'Failed to update event.';
    }
    $stmt->close();
}

?>