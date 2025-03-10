<?php
include('dbcon.php');

$response = ['success' => false, 'message' => ''];

if (isset($_POST['school_year'])) {
    $school_year = trim($_POST['school_year']);

    // Validate input
    if (empty($school_year)) {
        $response['message'] = "School Year is required.";
        echo json_encode($response);
        exit;
    }

    // Check if the school year already exists
    $check_stmt = $con->prepare("SELECT sy_id FROM school_year WHERE school_year = ?");
    $check_stmt->bind_param("s", $school_year);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = "School Year already exists!";
    } else {
        // Insert new school year
        $insert_stmt = $con->prepare("INSERT INTO school_year (school_year) VALUES (?)");
        $insert_stmt->bind_param("s", $school_year);

        if ($insert_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "School Year added successfully!";
        } else {
            $response['message'] = "Error adding School Year.";
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
} else {
    $response['message'] = "Required parameters are missing.";
}

echo json_encode($response);
