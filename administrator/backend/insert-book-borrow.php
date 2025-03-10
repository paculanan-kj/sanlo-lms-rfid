<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Modify with your database connection file

// Set the timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Get JSON data from AJAX request
$data = json_decode(file_get_contents("php://input"), true);

$book_id = $data['book_id'];
$student_id = $data['student_id'];
$quantity = $data['quantity'];
$status = 'borrowed'; // Set status to 'borrowed' directly in the script
$created_at = date('Y-m-d H:i:s'); // Current date and time

// Step 1: Check the total copies of the book
$total_copies_query = "SELECT copies FROM book WHERE book_id = ?";
$total_copies_stmt = $con->prepare($total_copies_query);
$total_copies_stmt->bind_param("i", $book_id);
$total_copies_stmt->execute();
$total_copies_result = $total_copies_stmt->get_result();

if ($total_copies_result->num_rows > 0) {
    $total_copies_row = $total_copies_result->fetch_assoc();
    $total_copies = (int)$total_copies_row['copies'];

    // Step 2: Check how many copies are currently borrowed
    $borrowed_query = "SELECT COALESCE(SUM(quantity), 0) AS borrowed_count FROM book_borrow WHERE book_id = ? AND status = 'borrowed'";
    $borrowed_stmt = $con->prepare($borrowed_query);
    $borrowed_stmt->bind_param("i", $book_id);
    $borrowed_stmt->execute();
    $borrowed_result = $borrowed_stmt->get_result();
    $borrowed_row = $borrowed_result->fetch_assoc();
    $borrowed_count = (int)$borrowed_row['borrowed_count'];

    // Step 3: Calculate available copies
    $available_copies = $total_copies - $borrowed_count;

    // Step 4: Check if the quantity requested exceeds available copies
    if ($quantity > $available_copies) {
        echo json_encode(['success' => false, 'message' => 'Cannot borrow. This book has ' . $available_copies . ' stock.']);
        exit; // Stop execution if the request exceeds available copies
    }

    // Step 5: Proceed to insert the borrow record
    $sql = "INSERT INTO book_borrow (book_id, student_id, quantity, status, created_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiiss", $book_id, $student_id, $quantity, $status, $created_at);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insertion failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Book not found.']);
}

$total_copies_stmt->close();
$borrowed_stmt->close();
$con->close();
