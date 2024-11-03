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

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="body-scan">
    <!-- Attendance System UI -->
    <div class="container main text-center">
        <h1>Student Time In / Time Out</h1>
        <div class="card" style="background-color: #042A4A; color: white; padding: 20px;">
            <h2>Scan RFID</h2>
            <h2 id="currentDate"></h2>
            <h1 id="currentTime"></h1>

            <input type="text" id="rfidInput" name="rfid" placeholder="Scan RFID here..." autofocus />
            <p><strong>Student ID:</strong> <span id="studentId"></span></p>
            <div class="d-flex justify-content-start">
                <button class="btn btn-danger" onclick="window.location.href='student-logged.php'"><i class="bx bx-arrow-back"></i></button>
            </div>
        </div>

        <!-- TimeIn/Out Card -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xxl-3 col-md-3 mx-auto">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center pt-4" id="studentInfo">
                                    <a href="#" class="student d-flex align-items-center w-auto">
                                        <img src="assets/img/images.png" alt="Student Picture" id="studentPicture" style="width: 120px; height: 120px;">
                                    </a>
                                </div>
                                <h3 id="studentName" class="d-flex justify-content-center mt-3"></h3>
                                <p><strong>Grade Level:</strong> <span id="studentGradeLevel"></span></p>
                                <p><strong>Time In:</strong> <span id="timeInDisplay"></span></p>
                                <p><strong>Time Out:</strong> <span id="timeOutDisplay"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End TimeIn/Out Card -->

    </div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.min.js"></script>

    <script>
        // Function to update the current date and time
        function updateDateTime() {
            const now = new Date();

            // Format date as MM/DD/YYYY
            const date = now.toLocaleDateString();
            document.getElementById('currentDate').innerText = date;

            // Format time as HH:MM:SS
            const time = now.toLocaleTimeString();
            document.getElementById('currentTime').innerText = time;
        }

        // Update the date and time when the page loads
        window.onload = updateDateTime;

        // Optionally, update the time every second
        setInterval(updateDateTime, 1000);
    </script>

    <script>
        // Function to update the current date and time
        function updateDateTime() {
            const now = new Date();

            // Format date as MM/DD/YYYY
            const date = now.toLocaleDateString();
            document.getElementById('currentDate').innerText = date;

            // Format time as HH:MM:SS
            const time = now.toLocaleTimeString();
            document.getElementById('currentTime').innerText = time;
        }

        // Update the date and time when the page loads
        window.onload = updateDateTime;

        // Optionally, update the time every second
        setInterval(updateDateTime, 1000);

        document.getElementById('rfidInput').addEventListener('change', function() {
            const rfidValue = this.value.trim(); // Trim whitespace from input

            if (rfidValue) {
                // Fetch student data based on the RFID
                fetchStudentData(rfidValue);
                // Clear the input field after fetching data
                this.value = '';
            }
        });

        function fetchStudentData(rfid) {
            fetch('backend/fetch-student-data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        rfid: rfid
                    }),
                })
                .then(response => response.json())
                .then(data => handleStudentDataResponse(data))
                .catch(error => handleError('Error fetching student data.', error));
        }

        function handleStudentDataResponse(data) {
            console.log(data); // Check the response structure
            if (data.success) {
                const student = data.student;
                if (student.student_id) {
                    document.getElementById('studentId').innerText = student.student_id;
                    fetchAttendance(student.student_id); // Fetch attendance data
                } else {
                    console.error('Student ID is undefined');
                }
            } else {
                alert('Student data not found: ' + data.message);
            }
        }

        function fetchAttendance(studentId) {
            fetch('backend/fetch-attendance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        student_id: studentId
                    }),
                })
                .then(response => response.json())
                .then(data => handleAttendanceResponse(data))
                .catch(error => handleError('Error fetching attendance.', error));
        }

        function handleAttendanceResponse(data) {
            console.log(data); // Log the data to check the response
            if (data.success) {
                const attendance = data.attendance;
                document.getElementById('studentName').innerText = `${attendance.firstname} ${attendance.middlename || ''} ${attendance.lastname}`;
                document.getElementById('studentGradeLevel').innerText = attendance.gradelevel || 'Not Recorded';
                document.getElementById('timeInDisplay').innerText = attendance.time_in || 'Not Recorded';
                document.getElementById('timeOutDisplay').innerText = attendance.time_out || 'Not Recorded';

                // Construct the picture path and set it
                const picturePath = attendance.picture ? '../administrator/uploads/' + attendance.picture : 'assets/img/default.png';
                console.log('Picture Path:', picturePath); // Log the constructed path
                document.getElementById('studentPicture').src = picturePath; // Update student picture source
            } else {
                console.error('Attendance data not found: ' + data.message);
                resetAttendanceDisplay();
            }
        }

        function resetAttendanceDisplay() {
            document.getElementById('studentName').innerText = 'Not Found';
            document.getElementById('studentGradeLevel').innerText = 'N/A';
            document.getElementById('timeInDisplay').innerText = 'Not Recorded';
            document.getElementById('timeOutDisplay').innerText = 'Not Recorded';
            document.getElementById('studentPicture').src = 'assets/img/default.png';
        }

        function logAttendance(studentId) {
            const currentTime = new Date();
            const timeIn = currentTime.toTimeString().split(' ')[0]; // Get current time in HH:MM:SS format
            const date = currentTime.toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format

            fetch('backend/log-attendance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        time_in: timeIn, // Insert current time
                        date: date // Insert current date in another column
                    }),
                })
                .then(response => response.json())
                .then(data => handleLogAttendanceResponse(data, timeIn, studentId))
                .catch(error => handleError('Error logging attendance.', error));
        }

        function handleLogAttendanceResponse(data, timeIn, studentId) {
            if (data.success) {
                console.log('Attendance logged successfully:', data);
                fetchAttendance(studentId); // Fetch attendance to update the display
                // Display Time In in the designated area
                document.getElementById('timeInDisplay').innerText = timeIn; // Update Time In directly here
            } else {
                console.error('Failed to log attendance:', data.message);
            }
        }

        function handleError(message, error) {
            console.error(message, error);
            alert(message);
        }
    </script>

</body>

</html>