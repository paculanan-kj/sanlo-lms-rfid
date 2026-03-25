<?php
include 'dbcon.php';

if (!$con) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$bookBorrowId = $_POST['book_borrow_id'] ?? null;

if (!$bookBorrowId) {
    echo json_encode(['success' => false, 'message' => 'Missing book_borrow_id']);
    exit;
}

// Step 1: Get current quantity
$queryCheck = "SELECT quantity FROM book_borrow WHERE book_borrow_id = ?";
$stmtCheck = $con->prepare($queryCheck);
$stmtCheck->bind_param("i", $bookBorrowId);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Book borrow record not found']);
    exit;
}

$row = $result->fetch_assoc();
$current_quantity = (int)$row['quantity'];

// Step 2: Subtract 1
$new_quantity = $current_quantity - 1;

if ($new_quantity < 0) {
    echo json_encode(['success' => false, 'message' => 'Return exceeds borrowed quantity']);
    exit;
}

// Step 3: Update quantity
$update = $con->prepare("UPDATE book_borrow SET quantity = ? WHERE book_borrow_id = ?");
$update->bind_param("ii", $new_quantity, $bookBorrowId);
$update->execute();

// Step 4: Insert return record
$insert = $con->prepare("INSERT INTO book_return (book_borrow_id, quantity, status, returned_at) VALUES (?, 1, 'returned', NOW())");
$insert->bind_param("i", $bookBorrowId);
$success = $insert->execute();

// Step 5: Optional - update status if all returned
if ($new_quantity == 0) {
    $updateStatus = $con->prepare("UPDATE book_borrow SET status = 'returned' WHERE book_borrow_id = ?");
    $updateStatus->bind_param("i", $bookBorrowId);
    $updateStatus->execute();
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to insert return record', 'error' => $insert->error]);
}
