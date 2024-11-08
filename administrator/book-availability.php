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
            <h1>Availability</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Books</li>
                    <li class="breadcrumb-item active">Availability</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Availability</h5>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Title</th>
                                        <th>Available</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        require('backend/dbcon.php'); // Ensure this file contains your database connection logic

                                        // Query to fetch all books with their associated category names and borrowed count
                                        $query = "
                                            SELECT b.*, c.category_id, c.category_name, 
                                            COALESCE(SUM(bb.quantity), 0) AS borrowed_count
                                            FROM book b
                                            LEFT JOIN book_categories c ON b.category_id = c.category_id
                                            LEFT JOIN book_borrow bb ON b.book_id = bb.book_id AND bb.status = 'borrowed' 
                                            GROUP BY b.book_id, c.category_id
                                        ";

                                        $result = $con->query($query);

                                        // Check for errors
                                        if ($result === false) {
                                            die("Error fetching data: " . $con->error);
                                        }

                                        // Fetch and display each book
                                        while ($row = $result->fetch_assoc()) {
                                            $totalCopies = (int)$row['copies'];
                                            $borrowedCount = (int)$row['borrowed_count'];
                                            $availableCount = $totalCopies - $borrowedCount;

                                            // Determine status based on available copies and set badge classes
                                            if ($availableCount > 0) {
                                                $status = 'Available';
                                                $badgeClass = 'bg-success'; // Green badge for available
                                            } else {
                                                $status = 'Unavailable';
                                                $badgeClass = 'bg-danger'; // Red badge for unavailable
                                            }

                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td><span class='badge bg-warning text-dark'>" . $availableCount . "</span></td>";
                                            echo "<td><span class='badge " . $badgeClass . " text-light'>" . htmlspecialchars($status) . "</span></td>";
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

</body>

</html>