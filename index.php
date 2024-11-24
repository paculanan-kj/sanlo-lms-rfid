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

  <style>
    .scanner-icon-container {
      position: relative;
      display: inline-block;
      text-align: center;
      margin-top: 20px;
    }

    /* Style for the RFID scan text */
    .scan-rfid-text {
      font-size: 24px;
      /* Font size */
      font-weight: bold;
      /* Bold text */
      text-transform: uppercase;
      /* All caps */
      letter-spacing: 2px;
      /* Slight spacing between letters */
      text-align: center;
      /* Center the text */
      color: #ffffff;
      /* White text color */
      padding: 10px 20px;
      /* Add some padding around the text */
      background: linear-gradient(45deg, #007bff, #00d2ff);
      /* Gradient background */
      border-radius: 5px;
      /* Rounded corners */
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.4);
      /* Subtle shadow */
      animation: pulseText 2s infinite;
      /* Apply animation */
      display: inline-block;
      /* Let it adjust with text */
    }


    .scan-rfid-text:hover {
      color: #fff;
      text-shadow: 0 0 20px rgba(0, 255, 255, 0.8), 0 0 30px rgba(0, 123, 255, 0.7);
      /* Glowing text effect */
      cursor: pointer;
    }

    /* Pulsing RFID icon - Make it larger */
    .pulse-icon {
      font-size: 100px;
      /* Increased size of the RFID icon */
      color: #007bff;
      animation: pulse 2s infinite;
      cursor: pointer;
    }

    /* Animation for pulsing */
    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 1;
      }

      50% {
        transform: scale(1.2);
        opacity: 0.7;
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* Scanner rings for animation */
    .scanner-rings {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 120px;
      /* Increased ring size to match the larger RFID icon */
      height: 120px;
      /* Increased ring size */
      border: 2px solid rgba(0, 123, 255, 0.5);
      border-radius: 50%;
      animation: scanner-ring 1.5s infinite;
    }

    @keyframes scanner-ring {
      0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 0.5;
      }

      100% {
        transform: translate(-50%, -50%) scale(1.5);
        opacity: 0;
      }
    }

    /* Positioning the arrow below the RFID icon - Lower it more */
    #toggleArrow {
      position: absolute;
      bottom: -60px;
      /* Moved the arrow lower */
      left: 50%;
      transform: translateX(-50%);
      /* Centers the arrow horizontally */
      font-size: 30px;
      /* Increased the size for better visibility */
      color: #007bff;
      transition: transform 0.3s ease, color 0.3s ease;
    }

    /* Hover effect for the arrow */
    #toggleArrow:hover {
      transform: translateX(-50%) translateY(-10px);
      /* Moves the arrow up slightly */
      color: #0056b3;
      /* Darker shade of blue on hover */
    }

    #toggleArrowBack {
      position: absolute;
      bottom: -60px;
      /* Moved the arrow lower */
      left: 50%;
      transform: translateX(-50%);
      /* Centers the arrow horizontally */
      font-size: 30px;
      /* Increased the size for better visibility */
      color: #007bff;
      transition: transform 0.3s ease, color 0.3s ease;
    }

    /* Hover effect for the arrow */
    #toggleArrowBack:hover {
      transform: translateX(-50%) translateY(-10px);
      /* Moves the arrow up slightly */
      color: #0056b3;
      /* Darker shade of blue on hover */
    }

    /* Hide the input field */
    .visually-hidden {
      position: absolute;
      left: -9999px;
    }

    /* Invalid feedback for missing scan */
    .invalid-feedback {
      color: #e74c3c;
      display: none;
      /* Hidden by default, can be shown on error */
    }
  </style>
</head>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex flex-column align-items-center py-4">
                <div class="logo d-flex flex-column align-items-center w-auto">
                  <img src="assets/logo/ndk-logo.png" alt="" style="max-width: 100px;"> <!-- Logo added here -->
                  <span class="d-none d-lg-block mt-2">St. Lorenzo School of Polomolok Library Management System</span>
                </div>
              </div><!-- End Logo -->

              <!-- RFID Scanning Card -->
              <div class="card mb-3" id="rfidCard">
                <div class="card-body text-center">
                  <h4 class="scan-rfid-text mt-4 mb-3">SCAN RFID CARD TO LOGIN</h4>

                  <div class="scanner-icon-container mb-5">
                    <!-- Animated Scanner Rings -->
                    <div class="scanner-rings"></div>

                    <!-- Pulsing RFID Icon -->
                    <i class="bi bi-broadcast pulse-icon" id="rfidIcon" tabindex="0"></i>

                    <!-- RFID Input Field (Hidden initially) -->
                    <input type="text" id="rfidInput" style="position: absolute; opacity: 0; pointer-events: none;">


                    <div class="invalid-feedback mt-2">Please scan your RFID</div>
                  </div>
                </div>
                <!-- Arrow Icon below the RFID Icon -->
                <i class="bi bi-arrow-right-circle" id="toggleArrow" style="cursor: pointer; font-size: 25px; color: #007bff;"></i>
              </div>

              <div id="loginCard" style="display: none; padding: 30px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);">
                <div class="text-center">
                  <h4 class="scan-rfid-text mt-1 mb-3">Enter credentials to login</h4>

                  <!-- Username Input -->
                  <div class="col-12 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username">
                    <div class="invalid-feedback">Please enter your username.</div>
                  </div>

                  <!-- Password Input -->
                  <div class="col-12 mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                    <div class="invalid-feedback">Please enter your password!</div>

                    <!-- Show/Hide Password Icon -->
                    <i class="bi bi-eye-slash" id="togglePassword" style="position: absolute; right: 10px; top: 38px; cursor: pointer; font-size: 20px; color: #007bff;"></i>
                  </div>

                  <!-- Login Button -->
                  <div class="col-12 mb-3">
                    <button class="btn btn-primary w-100" id="loginButton" type="button">Login</button>
                  </div>
                </div>

                <!-- Back Arrow Icon -->
                <i class="bi bi-arrow-left-circle" id="toggleArrowBack" style="cursor: pointer; font-size: 25px; color: #007bff;"></i>
              </div>

            </div>
          </div>
        </div>
      </section>
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
    const rfidCard = document.getElementById('rfidCard');
    const loginCard = document.getElementById('loginCard');
    const toggleArrow = document.getElementById('toggleArrow');
    const toggleArrowBack = document.getElementById('toggleArrowBack');

    // Toggle the visibility of cards on arrow click (RFID -> Login)
    toggleArrow.addEventListener('click', () => {
      // Hide the RFID scanning card
      rfidCard.style.display = 'none';

      // Show the login card
      loginCard.style.display = 'block';
    });

    // Toggle the visibility of cards on back arrow click (Login -> RFID)
    toggleArrowBack.addEventListener('click', () => {
      // Hide the login card
      loginCard.style.display = 'none';

      // Show the RFID scanning card
      rfidCard.style.display = 'block';
    });
  </script>

  <script>
    // Show/Hide Password Logic
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordField = document.getElementById('password');
      const icon = document.getElementById('togglePassword');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      } else {
        passwordField.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      }
    });
  </script>

  <script>
    const rfidIcon = document.getElementById('rfidIcon');
    const rfidInput = document.getElementById('rfidInput');
    let rfidValue = '';

    // Focus the hidden input when icon is clicked
    rfidIcon.addEventListener('click', () => {
      rfidInput.focus();
    });

    // Keep focus on input when clicking anywhere on the document
    document.addEventListener('click', () => {
      rfidInput.focus();
    });

    // Auto-focus when page loads
    document.addEventListener('DOMContentLoaded', () => {
      rfidInput.focus();
    });

    // Function to handle RFID authentication
    async function authenticateRFID(rfid) {
      try {
        const response = await fetch('backend/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `rfid=${encodeURIComponent(rfid)}`
        });

        const data = await response.json();

        if (data.success) {
          // Success alert
          await Swal.fire({
            icon: 'success',
            title: 'Login Successful',
            text: data.message,
            timer: 500,
            showConfirmButton: false
          });

          // Redirect after successful login
          window.location.href = data.redirect_url;
        } else {
          // Error alert
          await Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: data.message,
            timer: 2000,
            showConfirmButton: false
          });
        }
      } catch (error) {
        // Network or other error
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred. Please try again.',
          timer: 1500,
          showConfirmButton: false
        });
      }

      // Reset values and refocus
      rfidValue = '';
      rfidInput.value = '';
      rfidInput.focus();
    }

    // Handle keyboard input
    rfidInput.addEventListener('keydown', async (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();

        if (rfidValue) {
          // Show loading state
          Swal.fire({
            title: 'Scanning...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // Authenticate RFID
          await authenticateRFID(rfidValue);
        } else {
          // Empty input error
          Swal.fire({
            icon: 'error',
            title: 'Scan Failed',
            text: 'Please try scanning your card again',
            timer: 15000,
            showConfirmButton: false
          }).then(() => {
            rfidInput.focus();
          });
        }
      } else {
        // Build the RFID value as user types/scans
        if (event.key !== 'Shift' && event.key !== 'Tab') {
          rfidValue += event.key;
        }
      }
    });
  </script>

  <script>
    document.getElementById('loginButton').addEventListener('click', async () => {
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();

      // Check for empty inputs
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
        // Show loading state
        Swal.fire({
          title: 'Logging in...',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Send AJAX request to the backend
        const response = await fetch('backend/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        });

        const data = await response.json();

        if (data.success) {
          // Success alert and redirect
          await Swal.fire({
            icon: 'success',
            title: 'Login Successful',
            text: data.message,
            timer: 1000,
            showConfirmButton: false
          });
          window.location.href = data.redirect_url;
        } else {
          // Error alert
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          });
        }
      } catch (error) {
        // Network or server error
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred. Please try again later.',
          timer: 1500,
          showConfirmButton: false
        });
      }
    });
  </script>

</body>

</html>