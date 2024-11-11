<?php
require('dbcon.php'); // Ensure this file contains your database connection logic

// Get JSON data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Extract fields from the received data
$purchase_id = $data['purchase_id'];
$quantity = $data['quantity'];
$total_amount = $data['total_amount'];
$student_money = $data['student_money'];
$money_change = $data['money_change'];

// Prepare the SQL query to update the purchase details
$query = "
    UPDATE purchased_books pb
    JOIN purchase_details pd ON pb.purchase_id = pd.purchase_id
    SET pd.quantity = ?, 
        pb.total_amount = ?, 
        pb.cash = ?, 
        pb.money_change = ?
    WHERE pb.purchase_id = ?
";

// Prepare statement
$stmt = $con->prepare($query);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing the SQL query: " . $con->error);
}

// Bind parameters
$stmt->bind_param("iddii", $quantity, $total_amount, $student_money, $money_change, $purchase_id);

// Execute the statement
if ($stmt->execute()) {
    // Send success response
    echo json_encode(["status" => "success", "message" => "Purchase updated successfully"]);
} else {
    // Send error response
    echo json_encode(["status" => "error", "message" => "Error updating purchase"]);
}

// Close the statement and connection
$stmt->close();
$con->close();
