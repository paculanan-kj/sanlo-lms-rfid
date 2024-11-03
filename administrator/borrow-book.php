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
          <li class="breadcrumb-item active">Borrow Books</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Borrowed Books</h5>
              <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#borrowModal">
                <i class="bx bx-plus me-1"></i>Borrow
              </button>
            </div>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Book</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="button">
                    <button type="button" class="btn s btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewsof">Update</button>
                    <button type="button" class="btn s btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updatesof">Receive</button>
                    <button type="button" class="btn s btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#updatesof">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- Add Contract Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form id="addBookBorrowForm">
            <div class="modal-header">
              <h5 class="modal-title" id="borrowModalLabel">Borrow Book</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="inputRfid" class="form-label">Scan RFID</label>
                <input type="text" class="form-control" id="inputRfid" name="rfid" required placeholder="Scan RFID here ..." autofocus>
              </div>
              <div class="row">
                <div class="col-8">
                  <input type="hidden" class="form-control" id="studentId" name="student_id" required readonly>
                  <div class="mb-3">
                    <label for="studentName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="studentName" name="student_name" required readonly>
                  </div>
                  <div class="mb-3 ">
                    <label for="gradeLevel" class="form-label">Grade Level</label>
                    <input type="text" class="form-control" id="gradeLevel" name="grade_level" required readonly>
                  </div>
                </div>
                <div class="col-3">
                  <div class="mt-3 me-2">
                    <img id="profilePicture" src="" alt="Profile Picture" class="img-fluid " style="display:none; height: 140px; width: 140px;">
                    <input type="hidden" class="form-control" id="profilePictureInput" name="profile_picture">
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-4">
                  <div class="mb-3">
                    <label for="BorrowBook" class="form-label">Book</label>
                    <input type="text" class="form-control" id="BorrowBook" name="book" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="mb-3">
                    <label for="bookQuantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="bookQuantity" name="quantity" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>

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

  <script>
    let rfidTimeout;

    // Prevent form submission
    document.getElementById('addBookBorrowForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent default form submission
    });

    document.getElementById('inputRfid').addEventListener('input', function() {
      const rfidInput = this;

      // Clear any previous timeout to debounce input
      clearTimeout(rfidTimeout);

      // Set a new timeout to wait for user input to complete
      rfidTimeout = setTimeout(() => {
        const rfid = rfidInput.value.trim();

        if (rfid) {
          fetch('backend/fetch-student-borrow.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                rfid
              }),
            })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                const student = data.student;
                document.getElementById('studentId').value = student.student_id;
                document.getElementById('studentName').value = student.full_name;
                document.getElementById('gradeLevel').value = student.grade_level;

                // Set the image source and ensure it's visible
                document.getElementById('profilePicture').src = student.profile_picture;
                document.getElementById('profilePicture').style.display = 'block';

                // Store the image path in a hidden input
                document.getElementById('profilePictureInput').value = student.profile_picture;
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops!',
                  text: data.message || 'Student not found.',
                });
              }
            })

            .catch((error) => {
              console.error('Error fetching student data:', error);
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while fetching the student data.',
              });
            });
        } else {
          document.getElementById('studentId').value = '';
          document.getElementById('studentName').value = '';
          document.getElementById('gradeLevel').value = '';
          document.getElementById('profilePicture').src = '';
          document.getElementById('profilePicture').style.display = 'none';
        }
      }, 300);
    });
  </script>


</body>

</html>