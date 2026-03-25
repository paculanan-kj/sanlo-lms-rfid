<?php
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log incoming data for debugging (optional)
    file_put_contents('debug_return.txt', print_r($_POST, true));

    $equipment_id = isset($_POST['equipment_id']) ? intval($_POST['equipment_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Allow dynamic quantity
    $status = 'Returned';
    $returned_at = date('Y-m-d H:i:s');

    // Validate
    if ($equipment_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing or invalid equipment ID']);
        exit;
    }

    // Check if the equipment is already marked as returned
    $checkQuery = "SELECT * FROM return_equipment WHERE equipment_id = ? ORDER BY returned_at DESC LIMIT 1";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bind_param("i", $equipment_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Equipment already returned, no need to insert again
        echo json_encode(['success' => false, 'message' => 'Equipment has already been returned']);
        $checkStmt->close();
        $con->close();
        exit;
    }
    $checkStmt->close();

    // Insert into return_equipment table without updating borrowed_equipment
    $stmt = $con->prepare("INSERT INTO return_equipment (equipment_id, quantity, status, returned_at) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $con->error]);
        exit;
    }

    $stmt->bind_param("iiss", $equipment_id, $quantity, $status, $returned_at);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
