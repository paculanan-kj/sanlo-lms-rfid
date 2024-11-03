<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Include your database connection file

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['student_id']) && isset($data['time_in']) && isset($data['date'])) {
    $studentId = $data['student_id'];
    $timeIn = $data['time_in']; // Expecting just HH:MM:SS
    $date = $data['date']; // Format: 'YYYY-MM-DD'

    // Convert time from HH:MM:SS to 12-hour format with AM/PM
    $timeIn12Hour = date('h:i A', strtotime($timeIn)); // Convert to 12-hour format

    // Check if the student already has a time_in entry for the current date and no time_out
    $checkStmt = $con->prepare("SELECT * FROM attendance WHERE student_id = ? AND date = ? AND time_out IS NULL");
    $checkStmt->bind_param("is", $studentId, $date);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        // If there's an existing time_in without time_out, log the time_out
        $timeOut = $timeIn12Hour; // Reusing $timeIn to capture the current time for time_out
        $updateStmt = $con->prepare("UPDATE attendance SET time_out = ? WHERE student_id = ? AND date = ? AND time_out IS NULL");
        $updateStmt->bind_param("sis", $timeOut, $studentId, $date);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Time-out logged successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to log time-out.']);
        }
        
        $updateStmt->close();
    } else {
        // If there's no existing time_in, insert a new record with time_in
        $insertStmt = $con->prepare("INSERT INTO attendance (student_id, time_in, date) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iss", $studentId, $timeIn12Hour, $date); // Save in 12-hour format

        if ($insertStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Time-in logged successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to log time-in.']);
        }

        $insertStmt->close();
    }

    $checkStmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

$con->close();
?>
