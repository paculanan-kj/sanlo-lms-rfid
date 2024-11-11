<?php
require('dbcon.php'); // Include your database connection

// Get JSON data from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$purchase_id = $data['purchase_id'];

// Prepare the SQL query to fetch the purchase details
$query = "
    SELECT 
        pb.purchase_id, 
        b.title,
        pd.quantity, 
        pb.total_amount, 
        pb.cash AS student_money, 
        pb.money_change,
        CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name,
        s.picture
    FROM purchased_books pb 
    LEFT JOIN purchase_details pd ON pb.purchase_id = pd.purchase_id
    LEFT JOIN book b ON pd.book_id = b.book_id
    LEFT JOIN student s ON pb.student_id = s.student_id
    WHERE pb.purchase_id = ?
";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
