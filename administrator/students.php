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
            <h1>Students</h1>
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
                                <h5 class="card-title">Students</h5>
                                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addStudent">
                                    <i class="bx bx-plus me-1"></i> New Student
                                </button>
                            </div>
                            <?php
                            include 'backend/dbcon.php';
                            // Fetch user data
                            $sql = "SELECT user_id, firstname, middlename, lastname, username, email FROM user";
                            $result = $con->query($sql);
                            ?>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Address</th>
                                        <th>RFID</th> <!-- New column for RFID -->
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'backend/dbcon.php';

                                    // Check if there's an active school year
                                    $activeSYQuery = "SELECT sy_id FROM school_year WHERE status = 'active' LIMIT 1";
                                    $activeSYResult = $con->query($activeSYQuery);

                                    if ($activeSYResult->num_rows > 0) {
                                        // If there's an active school year, fetch students
                                        $query = "SELECT student_id, firstname, middlename, lastname, gradelevel, 
                         address, picture, rfid 
                  FROM student";

                                        $result = $con->query($query); // Execute the query

                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                // Combine first name, middle name (if exists), and last name for full name
                                                $fullName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
                                                // Construct the image path
                                                $picturePath = 'uploads/' . $row['picture']; // Adjust this path based on your folder structure

                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($fullName) . '</td>'; // Output full name safely
                                                echo '<td>' . htmlspecialchars($row['gradelevel']) . '</td>'; // Output grade level safely
                                                echo '<td>' . htmlspecialchars($row['address']) . '</td>'; // Output address safely
                                                echo '<td>' . htmlspecialchars($row['rfid']) . '</td>'; // Output RFID safely
                                                echo '<td>
                                                        <img src="' . htmlspecialchars($picturePath) . '" alt="Student Picture" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                                    </td>';
                                                echo '<td>
                                                        <button type="button" class="btn btn-primary btn-sm edit-button" 
                                                                data-id="' . $row['student_id'] . '" 
                                                                data-firstname="' . htmlspecialchars($row['firstname']) . '" 
                                                                data-middlename="' . htmlspecialchars($row['middlename']) . '" 
                                                                data-lastname="' . htmlspecialchars($row['lastname']) . '">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm delete-button" 
                                                                data-id="' . $row['student_id'] . '">
                                                            Delete
                                                        </button>
                                                    </td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            // If no students are found
                                            echo '<tr><td colspan="6" class="text-center">No students found</td></tr>';
                                        }
                                    } else {
                                        // If no active school year is found
                                        echo '<tr><td colspan="6" class="text-center text-danger"><strong>No active school year.</strong></td></tr>';
                                    }

                                    $con->close(); // Close the database connection
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Fetch Active School Year -->
        <?php
        include 'backend/dbcon.php'; // Adjust your database connection file
        $query = "SELECT sy_id, school_year FROM school_year WHERE status = 'active' LIMIT 1";
        $result = mysqli_query($con, $query);
        $activeSY = mysqli_fetch_assoc($result);
        ?>

        <!-- Add Modal -->
        <form id="addStudentForm" enctype="multipart/form-data" method="POST">
            <div class="modal fade" id="addStudent" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />

                            <!-- Display Active School Year -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <input type="hidden" id="sy_id" name="sy_id" value="<?php echo $activeSY['sy_id']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-8 mb-1">
                                    <label for="rfid" class="form-label">RFID Tag</label>
                                    <input type="text" class="form-control" id="rfid" name="rfid" placeholder="Scan RFID ..." required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 mb-1">
                                    <label for="firstname" class="form-label">Firstname</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                                <div class="col-4 mb-1">
                                    <label for="middlename" class="form-label">Middlename</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename">
                                </div>
                                <div class="col-4 mb-1">
                                    <label for="lastname" class="form-label">Lastname</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="gradelevel" class="form-label">Grade Level</label>
                                    <input type="text" class="form-control" id="gradelevel" name="gradelevel" required>
                                </div>
                                <div class="col-4">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="formFile" class="form-label">Upload Picture</label>
                                    <input class="form-control" type="file" id="formFile" name="picture" accept="image/*" required>
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
        <form id="updateStudentForm" enctype="multipart/form-data" method="POST">
            <div class="modal fade" id="updateStudentModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_student_id" name="student_id">
                            <div class="row mb-3">
                                <div class="col-12 mb-1">
                                    <label for="edit_rfid" class="form-label">RFID Tag</label>
                                    <input type="text" class="form-control" id="edit_rfid" name="rfid" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 mb-1">
                                    <label for="edit_firstname" class="form-label">Firstname</label>
                                    <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
                                </div>
                                <div class="col-4 mb-1">
                                    <label for="edit_middlename" class="form-label">Middlename</label>
                                    <input type="text" class="form-control" id="edit_middlename" name="middlename">
                                </div>
                                <div class="col-4 mb-1">
                                    <label for="edit_lastname" class="form-label">Lastname</label>
                                    <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 mb-1">
                                    <label for="edit_gradelevel" class="form-label">Grade Level</label>
                                    <input type="text" class="form-control" id="edit_gradelevel" name="gradelevel" required>
                                </div>
                                <div class="col-4 mb-1">
                                    <label for="edit_address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="edit_address" name="address" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="edit_picture" class="form-label">Update Picture</label>
                                    <input class="form-control" type="file" id="edit_picture" name="picture" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
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
            $('#addStudentForm').submit(function(event) {
                event.preventDefault(); // Prevent form from submitting the traditional way

                var formData = new FormData(this); // FormData object for file upload

                $.ajax({
                    type: 'POST',
                    url: 'backend/add-student.php',
                    data: formData,
                    contentType: false,
                    processData: false,
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
                                    location.reload(); // Refresh the page
                                }
                            });
                            $('#addStudentForm')[0].reset(); // Reset form
                            $('#addStudent').modal('hide'); // Close modal
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
                            text: 'An error occurred while adding the student.',
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
            // Open the update modal and populate fields when Edit is clicked
            $('.edit-button').click(function() {
                var studentId = $(this).data('id'); // Get the student ID from the button's data attribute
                var firstname = $(this).data('firstname'); // Get first name from button's data attribute
                var middlename = $(this).data('middlename'); // Get middle name from button's data attribute
                var lastname = $(this).data('lastname'); // Get last name from button's data attribute
                var gradeLevel = $(this).closest('tr').find('td:nth-child(2)').text(); // Get the grade level
                var address = $(this).closest('tr').find('td:nth-child(3)').text(); // Get the address
                var rfid = $(this).closest('tr').find('td:nth-child(4)').text(); // Updated index for RFID (4th column)

                // Populate the modal fields
                $('#edit_student_id').val(studentId);
                $('#edit_firstname').val(firstname); // First name
                $('#edit_middlename').val(middlename); // Middle name
                $('#edit_lastname').val(lastname); // Last name
                $('#edit_gradelevel').val(gradeLevel);
                $('#edit_address').val(address);
                $('#edit_rfid').val(rfid); // Populate RFID field

                // Show the modal
                $('#updateStudentModal').modal('show');
            });

            // Handle form submission with AJAX
            $('#updateStudentForm').submit(function(event) {
                event.preventDefault(); // Prevent traditional form submission

                var formData = new FormData(this); // Gather form data

                $.ajax({
                    type: 'POST',
                    url: 'backend/update-student.php', // PHP handler for updating student
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response); // Debugging
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                location.reload(); // Reload the page on success
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Log any error response
                        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                    }
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-id');
                    console.log("Delete button clicked for student ID:", studentId); // Debugging

                    // SweetAlert2 confirmation
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Once deleted, you will not be able to recover this student record!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Make an AJAX request to delete the student
                            fetch('backend/delete-student.php?id=' + studentId)
                                .then(response => response.text())
                                .then(data => {
                                    console.log("Response from server:", data); // Debugging
                                    Swal.fire(
                                        'Deleted!',
                                        data,
                                        'success'
                                    );
                                    setTimeout(() => {
                                        location.reload(); // Reload the page after 2 seconds
                                    });
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire(
                                        'Error!',
                                        'There was an error deleting the record.',
                                        'error'
                                    );
                                });
                        } else {
                            Swal.fire('Your record is safe!');
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>