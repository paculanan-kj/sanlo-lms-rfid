<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Ensure this file contains your database connection logic

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Check if title is present
if (!isset($data['title']) || empty($data['title'])) {
    echo json_encode(['success' => false, 'message' => 'Book title is required.']);
    exit();
}

$bookTitle = $data['title'];

// Prepare and execute the query to fetch the book details along with total quantity and borrowed quantity
$sql = "
    SELECT b.title, b.location, b.copies AS total_quantity, 
           IFNULL(SUM(bb.quantity), 0) AS borrowed_quantity
    FROM book b
    LEFT JOIN book_borrow bb ON b.book_id = bb.book_id
    WHERE b.title = ?
    GROUP BY b.book_id
";
$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare database query.']);
    exit();
}

$stmt->bind_param("s", $bookTitle);
$stmt->execute();
$result = $stmt->get_result();

// Check if the book was found
if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();

    // Calculate available copies
    $availableCopies = $book['total_quantity'] - $book['borrowed_quantity'];

    // Determine availability
    $availability = $availableCopies > 0 ? 'Available' : 'Unavailable';

    // Return JSON response with book details and availability
    echo json_encode([
        'success' => true,
        'book' => [
            'title' => $book['title'],
            'location' => $book['location'],
            'availability' => $availability, // Ensure availability is returned
        ]
    ]);
} else {
    // If the book is not found, return an error message
    echo json_encode(['success' => false, 'message' => 'Book not found.']);
}

$stmt->close();
$con->close();
