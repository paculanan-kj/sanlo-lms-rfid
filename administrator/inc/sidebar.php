<?php
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

    <!-- Borrow Books Dropdown -->
    <li class="nav-item">
      <a class="nav-link collapsed <?php echo ($current_page == 'borrow-book.php' || $current_page == 'returned-books.php') ? 'active' : ''; ?>" data-bs-target="#borrowBooks-nav" data-bs-toggle="collapse" href="#">
        <i class="bx bx-notepad"></i>
        <span>Borrow Books</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="borrowBooks-nav" class="nav-content collapse <?php echo ($current_page == 'borrow-book.php' || $current_page == 'returned-book.php') ? 'show' : ''; ?>" data-bs-parent="#sidebar-nav">
        <li>
          <a href="borrow-book.php?user_id=<?php echo $user_id; ?>" class="<?php echo ($current_page == 'borrow-book.php') ? 'active' : ''; ?>">
            <i class="bx bx-book"></i><span>Borrow</span>
          </a>
        </li>
        <li>
          <a href="returned-book.php?user_id=<?php echo $user_id; ?>" class="<?php echo ($current_page == 'returned-book.php') ? 'active' : ''; ?>">
            <i class="bx bx-book-open"></i><span>Returned</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Borrow Equipment Dropdown -->
    <li class="nav-item">
      <a class="nav-link collapsed <?php echo ($current_page == 'borrow-equipment.php' || $current_page == 'returned-equipment.php') ? 'active' : ''; ?>" data-bs-target="#borrowEquipment-nav" data-bs-toggle="collapse" href="#">
        <i class="bx bx-wrench"></i>
        <span>Borrow Equipment</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="borrowEquipment-nav" class="nav-content collapse <?php echo ($current_page == 'borrow-equipment.php' || $current_page == 'returned-equipment.php') ? 'show' : ''; ?>" data-bs-parent="#sidebar-nav">
        <li>
          <a href="borrow-equipment.php?user_id=<?php echo $user_id; ?>" class="<?php echo ($current_page == 'borrow-equipment.php') ? 'active' : ''; ?>">
            <i class="bx bx-wrench"></i><span>Borrow</span>
          </a>
        </li>
        <li>
          <a href="returned-equipment.php?user_id=<?php echo $user_id; ?>" class="<?php echo ($current_page == 'returned-equipment.php') ? 'active' : ''; ?>">
            <i class="bx bx-tools"></i><span>Returned</span>
          </a>
        </li>
      </ul>
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