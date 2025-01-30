<?php

session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
    $stmt->bind_param('ii', $event_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo 'Event deleted successfully.';
    } else {
        echo 'Failed to delete event.';
    }
    $stmt->close();
}

?>