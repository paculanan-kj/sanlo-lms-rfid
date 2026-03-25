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
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <?php
            include('backend/fetch-student-attendance.php');
            ?>

            <!-- TimeIn/out Card -->
            <div class="col-md-6">
              <div class="card info-card sales-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <!-- Include the user_id in the URL when applying filters -->
                    <li><a class="dropdown-item" href="?user_id=<?php echo $_GET['user_id']; ?>&filter=today">Today</a></li>
                    <li><a class="dropdown-item" href="?user_id=<?php echo $_GET['user_id']; ?>&filter=this_month">This Month</a></li>
                    <li><a class="dropdown-item" href="?user_id=<?php echo $_GET['user_id']; ?>&filter=this_year">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Student Time In/Out</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totalStudents; ?></h6> <!-- Display the total students logged based on the selected filter -->
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Card -->

            <div class="col-md-6">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title">Book Purchased</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-briefcase"></i>
                    </div>
                    <div class="ps-3">
                      <h6>
                        <?php
                        // Include database connection
                        include('backend/dbcon.php');

                        // Check the connection
                        if (!$con) {
                          die("Connection failed: " . mysqli_connect_error());
                        }

                        // Check if user_id is passed in the URL
                        if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
                          die('Error: user_id is missing or empty.');
                        }

                        // Initialize SQL query to get total purchased books
                        $sql = "SELECT SUM(COALESCE(pd.quantity, 0)) AS total_purchased_books 
                    FROM purchased_books pb
                    JOIN purchase_details pd ON pb.purchase_id = pd.purchase_id
                    WHERE pb.student_id = ?";

                        // Prepare the SQL statement
                        $stmt = $con->prepare($sql);

                        // Check if the statement was prepared successfully
                        if ($stmt === false) {
                          die('Error preparing SQL statement: ' . $con->error);
                        }

                        // Bind user_id parameter
                        $stmt->bind_param("i", $_GET['user_id']);

                        // Execute the query
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Fetch the result
                        $total_purchased_books = 0; // Default value if no books are purchased

                        if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          // Debugging: Check the result before displaying it
                          var_dump($row); // Output the raw result to see what is returned
                          $total_purchased_books = $row['total_purchased_books'] ?? 0;
                        }

                        // Display total purchased books
                        echo $total_purchased_books;

                        // Close the statement and connection
                        $stmt->close();
                        $con->close();
                        ?>
                      </h6>
                    </div>
                  </div>
                </div>
              </div>
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

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>