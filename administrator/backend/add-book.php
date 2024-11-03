<?php
session_start();
require('dbcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $publication_year = $_POST['publication_year'];
    $location = $_POST['location'];
    $copies = $_POST['copies'];
    $user_id = $_SESSION['user_id']; // Ensure the user is logged in and has a valid session

    // Prepare the SQL insertion query
    $query = "INSERT INTO book (title, author, isbn, publisher, publication_year, location, copies, user_id, added_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $con->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("ssssisii", $book_title, $author, $isbn, $publisher, $publication_year, $location, $copies, $user_id);

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
