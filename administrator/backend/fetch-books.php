<?php
include 'dbcon.php'; // Make sure to include your DB connection

$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
$sql = "SELECT book_id, title FROM book WHERE title LIKE ? LIMIT 10"; // Adjust the table and fields accordingly
$stmt = $con->prepare($sql); // Use $conn instead of $con
$likeTerm = "%$searchTerm%";
$stmt->bind_param("s", $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json'); // Set the header for JSON response
echo json_encode($books);
