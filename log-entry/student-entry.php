<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>St. Lorenzo School of Polomolok - Student Scan</title>
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
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/css/scan.css" rel="stylesheet">

</head>

<body>

    <?php
    include('inc/navbar.php'); // Include the navigation bar
    ?>

    <!-- Main Content Area -->
    <div class="container main">
        <?php
        // Include active school year check
        include('backend/dbcon.php');
        $query = "SELECT school_year FROM school_year WHERE status = 'active' LIMIT 1";
        $result = $con->query($query);
        $active_school_year = ($result->num_rows > 0) ? $result->fetch_assoc()['school_year'] : '';
        ?>

        <!-- Student Info Card -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="info-card">
                    <div class="scanner-content"> <!-- Animated RFID Scanner Icon -->
                        <div class="scanner-icon-container">
                            <div class="scanner-rings"></div>
                            <i class="bi bi-upc-scan scan-icon" id="rfidIcon" onclick="focusRFIDInput()"></i>
                            <input type="text" id="rfidInput" name="rfid" placeholder="Scan RFID here..."
                                autofocus oninput="handleRFIDScan()" />
                        </div>
                    </div>
                    <!-- Student Info -->
                    <div class="student-info-container">
                        <!-- Student Photo -->
                        <div class="student-photo-container">
                            <div class="student-photo">
                                <img id="studentPhoto" src="assets/logo/ndk-logo.png" alt="Student Photo" />
                            </div>
                        </div>

                        <!-- Student Details -->
                        <div class="student-details">
                            <h3 id="studentName">Student Name</h3>
                            <p id="studentIdContainer" style="display: none;">
                                <strong>Student ID:</strong> <span id="studentId">--</span>
                            </p>
                        </div>
                    </div>

                    <!-- Time Info -->
                    <div class="time-info-container">
                        <div class="time-box">
                            <i class="bi bi-clock"></i>
                            <div class="time-label">Time In</div>
                            <div class="time-value" id="timeInDisplay">--:--</div>
                        </div>
                        <div class="time-box">
                            <i class="bi bi-clock-history"></i>
                            <div class="time-label">Time Out</div>
                            <div class="time-value" id="timeOutDisplay">--:--</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Input for School Year -->
        <input type="hidden" id="activeSchoolYear" value="<?= htmlspecialchars($active_school_year); ?>">
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
        // Global Variables
        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
        let rfidScanTimeout;
        // Define default logo path to match your PHP code
        const DEFAULT_LOGO = 'assets/logo/ndk-logo.png';

        // Function to update the current time
        function updateDateTime() {
            const now = new Date();
            const time = now.toLocaleTimeString("en-US", {
                timeZone: "Asia/Manila"
            });
            document.getElementById('currentTime').innerText = time;
        }

        // Set up initial time and periodic updates
        window.onload = function() {
            updateDateTime();
            checkActiveSchoolYear();
            document.getElementById("rfidInput").focus();
        };

        setInterval(updateDateTime, 1000);

        // Check if there's an active school year
        function checkActiveSchoolYear() {
            let schoolYear = document.getElementById("activeSchoolYear").value;

            if (!schoolYear) {
                // Show SweetAlert2 error
                Swal.fire({
                    icon: 'error',
                    title: 'Scanning Disabled!',
                    text: 'No active school year found. Please activate a school year first.',
                    confirmButtonColor: '#d33'
                });

                // Disable RFID scanning (block keypress events)
                document.addEventListener("keydown", function(event) {
                    event.preventDefault(); // Prevent scanning input
                });
            }
        }

        // Focus function for RFID input
        function focusRFIDInput() {
            document.getElementById("rfidInput").focus();
        }

        function setStudentPhoto(studentPhoto, photoUrl) {
            if (photoUrl && photoUrl.trim() !== "") {
                // Ensure no folder is stored in DB
                let cleanUrl = photoUrl.replace(/^uploads\//, '').replace(/^students\/profile\//, '');

                studentPhoto.src = '../students/profile/' + cleanUrl + '?t=' + new Date().getTime();

                studentPhoto.onerror = function() {
                    console.log("Student photo not found, using default logo");
                    this.src = DEFAULT_LOGO + '?t=' + new Date().getTime();
                    this.onerror = null;
                };
            } else {
                studentPhoto.src = DEFAULT_LOGO + '?t=' + new Date().getTime();
            }
        }

        // Handle RFID scan
        function handleRFIDScan() {
            var rfidValue = document.getElementById("rfidInput").value.trim();
            var rfidIcon = document.getElementById("rfidIcon");

            rfidIcon.classList.add("scanning");
            clearTimeout(rfidScanTimeout);

            rfidScanTimeout = setTimeout(function() {
                rfidIcon.classList.remove("scanning");

                if (rfidValue) {
                    const currentTime = new Date().toLocaleTimeString("en-US", {
                        timeZone: "Asia/Manila",
                        hour: "2-digit",
                        minute: "2-digit",
                    });

                    const currentDate = new Date().toISOString().split("T")[0];

                    // Fetch student data
                    $.ajax({
                        url: "backend/fetch-student-data.php",
                        type: "POST",
                        data: {
                            rfid: rfidValue
                        },
                        success: function(response) {
                            try {
                                var data = JSON.parse(response);
                                if (data.success) {
                                    // Display student information
                                    document.getElementById("studentName").textContent = data.student_name || "N/A";
                                    document.getElementById("studentId").textContent = data.student_id || "N/A";

                                    // Set student photo with proper fallback
                                    const studentPhoto = document.getElementById("studentPhoto");
                                    setStudentPhoto(studentPhoto, data.photo_url);

                                    // Log attendance
                                    $.ajax({
                                        url: "backend/insert-attendance.php",
                                        type: "POST",
                                        data: {
                                            student_id: data.student_id,
                                            date: currentDate,
                                            user_id: currentUserId,
                                            time: currentTime
                                        },
                                        success: function(insertResponse) {
                                            var insertData = JSON.parse(insertResponse);
                                            if (insertData.success) {
                                                // Display time-in and time-out
                                                const timeInDisplay = document.getElementById("timeInDisplay");
                                                const timeOutDisplay = document.getElementById("timeOutDisplay");

                                                timeInDisplay.textContent = insertData.time_in || '--:--';
                                                timeOutDisplay.textContent = insertData.time_out || '--:--';

                                                // Refresh the student photo after time-in/time-out
                                                setStudentPhoto(studentPhoto, data.photo_url);

                                                Swal.fire({
                                                    icon: "success",
                                                    title: insertData.action === "time_in" ? "Time-In Logged" : "Time-Out Logged",
                                                    text: insertData.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Failed to Log Attendance",
                                                    text: insertData.message,
                                                });
                                            }
                                        },
                                        error: function() {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Error",
                                                text: "Failed to log attendance.",
                                            });
                                        },
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Student not found.",
                                    });
                                }
                            } catch (e) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Invalid server response.",
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Failed to fetch student data.",
                            });
                        },
                    });
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Empty RFID",
                        text: "Please scan a valid RFID.",
                    });
                }

                document.getElementById("rfidInput").value = "";
            }, 200); // Debounce
        }

        // Keep focus on the RFID input
        document.addEventListener("click", function() {
            document.getElementById("rfidInput").focus();
        });
    </script>
</body>

</html>