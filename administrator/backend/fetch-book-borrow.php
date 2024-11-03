<?php
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_borrow_id = $_POST['book_borrow_id'];

    // Fetch the specific borrow record
    $sql = "SELECT bb.book_borrow_id, 
        bb.book_id, 
        CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name, 
        s.gradelevel, 
        b.title AS book_title, 
        bb.quantity 
        FROM book_borrow bb 
        JOIN student s ON bb.student_id = s.student_id 
        JOIN book b ON bb.book_id = b.book_id 
        WHERE bb.book_borrow_id = ?";


    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $book_borrow_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $record = $result->fetch_assoc();

            // Fetch all available books for the dropdown
            $books_sql = "SELECT book_id, title FROM book";
            $books_result = $con->query($books_sql);
            $books = [];

            if ($books_result->num_rows > 0) {
                while ($book = $books_result->fetch_assoc()) {
                    $books[] = $book;
                }
            }

            echo json_encode(['success' => true, 'record' => $record, 'books' => $books]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'SQL error: ' . $con->error]);
    }
    $con->close();
}
