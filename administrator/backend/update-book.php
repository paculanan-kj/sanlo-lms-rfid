<?php
session_start();
require('dbcon.php'); // Ensure this contains the correct DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['book_title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $publication_year = $_POST['publication_year'];
    $location = $_POST['location'];
    $copies = $_POST['copies'];
    $category_id = $_POST['category_id']; // Get the category_id from the form
    $amount = $_POST['amount']; // Get the amount from the form

    // Modify the query to include amount and category_id
    $query = "UPDATE book SET title = ?, author = ?, isbn = ?, publisher = ?, publication_year = ?, location = ?, copies = ?, category_id = ?, amount = ? WHERE book_id = ?";
    $stmt = $con->prepare($query);
    
    // Update the bind_param to include amount
    $stmt->bind_param("ssssssiids", $title, $author, $isbn, $publisher, $publication_year, $location, $copies, $category_id, $amount, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Book updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating book: ' . $stmt->error]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
