<?php
session_start();

include 'backend/dbcon.php';
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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
    <link href="assets/sweet-alert/sweetalert2.min.css" rel="stylesheet">

</head>

<body>
    <?php
    include 'inc/navbar.php';
    include 'inc/sidebar.php';
    ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Borrow Books</li>
                    <li class="breadcrumb-item active">Returned Books</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Returned Books</h5>
                        </div>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Grade Level</th>
                                    <th>Equipment Name</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Returned At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // SQL query to join tables and retrieve returned equipment
                                $sql = "SELECT re.return_equipment_id, 
                    CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name, 
                    s.gradelevel,
                    eb.equipment AS equipment_name, -- Assuming you have an equipment name field
                    re.quantity, 
                    re.status,
                    re.returned_at
                FROM return_equipment re
                JOIN equipment_borrow eb ON re.equipment_id = eb.equipment_id -- Adjust the join if necessary
                JOIN student s ON eb.student_id = s.student_id
                JOIN return_equipment e ON eb.equipment_id = e.equipment_id"; // Assuming you have an equipment table

                                $result = $con->query($sql);

                                // Check if the query was successful
                                if ($result === false) {
                                    // Display the SQL error
                                    echo "<tr><td colspan='5'>Error: " . $con->error . "</td></tr>";
                                } elseif ($result->num_rows > 0) {
                                    // Fetch and display records
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['gradelevel']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['equipment_name']) . "</td>"; // Display equipment name
                                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";

                                        // Determine the badge class based on status
                                        switch (htmlspecialchars($row['status'])) {
                                            case 'returned':
                                                $badgeClass = 'bg-success'; // Green for returned
                                                break;
                                            case 'damaged':
                                                $badgeClass = 'bg-danger'; // Red for damaged
                                                break;
                                            case 'lost':
                                                $badgeClass = 'bg-warning'; // Yellow for lost
                                                break;
                                            default:
                                                $badgeClass = 'bg-secondary'; // Grey for unknown status
                                                break;
                                        }

                                        // Display the status with a badge
                                        echo "<td><span class='badge " . $badgeClass . "'>" . htmlspecialchars($row['status']) . "</span></td>";

                                        // Format the returned_at date
                                        $returnedAt = date('F j, Y, g:i a', strtotime($row['returned_at']));
                                        echo "<td>" . htmlspecialchars($returnedAt) . "</td>";

                                        echo "</tr>";
                                    }
                                } else {
                                    // No records found
                                    echo "<tr><td colspan='5'>No records found.</td></tr>";
                                }

                                // Close the database connection
                                $con->close();
                                ?>
                            </tbody>
                        </table>

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

    <!-- Template Main JS File -->
    <script src="assets/sweet-alert/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>


</body>

</html>