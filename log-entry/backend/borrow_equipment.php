<?php
ob_start();
header('Content-Type: application/json');
require 'dbcon.php';

$equipment = trim($_POST['equipment'] ?? '');
$student_id = trim($_POST['student_id'] ?? '');

if ($equipment === '' || $student_id === '') {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

$stmt = $con->prepare("INSERT INTO equipment_borrow (student_id, equipment, status, created_at) VALUES (?, ?, 'Borrowed', NOW())");

if (!$stmt) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $con->error]);
    exit;
}

$stmt->bind_param("ss", $student_id, $equipment);

if ($stmt->execute()) {
    ob_clean();
    echo json_encode(['success' => true]);
} else {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$con->close();
exit;
