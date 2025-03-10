<?php
include('dbcon.php');

$response = ['success' => false, 'message' => ''];

if (isset($_POST['sy_id'])) {
    $sy_id = intval($_POST['sy_id']); // Ensure it's an integer

    // Check if the record exists before deleting
    $check_stmt = $con->prepare("SELECT * FROM school_year WHERE sy_id = ?");
    $check_stmt->bind_param("i", $sy_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Proceed with deletion
        $delete_stmt = $con->prepare("DELETE FROM school_year WHERE sy_id = ?");
        $delete_stmt->bind_param("i", $sy_id);

        if ($delete_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "School Year deleted successfully!";
        } else {
            $response['message'] = "Failed to delete the School Year.";
        }

        $delete_stmt->close();
    } else {
        $response['message'] = "School Year not found.";
    }

    $check_stmt->close();
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
