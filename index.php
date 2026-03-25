<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>St. Lorenzo</title>
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
  <link href="assets/css/index-style.css" rel="stylesheet">
</head>

<body>
  <main class="login-main">
    <div class="container content-container">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

          <!-- Login Card -->
          <div id="loginCard" class="login-card">
            <div class="text-center">

              <!-- ✅ FORM ADDED -->
              <form id="loginForm">

                <div class="col-12 mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" id="username" required>
                  <div class="invalid-feedback">Please enter your username.</div>
                </div>

                <div class="col-12 mb-3 position-relative">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="password" required>
                  <div class="invalid-feedback">Please enter your password!</div>

                  <!-- ✅ FIXED TOGGLE ICON -->
                  <i class="bi bi-eye-slash toggle-password" id="togglePassword"
                    style="position:absolute; right:15px; top:38px; cursor:pointer;"></i>
                </div>

                <div class="col-12 mb-3">
                  <!-- ✅ type=submit so Enter key works -->
                  <button class="btn btn-primary w-100" type="submit">
                    Login
                  </button>
                </div>

              </form>
              <!-- END FORM -->

            </div>
          </div>
          <!-- End Login Card -->

        </div>
      </div>
    </div>
  </main>

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
    document.addEventListener("DOMContentLoaded", function() {

      // Show / Hide Password
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('password');

      togglePassword.addEventListener('click', function() {
        const isPassword = passwordField.type === 'password';
        passwordField.type = isPassword ? 'text' : 'password';

        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
      });

    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      const loginForm = document.getElementById('loginForm');

      loginForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent normal form submission

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        // Validate empty fields
        if (!username || !password) {
          Swal.fire({
            icon: 'error',
            title: 'Missing Credentials',
            text: 'Please enter both username and password.',
            timer: 1500,
            showConfirmButton: false
          });
          return;
        }

        try {

          // Loading state
          Swal.fire({
            title: 'Logging in...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          const response = await fetch('backend/login.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
          });

          const data = await response.json();

          if (data.success) {

            await Swal.fire({
              icon: 'success',
              title: 'Login Successful',
              text: data.message,
              timer: 1000,
              showConfirmButton: false
            });

            window.location.href = data.redirect_url;

          } else {

            Swal.fire({
              icon: 'error',
              title: 'Login Failed',
              text: data.message,
              timer: 1500,
              showConfirmButton: false
            });

          }

        } catch (error) {

          Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'An error occurred. Please try again later.',
            timer: 1500,
            showConfirmButton: false
          });

        }

      });

    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      const params = new URLSearchParams(window.location.search);

      if (params.has("timeout")) {
        Swal.fire({
          icon: "warning",
          title: "Session Expired",
          text: "Your session has timed out due to inactivity.",
          confirmButtonText: "OK"
        });
      }

    });
  </script>

</body>

</html>