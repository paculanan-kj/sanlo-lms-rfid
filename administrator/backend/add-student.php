<?php
include 'dbcon.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $sy_id = intval($_POST['sy_id']);  // Capture sy_id
    $rfid = trim($_POST['rfid']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']) ?: '';
    $lastname = trim($_POST['lastname']);
    $gradelevel = trim($_POST['gradelevel']);
    $address = trim($_POST['address']);

    if (empty($rfid) || empty($firstname) || empty($lastname) || empty($gradelevel) || empty($address) || empty($sy_id)) {
        $response['message'] = 'Required fields are missing.';
        echo json_encode($response);
        exit;
    }

    // Check if RFID already exists
    $checkSql = "SELECT COUNT(*) FROM student WHERE rfid = ?";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bind_param('s', $rfid);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        $response['message'] = 'Duplicate RFID found.';
        echo json_encode($response);
        exit;
    }

    // Handle Picture Upload
    $picturePath = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $pictureName = uniqid() . '_' . basename($_FILES['picture']['name']);
        $picturePath = $uploadDir . $pictureName;

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            $response['message'] = 'Failed to create upload directory.';
            echo json_encode($response);
            exit;
        }

        if (!move_uploaded_file($_FILES['picture']['tmp_name'], $picturePath)) {
            $response['message'] = 'Error moving uploaded file.';
            echo json_encode($response);
            exit;
        }
    }

    // Insert Student Data into Database (Including sy_id)
    $sql = "INSERT INTO student (user_id, sy_id, rfid, firstname, middlename, lastname, gradelevel, address, picture, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('iisssssss', $user_id, $sy_id, $rfid, $firstname, $middlename, $lastname, $gradelevel, $address, $picturePath);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Student added successfully.';
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
