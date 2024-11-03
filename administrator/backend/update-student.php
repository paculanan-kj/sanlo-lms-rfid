<?php
include 'dbcon.php'; // Database connection

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'];
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']) ?: '';
    $lastname = trim($_POST['lastname']);
    $gradelevel = trim($_POST['gradelevel']);
    $address = trim($_POST['address']);
    $rfid = trim($_POST['rfid']); // Add this line to retrieve the RFID

    // Handle picture upload if provided
    $picturePath = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $pictureName = uniqid() . '_' . basename($_FILES['picture']['name']);
        $picturePath = $uploadDir . $pictureName;

        if (!move_uploaded_file($_FILES['picture']['tmp_name'], $picturePath)) {
            $response['message'] = 'Error uploading the picture.';
            echo json_encode($response);
            exit;
        }
    }

    // Update query
    $sql = "UPDATE student SET rfid = ?, firstname = ?, middlename = ?, lastname = ?, gradelevel = ?, address = ?, picture = IFNULL(?, picture) WHERE student_id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        $response['message'] = 'Database error: ' . $con->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param('sssssssi', $rfid, $firstname, $middlename, $lastname, $gradelevel, $address, $picturePath, $studentId);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Student updated successfully.';
    } else {
        $response['message'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method.';
}

header('Content-Type: application/json');
echo json_encode($response);
$con->close();
