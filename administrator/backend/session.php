<?php
session_start();

$response = ['timeout' => false]; // Default response

// Check if the user is logged in
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    $timeout_duration = 60; // 1 minute

    // Check if the session has expired
    if (isset($_SESSION['last_activity'])) {
        $session_lifetime = time() - $_SESSION['last_activity'];

        if ($session_lifetime > $timeout_duration) {
            $response['timeout'] = true; // Mark as timed out
            session_unset();
            session_destroy();
        }
    }

    // Update last activity time if not timed out
    $_SESSION['last_activity'] = time();
}

// Handle AJAX requests to check timeout
if (isset($_GET['check_timeout'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
