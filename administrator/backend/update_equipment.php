<?php
// Include your database connection file
include 'dbcon.php'; // Adjust the path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the AJAX request
    $equipmentId = isset($_POST['equipment_id']) ? $_POST['equipment_id'] : '';
    $equipmentName = isset($_POST['equipment_name']) ? $_POST['equipment_name'] : '';

    // Validate inputs (add any necessary validation here)
    if (!empty($equipmentId) && !empty($equipmentName)) {
        // Prepare the SQL update statement
        $sql = "UPDATE equipment_borrow SET equipment = ? WHERE equipment_id = ?";
        
        // Prepare and bind parameters
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('si', $equipmentName, $equipmentId); // 'si' means string, integer
            if ($stmt->execute()) {
                echo "Equipment updated successfully.";
            } else {
                echo "Error: " . $con->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $con->error;
        }
    } else {
        echo "Invalid input.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$con->close();
?>
