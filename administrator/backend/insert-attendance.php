<?php
include('dbcon.php');

if (isset($_POST['student_id'], $_POST['date'], $_POST['user_id'], $_POST['time'])) {
    $student_id = $_POST['student_id'];
    $time = $_POST['time'];  // Time format expected: 'HH:MM:SS'
    $date = $_POST['date'];
    $user_id = $_POST['user_id'];

    // Convert time to 12-hour format with AM/PM
    $dateTime = new DateTime($time);
    $timeFormatted = $dateTime->format('h:i A');  // Format as 'hh:mm AM/PM'

    $check_stmt = $con->prepare(
        "SELECT attendance_id, time_in, time_out FROM attendance 
         WHERE student_id = ? AND date = ? AND time_out IS NULL 
         ORDER BY time_in DESC LIMIT 1"
    );
    $check_stmt->bind_param("is", $student_id, $date);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $attendance_id = $row['attendance_id'];

        $update_stmt = $con->prepare("UPDATE attendance SET time_out = ? WHERE attendance_id = ?");
        $update_stmt->bind_param("si", $timeFormatted, $attendance_id);

        if ($update_stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Time-out logged successfully.',
                'action' => 'time_out',
                'time_in' => $row['time_in'],
                'time_out' => $timeFormatted
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to log time-out.']);
        }
        $update_stmt->close();
    } else {
        $insert_stmt = $con->prepare("INSERT INTO attendance (user_id, student_id, time_in, date) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("iiss", $user_id, $student_id, $timeFormatted, $date);

        if ($insert_stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Time-in logged successfully.',
                'action' => 'time_in',
                'time_in' => $timeFormatted,
                'time_out' => null
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to log time-in.']);
        }
        $insert_stmt->close();
    }

    $check_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Required parameters are missing.']);
}
