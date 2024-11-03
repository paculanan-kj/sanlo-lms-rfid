<?php
include 'dbcon.php'; // Include database connection

header('Content-Type: application/json'); // JSON response header

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$rfid = $data['rfid'] ?? '';

if (empty($rfid)) {
    echo json_encode(['success' => false, 'message' => 'RFID is missing.']);
    exit;
}

$sql = "SELECT student_id, firstname, middlename, lastname, gradelevel AS grade_level, picture AS profile_picture FROM student WHERE rfid = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare query.', 'error' => $con->error]);
    exit;
}

$stmt->bind_param("s", $rfid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $student['full_name'] = trim($student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']);

    // Add correct path to the image if needed
    $student['profile_picture'] = 'uploads/' . $student['profile_picture'];

    echo json_encode(['success' => true, 'student' => $student]);
} else {
    echo json_encode(['success' => false, 'message' => 'Student not found.']);
}

$stmt->close();
$con->close();
