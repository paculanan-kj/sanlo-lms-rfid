
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

    <div class="pagetitle d-flex align-items-center justify-content-between">
        <div>
            <h1>Student Logged In</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">Student Logged In</li>
                </ol>
            </nav>
        </div>
        <button onclick="location.href='student-scan.php';" class="btn btn-success">Student In/Out</button>
    </div>

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- TimeIn/out Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <div class="d-flex justify-content-center pt-4">
                    <a href="#" class="student d-flex align-items-center w-auto">
                      <img src="assets/img/images.png" alt="">
                    </a>
                  </div><!-- End Logo -->
                  <span class="d-flex justify-content-center">Kristian Jay Paculanan</span>
                  <h1 class="pt-2">Time In: 9:40 AM</h1>
                  <h1>Time Out: </h1>
                  <h1>Date: 9/28/2024 </h1>
                </div>
              </div>
            </div><!-- End Card -->

            <!-- TimeIn/out Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <div class="d-flex justify-content-center pt-4">
                    <a href="#" class="student d-flex align-items-center w-auto">
                      <img src="assets/img/images.png" alt="">
                    </a>
                  </div><!-- End Logo -->
                  <span class="d-flex justify-content-center">John Paul L. Bayok</span>
                  <h1 class="pt-2">Time In: 7:40 AM</h1>
                  <h1>Time Out: </h1>
                  <h1>Date: 9/28/2024 </h1>
                </div>
              </div>
            </div><!-- End Card -->

            <!-- TimeIn/out Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <div class="d-flex justify-content-center pt-4">
                    <a href="#" class="student d-flex align-items-center w-auto">
                      <img src="assets/img/images.png" alt="">
                    </a>
                  </div><!-- End Logo -->
                  <span class="d-flex justify-content-center">Gerber Jay L. Palomo</span>
                  <h1 class="pt-2">Time In: 1:40 PM</h1>
                  <h1>Time Out: </h1>
                  <h1>Date: 9/28/2024 </h1>
                </div>
              </div>
            </div><!-- End Card -->

            <!-- TimeIn/out Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <div class="d-flex justify-content-center pt-4">
                    <a href="#" class="student d-flex align-items-center w-auto">
                      <img src="assets/img/images.png" alt="">
                    </a>
                  </div><!-- End Logo -->
                  <span class="d-flex justify-content-center">John Doe</span>
                  <h1 class="pt-2">Time In: 8:41 AM</h1>
                  <h1>Time Out: </h1>
                  <h1>Date: 9/29/2024 </h1>
                </div>
              </div>
            </div><!-- End Card -->
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