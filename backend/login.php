<?php
session_start();
include 'dbcon.php';

// Set the secret key for HMAC
$secretKey = 'SanLorenzoSchoolofPolomolokInc.'; // Use the same secret key as in add-user.php

// Check if RFID is provided
if (isset($_POST['rfid']) && !empty($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Prepare the SQL query to prevent SQL injection
    $query = "SELECT * FROM user WHERE rfid = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('s', $rfid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Store user information in session
            $_SESSION['username'] = $row['username']; // Store the username in session
            $_SESSION['user_id'] = $row['user_id']; // Store the user_id in session
            
            // Redirect to administrator.php since there's only one user (librarian)
            header("Location: ../administrator/index.php?user_id=" . $_SESSION['user_id']);
            exit();
        } else {
            $_SESSION['error'] = "Invalid RFID tag!";
            header("Location: ../index.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Database query failed!";
        header("Location: ../index.php");
        exit();
    }
} elseif (isset($_POST['username']) && isset($_POST['password'])) {
    // If RFID is not provided, check username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to prevent SQL injection
    $query = "SELECT * FROM user WHERE username = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Create HMAC hash of the entered password for comparison
            $hashedPassword = hash_hmac('sha256', $password, $secretKey);

            // Compare the hashed password with the stored hash
            if ($hashedPassword === $row['password']) {
                // Store user information in session
                $_SESSION['username'] = $row['username']; // Store the username in session
                $_SESSION['user_id'] = $row['user_id']; // Store the user_id in session

                // Redirect to administrator.php since there's only one user (librarian)
                header("Location: ../administrator/index.php?user_id=" . $_SESSION['user_id']);
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password!";
                header("Location: ../index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "User not found!";
            header("Location: ../index.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Database query failed!";
        header("Location: ../index.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Please provide either an RFID tag or username/password!";
    header("Location: ../index.php");
    exit();
}

// If the RFID scanning fails, show a prompt to log in using username and password
if (isset($_SESSION['error']) && strpos($_SESSION['error'], 'Invalid RFID tag!') !== false) {
    $_SESSION['error'] = "RFID scanning failed. Please log in using your username and password.";
    header("Location: ../index.php");
    exit();
}

$con->close();
?>
