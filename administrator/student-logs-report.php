<?php
session_start();

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
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

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
        /* Hide the print button during printing */
        @media print {
            .no-print {
                display: none;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table,
            th,
            td {
                border: 1px solid black;
            }

            th,
            td {
                padding: 8px;
                text-align: left;
            }

            .header {
                text-align: center;
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .date-range {
                text-align: center;
                font-size: 14px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <?php
    include 'inc/navbar.php';
    include 'inc/sidebar.php';
    include 'backend/dbcon.php'; // Include your database connection

    // Query to fetch users from the database
    $sql = "SELECT user_id, firstname, middlename, lastname, email, username FROM user";
    $result = $con->query($sql);
    ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Students Logged</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">Students Logged</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Students Logged Report</h5>
                            </div>
                            <?php
                            // Ensure the database connection and user session data are loaded
                            require('backend/dbcon.php');

                            // Define variables for selected month and year
                            $selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
                            $selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');
                            ?>

                            <!-- Form with "no-print" class to hide it during printing -->
                            <form method="POST" class="mb-3 d-flex justify-content-between align-items-center no-print">
                                <div class="d-flex align-items-center">
                                    <label for="month" style="margin-right: 5px;">Select Month:</label>
                                    <select name="month" id="month" class="form-select" style="width: auto; display: inline-block; margin-right: 10px;">
                                        <?php
                                        // Generate month options
                                        for ($m = 1; $m <= 12; $m++) {
                                            $monthValue = str_pad($m, 2, "0", STR_PAD_LEFT);
                                            $monthName = date("F", mktime(0, 0, 0, $m, 1));
                                            echo "<option value='$monthValue'" . ($selectedMonth == $monthValue ? " selected" : "") . ">$monthName</option>";
                                        }
                                        ?>
                                    </select>

                                    <label for="year" style="margin-right: 5px;">Select Year:</label>
                                    <select name="year" id="year" class="form-select" style="width: auto; display: inline-block; margin-right: 10px;">
                                        <?php
                                        // Generate year options (last 5 years for example)
                                        for ($y = date('Y'); $y >= date('Y') - 5; $y--) {
                                            echo "<option value='$y'" . ($selectedYear == $y ? " selected" : "") . ">$y</option>";
                                        }
                                        ?>
                                    </select>

                                    <button type="submit" class="btn btn-primary"><i class="bx bx-filter me-1"></i>Filter</button>
                                </div>

                                <!-- Print Button - with "no-print" class to hide it during printing -->
                                <button type="button" class="btn btn-secondary no-print" onclick="printTable()">
                                    <i class="bx bx-printer me-1"></i> Print
                                </button>
                            </form>

                            <!-- Table that will be printed -->
                            <table class="table" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Address</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'backend/dbcon.php';

                                    // Prepare the SQL query with filtering based on the selected month and year
                                    $query = "SELECT s.student_id, s.firstname, s.middlename, s.lastname, s.gradelevel, s.address, s.rfid, s.picture, 
                                            a.time_in, a.time_out, a.date
                                    FROM student s
                                    LEFT JOIN attendance a ON s.student_id = a.student_id
                                    WHERE MONTH(a.date) = ? AND YEAR(a.date) = ? ORDER BY attendance_id ASC"; // Filter by selected month and year

                                    // Prepare and execute the query
                                    if ($stmt = $con->prepare($query)) {
                                        // Bind the parameters
                                        $stmt->bind_param("ii", $selectedMonth, $selectedYear);
                                        $stmt->execute();
                                        $result = $stmt->get_result(); // Get the result set

                                        if ($result && $result->num_rows > 0) { // Check if the query was successful and has results
                                            while ($row = $result->fetch_assoc()) {
                                                // Combine first name, middle name (if exists), and last name for full name
                                                $fullName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
                                                // Construct the image path
                                                $picturePath = 'uploads/' . $row['picture']; // Adjust this path based on your folder structure

                                                echo '<tr>';
                                                // Display full name with the student's picture next to it
                                                echo '<td>';
                                                echo '<img src="' . htmlspecialchars($picturePath) . '" alt="Student Picture" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">';
                                                echo htmlspecialchars($fullName); // Display full name
                                                echo '</td>';

                                                echo '<td>' . htmlspecialchars($row['gradelevel']) . '</td>'; // Output grade level safely
                                                echo '<td>' . htmlspecialchars($row['address']) . '</td>'; // Output address safely
                                                echo '<td>' . htmlspecialchars($row['time_in']) . '</td>'; // Display time in
                                                echo '<td>' . htmlspecialchars($row['time_out']) . '</td>'; // Display time out
                                                echo '<td>' . htmlspecialchars($row['date']) . '</td>'; // Display attendance date
                                                echo '</tr>';
                                            }
                                        } else {
                                            // If no results are found or the query failed
                                            echo '<tr><td colspan="7" class="text-center">No attendance records found</td></tr>'; // Adjust colspan to 7 for the new student data and attendance columns
                                        }

                                        $stmt->close(); // Close the prepared statement
                                    }

                                    $con->close(); // Close the database connection
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->

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
        function printTable() {
            // Get the selected month and year from the form
            var selectedMonth = document.getElementById('month').value;
            var selectedYear = document.getElementById('year').value;

            // Format the date range string
            var monthName = new Date(selectedYear, selectedMonth - 1).toLocaleString('default', {
                month: 'long'
            });
            var dateRange = "Attendance for " + monthName + " " + selectedYear;

            // Get the table element
            var printContent = document.getElementById('attendanceTable');

            // Open the print dialog
            var printWindow = window.open('', '', 'height=800,width=1200');
            printWindow.document.write('<html><head><title>Attendance</title>');
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; }');
            printWindow.document.write('th, td { padding: 8px; border: 1px solid black; text-align: left; }</style>');
            printWindow.document.write('</head><body>');

            // Add the header with the school name
            printWindow.document.write('<div class="header">St. Lorenzo School of Polomolok Inc.</div>');

            // Add the date range (Month & Year)
            printWindow.document.write('<div class="date-range">' + dateRange + '</div>');

            // Add the table content
            printWindow.document.write(printContent.outerHTML); // Print the table content
            printWindow.document.write('</body></html>');
            printWindow.document.close(); // Close the document for printing
            printWindow.print(); // Trigger the print dialog
        }
    </script>
</body>

</html>