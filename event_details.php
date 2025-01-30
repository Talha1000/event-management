<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Check if the request is for a JSON API response
    if (isset($_GET['api']) && $_GET['api'] === 'true') {
        header('Content-Type: application/json');

        $stmt = $conn->prepare("SELECT id, name, description, date, capacity, (SELECT COUNT(*) FROM attendees WHERE event_id = ?) AS current_count FROM events WHERE id = ?");
        $stmt->bind_param('ii', $event_id, $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Event not found']);
        }
        $stmt->close();
        exit;
    }

    // Regular HTML response for event details page
    $stmt = $conn->prepare("SELECT id, name, description, date, capacity, (SELECT COUNT(*) FROM attendees WHERE event_id = ?) AS current_count FROM events WHERE id = ?");
    $stmt->bind_param('ii', $event_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
        echo '<title>Event Details</title>';
        echo '</head>';
        echo '<body>';
        echo '<div class="container mt-5">';
        echo '<h1>' . htmlspecialchars($row['name']) . '</h1>';
        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
        echo '<p><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>';
        echo '<p><strong>Capacity:</strong> ' . htmlspecialchars($row['capacity']) . '</p>';
        echo '<p><strong>Current Registrations:</strong> ' . htmlspecialchars($row['current_count']) . '</p>';

        if ($row['current_count'] < $row['capacity']) {
            echo '<form method="POST" action="register_attendee.php">';
            echo '<input type="hidden" name="event_id" value="' . $event_id . '">';
            echo '<button type="submit" class="btn btn-primary">Register</button>';
            echo '</form>';
        } else {
            echo '<p class="text-danger">This event is full. Registration is not allowed.</p>';
        }

        echo '<a href="event_report.php?event_id=' . $event_id . '" class="btn btn-secondary mt-3">Download Attendee Report</a>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    } else {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
        echo '<title>Event Not Found</title>';
        echo '</head>';
        echo '<body>';
        echo '<div class="container mt-5">';
        echo '<h1 class="text-danger">Event Not Found</h1>';
        echo '<p>The requested event does not exist or has been removed.</p>';
        echo '<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    }
    $stmt->close();
}
?>