<?php
include 'dbcon.php';

$input = isset($_POST['query']) ? trim($_POST['query']) : '';

if ($input === '') {
    echo json_encode(['success' => false, 'message' => 'No search term provided.']);
    exit;
}

$query = $con->prepare("
    SELECT 
        b.*, 
        bc.category_name,
        IFNULL(bb.total_borrowed, 0) AS borrowed_quantity,
        IFNULL(br.total_returned, 0) AS returned_quantity
    FROM book b
    LEFT JOIN book_categories bc ON b.category_id = bc.category_id

    LEFT JOIN (
        SELECT book_id, SUM(quantity) AS total_borrowed
        FROM book_borrow
        WHERE status = 'borrowed'
        GROUP BY book_id
    ) bb ON bb.book_id = b.book_id

    LEFT JOIN (
        SELECT bb.book_id, SUM(br.quantity) AS total_returned
        FROM book_return br
        JOIN book_borrow bb ON br.book_borrow_id = bb.book_borrow_id
        WHERE br.status = 'returned'
        GROUP BY bb.book_id
    ) br ON br.book_id = b.book_id

    WHERE b.title LIKE ? OR b.author LIKE ? OR bc.category_name LIKE ?
");

$searchTerm = "%" . $input . "%";
$query->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$query->execute();
$result = $query->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $borrowed = $row['borrowed_quantity'];
    $returned = $row['returned_quantity'];
    $row['available_copies'] = $row['copies'] - ($borrowed - $returned);
    $books[] = $row;
}

echo json_encode(['success' => true, 'books' => $books]);
