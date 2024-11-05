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

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/sweet-alert/sweetalert2.min.css" rel="stylesheet">

</head>

<body>
    <?php
  include 'inc/navbar.php';
  include 'inc/sidebar.php';
  ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Manage Borrow</a></li>
                    <li class="breadcrumb-item active">Borrow Equipment</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Borrowed Equipment</h5>
                            <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                data-bs-target="#borrowModal">
                                <i class="bx bx-plus me-1"></i>Borrow
                            </button>
                        </div>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Student</th> <!-- Combined column for the profile picture and name -->
                                    <th>Grade Level</th>
                                    <th>Equipment</th>
                                    <th>Status</th>
                                    <th>Borrowed at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  // SQL query to join tables and fetch the necessary fields, excluding returned equipment
                                  $sql = "SELECT 
                                              CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name, 
                                              s.gradelevel,
                                              s.picture, -- Include the picture column
                                              be.equipment_id,
                                              be.equipment AS equipment_name, 
                                              be.status, 
                                              be.created_at
                                          FROM equipment_borrow be
                                          JOIN student s ON be.student_id = s.student_id
                                          WHERE be.status != 'returned' -- Exclude returned equipment
                                          ORDER BY be.created_at DESC"; // Order by created_at to show the most recent first

                                  $result = $con->query($sql);

                                  // Check if the query was successful
                                  if ($result === false) {
                                      // Display the SQL error
                                      echo "<tr><td colspan='6'>Error: " . $con->error . "</td></tr>";
                                  } elseif ($result->num_rows > 0) {
                                      // Fetch and display records
                                      while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                          
                                          // Display the profile picture and name together
                                          $picturePath = 'uploads/' . htmlspecialchars($row['picture']); // Adjust this path based on your folder structure
                                          echo "<td>";
                                          echo "<div class='d-flex align-items-center'>"; // Flexbox for alignment
                                          if (!empty($row['picture'])) {
                                              echo "<img src='" . $picturePath . "' alt='Profile Picture' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;'>";
                                          } else {
                                              echo "<img src='path/to/default/image.jpg' alt='Default Picture' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;'>";
                                          }
                                          echo "<span>" . htmlspecialchars($row['student_name']) . "</span>"; // Display the student name
                                          echo "</div>";
                                          echo "</td>";

                                          // Display the student's grade level
                                          echo "<td>" . htmlspecialchars($row['gradelevel']) . "</td>";
                                          echo "<td>" . htmlspecialchars($row['equipment_name']) . "</td>"; // Display the equipment name

                                          // Display the status with a badge
                                          if ($row['status'] === 'borrowed') {
                                              echo "<td><span class='badge bg-warning'>Borrowed</span></td>";
                                          } else {
                                              echo "<td><span class='badge bg-secondary'>" . htmlspecialchars($row['status']) . "</span></td>";
                                          }

                                          // Format the created_at date
                                          $createdAt = new DateTime($row['created_at']);
                                          echo "<td>" . $createdAt->format('F j, Y, g:i a') . "</td>"; // Format: January 1, 2024

                                          // Action buttons with modals
                                          echo "<td>";
                                          echo "<button type='button' class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#returnModal' data-id='" . $row['equipment_id'] . "'>Return</button> "; // For returning equipment
                                          echo "<button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . $row['equipment_id'] . "' data-name='" . htmlspecialchars($row['equipment_name']) . "'>Edit</button> ";
                                          echo "</td>";

                                        echo "</tr>";
                                      }
                                  } else {
                                      // No records found
                                      echo "<tr><td colspan='6'>No records found.</td></tr>";
                                  }

                                  // Close the database connection
                                  $con->close();
                                  ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </section>

        <!-- Add Equipment Borrow Modal -->
        <div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="addBookBorrowForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrowModalLabel">Borrow Equipment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Profile Picture Row -->
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <img id="profilePicture" src="" alt="Profile Picture"
                                        class="img-fluid rounded-circle"
                                        style="display:none; height: 160px; width: 160px;">
                                    <input type="hidden" class="form-control" id="profilePictureInput"
                                        name="profile_picture">
                                </div>
                            </div>
                            <input type="text" id="hiddenRfidInput" style="display: none;" />
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="studentName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="studentName" name="student_name"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="gradeLevel" class="form-label">Grade Level</label>
                                        <input type="text" class="form-control" id="gradeLevel" name="grade_level"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="BorrowEquipment" class="form-label">Equipment</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="BorrowEquipment"
                                                name="equipment">
                                            <button type="button" class="btn btn-outline-secondary"
                                                id="addEquipmentButton">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <ul id="equipmentList" class="list-group mt-4"></ul>
                                        <!-- List to display added equipment -->
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="studentId" name="student_id" required
                                    readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Equipment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="equipmentName" class="form-label">Equipment Name</label>
                                <input type="text" class="form-control" id="equipmentName" name="equipmentName"
                                    required>
                            </div>
                            <!-- Add any other fields you want to edit here -->
                            <input type="hidden" id="equipmentId" name="equipmentId">
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

    <!-- Template Main JS File -->
    <script src="assets/sweet-alert/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script>
    let rfidTimeout;
    let rfidData = "";

    // Prevent form submission
    document.getElementById('addBookBorrowForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
    });

    // Listen for keydown events to capture RFID input
    document.addEventListener('keydown', function(event) {
        const borrowModal = document.getElementById('borrowModal');

        // Ensure input only captures when the modal is open
        if (borrowModal.classList.contains('show')) {
            // If Enter key is pressed, process the RFID data
            if (event.key === 'Enter') {
                clearTimeout(rfidTimeout); // Clear any existing timeout

                const rfid = rfidData.trim();
                if (rfid) {
                    fetch('backend/fetch-student-borrow.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                rfid
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                const student = data.student;
                                document.getElementById('studentId').value = student.student_id;
                                document.getElementById('studentName').value = student.full_name;
                                document.getElementById('gradeLevel').value = student.grade_level;

                                // Set the image source and make it visible
                                document.getElementById('profilePicture').src = student.profile_picture;
                                document.getElementById('profilePicture').style.display = 'block';

                                // Store the image path in a hidden input
                                document.getElementById('profilePictureInput').value = student
                                    .profile_picture;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: data.message || 'Student not found.',
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error fetching student data:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while fetching the student data.',
                            });
                        });
                }

                rfidData = ""; // Clear rfidData for the next scan
            } else {
                // Accumulate characters in rfidData
                rfidData += event.key;

                // Clear any previous timeout to debounce input
                clearTimeout(rfidTimeout);

                // Set a new timeout to clear rfidData if no activity occurs
                rfidTimeout = setTimeout(() => {
                    rfidData = ""; // Clear the input if no Enter key is detected in 300 ms
                }, 300);
            }
        }
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal and form elements
        const borrowModal = document.getElementById('borrowModal');
        const profilePicture = document.getElementById('profilePicture');
        const profilePictureInput = document.getElementById('profilePictureInput');
        const studentName = document.getElementById('studentName');
        const gradeLevel = document.getElementById('gradeLevel');
        const borrowEquipment = document.getElementById('BorrowEquipment');
        const equipmentList = document.getElementById('equipmentList');
        const studentId = document.getElementById('studentId');

        // Listen for the modal close event
        borrowModal.addEventListener('hidden.bs.modal', function() {
            // Clear input values
            studentName.value = '';
            gradeLevel.value = '';
            borrowEquipment.value = '';
            studentId.value = '';
            equipmentList.innerHTML = ''; // Clear the list of added equipment

            // Hide the profile picture and reset its input
            profilePicture.style.display = 'none';
            profilePicture.src = '';
            profilePictureInput.value = '';
        });
    });
    </script>


    <script>
    // Automatically focus RFID input field when the modal is shown
    document.addEventListener('DOMContentLoaded', function() {
        const borrowModal = document.getElementById('borrowModal');
        const inputRfid = document.getElementById('inputRfid');

        borrowModal.addEventListener('shown.bs.modal', function() {
            inputRfid.focus();
        });
    });
    document.getElementById('inputRfid').addEventListener('input', function() {
        const profilePicture = document.getElementById('profilePicture');

        // Assuming the RFID scan populates other fields, we show the profile picture.
        if (this.value) {
            profilePicture.style.display = 'block'; // Show the profile picture
        } else {
            profilePicture.style.display = 'none'; // Hide if RFID input is cleared
        }
    });
    </script>

    <script>
    $(document).ready(function() {
        // Event handler for the "Add" button click
        $('#addEquipmentButton').on('click', function() {
            var equipment = $('#BorrowEquipment').val().trim();
            console.log("Equipment input value:", equipment); // Log the input value

            if (equipment) {
                // Add equipment to the list
                $('#equipmentList').append(
                    '<li class="list-group-item position-relative">' +
                    equipment +
                    '<button class="btn btn-danger btn-sm position-absolute end-0 remove-btn">Remove</button></li>'
                );
                $('#BorrowEquipment').val(''); // Clear the input field after adding
                console.log("Equipment added to the list"); // Log successful addition
            } else {
                console.log("No equipment entered"); // Log if input is empty
            }
        });

        // Event handler to remove the item when the "Remove" button is clicked
        $('#equipmentList').on('click', '.remove-btn', function() {
            $(this).closest('li').remove();
            console.log("Equipment removed from the list"); // Log item removal
        });
    });

    $('#addBookBorrowForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var formData = $(this).serializeArray();
        var equipmentList = [];

        // Collect equipment from the list
        $('#equipmentList li').each(function() {
            equipmentList.push($(this).contents().get(0).nodeValue.trim());
        });

        console.log("Equipment List before sending:", equipmentList);

        if (equipmentList.length === 0) {
            Swal.fire("Error!", "No equipment has been added to the list.", "error");
            return; // Stop if the equipment list is empty
        }

        // Add equipment list to form data
        formData.push({
            name: 'equipment_list',
            value: JSON.stringify(equipmentList)
        });

        // Verify if the student ID is being retrieved correctly
        var student_id = $('#studentId').val();
        console.log("Student ID before sending:", student_id);

        if (!student_id) {
            Swal.fire("Error!", "Invalid student ID.", "error");
            return; // Stop if student ID is missing
        }

        formData.push({
            name: 'student_id',
            value: student_id
        });

        // AJAX call to insert data
        $.ajax({
            type: 'POST',
            url: 'backend/insert-equipment-borrow.php', // Path to your PHP script
            data: formData,
            success: function(response) {
                console.log("AJAX response:", response); // Log response for debugging
                var result = JSON.parse(response);

                if (result.success) {
                    Swal.fire("Success!", result.message, "success").then(() => {
                        location.reload();
                        $('#borrowModal').modal('hide'); // Close modal
                    });
                } else {
                    Swal.fire("Error!", result.message, "error");
                }
            },
            error: function(xhr) {
                console.error("AJAX error occurred:", xhr.responseText); // Log error response
                Swal.fire("Error!", 'Error borrowing equipment. Please try again.', "error");
            }
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // When the Return button is clicked
        $('button[data-bs-target="#returnModal"]').on('click', function() {
            var equipmentId = $(this).data('id'); // Get equipment_id from data attribute

            // Call the return function directly
            $.ajax({
                type: 'POST',
                url: 'backend/returned-equipment.php', // Update with your PHP script that handles the insertion
                data: {
                    equipment_id: equipmentId,
                    status: 'returned'
                }, // Send equipment ID and status
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        // Show success message with SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Equipment returned successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Optionally refresh or update your table here
                            location
                                .reload(); // Reload the page to refresh the table
                        });
                    } else {
                        // Show error message with SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: result.message ||
                                'An error occurred. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred: ' + error,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>

    <script>
    // Add event listener for when the modal is about to be shown
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var equipmentName = button.getAttribute('data-name'); // Extract info from data-* attributes
        var equipmentId = button.getAttribute('data-id'); // Extract the equipment ID

        // Update the modal's content.
        var modalTitle = editModal.querySelector('.modal-title');
        var modalBodyInput = editModal.querySelector('#equipmentName');
        var equipmentIdInput = editModal.querySelector('#equipmentId');

        modalTitle.textContent = 'Edit Equipment: ' + equipmentName; // Change title based on equipment name
        modalBodyInput.value = equipmentName; // Set input value to current equipment name
        equipmentIdInput.value = equipmentId; // Set the hidden input with the equipment ID
    });

    // Add an event listener to handle the form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var equipmentId = document.getElementById('equipmentId').value;
        var equipmentName = document.getElementById('equipmentName').value;

        // AJAX request to update equipment
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend/update_equipment.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successfully updated
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Equipment updated successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Refresh page or update the table
                });
            } else {
                // Error occurred
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error updating equipment: ' + xhr.responseText,
                    confirmButtonText: 'OK'
                });
            }
        };

        // Send the request with the data
        xhr.send('equipment_id=' + encodeURIComponent(equipmentId) + '&equipment_name=' + encodeURIComponent(
            equipmentName));
    });
    </script>



</body>

</html>