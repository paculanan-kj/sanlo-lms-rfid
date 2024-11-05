<?php
include 'dbcon.php'; // Include your DB connection script

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"));

// Prepare the SQL delete statement
if (isset($data->category_id)) {
    $categoryId = $data->category_id;
    $query = "DELETE FROM book_categories WHERE category_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $categoryId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete category.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$con->close();
?>
