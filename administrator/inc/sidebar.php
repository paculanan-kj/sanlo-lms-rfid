<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);  // Get the current page name
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-home-alt"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'student-logged.php') ? 'active' : ''; ?>" href="student-logged.php?user_id=<?php echo $user_id; ?>">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Student Logged In</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'borrow-book.php') ? 'active' : ''; ?>" href="borrow-book.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-notepad"></i>
        <span>Borrow Books</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'borrow-equipment.php') ? 'active' : ''; ?>" href="borrow-equipment.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-wrench"></i>
        <span>Borrow Equipment</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'manage-book.php') ? 'active' : ''; ?>" href="manage-book.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-library"></i>
        <span>Books</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'purchased-book.php') ? 'active' : ''; ?>" href="purchased-book.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-briefcase"></i>
        <span>Book Sale</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'students.php') ? 'active' : ''; ?>" href="students.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-group"></i>
        <span>Students</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>" href="users.php?user_id=<?php echo $user_id; ?>">
        <i class="bx bx-user-circle"></i>
        <span>Users</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="../backend/logout.php">
        <i class="bx bx-log-out"></i>
        <span>Logout</span>
      </a>
    </li>

  </ul>

</aside>