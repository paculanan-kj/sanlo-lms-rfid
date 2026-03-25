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
            <h1>Students</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">All Students</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title">Students</h5>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadCSV">
                                        <i class="bx bx-upload me-1"></i> Upload CSV File
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudent">
                                        <i class="bx bx-plus me-1"></i> New Student
                                    </button>
                                </div>
                            </div>

                            <?php
                            include 'backend/dbcon.php';

                            // Fetch all students including their profile picture
                            $query = "SELECT student_id, firstname, middlename, lastname, rfid, picture
                                FROM student 
                                ORDER BY lastname, firstname";
                            $result = $con->query($query);
                            ?>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>RFID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $fullName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);

                                            // Profile picture handling
                                            $defaultPhoto = 'assets/logo/ndk-logo.png'; // default profile picture
                                            $photoPath = !empty($row['picture']) && file_exists('../students/profile/' . $row['picture'])
                                                ? '../students/profile/' . $row['picture']
                                                : $defaultPhoto;

                                            echo '<tr>';
                                            echo '<td>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <img src="' . $photoPath . '" alt="Profile Picture" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                                                        <span>' . htmlspecialchars($fullName) . '</span>
                                                    </div>
                                                </td>';
                                            echo '<td>' . htmlspecialchars($row['rfid']) . '</td>';
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
                                        echo '<tr><td colspan="3" class="text-center">No students found.</td></tr>';
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


        <?php
        include('backend/dbcon.php');

        // Fetch the first available user ID (or from session)
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'No User Found';
        ?>

        <!-- Upload CSV Modal -->
        <div class="modal fade" id="uploadCSV" tabindex="-1" aria-labelledby="uploadCSVLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadCSVLabel">Upload CSV File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden User ID -->
                        <input type="hidden" class="form-control" value="<?= $user_id ?>" readonly>

                        <!-- File Upload Field -->
                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control" id="csvFile" accept=".csv" required>
                        </div>

                        <!-- Download Format Button -->
                        <div class="mb-3 text-center">
                            <a href="backend/download_format.php" class="btn btn-info btn-sm">
                                <i class="bx bx-download me-1"></i> Download CSV Format
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="uploadCsvBtn">Upload</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Student Modal -->
        <form id="addStudentForm" enctype="multipart/form-data" method="POST">
            <div class="modal fade" id="addStudent" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Hidden User ID -->
                            <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">

                            <!-- RFID Tag -->
                            <div class="row mb-3">
                                <div class="col-8 mb-1">
                                    <label for="rfid" class="form-label">RFID Tag</label>
                                    <input type="text" class="form-control" id="rfid" name="rfid" placeholder="Scan RFID ..." required>
                                </div>
                            </div>

                            <!-- Name Fields -->
                            <div class="row mb-3">
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

                            <!-- Upload Picture -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="formFile" class="form-label">Upload Picture</label>
                                    <input class="form-control" type="file" id="formFile" name="picture" accept="image/*" required>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
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
                            <!-- Hidden Student ID -->
                            <input type="hidden" id="edit_student_id" name="student_id">

                            <!-- RFID Field -->
                            <div class="mb-3">
                                <label for="edit_rfid" class="form-label">RFID Tag</label>
                                <input type="text" class="form-control" id="edit_rfid" name="rfid" required>
                            </div>

                            <!-- Name Fields -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="edit_firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_middlename" class="form-label">Middle Name (Optional)</label>
                                    <input type="text" class="form-control" id="edit_middlename" name="middlename">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
                                </div>
                            </div>

                            <!-- Optional Picture Upload -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="edit_formFile" class="form-label">Update Picture (Optional)</label>
                                    <input class="form-control" type="file" id="edit_formFile" name="picture" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
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
            // Open update modal and populate fields
            $('.edit-button').click(function() {
                var studentId = $(this).data('id');
                var firstname = $(this).data('firstname');
                var middlename = $(this).data('middlename');
                var lastname = $(this).data('lastname');
                var rfid = $(this).closest('tr').find('td:nth-child(1)').text().trim(); // RFID is in the first column
                var gradeLevel = $(this).closest('tr').find('td:nth-child(4)').text().trim();
                var strand = $(this).closest('tr').find('td:nth-child(5)').text().trim();
                var section = $(this).closest('tr').find('td:nth-child(6)').text().trim();

                // Populate the modal fields
                $('#edit_student_id').val(studentId);
                $('#edit_rfid').val(rfid);
                $('#edit_firstname').val(firstname);
                $('#edit_middlename').val(middlename);
                $('#edit_lastname').val(lastname);
                $('#edit_gradelevel').val(gradeLevel);
                $('#edit_strand').val(strand);
                $('#edit_section').val(section);

                // Show the modal
                $('#updateStudentModal').modal('show');
            });

            // Handle form submission with AJAX
            $('#updateStudentForm').submit(function(event) {
                event.preventDefault(); // Prevent traditional form submission

                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: 'backend/update-student.php', // Update this to your backend file
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response); // Debugging
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                location.reload(); // Reload page on success
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
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

    <script>
        document.getElementById('uploadCsvBtn').addEventListener('click', function() {
            let fileInput = document.getElementById('csvFile');
            let file = fileInput.files[0];

            if (!file) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No file selected',
                    text: 'Please select a CSV file to upload.',
                });
                return;
            }

            let formData = new FormData();
            formData.append("csvFile", file);
            formData.append("user_id", "<?= $user_id ?>");
            formData.append("active_sy_id", "<?= $active_sy_id ?>");

            fetch('backend/upload_csv.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Success' : 'Error',
                        text: data.message,
                    }).then(() => {
                        if (data.success) {
                            location.reload(); // Reload after success
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: 'An error occurred while uploading the file.',
                    });
                });
        });
    </script>


</body>

</html>