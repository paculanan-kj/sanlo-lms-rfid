<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection here
include('dbcon.php'); // Adjust this according to your setup

// Check if RFID is posted
if (isset($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Query to fetch student details based on RFID
    $sql = "SELECT student_id, CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname, gradelevel, picture FROM student WHERE rfid = ?";
    $stmt = $con->prepare($sql);

    // Check for successful preparation
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'SQL preparation failed']);
        exit;
    }

    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if student is found
    if ($result->num_rows > 0) {
        // Fetch the student details
        $row = $result->fetch_assoc();
        // Assuming the 'picture' field contains the image filename
        $picturePath = 'uploads/' . $row['picture']; // Adjust this based on your folder structure

        echo json_encode([
            'success' => true,
            'student_id' => $row['student_id'],
            'student_name' => $row['fullname'],
            'grade_level' => $row['gradelevel'],
            'photo_url' => $picturePath // Send the photo path
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Student not found']);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No RFID provided']);
}
