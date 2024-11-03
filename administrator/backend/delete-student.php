<?php
include 'dbcon.php'; // Include your database connection

if (isset($_GET['id'])) {
    $studentId = intval($_GET['id']); // Ensure it's an integer to prevent SQL injection

    // Prepare the DELETE query
    $query = "DELETE FROM student WHERE student_id = ?";

    // Create a prepared statement
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('i', $studentId); // Bind the parameter
        if ($stmt->execute()) {
            echo "Student record deleted successfully.";
        } else {
            echo "Error deleting record: " . $con->error;
        }
        $stmt->close(); // Close the statement
    } else {
        echo "Error preparing statement: " . $con->error;
    }
} else {
    echo "No student ID provided.";
}

$con->close(); // Close the database connection
