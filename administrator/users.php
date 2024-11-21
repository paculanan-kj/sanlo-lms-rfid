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
            <h1>Users</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">Manage Users</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Users</h5>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUser" style="margin-right: 12px;">
                                    <i class="bx bx-plus"></i> Add User
                                </button>
                            </div>
                            <?php
                            include 'backend/dbcon.php';

                            // Fetch user data, including profile picture
                            $sql = "SELECT user_id, firstname, middlename, lastname, username, email, profile_picture FROM user";
                            $result = $con->query($sql);
                            ?>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Profile Picture</th> <!-- Add column for profile picture -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            // Combine first name, middle name (if exists), and last name for full name
                                            $fullName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
                                            $profilePicture = htmlspecialchars($row['profile_picture'] ?? 'default.png'); // Use default if picture is not set
                                            $profilePicturePath = 'uploads/' . $profilePicture; // Set the correct path for the profile picture

                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($fullName) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                                            echo '<td><img src="' . $profilePicturePath . '" alt="Profile Picture" class="rounded-circle" style="width: 35px; height: 35px;"></td>'; // Display profile picture
                                            echo '<td>
                                                <button type="button" class="btn s btn-primary btn-sm update-user" 
                                                        data-id="' . $row['user_id'] . '" 
                                                        data-firstname="' . htmlspecialchars($row['firstname']) . '" 
                                                        data-middlename="' . htmlspecialchars($row['middlename']) . '" 
                                                        data-lastname="' . htmlspecialchars($row['lastname']) . '" 
                                                        data-username="' . htmlspecialchars($row['username']) . '" 
                                                        data-email="' . htmlspecialchars($row['email']) . '">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn s btn-danger btn-sm delete-user" 
                                                        data-id="' . $row['user_id'] . '">
                                                    Delete
                                                </button>
                                            </td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5">No users found.</td></tr>'; // Adjust column span
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

        <!-- Add Modal -->
        <form id="addUserForm" enctype="multipart/form-data">
            <div class="modal fade" id="addUser" tabindex="-1">
                <div class="modal-dialog modal-user">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-12 mb-1">
                                    <label for="rfid" class="form-label">RFID Tag</label>
                                    <input type="text" class="form-control" id="rfid" name="rfid" placeholder="Scan RFID tag" autofocus required>
                                </div>
                                <div class="col-12 mb-1">
                                    <label for="firstname" class="form-label">Firstname</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                                <div class="col-12 mb-1">
                                    <label for="middlename" class="form-label">Middlename</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename">
                                </div>
                                <div class="col-12 mb-1">
                                    <label for="lastname" class="form-label">Lastname</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                                <div class="col-12">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                                </div>
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


        <!-- Update Modal -->
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateUserForm">
                        <div class="modal-body">
                            <input type="hidden" id="userId" name="userId">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="updatefirstName" name="firstName" required>
                            </div>
                            <div class="mb-3">
                                <label for="middleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="updatemiddleName" name="middleName">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="updatelastName" name="lastName" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="updateusername" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="updateemail" name="email" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
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
            $('#addUser').on('shown.bs.modal', function() {
                $('#rfid').focus(); // Set focus on the RFID input
            });

            $('#addUserForm').submit(function(event) {
                event.preventDefault(); // Prevent traditional form submission

                var formData = new FormData(this); // Use FormData to include the file

                $.ajax({
                    type: 'POST',
                    url: 'backend/add-user.php', // Your PHP handler
                    data: formData,
                    processData: false, // Important! Prevent jQuery from processing the data
                    contentType: false, // Important! Prevent jQuery from setting contentType
                    success: function(response) {
                        if (response.includes('User added successfully')) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'User added successfully',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                $('#addUserForm')[0].reset(); // Reset form
                                $('#addUser').modal('hide'); // Close modal
                                setTimeout(function() {
                                    location.reload(); // Refresh the page
                                });
                            });
                        } else {
                            Swal.fire({
                                title: 'Duplicate Found!',
                                text: response,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#addUserForm')[0].reset();
                                $('#addUser').modal('hide');
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#addUserForm')[0].reset();
                            $('#addUser').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        });
                    }
                });
            });

            $('#addUser').on('hidden.bs.modal', function() {
                $('#addUserForm')[0].reset();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Show modal and populate data
            $(document).on('click', '.update-user', function() {
                const userId = $(this).data('id');
                const firstName = $(this).data('firstname');
                const middleName = $(this).data('middlename');
                const lastName = $(this).data('lastname');
                const username = $(this).data('username');
                const email = $(this).data('email');

                // Update the input fields with the corresponding IDs
                $('#userId').val(userId);
                $('#updatefirstName').val(firstName);
                $('#updatemiddleName').val(middleName);
                $('#updatelastName').val(lastName);
                $('#updateusername').val(username);
                $('#updateemail').val(email);

                // Show the modal
                $('#updateModal').modal('show');
            });

            // Handle form submission
            $('#updateUserForm').on('submit', function(e) {
                e.preventDefault();

                // Get the updated input values
                const userId = $('#userId').val();
                const firstName = $('#updatefirstName').val();
                const middleName = $('#updatemiddleName').val();
                const lastName = $('#updatelastName').val();
                const username = $('#updateusername').val();
                const email = $('#updateemail').val();

                // Prepare data to send
                const formData = {
                    userId: userId,
                    firstName: firstName,
                    middleName: middleName,
                    lastName: lastName,
                    username: username,
                    email: email
                };

                $.ajax({
                    type: 'POST',
                    url: 'backend/update-user.php', // Update with your update script URL
                    data: formData,
                    success: function(response) {
                        const result = JSON.parse(response);

                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'User information has been updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload(); // Refresh the page to see updated data
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: result.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again later.',
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Delete user
            $(document).on('click', '.delete-user', function() {
                const userId = $(this).data('id');

                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This user will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the delete action
                        $.ajax({
                            type: 'POST',
                            url: 'backend/delete-user.php', // Update with your delete script URL
                            data: {
                                userId: userId
                            },
                            success: function(response) {
                                const result = JSON.parse(response);

                                if (result.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'User has been deleted successfully.',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload(); // Refresh the page to see updated data
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: result.message,
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong. Please try again later.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>


</body>

</html>