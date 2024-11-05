<?php
// Set the timezone to the Philippines
date_default_timezone_set('Asia/Manila');

// Include your database connection file
include 'dbcon.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the student ID and decode the equipment list from the form submission
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
    $equipment_list = json_decode($_POST['equipment_list'], true);

    if (!$student_id || empty($equipment_list)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid student ID or empty equipment list.'
        ]);
        exit;
    }

    // Set the status for borrowed equipment
    $status = 'borrowed';

    // Prepare the SQL statement with error checking
    $stmt = $con->prepare("INSERT INTO equipment_borrow (student_id, equipment, status, created_at) VALUES (?, ?, ?, NOW())");
    if (!$stmt) {
        die(json_encode([
            'success' => false,
            'message' => 'Prepare failed: ' . $con->error
        ]));
    }

    // Loop through each equipment item and insert it into the database
    $success = true;
    $messages = [];

    foreach ($equipment_list as $equipment) {
        $stmt->bind_param("iss", $student_id, $equipment, $status);

        if ($stmt->execute()) {
            $messages[] = "Successfully borrowed: " . htmlspecialchars($equipment);
        } else {
            $success = false;
            $messages[] = "Error borrowing: " . htmlspecialchars($equipment) . " - " . $stmt->error;
        }
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();

    // Return a JSON response
    echo json_encode([
        'success' => $success,
        'message' => implode(', ', $messages)
    ]);
}
?>
