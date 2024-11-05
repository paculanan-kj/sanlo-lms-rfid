<?php
session_start();

include 'dbcon.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $equipment_id = $_POST['equipment_id'];
    $status = 'returned'; // Set status to returned
    $returned_at = date('Y-m-d H:i:s'); // Current timestamp

    // Prepare the SQL statement
    $stmt = $con->prepare("INSERT INTO return_equipment (equipment_id, status, returned_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $equipment_id, $status, $returned_at);

    // Execute and check for success
    if ($stmt->execute()) {
        // Update the status in the equipment_borrow table if necessary
        $updateStmt = $con->prepare("UPDATE equipment_borrow SET status = ? WHERE equipment_id = ? AND status = 'borrowed'");
        $updateStmt->bind_param("si", $status, $equipment_id);
        $updateStmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $updateStmt->close();
    $con->close();
}
?>
