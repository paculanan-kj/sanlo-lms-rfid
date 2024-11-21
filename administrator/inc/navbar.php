<?php
include 'backend/dbcon.php'; // Include database connection

// Get user_id from the URL
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

if ($userId) {
  // Fetch user's first name, last name, profile picture, and user role
  $stmt = $con->prepare("SELECT firstname, middlename, lastname, profile_picture, userrole FROM user WHERE user_id = ?");

  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result) {
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $firstname = htmlspecialchars($user['firstname']);
      $middlename = htmlspecialchars($user['middlename']);
      $lastname = htmlspecialchars($user['lastname']); // Fetch last name
      $profilePicture = htmlspecialchars($user['profile_picture'] ?? 'default.png');
      $userrole = htmlspecialchars($user['userrole'] ?? 'User'); // Default role if not set

      // Combine first and last name
      $fullname = $firstname . ' ' . $middlename . ' ' . $lastname; // Create full name
    } else {
      /* No user found: destroy session and redirect
      session_destroy(); // Destroy the session
      header("Location: ../index.php"); // Redirect to the login page
      exit(); // Exit to ensure no further code is executed*/
    }
  } else {
    /*session_destroy(); // Destroy the session
    header("Location: ../index.php"); // Redirect to the login page
    exit();*/
  }
} else {
  /*session_destroy(); // Destroy the session
  header("Location: ../index.php"); // Redirect to the login page
  exit();*/
}
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="#" class="logo d-flex align-items-center">
      <img src="assets/logo/ndk-logo.png" alt="">
      <span class="d-none d-lg-block">St. Lorenzo School of Polomolok</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo 'uploads/' . $profilePicture; ?>" alt="Profile" class="rounded-circle me-1">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $firstname; ?></span>
        </a><!-- End Profile Image Icon -->
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $fullname; ?></h6>
            <span><?php echo $userrole; ?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="#">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="#">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li> <a class="dropdown-item d-flex align-items-center" href="#" onclick="triggerLogout();">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->
    </ul>
  </nav><!-- End Icons Navigation -->
</header><!-- End Header -->


<link href="assets/sweet-alert/sweetalert2.min.css" rel="stylesheet">
<script src="assets/sweet-alert/sweetalert2.all.min.js"></script>
<script src="assets/js/jquery.min.js"></script>

<script>
  // Function to trigger SweetAlert and RFID scan
  function triggerLogout() {
    Swal.fire({
      title: 'Scan your RFID to log out',
      icon: 'info',
      html: `
        <div style="text-align: center;">
          <input 
            type="text" 
            id="rfidInput" 
            name="rfid" 
            placeholder="Scan RFID here..." 
            autofocus 
            oninput="handleRFIDScan()" 
            style="opacity: 0; position: absolute; pointer-events: none;" 
          />
          <i class="bi bi-broadcast" id="rfidIcon" style="font-size: 100px; cursor: pointer;"></i>
        </div>
      `,
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      willOpen: () => {
        const rfidInput = document.getElementById('rfidInput');
        rfidInput.focus(); // Automatically focus on the hidden input field
        console.log("Modal opened: Waiting for RFID input..."); // Debug log
      },
      didClose: () => {
        const rfidIcon = document.getElementById('rfidIcon');
        if (rfidIcon) {
          rfidIcon.classList.remove('pulse', 'green', 'red', 'scanning');
        }
        console.log("Modal closed."); // Debug log
      }
    });
  }

  // Function to handle RFID scan
  function handleRFIDScan() {
    const rfidInput = document.getElementById('rfidInput');
    const scannedRFID = rfidInput.value.trim(); // Get the scanned RFID value
    const rfidIcon = document.getElementById('rfidIcon');

    console.log(`RFID scanned: ${scannedRFID}`); // Log scanned RFID value

    if (scannedRFID) {
      // Send RFID data to backend for validation
      $.ajax({
        url: 'backend/validate_rfid.php', // Backend script for RFID validation
        method: 'POST',
        data: {
          rfid: scannedRFID
        },
        success: function(response) {
          console.log("Backend response:", response); // Log backend response
          if (response.status === 'success') {
            // RFID matches, show success feedback
            rfidIcon.classList.add('green');
            console.log("RFID validation successful. Redirecting to logout...");
            setTimeout(() => {
              window.location.href = '../backend/logout.php'; // Redirect to logout script
            }, 500);
          } else {
            // RFID does not match, show error feedback
            rfidIcon.classList.add('red');
            console.error("RFID validation failed: Mismatch.");
            Swal.fire('Error', 'RFID does not match. Please try again.', 'error');
            rfidInput.value = ''; // Clear the input field for the next attempt
          }
        },
        error: function(xhr, status, error) {
          // Handle server errors
          console.error("AJAX error:", error); // Log AJAX error
          rfidIcon.classList.add('red');
          Swal.fire('Error', 'Unable to validate RFID. Please try again later.', 'error');
          rfidInput.value = ''; // Clear the input field for the next attempt
        }
      });
    }
  }
</script>

<style>
  /* RFID icon default style */
  #rfidIcon {
    font-size: 50px;
    cursor: pointer;
    transition: color 0.3s ease;
  }

  /* Pulse animation for RFID icon */
  #rfidIcon.pulse {
    animation: pulse 1s infinite;
  }

  /* Scanning state */
  #rfidIcon.scanning {
    color: orange;
    /* Optional: Change to indicate scanning state */
  }

  /* Successful scan */
  #rfidIcon.green {
    color: #28a745;
    /* Green for success */
  }

  /* Failed scan */
  #rfidIcon.red {
    color: #dc3545;
    /* Red for error */
  }

  /* Hover effect */
  #rfidIcon:hover {
    transform: scale(1.1);
  }

  /* Keyframes for pulse animation */
  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 0.7;
    }

    50% {
      transform: scale(1.2);
      opacity: 1;
    }

    100% {
      transform: scale(1);
      opacity: 0.7;
    }
  }
</style>