<?php
include('dbcon.php');

$query = "SELECT * FROM school_year ORDER BY created_at DESC";
$result = $con->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $statusBadge = ($row['status'] == 'active') ?
        '<span class="badge bg-success">Active</span>' :
        '<span class="badge bg-danger">Inactive</span>';

    $actions = '
        <button class="btn btn-warning btn-sm toggle-status" data-id="' . $row['sy_id'] . '" data-status="' . $row['status'] . '">
            ' . (($row['status'] == 'active') ? 'Deactivate' : 'Activate') . '
        </button>
        <button class="btn btn-danger btn-sm delete-year" data-id="' . $row['sy_id'] . '">Delete</button>
    ';

    $data[] = [
        'school_year' => $row['school_year'],
        'status' => $statusBadge,
        'action' => $actions
    ];
}

echo json_encode(['data' => $data]);
