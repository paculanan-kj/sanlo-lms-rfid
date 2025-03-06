<?php
session_start();
header('Content-Type: application/json');

require 'dbcon.php'; // Replace with your DB connection file

if (isset($_POST['rfid']) && !empty($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Get the logged-in user's ID from the session
    $currentUserId = $_SESSION['user_id'] ?? null;

    if (!$currentUserId) {
        echo json_encode([
            'success' => false,
            'message' => 'User not logged in.'
        ]);
        exit;
    }

    // Query to check if the RFID matches the logged-in user
    $query = "SELECT * FROM user WHERE user_id = ? AND rfid = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('is', $currentUserId, $rfid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'message' => 'RFID verified successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'RFID does not match the logged-in user.'
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database query failed.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'RFID is required.'
    ]);
}
