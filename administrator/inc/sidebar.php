<?php
$current_page = basename($_SERVER['PHP_SELF']);  // Get the current page name
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'student-logged.php') ? 'active' : ''; ?>"
                href="student-logged.php?user_id=<?php echo $user_id; ?>">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Student Logged In</span>
            </a>
        </li>

        <!-- Borrow Books Dropdown -->
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo ($current_page == 'borrow-book.php' || $current_page == 'returned-books.php') ? 'active' : ''; ?>"
                data-bs-target="#borrowBooks-nav" data-bs-toggle="collapse" href="#">
                <i class="bx bx-notepad"></i>
                <span>Borrow Books</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="borrowBooks-nav"
                class="nav-content collapse <?php echo ($current_page == 'borrow-book.php' || $current_page == 'returned-book.php') ? 'show' : ''; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="borrow-book.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'borrow-book.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Borrow</span>
                    </a>
                </li>
                <li>
                    <a href="returned-book.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'returned-book.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Return</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Borrow Equipment Dropdown -->
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo ($current_page == 'borrow-equipment.php' || $current_page == 'returned-equipment.php') ? 'active' : ''; ?>"
                data-bs-target="#borrowEquipment-nav" data-bs-toggle="collapse" href="#">
                <i class="bx bx-wrench"></i>
                <span>Borrow Equipment</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="borrowEquipment-nav"
                class="nav-content collapse <?php echo ($current_page == 'borrow-equipment.php' || $current_page == 'returned-equipment.php') ? 'show' : ''; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="borrow-equipment.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'borrow-equipment.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Borrow</span>
                    </a>
                </li>
                <li>
                    <a href="returned-equipment.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'returned-equipment.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Return</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Books Management Dropdown -->
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo ($current_page == 'manage-book.php' || $current_page == 'book-availability.php' || $current_page == 'book-location.php' || $current_page == 'book-category.php') ? 'active' : ''; ?>"
                data-bs-target="#books-nav" data-bs-toggle="collapse" href="#">
                <i class="bx bx-library"></i>
                <span>Books</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="books-nav"
                class="nav-content collapse <?php echo ($current_page == 'manage-book.php' || $current_page == 'book-availability.php' || $current_page == 'book-location.php' || $current_page == 'book-category.php') ? 'show' : ''; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="manage-book.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'manage-book.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Manage Books</span>
                    </a>
                </li>
                <li>
                    <a href="book-availability.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'book-availability.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Availability</span>
                    </a>
                </li>
                <li>
                    <a href="book-location.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'book-location.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Location</span>
                    </a>
                </li>
                <li>
                    <a href="book-category.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'book-category.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Category</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'purchased-books.php') ? 'active' : ''; ?>"
                href="purchased-books.php?user_id=<?php echo $user_id; ?>">
                <i class="bx bx-book"></i>
                <span>Purchase Book</span>
            </a>
        </li>

        <!-- Reports Section -->
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo in_array($current_page, ['sold-books-report.php', 'student-logs-report.php']) ? 'active' : ''; ?>"
                data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
                <i class="bx bx-file"></i>
                <span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="reports-nav"
                class="nav-content collapse <?php echo in_array($current_page, ['sold-books-report.php', 'student-logs-report.php']) ? 'show' : ''; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="sold-books-report.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'sold-books-report.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Sold Books</span>
                    </a>
                </li>
                <li>
                    <a href="student-logs-report.php?user_id=<?php echo $user_id; ?>"
                        class="<?php echo ($current_page == 'student-logs-report.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Student Logs</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'students.php') ? 'active' : ''; ?>"
                href="students.php?user_id=<?php echo $user_id; ?>">
                <i class="bx bx-group"></i>
                <span>Students</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>"
                href="users.php?user_id=<?php echo $user_id; ?>">
                <i class="bx bx-user-circle"></i>
                <span>Users</span>
            </a>
        </li>
    </ul>
</aside>