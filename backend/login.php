<?php
session_start();
include 'dbcon.php';
header('Content-Type: application/json');

$response = [];

if (!empty($_POST['username']) && !empty($_POST['password'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = "SELECT user_id, username, password, userrole 
              FROM user 
              WHERE username = ? 
              LIMIT 1";

    if ($stmt = $con->prepare($query)) {

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            if (password_verify($password, $row['password'])) {

                // 🔐 Secure session setup
                session_regenerate_id(true);

                $_SESSION['user_id']  = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['userrole'] = $row['userrole'];

                $response = [
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect_url' => 'administrator/student-logged.php'
                ];
            } else {
                $response = ['success' => false, 'message' => 'Incorrect password!'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Invalid username!'];
        }

        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'Database error!'];
    }
} else {
    $response = ['success' => false, 'message' => 'Username and password required!'];
}

echo json_encode($response);
$con->close();
