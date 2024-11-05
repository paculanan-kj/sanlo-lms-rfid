<?php
include 'dbcon.php'; // Include your DB connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        // Prevent duplicate categories
        $checkQuery = "SELECT * FROM book_categories WHERE category_name = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Category already exists.";
        } else {
            // Insert the category into the database with current timestamp for created_at
            $insertQuery = "INSERT INTO book_categories (category_name, created_at) VALUES (?, CURRENT_TIMESTAMP)";
            $stmt = $con->prepare($insertQuery);
            $stmt->bind_param("s", $category_name);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Failed to add category.";
            }
        }

        $stmt->close();
        $con->close();
    } else {
        echo "Please enter a category name.";
    }
}
?>
