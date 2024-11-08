<?php
include 'dbcon.php';

if (isset($_GET['term'])) {
    $term = '%' . $_GET['term'] . '%';
    $query = "SELECT b.book_id, b.title, b.amount, 
    (b.copies - COALESCE(SUM(bb.quantity), 0)) AS availableCount
    FROM book b
    LEFT JOIN book_borrow bb ON b.book_id = bb.book_id AND bb.status = 'borrowed'
    WHERE b.title LIKE ? 
    GROUP BY b.book_id
    LIMIT 5";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'book_id' => $row['book_id'],
            'title' => $row['title'],
            'amount' => $row['amount'],
            'availableCount' => $row['availableCount']
        ];        
    }
    
    header('Content-Type: application/json');
    echo json_encode($suggestions);
}
?>