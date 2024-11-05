<?php
session_start();
require('dbcon.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $category_id = $_POST['category_id'];
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $publication_year = $_POST['publication_year'];
    $location = $_POST['location'];
    $copies = $_POST['copies'];
    $amount = $_POST['amount']; // Capture the amount
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL insertion query
    $query = "INSERT INTO book (user_id, category_id, title, author, isbn, publisher, publication_year, location, amount,  copies, added_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $con->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("iissssssdi", $user_id, $category_id, $book_title, $author, $isbn, $publisher, $publication_year, $location, $amount, $copies);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Book added successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $con->error]);
    }
}
$con->close();
