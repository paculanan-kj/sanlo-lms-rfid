<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header class="topbar">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <div class="logo">
      <img src="assets/logo/ndk-logo.png" alt="School Logo">
      St. Lorenzo School of Polomolok
    </div>
    <nav class="navbar d-flex">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'student-entry.php') ? 'active' : ''; ?>" href="student-entry.php">
            <i class="bi bi-upc-scan"></i> Attendance
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'borrow-book.php') ? 'active' : ''; ?>" href="borrow-book.php">
            <i class="bi bi-book"></i> Books
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'borrow-equipment.php') ? 'active' : ''; ?>" href="borrow-equipment.php">
            <i class="bi bi-tools"></i> Equipment
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'search_book.php') ? 'active' : ''; ?>" href="search_book.php">
            <i class="bi bi-search"></i> Search Book
          </a>
        </li>
      </ul>
    </nav>
  </div>
</header>