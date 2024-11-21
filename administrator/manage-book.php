<?php
session_start();
?>
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
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

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
</head>

<body>
    <?php
    include 'inc/navbar.php';
    include 'inc/sidebar.php';
    include 'backend/dbcon.php'; // Include your database connection

    // Query to fetch users from the database
    $sql = "SELECT user_id, firstname, middlename, lastname, email, username FROM user";
    $result = $con->query($sql);
    ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Books</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">Books</li>
                    <li class="breadcrumb-item active">Manage Books</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Books</h5>
                                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#addbook">
                                    <i class="bx bx-plus"></i> Add Book
                                </button>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Category</th>
                                        <th style="width: 20%">Title</th>
                                        <th style="width: 15%">Author</th>
                                        <th>ISBN</th>
                                        <th style="width: 15%">Publisher</th>
                                        <th style="display: none;">Publication Year</th>
                                        <th style="display: none;">Location</th>
                                        <th>Copies</th>
                                        <th style="display: none;">Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require('backend/dbcon.php'); // Ensure this file contains your database connection logic

                                    // Query to fetch all books with their associated category names
                                    $query = "
                                          SELECT b.*, c.category_id, c.category_name
                                          FROM book b
                                          LEFT JOIN book_categories c ON b.category_id = c.category_id
                                      ";
                                    $result = $con->query($query);

                                    // Check for errors
                                    if ($result === false) {
                                        die("Error fetching data: " . $con->error);
                                    }

                                    // Fetch and display each book
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['category_name']) . "</td>"; // Display the category name
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>"; // Assuming the title column is book_title
                                        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['publisher']) . "</td>";
                                        echo "<td style='display: none;'>" . htmlspecialchars($row['publication_year']) . "</td>";
                                        echo "<td style='display: none;'>" . htmlspecialchars($row['location']) . "</td>";

                                        // Display copies as a badge
                                        echo "<td><span class='badge bg-warning text-dark'>" . htmlspecialchars($row['copies']) . "</span></td>";
                                        echo "<td style='display: none;'>₱" . number_format($row['amount'], 2) . "</td>";

                                        echo "<td>
                                              <button 
                                                  class='btn btn-primary btn-sm btn-update' 
                                                  data-id='" . $row['book_id'] . "' 
                                                  data-category-id='" . $row['category_id'] . "' 
                                                  data-category-name='" . htmlspecialchars($row['category_name']) . "' 
                                                  data-bs-toggle='modal' 
                                                  data-bs-target='#updatebook'>Edit
                                              </button>
                                              <button 
                                                  class='btn btn-danger btn-sm btn-delete' 
                                                  data-id='" . $row['book_id'] . "'>Delete
                                              </button>
                                            </td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add Modal -->
        <form id="addBookForm" method="POST">
            <div class="card">
                <div class="card-body">
                    <div class="modal fade" id="addbook" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Book</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                                    <!-- Category Selection -->
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-select" id="category" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            include 'backend/dbcon.php'; // Include your DB connection script

                                            // Fetch categories from the database
                                            $query = "SELECT category_id, category_name FROM book_categories ORDER BY category_name ASC";
                                            $result = $con->query($query);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['category_id'] . "'>" . htmlspecialchars($row['category_name']) . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No categories available</option>";
                                            }
                                            $con->close();
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Hidden field to display selected category_id -->
                                    <input type="hidden" id="selectedCategoryId" name="selected_category_id" value="">

                                    <div class="mb-3">
                                        <label for="book-title" class="form-label">Book Title</label>
                                        <input type="text" class="form-control" id="book-title" name="book_title"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="publisher" class="form-label">Publisher</label>
                                        <input type="text" class="form-control" id="publisher" name="publisher"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="publication-year" class="form-label">Publication Year</label>
                                        <input type="text" class="form-control" id="publication-year"
                                            name="publication_year" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Shelf Location</label>
                                        <input type="text" class="form-control" id="location" name="location" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="copies" class="form-label">Number of Copies</label>
                                        <input type="number" class="form-control" id="copies" name="copies" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount" step="0.01"
                                            required>
                                        <div class="form-text">Enter the price of the book.</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Book</button>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Add Book Modal -->
                </div>
            </div>
        </form>

        <?php
        require('backend/dbcon.php'); // Ensure this file contains your database connection logic

        // Query to fetch all categories
        $categoryQuery = "SELECT category_id, category_name FROM book_categories";
        $categoryResult = $con->query($categoryQuery);

        // Check for errors
        if ($categoryResult === false) {
            die("Error fetching categories: " . $con->error);
        }
        ?>

        <!-- Update Book Modal -->
        <form id="updateBookForm">
            <div class="modal fade" id="updatebook" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Book</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="book-id" name="id">
                            <div class="mb-3">
                                <label for="update-category" class="form-label">Category</label>
                                <select class="form-select" id="update-category" name="category_id" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <?php
                                    // Populate categories into the dropdown
                                    while ($categoryRow = $categoryResult->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($categoryRow['category_id']) . "'>" . htmlspecialchars($categoryRow['category_name']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="update-book-title" class="form-label">Book Title</label>
                                <input type="text" class="form-control" id="update-book-title" name="book_title"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="update-author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="update-author" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="update-isbn" name="isbn" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-publisher" class="form-label">Publisher</label>
                                <input type="text" class="form-control" id="update-publisher" name="publisher" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-publication-year" class="form-label">Publication Year</label>
                                <input type="text" class="form-control" id="update-publication-year"
                                    name="publication_year" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-location" class="form-label">Shelf Location</label>
                                <input type="text" class="form-control" id="update-location" name="location" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-copies" class="form-label">Number of Copies</label>
                                <input type="number" class="form-control" id="update-copies" name="copies" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="update-amount" name="amount" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Book</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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
    <script src="assets/sweet-alert/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            document.getElementById('category').addEventListener('change', function() {
                // Get the selected category id
                var selectedId = this.value;
                // Set the value of the hidden input field
                document.getElementById('selectedCategoryId').value = selectedId;
            });
            // Handle form submission
            $('#addBookForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                // Serialize form data
                var formData = $(this).serialize();

                // AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'backend/add-book.php', // PHP script for book insertion
                    data: formData,
                    success: function(response) {
                        if (response.includes('Book added successfully')) {
                            // Show success message with SweetAlert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Book added successfully',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                $('#addBookForm')[0].reset(); // Reset form
                                $('#addbook').modal('hide'); // Close modal
                                setTimeout(() => location
                                    .reload()); // Reload to reflect changes
                            });
                        } else {
                            // Show warning if there is an issue (e.g., duplicate ISBN)
                            Swal.fire({
                                title: 'Warning!',
                                text: response,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        // Handle error case
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Clear form when the modal is closed
            $('#addbook').on('hidden.bs.modal', function() {
                $('#addBookForm')[0].reset();
            });
        });
    </script>

    <script>
        // Listen for the modal close event
        const addBookModal = document.getElementById('addbook');
        const addBookForm = document.getElementById('addBookForm');

        addBookModal.addEventListener('hidden.bs.modal', function() {
            // Reset all form inputs when the modal is closed
            addBookForm.reset();
        });
    </script>

    <script>
        // Handle form submission with AJAX
        document.getElementById('updateBookForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            const formData = new FormData(this);

            fetch('backend/update-book.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success alert using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        // Show error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred.'
                    });
                });
        });

        // Populate the update modal with book data
        const updateBookModal = document.getElementById('updatebook');
        updateBookModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const bookId = button.getAttribute('data-id');
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.getAttribute('data-category-name'); // Get the category name

            // Get the row to extract other book details
            const row = button.closest('tr');

            // Set the values in the modal input fields
            document.getElementById('book-id').value = bookId;
            document.getElementById('update-category').value = categoryId; // Set the selected category ID
            document.getElementById('update-book-title').value = row.children[1]
                .textContent; // Assuming title is in the second cell
            document.getElementById('update-author').value = row.children[2].textContent;
            document.getElementById('update-isbn').value = row.children[3].textContent;
            document.getElementById('update-publisher').value = row.children[4].textContent;
            document.getElementById('update-publication-year').value = row.children[5].textContent;
            document.getElementById('update-location').value = row.children[6].textContent;
            document.getElementById('update-copies').value = row.children[7].textContent;
            document.getElementById('update-amount').value = row.children[8].textContent.replace(/₱|,/g, '')
                .trim(); // Amount (remove currency symbol and format)
        });
    </script>

    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const bookId = this.getAttribute('data-id');

                // SweetAlert confirmation before deleting
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX request to delete the book
                        fetch('backend/delete-book.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: bookId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        location
                                            .reload(); // Reload the page to reflect changes
                                    });
                                } else {
                                    Swal.fire('Error!', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Failed to delete the book.', 'error');
                            });
                    }
                });
            });
        });
    </script>

</body>

</html>