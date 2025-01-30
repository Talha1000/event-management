<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Pagination logic
    $events_per_page = 5; // Number of events per page
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $events_per_page;

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
    echo '<title>Your Dashboard</title>';
    echo '</head>';
    echo '<body>';
    echo '<script>AOS.init();</script>';
    echo '<div class="container mt-5">';
    echo '<h1 class="mb-4 text-center">Your Events</h1>';

    echo '<div class="d-flex justify-content-between mb-3">';
    echo '<form method="GET" class="d-flex">';
    echo '<input type="text" name="search" class="form-control me-2" placeholder="Search events..." value="' . ($_GET['search'] ?? '') . '">';
    echo '<button type="submit" class="btn btn-primary">Search</button>';
    echo '</form>';
    echo '<form method="GET">';
    echo '<select name="sort" class="form-select" onchange="this.form.submit()">';
    echo '<option value="date"' . (($_GET['sort'] ?? '') === 'date' ? ' selected' : '') . '>Sort by Date</option>';
    echo '<option value="name"' . (($_GET['sort'] ?? '') === 'name' ? ' selected' : '') . '>Sort by Name</option>';
    echo '<option value="capacity"' . (($_GET['sort'] ?? '') === 'capacity' ? ' selected' : '') . '>Sort by Capacity</option>';
    echo '</select>';
    echo '</form>';
    echo '</div>';

    $search = '%' . ($_GET['search'] ?? '') . '%';
    $sort = $_GET['sort'] ?? 'date';

    // Fetch total number of events for pagination
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM events WHERE created_by = ? AND (name LIKE ? OR description LIKE ?)");
    $count_stmt->bind_param('iss', $user_id, $search, $search);
    $count_stmt->execute();
    $count_stmt->bind_result($total_events);
    $count_stmt->fetch();
    $count_stmt->close();

    $total_pages = ceil($total_events / $events_per_page);

    // Fetch events with pagination, search, and sorting
    $stmt = $conn->prepare("SELECT * FROM events WHERE created_by = ? AND (name LIKE ? OR description LIKE ?) ORDER BY $sort ASC LIMIT ? OFFSET ?");
    $stmt->bind_param('issii', $user_id, $search, $search, $events_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<div class="row">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-4" data-aos="zoom-in">';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
        echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
        echo '<p class="card-text"><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>';
        echo '<p class="card-text"><strong>Capacity:</strong> ' . htmlspecialchars($row['capacity']) . '</p>';
        echo '<a href="event_details.php?event_id=' . $row['id'] . '" class="btn btn-primary">View Details</a> ';
        echo '<a href="edit_event.php?event_id=' . $row['id'] . '" class="btn btn-warning">Edit</a> ';
        echo '<a href="delete_event.php?event_id=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this event?\')">Delete</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';

    // Pagination controls
    echo '<nav aria-label="Page navigation">';
    echo '<ul class="pagination justify-content-center">';
    if ($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Previous</a></li>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
    if ($page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next</a></li>';
    }
    echo '</ul>';
    echo '</nav>';

    echo '</div>'; // Container end
    echo '</body>';
    echo '</html>';
}
?>