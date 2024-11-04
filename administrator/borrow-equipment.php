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
              <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#borrowModal">
                <i class="bx bx-plus me-1"></i>Borrow
              </button>
            </div>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Grade Level</th>
                  <th>Equipment</th> <!-- Adjusted to match your context -->
                  <th>Quantity</th>
                  <th>Status</th>
                  <th>Borrowed at</th> <!-- New column for created_at -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // SQL query to join tables and fetch the necessary fields
                $sql = "SELECT e.equipment_id, 
           CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name, 
           s.gradelevel,
           e.equipment AS equipment_name, 
           bb.status, 
           bb.quantity,
           bb.created_at
        FROM book_borrow bb
        JOIN student s ON bb.student_id = s.student_id
        JOIN equipment_borrow e ON e.equipment_id = e.equipment_id  
        WHERE bb.quantity > 0 
        ORDER BY e.equipment_id DESC"; // Exclude entries with quantity 0


                $result = $con->query($sql);

                // Check if the query was successful
                if ($result === false) {
                  // Display the SQL error
                  echo "<tr><td colspan='7'>Error: " . $con->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                  // Fetch and display records
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gradelevel']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['equipment_name']) . "</td>"; // Display the equipment name
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";

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
                    echo "<button type='button' class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#returnModal' data-id='" . $row['equipment_id'] . "'>Return</button> "; // For removing equipment
                    echo "<button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . $row['equipment_id'] . "'>Edit</button> ";
                    echo "</td>";
                    echo "</tr>";
                  }
                } else {
                  // No records found
                  echo "<tr><td colspan='7'>No records found.</td></tr>";
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
                  <img id="profilePicture" src="" alt="Profile Picture" class="img-fluid rounded-circle" style="display:none; height: 160px; width: 160px;">
                  <input type="hidden" class="form-control" id="profilePictureInput" name="profile_picture">
                </div>
              </div>
              <input type="text" id="hiddenRfidInput" style="display: none;" />
              <div class="row">
                <div class="col-6">
                  <div class="mb-3">
                    <label for="studentName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="studentName" name="student_name" required readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="gradeLevel" class="form-label">Grade Level</label>
                    <input type="text" class="form-control" id="gradeLevel" name="grade_level" required readonly>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">
                  <div class="mb-3">
                    <label for="BorrowEquipment" class="form-label">Equipment</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="BorrowEquipment" name="equipment">
                      <button type="button" class="btn btn-outline-secondary" id="addEquipmentButton">Add</button>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <ul id="equipmentList" class="list-group mt-4"></ul> <!-- List to display added equipment -->
                  </div>
                </div>
                <input type="text" class="form-control" id="studentId" name="student_id" required readonly>
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

    <!-- Return Book Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="returnModalLabel">Return Book</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="returnForm">
              <input type="hidden" id="equipment_id" name="equipment_id" value="">
              <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="returned">Returned</option>
                  <option value="damaged">Damaged</option>
                  <option value="lost">Lost</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submitReturn">Submit Return</button>
          </div>
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
                  document.getElementById('profilePictureInput').value = student.profile_picture;
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
      // Handle the click event for the Add button
      $('#addEquipmentButton').on('click', function() {
        var equipment = $('#BorrowEquipment').val(); // Get the value from the input
        if (equipment) {
          // Create a new list item
          $('#equipmentList').append('<li class="list-group-item d-flex justify-content-between align-items-center p-1 mt-2">' +
            equipment +
            '<button type="button" class="btn btn-danger btn-sm ms-2 remove-equipment">Remove</button>' +
            '</li>'
          );
          $('#BorrowEquipment').val(''); // Clear the input field
        } else {
          alert('Please enter equipment name.'); // Alert if input is empty
        }
      });

      // Handle the click event for removing an equipment item
      $('#equipmentList').on('click', '.remove-equipment', function() {
        $(this).closest('li').remove(); // Remove the corresponding list item
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      // Handle the "Add" button click to add equipment to the list
      $('#addEquipmentButton').on('click', function() {
        var equipment = $('#BorrowEquipment').val().trim();
        if (equipment) {
          // Append the equipment name along with the "Remove" button
          $('#equipmentList').append('<li class="list-group-item position-relative">' + equipment +
            '<button class="btn btn-danger btn-sm position-absolute end-0 remove-btn">Remove</button></li>');
          $('#BorrowEquipment').val(''); // Clear the input field
        }
      });

      // Handle the remove button click to remove the item
      $('#equipmentList').on('click', '.remove-btn', function() {
        $(this).closest('li').remove(); // Remove the list item
      });

      // Clear all inputs and equipment list when modal is closed
      $('#borrowModal').on('hidden.bs.modal', function() {
        $('#addBookBorrowForm')[0].reset(); // Reset the form inputs
        $('#equipmentList').empty(); // Clear the equipment list
      });

      // Handle the form submission
      $('#addBookBorrowForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = $(this).serializeArray();
        var equipmentList = [];

        // Collect the equipment from the list
        $('#equipmentList li').each(function() {
          equipmentList.push($(this).contents().get(0).nodeValue.trim()); // Get the text from the list item
        });

        // Add the equipment list to the form data
        formData.push({
          name: 'equipment_list',
          value: JSON.stringify(equipmentList)
        });

        // Assuming you have a single student ID selected from a dropdown or similar input
        var student_id = $('#student_id').val(); // Get the selected student ID

        // Add the student ID to the form data
        formData.push({
          name: 'student_id',
          value: student_id
        });

        // AJAX call to insert the data
        $.ajax({
          type: 'POST',
          url: 'backend/insert-equipment-borrow.php', // Path to your PHP script
          data: formData,
          success: function(response) {
            console.log("AJAX response:", response); // Log the response for debugging
            var result = JSON.parse(response); // Parse the JSON response
            if (result.success) {
              Swal.fire("Success!", result.message, "success").then(() => {
                location.reload();
                $('#borrowModal').modal('hide'); // Close the modal
              });
            } else {
              Swal.fire("Error!", result.message, "error");
            }
          },
          error: function(xhr) {
            console.error("AJAX error occurred:", xhr.responseText); // Log any error response
            Swal.fire("Error!", 'Error borrowing equipment. Please try again.', "error"); // Display error alert
          }
        });
      });
    });
  </script>


  <script>
    $(document).ready(function() {
      // When the Return button is clicked
      $('button[data-bs-target="#returnModal"]').on('click', function() {
        var equipmentId = $(this).data('id'); // Get equipment_id from data attribute
        $('#equipment_id').val(equipmentId); // Set hidden input field
        $('#equipment_id_display').val(equipmentId); // Display equipment_id in the modal
      });
    });

    $(document).ready(function() {
      // Handle the submission of the return form
      $('#submitReturn').on('click', function() {
        // Serialize the form data
        var formData = $('#returnForm').serialize();

        // Send the AJAX request
        $.ajax({
          type: 'POST',
          url: 'backend/returned-equipment.php', // Update with your PHP script that handles the insertion
          data: formData,
          success: function(response) {
            // Parse JSON response if your PHP script returns JSON
            var result = JSON.parse(response);
            if (result.success) {
              // Show success message with SweetAlert
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Return submitted successfully!',
                confirmButtonText: 'OK'
              }).then(() => {
                // Close the modal
                $('#returnModal').modal('hide');
                // Optionally, refresh or update your table here
              });
            } else {
              // Show error message with SweetAlert
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: result.message || 'An error occurred. Please try again.',
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

</body>

</html>