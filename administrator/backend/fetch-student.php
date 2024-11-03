<?php
header('Content-Type: application/json');
include 'dbcon.php'; // Include your database connection file

// Get the posted JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['rfid'])) {
    $rfid = $data['rfid'];

    // Query to fetch student details from the 'student' table
    $stmt = $con->prepare("
        SELECT firstname, middlename, lastname, gradelevel, picture 
        FROM student 
        WHERE rfid = ?
    ");
    $stmt->bind_param("s", $rfid);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            echo json_encode(['success' => true, 'student' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No student found with this RFID.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database query failed.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No RFID provided.']);
}

$con->close();
