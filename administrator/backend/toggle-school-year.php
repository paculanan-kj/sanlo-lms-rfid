<?php
include('dbcon.php');

$response = ['success' => false, 'message' => ''];

if (isset($_POST['sy_id'], $_POST['status'])) {
    $sy_id = $_POST['sy_id'];
    $new_status = ($_POST['status'] == 'active') ? 'inactive' : 'active';

    $stmt = $con->prepare("UPDATE school_year SET status = ? WHERE sy_id = ?");
    $stmt->bind_param("si", $new_status, $sy_id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "School Year updated to " . ucfirst($new_status) . "!";
    } else {
        $response['message'] = "Failed to update status.";
    }

    $stmt->close();
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
