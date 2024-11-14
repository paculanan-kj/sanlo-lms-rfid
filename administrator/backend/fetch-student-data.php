<?php
header('Content-Type: application/json');
include 'dbcon.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['rfid'])) {
    $rfid = $data['rfid'];

    // Check RFID in the database
    $stmt = $con->prepare("SELECT student_id, firstname, middlename, lastname FROM student WHERE rfid = ?");
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
        echo json_encode(['success' => false, 'message' => 'Database query failed.', 'error' => $con->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No RFID provided.']);
}

$con->close();
