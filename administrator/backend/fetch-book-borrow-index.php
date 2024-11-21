<?php
// Include database connection
include('dbcon.php'); // Modify according to your actual DB connection file

// Get today's date
$date = date('Y-m-d');

// Default filter is "Today"
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Initialize SQL query based on selected filter
$sql = "SELECT COUNT(*) AS total_borrowed_books 
        FROM book_borrow bb 
        LEFT JOIN book_return br ON bb.book_borrow_id = br.book_borrow_id 
        WHERE bb.student_id = ? AND br.return_book_id IS NULL";

// Modify SQL based on the filter
if ($filter == 'today') {
    $sql .= " AND DATE(bb.created_at) = ?";
} elseif ($filter == 'this_month') {
    $sql .= " AND MONTH(bb.created_at) = MONTH(CURRENT_DATE) AND YEAR(bb.created_at) = YEAR(CURRENT_DATE)";
} elseif ($filter == 'this_year') {
    $sql .= " AND YEAR(bb.created_at) = YEAR(CURRENT_DATE)";
}

$stmt = $con->prepare($sql);

// Now bind parameters based on the filter
if ($filter == 'today') {
    $stmt->bind_param("is", $_GET['user_id'], $date);  // Bind user_id and date for "today" filter
} else {
    $stmt->bind_param("i", $_GET['user_id']);  // Bind only user_id for other filters
}

$stmt->execute();
$result = $stmt->get_result();

$total_borrowed_books = 0; // Default value if no books are borrowed

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_borrowed_books = $row['total_borrowed_books'];
}

$stmt->close();
$con->close();
