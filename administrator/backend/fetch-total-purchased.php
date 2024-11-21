<?php
// Include database connection
include('dbcon.php'); // Modify according to your actual DB connection file

// Debugging: Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get today's date
$date = date('Y-m-d');

// Default filter is "Today"
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Debugging: Check if user_id is passed in the URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die('Error: user_id is missing or empty.');
}

// Initialize SQL query based on selected filter
$sql = "SELECT SUM(COALESCE(pd.quantity, 0)) AS total_purchased_books 
        FROM purchase_details pd
        JOIN purchased_books pb ON pd.purchase_id = pb.purchase_id
        WHERE pb.student_id = ?";

// Modify SQL based on the filter
if ($filter == 'today') {
    $sql .= " AND DATE(pb.created_at) = ?";
} elseif ($filter == 'this_month') {
    $sql .= " AND MONTH(pb.created_at) = MONTH(CURRENT_DATE) AND YEAR(pb.created_at) = YEAR(CURRENT_DATE)";
} elseif ($filter == 'this_year') {
    $sql .= " AND YEAR(pb.created_at) = YEAR(CURRENT_DATE)";
}

// Prepare the SQL statement
$stmt = $con->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die('Error preparing SQL statement: ' . $con->error);
}

// Now bind parameters based on the filter
if ($filter == 'today') {
    $stmt->bind_param("is", $_GET['user_id'], $date);  // Bind user_id and date for "today" filter
} else {
    $stmt->bind_param("i", $_GET['user_id']);  // Bind only user_id for other filters
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the query executed successfully
if ($result === false) {
    die('Error executing query: ' . $stmt->error);
}

// Fetch the result
$total_purchased_books = 0; // Default value if no books are purchased

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_purchased_books = $row['total_purchased_books'];
} else {
    echo 'No records found for the selected filter and user.';
}

// Debugging line to check if result was fetched
echo "Total Books Purchased: " . $total_purchased_books;  // Debugging line

// Close the statement and connection
$stmt->close();
$con->close();
