<?php

include 'dbcon.php'; // Include database connection

// Assuming the user ID is stored in the session (adjust based on your setup)
$userId = $_SESSION['user_id'];

// Query to fetch user details
$sql = "SELECT firstname, profile_picture FROM user WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user); // Return data as JSON
} else {
    echo json_encode(['firstname' => 'Guest', 'profile_picture' => 'default.jpg']);
}
