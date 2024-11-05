<?php
// Include your database connection
require_once 'dbcon.php';

// Set the timezone to Philippines
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_borrow_id = $_POST['book_borrow_id'];
    $returned_quantity = $_POST['quantity'];
    $status = $_POST['status'];
    $returned_at = date('Y-m-d H:i:s'); // Current timestamp

    // Get the current quantity from the book_borrow table
    $sql = "SELECT quantity FROM book_borrow WHERE book_borrow_id = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $book_borrow_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_quantity = $row['quantity'];

            // Calculate the new quantity
            $new_quantity = $current_quantity - $returned_quantity;

            // Update the book_borrow table
            if ($new_quantity >= 0) { // Ensure we don't set a negative quantity
                $update_sql = "UPDATE book_borrow SET quantity = ? WHERE book_borrow_id = ?";
                if ($update_stmt = $con->prepare($update_sql)) {
                    $update_stmt->bind_param("ii", $new_quantity, $book_borrow_id);
                    if ($update_stmt->execute()) {
                        // Insert into book_return table
                        $insert_sql = "INSERT INTO book_return (book_borrow_id, quantity, status, returned_at) VALUES (?, ?, ?, ?)";
                        if ($insert_stmt = $con->prepare($insert_sql)) {
                            $insert_stmt->bind_param("iiss", $book_borrow_id, $returned_quantity, $status, $returned_at);
                            if ($insert_stmt->execute()) {
                                echo json_encode(['success' => true]);
                            } else {
                                echo json_encode(['success' => false, 'message' => 'Database error during insert: ' . $insert_stmt->error]);
                            }
                            $insert_stmt->close();
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Prepare failed for insert: ' . $con->error]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Database error during update: ' . $update_stmt->error]);
                    }
                    $update_stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Prepare failed for update: ' . $con->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Returned quantity exceeds borrowed quantity.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No borrowing record found.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Prepare failed for select: ' . $con->error]);
    }

    $con->close();
}
