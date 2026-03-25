<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Ensure this file contains your database connection logic

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);
$bookTitle = $data['title'];

// Prepare and execute the query
$sql = "SELECT title FROM book WHERE title LIKE ?";
$stmt = $con->prepare($sql);
$searchTerm = "%$bookTitle%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Check if any suggestions were found
$suggestions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row; // Add each suggestion to the array
    }
    echo json_encode(['success' => true, 'suggestions' => $suggestions]);
} else {
    echo json_encode(['success' => false, 'suggestions' => []]);
}

$stmt->close();
$con->close();
?>
