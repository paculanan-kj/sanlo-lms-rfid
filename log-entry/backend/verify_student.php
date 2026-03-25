<?php
// backend/verify_student.php

header('Content-Type: application/json');
error_reporting(0);

include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rfid'])) {
    $rfid = trim($_POST['rfid']);

    $stmt = $con->prepare("SELECT * FROM student WHERE rfid = ?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();

        echo json_encode([
            'success' => true,
            'data' => [
                'student_id' => $student['student_id'],
                'firstname' => $student['firstname'],
                'lastname' => $student['lastname'],
                'gradelevel' => $student['gradelevel'],
                'strand' => $student['strand'],
                'section' => $student['section']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'RFID not found.'
        ]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request or missing RFID.'
    ]);
}
