<?php
include('dbcon.php');

$response = ['success' => false, 'message' => ''];

if (isset($_POST['sy_id'], $_POST['status'])) {
    $sy_id = intval($_POST['sy_id']);
    $status = ($_POST['status'] === 'active') ? 'active' : 'inactive';

    if ($status === 'active') {
        // Check if another school year is already active
        $check_stmt = $con->prepare("SELECT school_year FROM school_year WHERE status = 'active'");
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $active_row = $result->fetch_assoc();
            $active_year = $active_row['school_year'];
            $response['message'] = "Deactivate School Year '$active_year' to Activate this!";
            echo json_encode($response);
            exit;
        }
        $check_stmt->close();
    }

    // Update the selected school year's status
    $update_stmt = $con->prepare("UPDATE school_year SET status = ? WHERE sy_id = ?");
    $update_stmt->bind_param("si", $status, $sy_id);

    if ($update_stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "School Year status updated to " . ucfirst($status) . "!";
    } else {
        $response['message'] = "Failed to update the status.";
    }

    $update_stmt->close();
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
