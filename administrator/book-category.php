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
            <h1>Category</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Books</li>
                    <li class="breadcrumb-item active">Manage Category</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Category</h5>
                                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#addCategoryModal">
                                    <i class="bx bx-plus"></i> Add Category
                                </button>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>

                                        <th style="width:80%">Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      include 'backend/dbcon.php'; // Include your DB connection script

                                      // Query to fetch categories from the database
                                      $query = "SELECT category_id, category_name, created_at FROM book_categories ORDER BY created_at DESC";
                                      $result = $con->query($query);

                                      if ($result->num_rows > 0) {
                                          while ($row = $result->fetch_assoc()) {
                                              echo "<tr>";
                                              echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                              echo "<td>
                                                      <button class='btn btn-primary btn-sm edit-btn' 
                                                              data-id='" . $row['category_id'] . "' 
                                                              data-name='" . htmlspecialchars($row['category_name']) . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='#editCategoryModal'>Edit
                                                      </button>
                                                      <button class='btn btn-danger btn-sm delete-btn' 
                                                        data-id='" . $row['category_id'] . "' 
                                                        data-name='" . htmlspecialchars($row['category_name']) . "'>
                                                          Delete
                                                      </button>
                                                    </td>";


                                              echo "</tr>";
                                          }
                                      } else {
                                          echo "<tr><td colspan='2'>No categories found</td></tr>";
                                      }

                                      $con->close();
                                      ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--Add Category Modal-->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addCategoryForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="category_name"
                                    placeholder="Enter category name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-content-centered">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editCategoryForm" method="post">
                        <!-- Added the form here -->
                        <div class="modal-body">
                            <input type="hidden" id="editCategoryId" name="category_id">
                            <div class="mb-3">
                                <label for="editCategoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="editCategoryName" name="category_name"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <!-- Changed to type="submit" -->
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
        $('#addCategoryForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var categoryName = $('#categoryName').val();

            $.ajax({
                url: 'backend/add-category.php',
                type: 'POST',
                data: {
                    category_name: categoryName
                },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Category Added',
                            text: 'The category has been added successfully!'
                        }).then(() => {
                            // Close modal and refresh page or table
                            $('#addCategoryModal').modal('hide');
                            location
                                .reload(); // Optionally refresh to show updated data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while adding the category.'
                    });
                }
            });
        });
    });
    </script>

    <script>
    // Populate the edit modal with category data
    const editCategoryModal = document.getElementById('editCategoryModal');
    editCategoryModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const categoryId = button.getAttribute('data-id'); // Extract info from data-* attributes
        const categoryName = button.getAttribute('data-name'); // Extract name from data-* attributes

        // Update the modal's content.
        document.getElementById('editCategoryId').value = categoryId;
        document.getElementById('editCategoryName').value = categoryName;
    });

    // Handle form submission with AJAX
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        const formData = new FormData(this);

        fetch('backend/edit-category.php', {
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
    </script>

    <script>
    // Handle delete category
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const button = event.target; // Get the clicked button
            const categoryId = button.getAttribute('data-id'); // Get the category ID
            const categoryName = button.getAttribute('data-name'); // Get the category name

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete the category: ${categoryName}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX call to delete the category
                    fetch('backend/delete-category.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                category_id: categoryId
                            }) // Send category ID as JSON
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload(); // Reload the page to reflect changes
                                });
                            } else {
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
                }
            });
        }
    });
    </script>

</body>

</html>