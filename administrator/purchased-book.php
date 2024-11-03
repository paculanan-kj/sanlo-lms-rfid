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
</head>

<body>
  <?php
  include 'inc/navbar.php';
  include 'inc/sidebar.php';
  ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Purchased Book</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
          <li class="breadcrumb-item active">Purchased Book</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Purchased Book</h5>
              <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addbook" style="margin-right: 12px;">
                <i class="bx bx-plus"></i>Add Transaction
              </button>
            </div>
            <table class="table datatable">
              <thead>
                <tr>
                  <th class="mt">Namge</th>
                  <th>Grade Level</th>
                  <th class="mi">Book</th>
                  <th class="mp">Amount</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require('backend/dbcon.php'); // Ensure this file contains your database connection logic

                // Query to fetch all books
                $query = "SELECT * FROM book";
                $result = $con->query($query);

                // Check for errors
                if ($result === false) {
                  die("Error fetching data: " . $con->error);
                }

                // Fetch and display each book
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['publisher']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['publication_year']) . "</td>";
                  echo "<td>
                            <a href='#' class='btn btn-success btn-sm btn-update' data-id='" . $row['book_id'] . "' data-bs-toggle='modal' data-bs-target='#updatebook'>Update</a>
                            <a href='#' class='btn btn-danger btn-sm'>Delete</a>
                          </td>";
                  echo "</tr>";
                }
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
  <script src="assets/js/main.js"></script>

</body>

</html>