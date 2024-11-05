<?php
session_start();

include 'backend/dbcon.php';
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

    <style>
    .suggestions-container {
        position: absolute;
        /* Ensure it's positioned correctly */
        background: white;
        /* Background color for better visibility */
        z-index: 1000;
        /* Ensure it appears above other elements */
        max-height: 200px;
        /* Optional: Set max height */
        overflow-y: auto;
        /* Optional: Scrollable suggestions */
    }

    .suggestion-item {
        padding: 10px;
        /* Add some padding */
        cursor: pointer;
        /* Change cursor to pointer */
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
        /* Highlight on hover */
    }
    </style>

</head>

<body>
    <?php
  include 'inc/navbar.php';
  include 'inc/sidebar.php';
  ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Purchased Books</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Purchased Books</li>
                    <li class="breadcrumb-item active">Purchase</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Purchase</h5>
                            <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                data-bs-target="#borrowModal">
                                <i class="bx bx-plus me-1"></i>Purchase
                            </button>
                        </div>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </section>

        <!-- Add Book Borrow Modal -->
        <div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="addBookBorrowForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrowModalLabel">Borrow Book</h5>
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
                                            required readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="gradeLevel" class="form-label">Grade Level</label>
                                        <input type="text" class="form-control" id="gradeLevel" name="grade_level"
                                            required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="BorrowBook" class="form-label">Book</label>
                                        <input type="text" class="form-control" id="BorrowBook" name="book" required>
                                        <div id="bookSuggestions" class="suggestions-container"
                                            style="border: 1px solid #ccc; display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="bookQuantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="bookQuantity" name="quantity"
                                            required>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="studentId" name="student_id" required
                                    readonly>
                                <input type="hidden" id="bookId" name="book_id">
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
                            <input type="hidden" id="book_borrow_id" name="book_borrow_id" value="">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    required>
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
        <!-- End Return Book Modal -->

        <!-- Update Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Borrowed Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm">
                            <input type="hidden" id="edit_book_borrow_id" name="book_borrow_id">
                            <!-- Use hidden input -->
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="edit_student_name" name="student_name"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <input type="text" class="form-control" id="edit_grade_level" name="grade_level"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="book_title" class="form-label">Book Title</label>
                                <select class="form-select" id="edit_book_title" name="book_title"></select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateButton">Update</button>
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
    document.getElementById('BorrowBook').addEventListener('input', function() {
        const searchTerm = this.value;

        // Check if the input is not empty
        if (searchTerm.length > 2) { // Trigger after 2 characters
            fetch(`backend/fetch-books.php?term=${encodeURIComponent(searchTerm)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const suggestionsContainer = document.getElementById('bookSuggestions');
                    suggestionsContainer.innerHTML = ''; // Clear previous suggestions
                    suggestionsContainer.style.display = data.length ? 'block' :
                        'none'; // Show or hide the suggestions container

                    if (data.length > 0) {
                        data.forEach(book => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.className = 'suggestion-item';
                            suggestionItem.textContent = book.title; // Display book title
                            suggestionItem.dataset.id = book.book_id; // Store the book id

                            // Add click event to select the book
                            suggestionItem.addEventListener('click', function() {
                                console.log('Suggestion clicked:', this
                                    .textContent); // Debug log
                                document.getElementById('BorrowBook').value = this
                                    .textContent; // Set input value
                                document.getElementById('bookId').value = this.dataset
                                    .id; // Store selected book ID

                                // Clear and hide suggestions after selection
                                suggestionsContainer.innerHTML = ''; // Clear suggestions
                                suggestionsContainer.style.display =
                                    'none'; // Hide suggestions after selection
                            });

                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    } else {
                        suggestionsContainer.innerHTML = '<div>No books found</div>'; // No results
                    }
                })
                .catch(error => console.error('Error fetching books:', error));
        } else {
            document.getElementById('bookSuggestions').style.display =
                'none'; // Hide suggestions if input is less than 3 characters
        }
    });

    // Reset inputs when the modal is closed
    const borrowModal = document.getElementById('borrowModal');
    borrowModal.addEventListener('hidden.bs.modal', function() {
        // Reset all input fields
        document.getElementById('addBookBorrowForm').reset(); // Reset the form
        document.getElementById('bookSuggestions').innerHTML = ''; // Clear suggestions
        document.getElementById('bookSuggestions').style.display = 'none'; // Hide suggestions

        // Reset the profile picture
        const profilePicture = document.getElementById('profilePicture');
        profilePicture.src = ''; // Clear the image source
        profilePicture.style.display = 'none'; // Hide the image
    });
    </script>

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


</body>

</html>