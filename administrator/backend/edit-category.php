<?php
include 'dbcon.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    $query = "UPDATE book_categories SET category_name = ? WHERE category_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $category_name, $category_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update category.']);
    }

    $stmt->close();
}
$con->close();
?>
