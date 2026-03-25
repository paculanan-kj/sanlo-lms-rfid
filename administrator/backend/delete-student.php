<?php
header('Content-Type: text/plain'); // Helpful for raw fetch responses
include 'dbcon.php';

if (isset($_GET['id'])) {
    $studentId = intval($_GET['id']);

    $query = "DELETE FROM student WHERE student_id = ?";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('i', $studentId);
        if ($stmt->execute()) {
            echo "Student record deleted successfully.";
        } else {
            http_response_code(500);
            echo "Error deleting record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo "Error preparing statement: " . $con->error;
    }
} else {
    http_response_code(400);
    echo "No student ID provided.";
}

$con->close();
