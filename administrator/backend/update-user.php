<?php
require('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'] ?? ''; // Use empty string if not set
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Sanitize input
    $firstName = htmlspecialchars($firstName);
    $middleName = htmlspecialchars($middleName);
    $lastName = htmlspecialchars($lastName);
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);

    // Update user information
    $sql = "UPDATE user SET firstname=?, middlename=?, lastname=?, username=?, email=? WHERE user_id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $firstName, $middleName, $lastName, $username, $email, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
    }

    $stmt->close();
}
$con->close();
