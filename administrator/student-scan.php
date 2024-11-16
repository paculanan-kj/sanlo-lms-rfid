<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>St. Lorenzo School of Polomolok</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/logo/ndk-logo.png" rel="icon">
    <link href="assets/logo/ndk-logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/sweet-alert/sweetalert2.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        /* Container Styles */
        .main {
            max-width: 1200px;
            padding: 2rem;
        }

        /* RFID Scan Card Styles */
        .scan-card {
            background-color: #042A4A;
            color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .scan-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        /* Scanner Icon Animation */
        .scanner-icon-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scanner-rings {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.5;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        #rfidIcon {
            font-size: 80px;
            color: white;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        #rfidIcon:hover {
            transform: scale(1.1);
        }

        #rfidInput {
            opacity: 0;
            position: absolute;
            left: -9999px;
        }

        /* Student Card */
        .student-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .student-card:hover {
            transform: translateY(-5px);
        }

        /* Student Info Layout */
        .student-info-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: flex-start;
            /* Align the photo and details to the left */
        }

        /* Student Photo */
        .student-photo-container {
            flex-shrink: 0;
        }

        .student-photo {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: #aaa;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Student Details */
        .student-details {
            text-align: left;
            margin-left: 40px;
            /* Reset the negative margin to position the student info normally */
        }

        .student-details h3 {
            margin: 0;
            font-size: 2.5rem;
            color: #042A4A;
        }

        .student-details p {
            margin: 5px 0;
            font-size: 1.2rem;
            color: #333;
        }

        /* Time Info Section */
        .time-info-container {
            display: flex;
            justify-content: flex-start;
            margin-top: 20px;
            /* Position time info below student info */
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 10px;
            gap: 15px;
        }

        .time-box {
            text-align: center;
            flex: 1;
        }

        .time-box:not(:last-child) {
            border-right: 1px solid #ddd;
        }

        .time-box i {
            font-size: 1.2rem;
            color: #042A4A;
        }

        .time-details {
            text-align: left;
        }

        .time-label {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .time-value {
            font-size: 1.1rem;
            color: #042A4A;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main {
                padding: 1rem;
            }

            .time-info-container {
                grid-template-columns: 1fr;
            }

            .scan-title {
                font-size: 1.5rem;
            }
        }

        .green {
            color: green !important;
        }

        .scanning {
            color: #00ff00;
            /* Change this color to whatever you want for scanning */
            animation: pulse 1s infinite;
            /* Optional: Add an animation to give a "pulsing" effect */
        }
    </style>

</head>

<body class="body-scan">


    <div class="button-container">
        <button class="btn btn-danger" onclick="window.location.href='student-logged.php?user_id=<?php echo $user_id; ?>'">
            <i class="bx bx-arrow-back"></i>
        </button>
    </div>
    <!-- Attendance System UI -->
    <div class="container main text-center">
        <!-- RFID Scan Card -->
        <div class="card scan-card">
            <div class="scanner-content">
                <h2 class="scan-title">Scan your RFID card</h2>

                <!-- Animated RFID Scanner Icon -->
                <div class="scanner-icon-container">
                    <div class="scanner-rings"></div>
                    <i class="bi bi-broadcast" id="rfidIcon" onclick="focusRFIDInput()"></i>
                    <input type="text" id="rfidInput" name="rfid" placeholder="Scan RFID here..." autofocus oninput="handleRFIDScan()" />
                </div>

                <p class="student-id-display" style="display: none;"><strong>Student ID:</strong> <span id="studentId"></span></p>
            </div>

            <!-- Student Info Card -->
            <div class="row mt-1">
                <div class="col-lg-12">
                    <div class="main">
                        <!-- Student Card -->
                        <div class="student-card">
                            <!-- Student Info -->
                            <div class="student-info-container">
                                <!-- Student Photo -->
                                <div class="student-photo-container">
                                    <img id="studentPhoto" class="student-photo" src="uploads/default.png" alt="Student Photo" />
                                </div>

                                <!-- Student Details -->
                                <div class="student-details">
                                    <h3 id="studentName">John Smith</h3>
                                    <p><strong>Grade Level:</strong> <span id="studentGradeLevel"></span></p>
                                </div>
                            </div>

                            <!-- Time Info -->
                            <div class="time-info-container">
                                <div class="time-box">
                                    <div class="time-label">Time In</div>
                                    <div class="time-value" id="timeInDisplay">--:--</div>
                                </div>
                                <div class="time-box">
                                    <div class="time-label">Time Out</div>
                                    <div class="time-value" id="timeOutDisplay">--:--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/sweet-alert/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.min.js"></script>

    <script>
        // Function to update the current date and time in Philippine Standard Time
        function updateDateTime() {
            const now = new Date();

            // Format time as HH:MM:SS in Philippine Standard Time
            const time = now.toLocaleTimeString("en-US", {
                timeZone: "Asia/Manila"
            });
            document.getElementById('currentTime').innerText = time;
        }

        // Update the date and time when the page loads
        window.onload = updateDateTime;

        // Update the time every second
        setInterval(updateDateTime, 1000);

        let rfidScanTimeout;

        function handleRFIDScan() {
            var rfidValue = document.getElementById("rfidInput").value;
            var rfidIcon = document.getElementById("rfidIcon");

            // Add the scanning class to change the color of the RFID icon
            rfidIcon.classList.add("scanning");

            // Clear the previous timeout, so we don't process an incomplete scan
            clearTimeout(rfidScanTimeout);

            // Set a timeout to process the scan after a delay (e.g., 1 second)
            rfidScanTimeout = setTimeout(function() {
                // Remove the scanning class after the scan is completed
                rfidIcon.classList.remove("scanning");

                // Check if RFID value is not empty
                if (rfidValue) {
                    console.log("RFID Value:", rfidValue);

                    // Get the current time in Philippine Standard Time for Time In or Time Out
                    const currentTime = new Date().toLocaleTimeString("en-US", {
                        timeZone: "Asia/Manila",
                        hour: "2-digit",
                        minute: "2-digit"
                    });

                    // Set the Time In value to the current time
                    document.getElementById("timeInDisplay").textContent = currentTime;

                    // AJAX request to fetch student info based on RFID
                    $.ajax({
                        url: "backend/fetch-student-data.php", // PHP script to handle the RFID and fetch the student ID
                        type: "POST",
                        data: {
                            rfid: rfidValue
                        },
                        success: function(response) {
                            console.log("Response:", response); // Log the response to check its format
                            try {
                                var data = JSON.parse(response);
                                if (data.success) {
                                    // Display student info
                                    var studentIdElem = document.getElementById("studentId");
                                    if (studentIdElem) {
                                        studentIdElem.textContent = data.student_id;
                                    }

                                    var studentNameElem = document.getElementById("studentName");
                                    if (studentNameElem) {
                                        studentNameElem.textContent = data.student_name; // Set student name
                                    }

                                    var gradeLevelElem = document.getElementById("studentGradeLevel");
                                    if (gradeLevelElem) {
                                        gradeLevelElem.textContent = data.grade_level; // Set grade level
                                    }

                                    var studentPhotoElem = document.getElementById("studentPhoto");
                                    if (studentPhotoElem) {
                                        studentPhotoElem.src = data.photo_url || "uploads/default.png"; // Set student photo
                                    }

                                    // Proceed with checking if the student already time-in
                                    const currentDate = new Date().toISOString().split('T')[0]; // 'YYYY-MM-DD'

                                    // AJAX to check if the student already has time-in
                                    $.ajax({
                                        url: "backend/check-time-in.php", // PHP script to check if the student has time-in
                                        type: "POST",
                                        data: {
                                            student_id: data.student_id,
                                            date: currentDate
                                        },
                                        success: function(checkResponse) {
                                            var checkData = JSON.parse(checkResponse);

                                            if (checkData.success) {
                                                // If student already time-in, insert time-out
                                                $.ajax({
                                                    url: "backend/insert-attendance.php",
                                                    type: "POST",
                                                    data: {
                                                        student_id: data.student_id,
                                                        time_out: currentTime, // Insert time-out
                                                        date: currentDate
                                                    },
                                                    success: function(insertResponse) {
                                                        var insertData = JSON.parse(insertResponse);
                                                        if (insertData.success) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Attendance Logged',
                                                                text: insertData.message,
                                                            });
                                                        } else {
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Failed to log attendance',
                                                                text: insertData.message,
                                                            });
                                                        }
                                                    },
                                                    error: function() {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'Failed to log time-out.',
                                                        });
                                                    }
                                                });
                                            } else {
                                                // If student hasn't time-in yet, insert time-in
                                                $.ajax({
                                                    url: "backend/insert-attendance.php",
                                                    type: "POST",
                                                    data: {
                                                        student_id: data.student_id,
                                                        time_in: currentTime, // Insert time-in
                                                        date: currentDate
                                                    },
                                                    success: function(insertResponse) {
                                                        var insertData = JSON.parse(insertResponse);
                                                        if (insertData.success) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Attendance Logged',
                                                                text: insertData.message,
                                                            });
                                                        } else {
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Failed to log attendance',
                                                                text: insertData.message,
                                                            });
                                                        }
                                                    },
                                                    error: function() {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'Failed to log time-in.',
                                                        });
                                                    }
                                                });
                                            }
                                        },
                                        error: function() {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Failed to check attendance status.',
                                            });
                                        }
                                    });

                                    // Clear the RFID input field after successful scan (optional)
                                    document.getElementById("rfidInput").value = '';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: data.error || 'Student not found. Please check the RFID card.',
                                    });
                                }
                            } catch (e) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to parse the response from the server.',
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was an error processing your request.',
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Empty RFID',
                        text: 'Please scan a valid RFID card.',
                    });
                }
            }, 300); // Wait 300ms after the last input before making the request
        }
    </script>

</body>

</html>