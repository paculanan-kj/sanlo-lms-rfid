<?php
// Database connection
include 'dbcon.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect data from the form
    $student_id = $_POST['student_id']; // Get the single student ID
    $equipment_list = json_decode($_POST['equipment_list'], true); // Get the equipment list
    $status = 'borrowed'; // Assuming the status is set to 'borrowed'

    // Prepare an SQL statement
    $stmt = $con->prepare("INSERT INTO equipment_borrow (student_id, equipment, status, created_at) VALUES (?, ?, ?, NOW())");

    // Loop through each equipment item and insert into the database
    $success = true;
    $messages = [];

    foreach ($equipment_list as $equipment) {
        $stmt->bind_param("iss", $student_id, $equipment, $status);
        if ($stmt->execute()) {
            $messages[] = "Successfully borrowed: " . $equipment;
        } else {
            $success = false;
            $messages[] = "Error borrowing: " . $equipment;
        }
    }

    // Return a JSON response
    echo json_encode([
        'success' => $success,
        'message' => implode(', ', $messages)
    ]);

    $stmt->close();
    $con->close();
}
