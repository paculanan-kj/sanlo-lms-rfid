<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session
$encoded_user_id = base64_encode($user_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>St. Lorenzo School of Polomolok - Equipment Borrowing</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/logo/ndk-logo.png" rel="icon">
    <link href="assets/logo/ndk-logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

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
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/css/equipment.css" rel="stylesheet">

    <link href="assets/css/book.css" rel="stylesheet">

</head>

<body>

    <?php
    include('inc/navbar.php'); // Include the navigation bar
    ?>

    <main class="container main">
        <!-- Student Authentication Section -->
        <div class="row mb-4" id="authSection">
            <div class="feature-card">
                <h3 class="card-title">Scan Your RFID</h3>
                <div class="scanner-icon-container">
                    <div class="scanner-rings"></div>
                    <i class="bi bi-upc-scan scan-icon" id="studentScanTrigger"></i>
                </div>
                <input type="text" id="studentRfidInput" autofocus autocomplete="off">

                <p class="text-center mt-4"></p>
                <div class="scan-feedback text-center mt-3">
                    <p id="studentScanStatus">Ready to scan student RFID</p>
                </div>
            </div>
        </div>

        <!-- This is hidden by default and shown after RFID scan -->
        <div id="authenticatedContent" style="display: none;">
            <div class="mb-3">
                <button class="btn btn-outline-primary" id="backToAuth">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
            <div class="row">
                <!-- Student Information Section -->
                <div class="col-lg-4">
                    <div class="info-card mb-4">
                        <h4>Student Information</h4>
                        <div class="student-info">
                            <p><strong>Full Name:</strong> <span id="studentName"></span></p>
                            <p style="display: none"><strong>ID Number:</strong> <span id="studentId"></span></p>
                            <p><strong>Grade Level:</strong> <span id="studentYear"></span></p>
                            <p><strong>Strand:</strong> <span id="studentCourse"></span></p>
                        </div>
                    </div>

                    <!-- Borrowed Equipment List -->
                    <div class="item-list">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Borrowed Equipment</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Equipment</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="borrowedEquipmentList">
                                    <!-- To be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Equipment Borrowing Form Section -->
                <div class="col-lg-8">
                    <div class="info-card mb-4">
                        <h4>Borrow Equipment</h4>
                        <form id="borrowEquipmentForm">
                            <div class="mb-3">
                                <label for="equipmentName" class="form-label">Equipment Name</label>
                                <input type="text" class="form-control" id="equipmentName" name="equipmentName" placeholder="e.g., Laptop - HP ProBook" required>
                            </div>
                            <button type="submit" class="btn btn-success">Borrow Equipment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

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
            $('#studentRfidInput').on('keypress', function(e) {
                if (e.which === 13) {
                    let rfid = $(this).val().trim();
                    if (rfid === "") return;

                    $.ajax({
                        url: "backend/verify_student.php",
                        type: "POST",
                        data: {
                            rfid: rfid
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'RFID Scan Successful!',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                });

                                $('#authSection').hide();
                                $('#authenticatedContent').show();

                                // Set student info
                                $('#studentName').text(`${response.data.firstname} ${response.data.lastname}`);
                                $('#studentId').text(response.data.student_id);
                                $('#studentCourse').text(response.data.strand);
                                $('#studentYear').text(`Grade ${response.data.gradelevel}`);

                                $('#studentRfidInput').val('');

                                // ✅ Call function to load borrowed books after student info is set
                                loadBorrowedBooks();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error'
                                });
                                $('#studentRfidInput').val('').focus();
                                $('#studentScanStatus').text('Scan failed. Try again.');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("AJAX Error:", textStatus, errorThrown);
                            Swal.fire({
                                title: 'Server Error',
                                text: 'Could not connect to the server.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            $('#backToAuth').on('click', function() {
                $('#authenticatedContent').hide();
                $('#authSection').show();
                $('#studentRfidInput').val('').focus();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#borrowEquipmentForm').on('submit', function(e) {
                e.preventDefault();

                const equipment = $('#equipmentName').val().trim();
                const studentId = $('#studentId').text().trim(); // from scanned RFID

                if (!equipment || !studentId) {
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please scan RFID and input equipment.',
                        icon: 'warning'
                    });
                    return;
                }

                $.ajax({
                    url: 'backend/borrow_equipment.php',
                    type: 'POST',
                    data: {
                        equipment: equipment,
                        student_id: studentId
                    },
                    dataType: 'json', // 👈 tell jQuery to treat response as JSON
                    success: function(res) {
                        console.log("Parsed response:", res); // ✅ already parsed

                        if (res.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Equipment borrowed successfully.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $('#equipmentName').val('');
                            if (typeof loadBorrowedEquipment === "function") {
                                loadBorrowedEquipment();
                            }
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: res.message || 'Something went wrong.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        Swal.fire({
                            title: 'Server Error',
                            text: 'Could not process the request.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            loadBorrowedEquipment(); // 🔄 Load on initial page load

            $('#borrowEquipmentForm').on('submit', function(e) {
                // Your submit logic already includes another loadBorrowedEquipment() call
            });
        });

        // Function to load borrowed equipment
        function loadBorrowedEquipment() {
            $.ajax({
                url: 'backend/get_borrowed_equipment.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let rows = '';
                    if (data.length === 0) {
                        rows = '<tr><td colspan="3" class="text-center">No records found.</td></tr>';
                    } else {
                        data.forEach(item => {
                            rows += `
                        <tr data-equipment-id="${item.equipment_id}">
                            <td>${item.equipment}</td>
                            <td>${new Date(item.created_at).toLocaleString()}</td>
                            <td><button class="btn btn-warning btn-sm return-button">Return</button></td>
                        </tr>`;
                        });
                    }
                    $('#borrowedEquipmentList').html(rows);
                },
                error: function(err) {
                    console.error('Error loading borrowed equipment:', err);
                    Swal.fire({
                        title: 'Error',
                        text: 'Could not load borrowed equipment data.',
                        icon: 'error'
                    });
                }
            });
        }

        // Handle return equipment click
        $(document).on('click', '.return-button', function() {
            const row = $(this).closest('tr');
            const equipmentId = row.data('equipment-id'); // Get equipment_id from data attribute

            if (!equipmentId) {
                Swal.fire({
                    title: 'Error',
                    text: 'Could not find equipment ID.',
                    icon: 'error'
                });
                return;
            }

            // Confirm the return action
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to return this equipment?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'No, keep it'
            }).then(result => {
                if (result.isConfirmed) {
                    // Make an AJAX request to return the equipment
                    $.ajax({
                        url: 'backend/return_equipment.php',
                        type: 'POST',
                        data: {
                            equipment_id: equipmentId
                        },
                        success: function(response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Equipment returned successfully.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    loadBorrowedEquipment(); // Refresh the borrowed equipment list
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: res.message || 'Something went wrong.',
                                        icon: 'error'
                                    });
                                }
                            } catch (err) {
                                console.error('Error parsing response:', err);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Invalid server response.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Server error:', error);
                            Swal.fire({
                                title: 'Server Error',
                                text: 'Could not process the request.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>


</body>

</html>