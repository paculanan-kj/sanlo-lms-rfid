<?php
// Get today's date
$date = date('Y-m-d');

// Default filter is "Today"
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Initialize SQL query based on selected filter
$sql = "SELECT COUNT(*) AS total_students FROM attendance a
        JOIN student s ON a.student_id = s.student_id
        WHERE 1=1";

// Modify SQL based on the filter
if ($filter == 'today') {
    $sql .= " AND a.date = ?";
} elseif ($filter == 'this_month') {
    $sql .= " AND MONTH(a.date) = MONTH(CURRENT_DATE) AND YEAR(a.date) = YEAR(CURRENT_DATE)";
} elseif ($filter == 'this_year') {
    $sql .= " AND YEAR(a.date) = YEAR(CURRENT_DATE)";
}

$stmt = $con->prepare($sql);

if ($filter == 'today') {
    $stmt->bind_param("s", $date);  // Only bind date for "today" filter
}

$stmt->execute();
$result = $stmt->get_result();

$totalStudents = 0; // Default value if no students are logged

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalStudents = $row['total_students'];
}

$stmt->close();
$con->close();
