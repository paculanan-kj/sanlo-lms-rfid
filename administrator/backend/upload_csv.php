<?php
include('dbcon.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES["csvFile"])) {
        $file = $_FILES["csvFile"]["tmp_name"];
        $user_id = $_POST['user_id']; // Get user ID from form
        $active_sy_id = $_POST['active_sy_id']; // Get active school year ID

        if (($handle = fopen($file, "r")) !== FALSE) {
            fgetcsv($handle); // Skip the header row

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Map each value to the correct column
                $rfid = trim($data[0]); // Ensure RFID is read first
                $firstname = trim($data[1]);
                $middlename = trim($data[2]);
                $lastname = trim($data[3]);
                $gradelevel = trim($data[4]);
                $strand = trim($data[5]);
                $section = trim($data[6]);

                // Check if the student already exists
                $checkQuery = "SELECT student_id FROM student WHERE firstname = ? AND lastname = ?";
                $stmt = $con->prepare($checkQuery);
                $stmt->bind_param("ss", $firstname, $lastname);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Student exists, update gradelevel, strand, section, and RFID
                    $stmt->bind_result($student_id);
                    $stmt->fetch();
                    $updateQuery = "UPDATE student SET gradelevel = ?, strand = ?, section = ?, rfid = ?, sy_id = ?, updated_at = NOW() WHERE student_id = ?";
                    $updateStmt = $con->prepare($updateQuery);
                    $updateStmt->bind_param("ssssii", $gradelevel, $strand, $section, $rfid, $active_sy_id, $student_id);
                    $updateStmt->execute();
                    $updateStmt->close();
                } else {
                    // Student does not exist, insert new record
                    $insertQuery = "INSERT INTO student (user_id, sy_id, firstname, middlename, lastname, gradelevel, strand, section, rfid, created_at) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    $insertStmt = $con->prepare($insertQuery);
                    $insertStmt->bind_param("iisssssss", $user_id, $active_sy_id, $firstname, $middlename, $lastname, $gradelevel, $strand, $section, $rfid);
                    $insertStmt->execute();
                    $insertStmt->close();
                }
                $stmt->close();
            }
            fclose($handle);

            echo json_encode(["success" => true, "message" => "CSV uploaded successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error opening the file."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No file uploaded."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$con->close();
