<?php
include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userrole = 'librarian';
    $rfid = $_POST['rfid'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'] ?? '';
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $defaultPassword = 'Sanlorenzo2024';
    $secretKey = 'SanLorenzoSchoolofPolomolokInc.';
    $hashedPassword = hash_hmac('sha256', $defaultPassword, $secretKey);

    $uploadDir = '../uploads/';
    $profilePicture = $_FILES['profile_picture']['name'];
    $targetFile = $uploadDir . basename($profilePicture);
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile);

    // Prepare SQL to check for existing RFID
    $checkRfidSql = "SELECT COUNT(*) AS count FROM user WHERE rfid = ?";
    $checkUsernameSql = "SELECT COUNT(*) AS count FROM user WHERE username = ?";

    // Check for duplicate RFID
    if ($checkRfidStmt = $con->prepare($checkRfidSql)) {
        $checkRfidStmt->bind_param("s", $rfid);
        $checkRfidStmt->execute();
        $checkRfidStmt->bind_result($rfidCount);
        $checkRfidStmt->fetch();

        if ($rfidCount > 0) {
            echo "Error: Duplicate RFID tag found.";
            exit();
        }
        $checkRfidStmt->close();
    }

    // Check for duplicate Username
    if ($checkUsernameStmt = $con->prepare($checkUsernameSql)) {
        $checkUsernameStmt->bind_param("s", $username);
        $checkUsernameStmt->execute();
        $checkUsernameStmt->bind_result($usernameCount);
        $checkUsernameStmt->fetch();

        if ($usernameCount > 0) {
            echo "Error: Duplicate username found.";
            exit();
        }
        $checkUsernameStmt->close();
    }

    // Insert user data if no duplicates are found
    $insertSql = "INSERT INTO user (userrole, rfid, firstname, middlename, lastname, username, email, password, profile_picture) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($insertStmt = $con->prepare($insertSql)) {
        $insertStmt->bind_param("sssssssss", $userrole, $rfid, $firstname, $middlename, $lastname, $username, $email, $hashedPassword, $profilePicture);
        if ($insertStmt->execute()) {
            echo "User added successfully";
        } else {
            echo "Error: " . $insertStmt->error;
        }
        $insertStmt->close();
    } else {
        echo "Error preparing statement: " . $con->error;
    }

    $con->close();
}
