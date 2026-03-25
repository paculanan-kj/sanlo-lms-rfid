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
            <h1>Borrow Equipment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">Borrow Equipment</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Borrow Equipment Report</h5>
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


                            <table class="table" id="equipmentBorrowTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Equipment</th>
                                        <th>Borrowed Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'backend/dbcon.php';

                                    $query = "
                                            SELECT 
                                                eb.equipment, 
                                                eb.created_at, 
                                                s.firstname, 
                                                s.middlename, 
                                                s.lastname, 
                                                s.gradelevel
                                            FROM equipment_borrow eb
                                            LEFT JOIN student s ON eb.student_id = s.student_id
                                            WHERE MONTH(eb.created_at) = ? AND YEAR(eb.created_at) = ?
                                            ORDER BY eb.created_at DESC
                                        ";

                                    if ($stmt = $con->prepare($query)) {
                                        $stmt->bind_param("ii", $selectedMonth, $selectedYear);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $middleInitial = $row['middlename'] ? strtoupper(substr($row['middlename'], 0, 1)) . '.' : '';
                                                $fullName = "{$row['lastname']}, {$row['firstname']} $middleInitial";

                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<div class="d-flex align-items-center">';
                                                echo '<span>' . htmlspecialchars($fullName) . '</span>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td>' . htmlspecialchars($row['gradelevel']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['equipment']) . '</td>';
                                                echo '<td>' . htmlspecialchars(date('F d, Y', strtotime($row['created_at']))) . '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="text-center">No equipment borrow records found</td></tr>';
                                        }

                                        $stmt->close();
                                    }

                                    $con->close();
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
        // Function to print the equipment borrow table with proper formatting
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
            const printContent = document.getElementById('equipmentBorrowTable').cloneNode(true);

            // Create a new window for printing
            const printWindow = window.open('', '', 'height=800,width=1200');

            // Add content to the print window
            printWindow.document.write(`
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Equipment Borrow Report</title>
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
                <div class="school-contact">School Library Equipment Borrowing | Tel: (123) 456-7890</div>
            </div>
            
            <div class="report-title">EQUIPMENT BORROW REPORT</div>
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

            // Close the document and trigger print
            printWindow.document.close();

            // Add a slight delay to ensure styles are applied before printing
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }

        // Function to export table data to CSV
        function exportTableToCSV(filename) {
            // Get the selected month and year
            const selectedMonth = document.getElementById('month').value;
            const selectedYear = document.getElementById('year').value;
            const monthName = new Date(selectedYear, selectedMonth - 1).toLocaleString('default', {
                month: 'long'
            });

            // Get the table
            const table = document.getElementById('equipmentBorrowTable');
            const rows = table.querySelectorAll('tr');

            // Prepare CSV content
            let csvContent = '\uFEFF'; // BOM for UTF-8

            // Add title and date range
            csvContent += `EQUIPMENT BORROW REPORT - ${monthName} ${selectedYear}\r\n\r\n`;

            // Add headers
            const headers = [];
            const headerCells = rows[0].querySelectorAll('th');
            headerCells.forEach(cell => {
                headers.push('"' + cell.textContent.trim().replace(/"/g, '""') + '"');
            });
            csvContent += headers.join(',') + '\r\n';

            // Add data rows
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    const rowData = [];
                    cells.forEach(cell => {
                        // Replace quotes with double quotes for CSV format
                        let cellText = cell.textContent.trim().replace(/"/g, '""');
                        rowData.push('"' + cellText + '"');
                    });
                    csvContent += rowData.join(',') + '\r\n';
                }
            }

            // Create a download link
            const encodedUri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `Equipment_Borrow_Report_${selectedMonth}_${selectedYear}.csv`);
            document.body.appendChild(link);

            // Trigger download
            link.click();

            // Clean up
            document.body.removeChild(link);
        }
    </script>
</body>

</html>