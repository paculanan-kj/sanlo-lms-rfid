<?php
require 'dbcon.php';

$query = "
    SELECT be.equipment_id, be.equipment, be.created_at
    FROM equipment_borrow be
    LEFT JOIN return_equipment re ON be.equipment_id = re.equipment_id
    WHERE re.equipment_id IS NULL
";

$result = $con->query($query);

if ($result->num_rows > 0) {
    $borrowedEquipment = [];
    while ($row = $result->fetch_assoc()) {
        $borrowedEquipment[] = $row;
    }
    echo json_encode($borrowedEquipment);
} else {
    echo json_encode([]);
}

$con->close();
