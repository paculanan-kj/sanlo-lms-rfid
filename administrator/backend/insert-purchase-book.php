<?php
include 'dbcon.php';  // Include your database connection

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Start transaction
    $con->begin_transaction();

    // Get form data
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : null;
    $total_amount = isset($_POST['total_amount']) ? floatval(str_replace('₱', '', $_POST['total_amount'])) : 0.0;
    $student_money = isset($_POST['student_money']) ? floatval($_POST['student_money']) : 0.0;
    $change = isset($_POST['change']) ? floatval(str_replace('₱', '', $_POST['change'])) : 0.0;
    $created_at = date("Y-m-d H:i:s");

    // Get the book details from the table rows
    $bookRows = json_decode($_POST['bookRows'], true);

    if (empty($bookRows)) {
        throw new Exception("No books selected for purchase.");
    }

    // Insert main purchase record
    $main_query = "INSERT INTO purchased_books 
                   (user_id, student_id, total_amount, cash, money_change, created_at) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($main_query);
    $stmt->bind_param(
        "iiddds",
        $user_id,
        $student_id,
        $total_amount,
        $student_money,
        $change,
        $created_at
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert purchase record: " . $stmt->error);
    }

    $purchase_id = $con->insert_id;

    // Prepare statement for purchase details
    $detail_query = "INSERT INTO purchase_details (purchase_id, book_id, quantity) VALUES (?, ?, ?)";
    $detail_stmt = $con->prepare($detail_query);

    // Insert each book as a purchase detail
    foreach ($bookRows as $row) {
        // Get book_id from title
        $book_query = "SELECT book_id, copies FROM book WHERE title = ?";
        $book_stmt = $con->prepare($book_query);
        $book_stmt->bind_param("s", $row['title']);
        $book_stmt->execute();
        $book_result = $book_stmt->get_result()->fetch_assoc();

        if (!$book_result) {
            throw new Exception("Book not found: " . $row['title']);
        }

        $book_id = $book_result['book_id'];
        $current_copies = $book_result['copies'];
        $quantity = $row['quantity'];

        if ($current_copies < $quantity) {
            throw new Exception("Insufficient copies for book: " . $row['title']);
        }

        // Insert purchase detail
        $detail_stmt->bind_param("iii", $purchase_id, $book_id, $quantity);

        if (!$detail_stmt->execute()) {
            throw new Exception("Failed to insert purchase detail: " . $detail_stmt->error);
        }

        // Update book copies
        $update_query = "UPDATE book SET copies = copies - ? WHERE book_id = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("ii", $quantity, $book_id);

        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update book copies: " . $update_stmt->error);
        }
    }

    // Commit the transaction
    $con->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Purchase completed successfully!",
        "purchase_id" => $purchase_id
    ]);
} catch (Exception $e) {
    // Rollback on error
    $con->rollback();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

// Close connection
$con->close();
