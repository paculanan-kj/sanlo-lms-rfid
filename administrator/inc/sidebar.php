<?php
// Include authentication check
require_once 'auth.php';

// Get the current page name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Optional: fetch current user's name/profile for display
$firstname = $_SESSION['firstname'] ?? 'User';
$fullname  = trim(($_SESSION['firstname'] ?? '') . ' ' . ($_SESSION['middlename'] ?? '') . ' ' . ($_SESSION['lastname'] ?? ''));
$profilePicture = $_SESSION['profile_picture'] ?? 'default.png';
$userrole = $_SESSION['userrole'] ?? 'User';
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'student-logged.php') ? 'active' : ''; ?>"
                href="student-logged.php">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Student Logged In</span>
            </a>
        </li>

        <!-- Books Management Dropdown -->
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo in_array($current_page, ['manage-book.php', 'book-availability.php', 'book-category.php']) ? 'active' : ''; ?>"
                data-bs-target="#books-nav" data-bs-toggle="collapse" href="#">
                <i class="bx bx-library"></i>
                <span>Books</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="books-nav"
                class="nav-content collapse <?php echo in_array($current_page, ['manage-book.php', 'book-availability.php', 'book-category.php']) ? 'show' : ''; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="manage-book.php" class="<?php echo ($current_page == 'manage-book.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Manage Books</span>
                    </a>
                </li>
                <li>
                    <a href="book-availability.php" class="<?php echo ($current_page == 'book-availability.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Availability</span>
                    </a>
                </li>
                <li>
                    <a href="book-category.php" class="<?php echo ($current_page == 'book-category.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Category</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'purchased-books.php') ? 'active' : ''; ?>"
                href="purchased-books.php">
                <i class="bx bx-book"></i>
                <span>Purchase Book</span>
            </a>
        </li>

        <!-- Reports Section -->
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo in_array($current_page, ['sold-books-report.php', 'student-logs-report.php', 'book-borrow-report.php', 'equipment-borrow-report.php']) ? 'active' : ''; ?>"
                data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
                <i class="bx bx-file"></i>
                <span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="reports-nav"
                class="nav-content collapse <?php echo in_array($current_page, ['sold-books-report.php', 'student-logs-report.php', 'book-borrow-report.php', 'equipment-borrow-report.php']) ? 'show' : ''; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="sold-books-report.php" class="<?php echo ($current_page == 'sold-books-report.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Sold Books</span>
                    </a>
                </li>
                <li>
                    <a href="student-logs-report.php" class="<?php echo ($current_page == 'student-logs-report.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Student Logs</span>
                    </a>
                </li>
                <li>
                    <a href="book-borrow-report.php" class="<?php echo ($current_page == 'book-borrow-report.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Book Borrow</span>
                    </a>
                    <!-- </li>
                <li>
                    <a href="equipment-borrow-report.php" class="<?php echo ($current_page == 'equipment-borrow-report.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Equipment Borrow</span>
                    </a>
                </li> -->
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'students.php') ? 'active' : ''; ?>" href="students.php">
                <i class="bx bx-group"></i>
                <span>Students</span>
            </a>
        </li>

        <!-- <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'school-year.php') ? 'active' : ''; ?>" href="school-year.php">
                <i class="bx bx-calendar"></i>
                <span>School Year</span>
            </a>
        </li> -->

        <!-- <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>" href="users.php">
                <i class="bx bx-user-circle"></i>
                <span>Users</span>
            </a>
        </li> -->

    </ul>
</aside>