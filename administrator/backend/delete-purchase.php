<?php
require('dbcon.php'); // Include your DB connection

// Check if purchase_id is provided
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['purchase_id'])) {
    $purchase_id = $data['purchase_id'];

    // Prepare delete query
    $query = "DELETE FROM purchased_books WHERE purchase_id = ?";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $purchase_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete purchase']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
