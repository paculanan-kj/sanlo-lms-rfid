<?php
include('dbcon.php');

// Get today's date in 'Y-m-d' format
$date = date('Y-m-d');

// Fetch attendance data for today's date, ordered by attendance_id in descending order
$sql = "
    SELECT a.attendance_id, a.student_id, CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name, 
           a.time_in, a.time_out, a.date, s.picture 
    FROM attendance a
    JOIN student s ON a.student_id = s.student_id
    WHERE a.date = ?
    ORDER BY a.attendance_id DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$attendanceData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendanceData[] = [
            'attendance_id' => $row['attendance_id'],
            'student_id' => $row['student_id'],
            'student_name' => $row['student_name'],
            'time_in' => $row['time_in'] ?: '--:--',
            'time_out' => $row['time_out'] ?: '--:--',
            'attendance_date' => $row['date'],
            'photo_url' => '../../students/profile/' . $row['picture']
        ];
    }
}

// Clean up
$stmt->close();
$con->close();

// Do not echo JSON here when included in other scripts
