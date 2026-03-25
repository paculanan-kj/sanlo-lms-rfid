<?php
include 'dbcon.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $rfid = trim($_POST['rfid']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']) ?: '';
    $lastname = trim($_POST['lastname']);

    // Required fields check
    if (empty($rfid) || empty($firstname) || empty($lastname)) {
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

        // Server directory (for saving file)
        $uploadDir = '../../students/profile/';

        // Clean filename (remove spaces)
        $originalName = preg_replace('/\s+/', '_', basename($_FILES['picture']['name']));
        $pictureName = uniqid() . '_' . $originalName;

        $serverPath = $uploadDir . $pictureName;

        // Create folder if not existing
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $response['message'] = 'Failed to create upload directory.';
                echo json_encode($response);
                exit;
            }
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES['picture']['tmp_name'], $serverPath)) {
            $response['message'] = 'Error moving uploaded file.';
            echo json_encode($response);
            exit;
        }

        // Store ONLY filename in DB
        $picturePath = $pictureName;
    }

    // Insert Student Data into Database (No gradelevel, strand, section)
    $sql = "INSERT INTO student (user_id, rfid, firstname, middlename, lastname, picture, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('isssss', $user_id, $rfid, $firstname, $middlename, $lastname, $picturePath);

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
