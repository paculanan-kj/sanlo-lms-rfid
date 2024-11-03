<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Include your database connection file

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['student_id'])) {
    $studentId = $data['student_id'];
    
    // Get today's date
    $date = date('Y-m-d');

    // Query to check if there is an existing attendance record for the student today
    $stmt = $con->prepare("SELECT * FROM attendance WHERE student_id = ? AND date = ?");
    $stmt->bind_param("is", $studentId, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the attendance record
        $attendance = $result->fetch_assoc();
        echo json_encode(['success' => true, 'attendance' => $attendance]);
    } else {
        // No attendance record found for today
        echo json_encode(['success' => true, 'attendance' => ['time_in' => null, 'time_out' => null]]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$con->close();
?>
