<?php
include 'dbcon.php';

$studentId = $_GET['student_id'] ?? null;

if (!$studentId) {
    echo json_encode(['success' => false, 'message' => 'Student ID is required']);
    exit;
}

// Get only borrowed books that are not yet returned
$query = "
    SELECT bb.book_borrow_id, bb.created_at, b.title
    FROM book_borrow bb
    JOIN book b ON bb.book_id = b.book_id
    WHERE bb.student_id = ? 
      AND bb.status = 'borrowed'
      AND bb.book_borrow_id NOT IN (SELECT book_borrow_id FROM book_return)
";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode(['success' => true, 'books' => $books]);
