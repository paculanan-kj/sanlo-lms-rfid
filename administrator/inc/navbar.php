<?php
require_once 'auth.php';          // Protect page
include '../backend/dbcon.php';   // Adjust path if needed

$userId = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT firstname, lastname, profile_picture, userrole FROM user WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {

  $firstname = htmlspecialchars($user['firstname']);
  $lastname = htmlspecialchars($user['lastname']);
  $profilePicture = !empty($user['profile_picture'])
    ? htmlspecialchars($user['profile_picture'])
    : 'default.png';

  $userrole = htmlspecialchars($user['userrole']);
  $fullname = trim("$firstname $lastname");
} else {
  // If somehow user doesn't exist anymore
  session_unset();
  session_destroy();
  header("Location: ../index.php");
  exit();
}

if ($userId) {
  // Fetch user's first name, last name, profile picture, and user role
  $stmt = $con->prepare("SELECT firstname, lastname, profile_picture, userrole FROM user WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result) {
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $firstname = htmlspecialchars($user['firstname']);
      $lastname = htmlspecialchars($user['lastname']);
      $profilePicture = htmlspecialchars($user['profile_picture'] ?? 'default.png');
      $userrole = htmlspecialchars($user['userrole'] ?? 'User'); // Default role if not set

      // Combine first and last name
      $fullname = $firstname . ' ' . $lastname;
    } else {
      // No user found: handle accordingly (optional: session destroy and redirect)
      // session_destroy();
      // header("Location: ../index.php");
      // exit();
    }
  } else {
    // Error in fetching data: handle accordingly (optional: session destroy and redirect)
    // session_destroy();
    // header("Location: ../index.php");
    // exit();
  }
} else {
  // Invalid or missing user_id: handle accordingly (optional: session destroy and redirect)
  // session_destroy();
  // header("Location: ../index.php");
  // exit();
}
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="#" class="logo d-flex align-items-center">
      <img src="assets/logo/ndk-logo.png" alt="">
      <span class="d-none d-lg-block">St. Lorenzo School of Polomolok Inc.</span>
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
            <a class="dropdown-item d-flex align-items-center" href="../backend/logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->
    </ul>
  </nav><!-- End Icons Navigation -->
</header><!-- End Header -->