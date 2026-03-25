<?php
include('dbcon.php');

if (isset($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    $sql = "SELECT student_id, CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname, gradelevel, picture FROM student WHERE rfid = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $picturePath = 'uploads/' . $row['picture'];

        echo json_encode([
            'success' => true,
            'student_id' => $row['student_id'],
            'student_name' => $row['fullname'],
            'grade_level' => $row['gradelevel'],
            'photo_url' => $picturePath
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Student not found']);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No RFID provided']);
}
