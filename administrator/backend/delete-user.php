<?php
require('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM user WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $userId); // Assuming user_id is an integer

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
    }

    $stmt->close();
}
$con->close();
