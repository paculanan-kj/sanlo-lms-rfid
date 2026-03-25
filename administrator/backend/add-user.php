<?php
include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userrole = 'librarian';
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $uploadDir = '../uploads/';
    $profilePicture = $_FILES['profile_picture']['name'];
    $targetFile = $uploadDir . basename($profilePicture);
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile);

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

    $insertSql = "INSERT INTO user (userrole, firstname, lastname, username, password, profile_picture) 
                  VALUES (?, ?, ?, ?, ?, ?)";

    if ($insertStmt = $con->prepare($insertSql)) {
        $insertStmt->bind_param("ssssss", $userrole, $firstname, $lastname, $username, $hashedPassword, $profilePicture);
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
