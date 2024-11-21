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
        /* Hide the print button and filter form during printing */
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

            /* Header styling */
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
            <h1>Sold Books</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">Sold Books</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Sold Books Report</h5>
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
                                        <th style="width: 20%">Book</th>
                                        <th>Student Name</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Money Paid</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <?php
                                require('backend/dbcon.php'); // Ensure this file contains your database connection logic

                                // Ensure the user_id is set correctly before executing the query
                                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session

                                $query = "
                                        SELECT 
                                            pb.purchase_id, 
                                            b.title,
                                            pd.quantity, 
                                            pb.total_amount, 
                                            pb.cash AS student_money, 
                                            pb.money_change, 
                                            pb.created_at, 
                                            CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name,
                                            s.picture
                                        FROM purchased_books pb 
                                        LEFT JOIN purchase_details pd ON pb.purchase_id = pd.purchase_id
                                        LEFT JOIN book b ON pd.book_id = b.book_id
                                        LEFT JOIN book_categories c ON b.category_id = c.category_id
                                        LEFT JOIN student s ON pb.student_id = s.student_id
                                        WHERE pb.user_id = ?
                                        AND MONTH(pb.created_at) = ?
                                        AND YEAR(pb.created_at) = ?
                                        ORDER BY pb.created_at DESC
                                    ";

                                // Prepare the query
                                $stmt = $con->prepare($query);

                                // Check if the statement was prepared successfully
                                if ($stmt === false) {
                                    die("Error preparing the SQL query: " . $con->error);
                                }

                                // Bind the parameters, adding selected month and year
                                $stmt->bind_param("iii", $user_id, $selectedMonth, $selectedYear);

                                // Execute the statement
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result === false) {
                                    die("Error executing the query: " . $con->error);
                                }
                                ?>
                                <tbody>
                                    <?php
                                    // Fetch and display each book purchase
                                    while ($row = $result->fetch_assoc()) {
                                        $picturePath = 'uploads/' . $row['picture'];
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>"; // Title
                                        echo "<td>";
                                        echo "<div class='d-flex align-items-center'>";
                                        echo "<img src='" . htmlspecialchars($picturePath) . "' alt='Student Image' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;'>";
                                        echo "<span>" . htmlspecialchars($row['student_name']) . "</span>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "<td><span class='badge bg-warning text-dark'>" . htmlspecialchars($row['quantity']) . "</span></td>"; // Quantity
                                        echo "<td>₱" . number_format($row['total_amount'], 2) . "</td>"; // Total Amount
                                        echo "<td>₱" . number_format($row['student_money'], 2) . "</td>"; // Money Paid
                                        echo "<td>₱" . number_format($row['money_change'], 2) . "</td>"; // Change
                                        echo "</tr>";
                                    }
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
            var dateRange = "Sold Books for " + monthName + " " + selectedYear;

            // Get the table element
            var printContent = document.getElementById('attendanceTable');

            // Open the print dialog
            var printWindow = window.open('', '', 'height=800,width=1200');
            printWindow.document.write('<html><head><title>Sold Books Report</title>');
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