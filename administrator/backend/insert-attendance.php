<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection here
include('dbcon.php'); // Adjust this according to your setup

// Check if student_id and date are provided
if (isset($_POST['student_id']) && isset($_POST['date'])) {
    $student_id = $_POST['student_id'];
    $date = $_POST['date'];

    // Query to check if the student already has a time-in for the specified date
    $sql = "SELECT * FROM attendance WHERE student_id = ? AND date = ? AND time_in IS NOT NULL";
    $stmt = $con->prepare($sql);

    // Check for successful preparation
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'SQL preparation failed']);
        exit;
    }

    $stmt->bind_param("is", $student_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if time-in exists
    if ($result->num_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing student_id or date']);
}
