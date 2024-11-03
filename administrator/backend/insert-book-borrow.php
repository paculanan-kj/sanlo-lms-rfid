<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Modify with your database connection file

// Get JSON data from AJAX request
$data = json_decode(file_get_contents("php://input"), true);

$book_id = $data['book_id'];
$student_id = $data['student_id'];
$quantity = $data['quantity'];
$status = 'borrowed'; // Set status to 'borrowed' directly in the script
$created_at = date('Y-m-d H:i:s'); // Current date and time

// Insert query
$sql = "INSERT INTO book_borrow (book_id, student_id, quantity, status, created_at) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("iiiss", $book_id, $student_id, $quantity, $status, $created_at);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database insertion failed']);
}

$stmt->close();
$con->close();
