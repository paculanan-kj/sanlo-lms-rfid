<?php
session_start();
include 'dbcon.php';
header('Content-Type: application/json'); // Ensure JSON response

$response = [];
$secretKey = 'SanLorenzoSchoolofPolomolokInc.'; // Same secret key used during user creation

if (isset($_POST['rfid']) && !empty($_POST['rfid'])) {
    // RFID Login Logic
    $rfid = $_POST['rfid'];
    $query = "SELECT * FROM user WHERE rfid = ?";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('s', $rfid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Store user information in session
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['user_id'];

            $response = [
                'success' => true,
                'message' => 'RFID login successful!',
                'redirect_url' => 'administrator/student-logged.php?user_id=' . $_SESSION['user_id'],
            ];
        } else {
            // RFID not found
            $response = [
                'success' => false,
                'message' => 'RFID tag not found!',
            ];
        }
        $stmt->close();
    } else {
        $response = [
            'success' => false,
            'message' => 'Database query failed for RFID login!',
        ];
    }
} elseif (isset($_POST['username']) && isset($_POST['password'])) {
    // Username and Password Login Logic
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM user WHERE username = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // Hash the provided password using the same method used during user creation
                $hashedPassword = hash_hmac('sha256', $password, $secretKey);

                if ($hashedPassword === $row['password']) {
                    // Store user information in session
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_id'] = $row['user_id'];

                    $response = [
                        'success' => true,
                        'message' => 'Login successful!',
                        'redirect_url' => 'administrator/student-logged.php?user_id=' . $_SESSION['user_id'],
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Incorrect password!',
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid username!',
                ];
            }
            $stmt->close();
        } else {
            $response = [
                'success' => false,
                'message' => 'Database query failed for username-password login!',
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Username and password are required!',
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'No login method detected! Please provide RFID or username/password.',
    ];
}

// Return the response in JSON format
echo json_encode($response);
$con->close();
