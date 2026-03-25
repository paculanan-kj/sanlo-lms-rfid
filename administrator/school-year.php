<?php
require_once 'auth.php';
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
            <h1>School Year</h1>
            <?php
            include('backend/dbcon.php');

            // Fetch the active school year
            $query = "SELECT school_year FROM school_year WHERE status = 'active' LIMIT 1";
            $result = $con->query($query);
            $active_school_year = ($result->num_rows > 0) ? $result->fetch_assoc()['school_year'] : 'No Active School Year';
            ?>

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">School Year: <?= htmlspecialchars($active_school_year); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">School Year</h5>
                                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addSchoolYear">
                                    <i class="bx bx-plus me-1"></i> Add School Year
                                </button>

                            </div>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 60%">School Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('backend/dbcon.php');

                                    $query = "SELECT * FROM school_year ORDER BY school_year DESC";
                                    $result = $con->query($query);

                                    while ($row = $result->fetch_assoc()) {
                                        $status = ($row['status'] == 'active') ?
                                            '<span class="badge bg-success">Active</span>' :
                                            '<span class="badge bg-danger">Inactive</span>';

                                        $buttonClass = ($row['status'] == 'active') ? 'btn-warning' : 'btn-success'; // Green for "Activate"
                                        $buttonText = ($row['status'] == 'active') ? 'Deactivate' : 'Activate';

                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($row['school_year']) . '</td>';
                                        echo '<td>' . $status . '</td>';
                                        echo '<td>
            <button class="btn ' . $buttonClass . ' btn-sm toggle-status" data-id="' . $row['sy_id'] . '" data-status="' . $row['status'] . '">
                ' . $buttonText . '
            </button>
            <button class="btn btn-danger btn-sm delete-year" data-id="' . $row['sy_id'] . '">Delete</button>
          </td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Add School Year Modal -->
        <form id="addSchoolYearForm" method="POST">
            <div class="modal fade" id="addSchoolYear" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add School Year</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="school_year" class="form-label">School Year</label>
                                <input type="text" class="form-control" id="school_year" name="school_year" placeholder="e.g. 2024-2025" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
            $('#addSchoolYearForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                var formData = $(this).serialize(); // Serialize form data (no files needed)

                $.ajax({
                    type: 'POST',
                    url: 'backend/add-school-year.php', // Backend PHP script
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(); // Refresh the page to update data
                                }
                            });
                            $('#addSchoolYearForm')[0].reset(); // Reset form
                            $('#addSchoolYear').modal('hide'); // Close modal
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while adding the school year.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Delete school year
            $('.delete-year').click(function() {
                var sy_id = $(this).data('id'); // Get school year ID

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "backend/delete-school-year.php", // Backend script
                            data: {
                                sy_id: sy_id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: response.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload(); // Refresh the table
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    title: "Error!",
                                    text: "An error occurred while deleting the school year.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.toggle-status').click(function() {
                var sy_id = $(this).data('id');
                var currentStatus = $(this).data('status');
                var newStatus = (currentStatus === 'active') ? 'inactive' : 'active';

                $.ajax({
                    type: "POST",
                    url: "backend/update-school-year-status.php",
                    data: {
                        sy_id: sy_id,
                        status: newStatus
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Updated!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                location.reload(); // Refresh to reflect changes
                            });
                        } else {
                            Swal.fire({
                                title: "Warning!",
                                text: response.message,
                                icon: "warning",
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while updating the status.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>