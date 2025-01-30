<?php
session_start();
include 'config.php';

$error = $success = ""; // Initialize error and success variables

// Check if the form was submitted (i.e., POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only process the form if all fields are set
    if (isset($_POST['event_id'], $_POST['name'], $_POST['email'])) {
        $event_id = intval($_POST['event_id']);
        $attendee_name = sanitize_input($_POST['name']);
        $attendee_email = sanitize_input($_POST['email']);

        // Check event capacity
        $capacityStmt = $conn->prepare("SELECT capacity, (SELECT COUNT(*) FROM attendees WHERE event_id = ?) AS current_count FROM events WHERE id = ?");
        $capacityStmt->bind_param('ii', $event_id, $event_id);
        $capacityStmt->execute();
        $capacityStmt->bind_result($capacity, $currentCount);
        $capacityStmt->fetch();
        $capacityStmt->close();

        if ($currentCount >= $capacity) {
            $error = 'Event is full. Registration not allowed.';
        } else {
            // Register attendee
            $stmt = $conn->prepare("INSERT INTO attendees (event_id, name, email) VALUES (?, ?, ?)");
            $stmt->bind_param('iss', $event_id, $attendee_name, $attendee_email);
            if ($stmt->execute()) {
                $success = 'Registration successful!';
            } else {
                $error = 'Failed to register: ' . $conn->error;
            }
            $stmt->close();
        }
    } else {
        $error = 'All fields are required.';
    }
} // End of POST request check

// Display HTML form (this is independent of whether the form is submitted or not)
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<title>Register for Event</title>';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
echo '<script>';
echo 'function submitForm(event) {'; 
echo '    event.preventDefault();'; 
echo '    var formData = new FormData(event.target);'; // Using FormData for better compatibility
echo '    var xhr = new XMLHttpRequest();'; // Using raw AJAX (no jQuery)
echo '    xhr.open("POST", "", true);'; // Send to the current page
echo '    xhr.onreadystatechange = function() {'; 
echo '        if (xhr.readyState === 4 && xhr.status === 200) {'; 
echo '            var response = JSON.parse(xhr.responseText);'; 
echo '            var messageDiv = document.getElementById("message");'; 
echo '            if (response.error) {'; 
echo '                messageDiv.innerHTML = "<p class=\'text-danger\'>" + response.error + "</p>";'; 
echo '            } else if (response.success) {'; 
echo '                messageDiv.innerHTML = "<p class=\'text-success\'>" + response.success + "</p>";'; 
echo '            }'; 
echo '        }'; 
echo '    };'; 
echo '    xhr.send(formData);'; 
echo '}'; 
echo '</script>';
echo '</head>';
echo '<body>';
echo '<div class="container mt-5">';
echo '<h1>Event Registration</h1>';
echo '<div id="message"></div>';  // For displaying messages

// Display the error or success messages here
if ($error) {
    echo '<p class="text-danger">' . $error . '</p>';
} elseif ($success) {
    echo '<p class="text-success">' . $success . '</p>';
}

echo '<form onsubmit="submitForm(event)">';  // Attach the raw AJAX submit handler
echo '<div class="mb-3">';
echo '<label for="id" class="form-label">Attendee ID</label>';
echo '<input type="number" name="id" id="id" class="form-control" required>';
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="event_id" class="form-label">Event ID</label>';
echo '<input type="number" name="event_id" id="event_id" class="form-control" required>';
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="name" class="form-label">Your Name</label>';
echo '<input type="text" name="name" id="name" class="form-control" required>';
echo '</div>';
echo '<div class="mb-3">';
echo '<label for="email" class="form-label">Your Email</label>';
echo '<input type="email" name="email" id="email" class="form-control" required>';
echo '</div>';
echo '<button type="submit" class="btn btn-primary">Register</button>';
echo '</form>';
echo '</div>';
echo '</body>';
echo '</html>';
?>