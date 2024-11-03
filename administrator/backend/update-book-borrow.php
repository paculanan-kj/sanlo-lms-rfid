<?php
require_once 'dbcon.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $book_borrow_id = $_POST['book_borrow_id'];
    $book_id = $_POST['book_title']; // Assuming book_title is the dropdown for book_id
    $quantity = $_POST['quantity'];

    // Prepare the SQL update statement
    $sql = "UPDATE book_borrow 
            SET book_id = ?, quantity = ? 
            WHERE book_borrow_id = ?";

    if ($stmt = $con->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("iii", $book_id, $quantity, $book_borrow_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Record updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update record: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'SQL error: ' . $con->error]);
    }
    $con->close();
}
