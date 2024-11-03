<?php
require('dbcon.php'); // Ensure this file contains your database connection logic

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $data['id'];

    // Prepare and execute delete query
    $query = "DELETE FROM book WHERE book_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Book deleted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting book: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
}

$con->close();
