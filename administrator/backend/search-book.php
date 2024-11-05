<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Ensure this file contains your database connection logic

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);
$bookTitle = $data['title'];

// Prepare and execute the query to fetch the book details
$sql = "SELECT title, location FROM book WHERE title = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $bookTitle);
$stmt->execute();
$result = $stmt->get_result();

// Check if the book was found
if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    echo json_encode(['success' => true, 'book' => $book]);
} else {
    echo json_encode(['success' => false, 'message' => 'Book not found.']);
}

$stmt->close();
$con->close();
?>
