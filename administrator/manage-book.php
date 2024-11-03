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
      <h1>Books</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
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
                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addbook">
                  <i class="bx bx-plus"></i> Add Book
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
                    <th style="width: 20%">Title</th>
                    <th style="width: 15%">Author</th>
                    <th class="mi">ISBN</th>
                    <th>Location</th>
                    <th>Copies</th>
                    <th>Available</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  require('backend/dbcon.php'); // Ensure this file contains your database connection logic

                  // Query to fetch all books
                  $query = "SELECT * FROM book";
                  $result = $con->query($query);

                  // Check for errors
                  if ($result === false) {
                    die("Error fetching data: " . $con->error);
                  }

                  // Fetch and display each book
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['copies']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['copies']) . "</td>";
                    echo "<td>
              <a href='#' 
                class='btn btn-primary btn-sm btn-update' 
                data-id='" . $row['book_id'] . "' 
                data-bs-toggle='modal' 
                data-bs-target='#updatebook'>Edit</a>
              <a href='#' 
                class='btn btn-danger btn-sm btn-delete' 
                data-id='" . $row['book_id'] . "'>Delete</a>
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

    <!--add Modal-->
    <form id="addBookForm" method="POST">
      <div class="card">
        <div class="card-body">
          <div class="modal fade" id="addbook" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add New Book</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                  <div class="mb-3">
                    <label for="book-title" class="form-label">Book Title</label>
                    <input type="text" class="form-control" id="book-title" name="book_title" required>
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
                    <input type="text" class="form-control" id="publisher" name="publisher" required>
                  </div>
                  <div class="mb-3">
                    <label for="publication-year" class="form-label">Publication Year</label>
                    <input type="text" class="form-control" id="publication-year" name="publication_year" required>
                  </div>
                  <div class="mb-3">
                    <label for="location" class="form-label">Shelf Location</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                  </div>
                  <div class="mb-3">
                    <label for="copies" class="form-label">Number of Copies</label>
                    <input type="number" class="form-control" id="copies" name="copies" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add Book</button>
                </div>
              </div>
            </div>
          </div><!-- End Add Book Modal -->
        </div>
      </div>
    </form>

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
                <label for="book-title" class="form-label">Book Title</label>
                <input type="text" class="form-control" id="update-book-title" name="book_title" required>
              </div>
              <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="update-author" name="author" required>
              </div>
              <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="update-isbn" name="isbn" required>
              </div>
              <div class="mb-3">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" id="update-publisher" name="publisher" required>
              </div>
              <div class="mb-3">
                <label for="publication-year" class="form-label">Publication Year</label>
                <input type="text" class="form-control" id="update-publication-year" name="publication_year" required>
              </div>
              <div class="mb-3">
                <label for="location" class="form-label">Shelf Location</label>
                <input type="text" class="form-control" id="update-location" name="location" required>
              </div>
              <div class="mb-3">
                <label for="copies" class="form-label">Number of Copies</label>
                <input type="number" class="form-control" id="update-copies" name="copies" required>
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
                setTimeout(() => location.reload()); // Reload to reflect changes
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

  <!-- Script to clear the form when modal is closed -->
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
      const button = event.relatedTarget;
      const bookId = button.getAttribute('data-id');
      const row = button.closest('tr');

      document.getElementById('book-id').value = bookId;
      document.getElementById('update-book-title').value = row.children[0].textContent;
      document.getElementById('update-author').value = row.children[1].textContent;
      document.getElementById('update-isbn').value = row.children[2].textContent;
      document.getElementById('update-publisher').value = row.children[3].textContent;
      document.getElementById('update-publication-year').value = row.children[4].textContent;
      document.getElementById('update-location').value = row.children[5].textContent;
      document.getElementById('update-copies').value = row.children[6].textContent;
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
                    location.reload(); // Reload the page to reflect changes
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