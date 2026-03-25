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
            <h1>Book Borrow</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">Book Borrow</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Book Borrow Report</h5>
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


                            <!-- Table that will be printed -->
                            <table class="table" id="borrowTable">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Book Title</th>
                                        <th>Status</th> <!-- Changed column -->
                                        <th>Borrow Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'backend/dbcon.php';

                                    $query = "SELECT bb.book_borrow_id, bb.book_id, bb.student_id, bb.quantity, bb.created_at, bb.status,
                    s.firstname, s.middlename, s.lastname,
                    b.title
                  FROM book_borrow bb
                  LEFT JOIN student s ON bb.student_id = s.student_id
                  LEFT JOIN book b ON bb.book_id = b.book_id
                  WHERE MONTH(bb.created_at) = ? AND YEAR(bb.created_at) = ?
                  ORDER BY s.lastname ASC, s.firstname ASC, s.middlename ASC";

                                    if ($stmt = $con->prepare($query)) {
                                        $stmt->bind_param("ii", $selectedMonth, $selectedYear);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                // Format name as: Lastname, Firstname M.
                                                $middle = $row['middlename'] ? ucfirst(substr($row['middlename'], 0, 1)) . '.' : '';
                                                $fullName = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $middle;

                                                // Get status from DB
                                                $status = ucfirst($row['status']); // Make first letter uppercase
                                                $statusClass = strtolower($status) === 'returned' ? 'text-success' : 'text-danger';

                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($fullName) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                                echo '<td class="' . $statusClass . '">' . $status . '</td>';
                                                echo '<td>' . date('Y-m-d', strtotime($row['created_at'])) . '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="4" class="text-center">No book borrowing records found for the selected period</td></tr>';
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
            const printContent = document.getElementById('borrowTable').cloneNode(true);

            // Create a new window for printing
            const printWindow = window.open('', '', 'height=800,width=1200');

            // Add content to the print window
            printWindow.document.write(`
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Book Borrow Report</title>
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
            
            <div class="report-title">BOOK BORROW REPORT</div>
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
            const table = document.getElementById('borrowTable');
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
            const customFilename = `Book_Borrow_Report_${monthName}_${selectedYear}.csv`;

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