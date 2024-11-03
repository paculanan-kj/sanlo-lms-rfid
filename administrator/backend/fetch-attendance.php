<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Include your database connection file

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['student_id'])) {
    $studentId = $data['student_id'];

    // Prepare SQL statement to fetch time_in, time_out, and student details
    $stmt = $con->prepare("
        SELECT 
            a.time_in, 
            a.time_out, 
            s.firstname, 
            s.middlename, 
            s.lastname, 
            s.gradelevel, 
            s.picture 
        FROM 
            attendance a 
        LEFT JOIN 
            student s ON a.student_id = s.student_id 
        WHERE 
            a.student_id = ? 
        ORDER BY 
            a.attendance_id DESC 
        LIMIT 1
    ");
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $con->error]);
        exit();
    }

    $stmt->bind_param("i", $studentId);
    
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Query execution failed: ' . $stmt->error]);
        exit();
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $attendance = $result->fetch_assoc();
        echo json_encode(['success' => true, 'attendance' => $attendance]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No attendance record found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

$con->close();
?>
