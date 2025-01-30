<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Fetch attendees for the specified event
    $stmt = $conn->prepare("SELECT name, email FROM attendees WHERE event_id = ?");
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare CSV file for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="event_' . $event_id . '_attendees.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Name', 'Email']); // Header row

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['name'], $row['email']]);
    }

    fclose($output);
    exit;
}
?>