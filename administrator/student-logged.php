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
  <link href="assets/sweet-alert/sweetalert2.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    .student-attendance-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .student-attendance-card {
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .student-attendance-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
    }

    .student-avatar-wrapper {
      display: flex;
      justify-content: center;
    }

    .student-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #f1f3f9;
    }

    .time-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 12px;
      border-radius: 8px;
      min-height: 100px;
      transition: transform 0.3s ease;
    }

    .time-box:hover {
      transform: scale(1.05);
    }

    .time-box .time-label {
      font-size: 0.75rem;
      /* Smaller label */
      font-weight: 600;
      color: rgba(0, 0, 0, 0.5);
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .time-box .time-value {
      font-size: 0.9rem;
      /* Larger value */
      font-weight: 700;
    }

    .text-info {
      color: #0dcaf0 !important;
    }

    @media (max-width: 768px) {
      .time-box {
        min-height: 80px;
      }

      .time-box .time-label {
        font-size: 0.7rem;
      }

      .time-box .time-value {
        font-size: 1rem;
      }
    }

    .bg-success-soft {
      background-color: rgba(25, 135, 84, 0.1);
    }

    .bg-warning-soft {
      background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-info-soft {
      background-color: rgba(13, 202, 240, 0.1);
    }
  </style>

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
        <?php
        include('backend/dbcon.php');

        // Fetch the active school year
        $query = "SELECT school_year FROM school_year WHERE status = 'active' LIMIT 1";
        $result = $con->query($query);
        $active_school_year = ($result->num_rows > 0) ? $result->fetch_assoc()['school_year'] : 'No Active School Year';
        ?>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item active">School Year: <?= htmlspecialchars($active_school_year); ?></li>
          </ol>
        </nav>
      </div>
      <?php
      $encoded_user_id = base64_encode($user_id);
      ?>
      <button onclick="location.href='student-scan.php?user_id=<?php echo $encoded_user_id; ?>';" class="btn btn-success">Student In/Out</button>
    </div>

    <?php
    include('backend/fetch-today-attendance.php');
    ?>

    <!-- Display the Attendance Data -->
    <section class="section dashboard">
      <div class="row">
        <?php if (empty($attendanceData)): ?>
          <div class="col-12 text-center">
            <p class="text-muted">No Students are logged for today.</p>
          </div>
        <?php else: ?>
          <?php foreach ($attendanceData as $student): ?>
            <div class="col-md-3 mb-2"> <!-- Changed from col-3 to col-md-3 -->
              <div class="card student-attendance-card">
                <div class="card-body">
                  <div class="student-header text-center">
                    <div class="student-avatar-wrapper mt-4">
                      <img src="<?php echo $student['photo_url']; ?>" alt="Student Photo" class="student-avatar">
                    </div>
                    <span class="student-id text-muted"><?php echo $student['student_name']; ?></span>
                  </div>

                  <div class="attendance-details mt-4">
                    <div class="row g-3">
                      <div class="col-md-4">
                        <div class="time-box time-in bg-success-soft">
                          <div class="time-label">Time In</div>
                          <div class="time-value text-success"><?php echo $student['time_in']; ?></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="time-box time-out bg-warning-soft">
                          <div class="time-label">Time Out</div>
                          <div class="time-value text-warning"><?php echo $student['time_out']; ?></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="time-box date bg-info-soft">
                          <div class="time-label">Date</div>
                          <div class="time-value text-info"><?php echo $student['attendance_date']; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="attendance-status mt-3 text-center mb-3">
                    <span class="badge <?php echo ($student['time_out'] === '--:--') ? 'bg-warning' : 'bg-success'; ?>">
                      <?php echo ($student['time_out'] === '--:--') ? 'Partially Logged' : 'Fully Logged'; ?>
                    </span>
                  </div>

                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
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
    function checkSessionTimeout() {
      fetch('backend/session.php?check_timeout=true')
        .then(response => response.json())
        .then(data => {
          if (data.timeout) {
            Swal.fire({
              icon: 'warning',
              title: 'Session Expired',
              text: 'Your session has ended. Please log in again.',
              confirmButtonText: 'OK'
            }).then(() => {
              window.location.href = '../index.php';
            });
          }
        })
        .catch(error => {
          console.error('Session timeout check failed:', error);
        });
    }

    // Check every 30 seconds
    setInterval(checkSessionTimeout, 30000);
  </script>
</body>

</html>