<?php
require 'dbcon.php';

$book_id = $_POST['book_id'] ?? null;
$student_id = $_POST['student_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;
$status = $_POST['status'] ?? 'Borrowed';

if (!$book_id || !$student_id) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Check available copies
$checkQuery = "
    SELECT b.copies - COALESCE(SUM(bb.quantity), 0) AS available
    FROM book b
    LEFT JOIN book_borrow bb ON b.book_id = bb.book_id AND bb.status = 'borrowed'
    WHERE b.book_id = ?
    GROUP BY b.book_id
";
$stmt = $con->prepare($checkQuery);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$checkResult = $stmt->get_result();
$available = 0;

if ($checkRow = $checkResult->fetch_assoc()) {
    $available = (int)$checkRow['available'];
}

if ($available <= 0) {
    echo json_encode(['success' => false, 'message' => 'Book is currently unavailable for borrowing.']);
    exit;
}

// Proceed to insert
$insert = $con->prepare("INSERT INTO book_borrow (book_id, student_id, quantity, status, created_at) VALUES (?, ?, ?, ?, NOW())");
$insert->bind_param("iiis", $book_id, $student_id, $quantity, $status);

if ($insert->execute()) {
    // Fetch book title
    $titleQuery = $con->prepare("SELECT title FROM book WHERE book_id = ?");
    $titleQuery->bind_param("i", $book_id);
    $titleQuery->execute();
    $titleResult = $titleQuery->get_result()->fetch_assoc();

    echo json_encode([
        'success' => true,
        'message' => 'Book borrowed successfully.',
        'bookTitle' => $titleResult['title'] ?? 'Unknown Title',
        'borrowDate' => date('Y-m-d')
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to borrow book.']);
}
