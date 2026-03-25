<?php
require_once 'auth.php';
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

    <style type="text/css" media="print">
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

        /* Hide non-printable elements */
        .no-print,
        .sidebar,
        .navbar,
        .footer,
        .pagetitle,
        button {
            display: none !important;
        }

        /* Reset body/page styles for printing */
        body {
            margin: 0;
            padding: 0;
            background: #fff !important;
            font-size: 12pt;
            color: #000;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .card-body {
            padding: 0 !important;
        }

        /* Add header for the report with school information */
        .card-title:before {
            content: "SCHOOL NAME HERE";
            display: block;
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .card-title:after {
            content: "School Address Line, City, State, ZIP";
            display: block;
            font-size: 10pt;
            text-align: center;
            margin-bottom: 10px;
        }

        /* Style for the main title */
        .card-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        /* Add report period info */
        #attendanceTable::before {
            content: "Report Period: <?php echo date('F Y', mktime(0, 0, 0, intval($selectedMonth), 1, intval($selectedYear))); ?>";
            display: block;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 12pt;
        }

        /* Table styling for print */
        #attendanceTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #attendanceTable th {
            background-color: #f2f2f2 !important;
            color: #000;
            font-weight: bold;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        #attendanceTable td {
            border: 1px solid #000;
            padding: 6px;
        }

        /* Add page numbers */
        @page {
            margin: 1cm;
        }

        @page: first {
            margin-top: 1.5cm;
        }

        /* Footer with pagination and date */
        #attendanceTable::after {
            content: "Page " counter(page) " of " counter(pages) " - Printed on: <?php echo date('F d, Y'); ?>";
            display: block;
            text-align: center;
            font-size: 9pt;
            margin-top: 20px;
            font-style: italic;
        }

        /* Alternating row colors */
        #attendanceTable tbody tr:nth-child(even) {
            background-color: #f9f9f9 !important;
        }

        /* Add signatures section at the bottom */
        .table::after {
            content: "\A\A\A\A\A\A\A\A Prepared by: _____________________ \A\A Verified by: _____________________ \A\A Approved by: _____________________";
            display: block;
            white-space: pre;
            margin-top: 50px;
            font-size: 11pt;
            text-align: right;
            page-break-inside: avoid;
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

                            $selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
                            $selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');

                            // Debug: Print the selected month and year to verify values
                            // echo "Selected Month: $selectedMonth, Selected Year: $selectedYear";
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

                                <!-- Export and Print Buttons -->
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success no-print" onclick="exportTableToCSV('report.csv')">
                                        <i class="bx bx-download me-1"></i> Export
                                    </button>

                                    <button type="button" class="btn btn-secondary no-print" onclick="printTable()">
                                        <i class="bx bx-printer me-1"></i> Print
                                    </button>
                                </div>
                            </form>



                            <table class="table" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Make sure we have a fresh connection
                                    include 'backend/dbcon.php';

                                    // Convert month and year to integers for consistent comparison
                                    $monthInt = intval($selectedMonth);
                                    $yearInt = intval($selectedYear);

                                    // Debug: echo out the query parameters
                                    // echo "Month: $monthInt, Year: $yearInt<br>";

                                    // Prepare the SQL query with filtering based on the selected month and year
                                    $query = "SELECT s.student_id, s.firstname, s.middlename, s.lastname, s.gradelevel, 
                                         a.time_in, a.time_out, a.date
                                        FROM student s
                                        INNER JOIN attendance a ON s.student_id = a.student_id
                                        WHERE MONTH(a.date) = ? AND YEAR(a.date) = ? 
                                        ORDER BY s.lastname ASC, a.date ASC, a.time_in ASC";

                                    // Prepare and execute the query
                                    if ($stmt = $con->prepare($query)) {
                                        // Bind the parameters
                                        $stmt->bind_param("ii", $monthInt, $yearInt);
                                        $stmt->execute();
                                        $result = $stmt->get_result(); // Get the result set

                                        // Debug: Print the number of rows returned
                                        // echo "Number of rows: " . $result->num_rows . "<br>";

                                        if ($result && $result->num_rows > 0) { // Check if the query was successful and has results
                                            while ($row = $result->fetch_assoc()) {
                                                // Combine first name, middle name (if exists), and last name for full name
                                                $middle = $row['middlename'] ? ucfirst(substr($row['middlename'], 0, 1)) . '.' : '';
                                                $fullName = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $middle;
                                                // Adjust this path based on your folder structure

                                                echo '<tr>';
                                                // Display full name with the student's picture next to it
                                                echo '<td>';
                                                echo htmlspecialchars($fullName); // Display full name
                                                echo '</td>';
                                                echo '<td>' . htmlspecialchars($row['time_in']) . '</td>'; // Display time in
                                                echo '<td>' . htmlspecialchars($row['time_out']) . '</td>'; // Display time out
                                                echo '<td>' . htmlspecialchars($row['date']) . '</td>'; // Display attendance date
                                                echo '</tr>';
                                            }
                                        } else {
                                            // If no results are found or the query failed
                                            echo '<tr><td colspan="6" class="text-center">No attendance records found for ' . date('F', mktime(0, 0, 0, $monthInt, 1)) . ' ' . $yearInt . '</td></tr>';
                                        }

                                        $stmt->close(); // Close the prepared statement
                                    } else {
                                        // If there was an error preparing the statement
                                        echo '<tr><td colspan="6" class="text-center">Error: ' . $con->error . '</td></tr>';
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
            const selectedMonth = document.getElementById('month').value;
            const selectedYear = document.getElementById('year').value;

            // Format the date range string
            const monthName = new Date(selectedYear, selectedMonth - 1).toLocaleString('default', {
                month: 'long'
            });
            const dateRange = `${monthName} ${selectedYear}`;

            // Set timezone to Philippines
            const currentDate = new Date();
            const options = {
                timeZone: 'Asia/Manila',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const printDate = new Intl.DateTimeFormat('en-PH', options).format(currentDate);

            // Get the table element
            const printContent = document.getElementById('attendanceTable').cloneNode(true);

            // Create a new window for printing
            const printWindow = window.open('', '', 'height=800,width=1200');

            // Add content to the print window
            printWindow.document.write(`
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Students Logged Report</title>
            <style>
                @page {
                    size: portrait;
                    margin: 1cm;
                }
                body {
                    font-family: 'Arial', sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    margin-bottom: 5px;
                }
                .school-name {
                    font-size: 22px;
                    font-weight: bold;
                    color: rgb(10, 43, 194);
                    margin-bottom: 5px;
                }
                .school-address {
                    font-size: 14px;
                    margin-bottom: 5px;
                }
                .school-contact {
                    font-size: 14px;
                    margin-bottom: 15px;
                }
                .report-title {
                    font-size: 18px;
                    font-weight: bold;
                    text-align: center;
                    margin: 20px 0 10px;
                    text-transform: uppercase;
                }
                .report-subtitle {
                    font-size: 16px;
                    text-align: center;
                    margin-bottom: 20px;
                }
                .timestamp {
                    font-size: 12px;
                    text-align: right;
                    margin-bottom: 15px;
                    font-style: italic;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th {
                    background-color: rgb(10, 43, 194);
                    color: white;
                    padding: 10px 8px;
                    text-align: left;
                    font-size: 14px;
                    border: 1px solid #ddd;
                }
                td {
                    padding: 8px;
                    border: 1px solid #ddd;
                    font-size: 13px;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .badge {
                    background-color: #ffc107;
                    color: #212529;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: bold;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
                .signature-area {
                    margin-top: 40px;
                    display: flex;
                    justify-content: space-between;
                }
                .signature-box {
                    width: 30%;
                    text-align: center;
                }
                .signature-line {
                    border-top: 1px solid #000;
                    margin-top: 30px;
                    padding-top: 5px;
                    font-weight: bold;
                }
                .position {
                    font-size: 12px;
                    font-style: italic;
                }
                @media print {
                    body {
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                }
                .no-print {
                    display: none;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="school-name">ST. LORENZO SCHOOL OF POLOMOLOK</div>
                <div class="school-address">Polomolok, South Cotabato, Philippines</div>
                <div class="school-contact">School Library | Tel: (123) 456-7890</div>
            </div>
            
            <div class="report-title">STUDENTS LOGGED REPORT</div>
            <div class="report-subtitle">For the Month of ${dateRange}</div>
            
            <div class="timestamp">Printed on: ${printDate} (PHT)</div>
            
            ${printContent.outerHTML}
            
            <div class="signature-area">
                <div class="signature-box">
                    <div class="signature-line">Prepared by</div>
                    <div class="position">Librarian</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-line">Verified by</div>
                    <div class="position">Library Coordinator</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-line">Approved by</div>
                    <div class="position">School Principal</div>
                </div>
            </div>
            
            <div class="footer">
                This is an official document of St. Lorenzo School of Polomolok.<br>
                For any queries, please contact the School Library.
            </div>
        </body>
        </html>
    `);

            // Hide any no-print elements that might be in the table
            const noPrintElements = printWindow.document.querySelectorAll('.no-print');
            noPrintElements.forEach(element => {
                element.style.display = 'none';
            });

            // Replace the badge class elements with styled spans
            const badgeElements = printWindow.document.querySelectorAll('.badge');
            badgeElements.forEach(badge => {
                // Keep the text but apply our custom badge style
                badge.classList.remove('bg-warning', 'text-dark');
                badge.classList.add('badge');
            });

            // Close the document and trigger print
            printWindow.document.close();

            // Add a slight delay to ensure styles are applied before printing
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }
    </script>
    <script>
        function exportTableToCSV(filename) {
            // Get the table element
            const table = document.getElementById('attendanceTable');
            const rows = table.querySelectorAll('tr');

            // Array to store CSV content
            let csvContent = [];

            // Get header row
            const headerRow = rows[0];
            const headers = headerRow.querySelectorAll('th');
            let headerData = [];

            // Extract header text
            headers.forEach(header => {
                headerData.push('"' + header.textContent.trim() + '"');
            });

            csvContent.push(headerData.join(','));

            // Extract data from rows (starting from index 1 to skip header)
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.querySelectorAll('td');
                let rowData = [];

                // Extract cell text
                cells.forEach(cell => {
                    // Remove any HTML and get just the text
                    let text = cell.textContent.trim();
                    // Escape double quotes and wrap in quotes
                    text = '"' + text.replace(/"/g, '""') + '"';
                    rowData.push(text);
                });

                csvContent.push(rowData.join(','));
            }

            // Get the selected month and year for filename
            const selectedMonth = document.getElementById('month').value;
            const selectedYear = document.getElementById('year').value;
            const monthName = new Date(selectedYear, selectedMonth - 1).toLocaleString('default', {
                month: 'long'
            });

            // Create a custom filename with date information
            const customFilename = `Students_Logged_Report_${monthName}_${selectedYear}.csv`;

            // Create a downloadable link with the CSV content
            const csvData = csvContent.join('\n');
            const blob = new Blob([csvData], {
                type: 'text/csv;charset=utf-8;'
            });

            // Create download link
            if (navigator.msSaveBlob) {
                // For IE and Edge
                navigator.msSaveBlob(blob, customFilename);
            } else {
                // For other browsers
                const link = document.createElement('a');

                // Create a URL for the blob
                const url = URL.createObjectURL(blob);

                // Set link properties
                link.href = url;
                link.setAttribute('download', customFilename);
                link.style.visibility = 'hidden';

                // Append to document, trigger click and remove
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    </script>
</body>

</html>