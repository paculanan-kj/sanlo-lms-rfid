<?php
// your_insertion_script.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    include 'dbcon.php'; // Adjust with your database connection file

    // Get form data
    $equipment_id = $_POST['equipment_id'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];
    $returned_at = date('Y-m-d H:i:s'); // Current timestamp

    // Prepare the SQL statement
    $stmt = $con->prepare("INSERT INTO return_equipment (equipment_id, quantity, status, returned_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $equipment_id, $quantity, $status, $returned_at);

    // Execute and check for success
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
