<?php
// Include your database connection
include 'dbcon.php';

// Get the RFID value from the AJAX request
$rfid = $_POST['rfid'] ?? '';

if ($rfid) {
    // Query the database to check if the RFID matches a user
    $stmt = $conn->prepare("SELECT * FROM user WHERE rfid = ?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // RFID is valid
        echo json_encode(['status' => 'success']);
    } else {
        // RFID not found
        echo json_encode(['status' => 'error', 'message' => 'Invalid RFID']);
    }
    $stmt->close();
} else {
    // Invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid RFID']);
}
