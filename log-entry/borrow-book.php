<?php
include 'backend/dbcon.php';
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session
$encoded_user_id = base64_encode($user_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>St. Lorenzo School of Polomolok - Book Borrowing</title>
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

        <!-- Content visible after authentication -->
        <div class="student-authenticated" id="authenticatedContent">
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
                            <p><strong>Full Name:</strong> <span id="studentName">Full Name</span></p>
                            <p style="display: none"><strong>ID Number:</strong> <span id="studentId">ID Number</span></p>
                            <p><strong>Grade Level:</strong> <span id="studentYear">Grade Level</span></p>
                            <p><strong>Strand:</strong> <span id="studentCourse">Strand</span></p>

                        </div>
                    </div>

                    <!-- Borrowed Books List -->
                    <div class="item-list">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Borrowed Books</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Date</th> <!-- This will be the date of the borrow tansaction -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="borrowedBooksList">
                                    <!-- This will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Book Search Section -->
                <div class="col-lg-8">
                    <div class="feature-card mb-4">
                        <h3 class="card-title">Search Books</h3>
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" id="bookSearchInput" placeholder="Search by title, author, or category...">
                                <button class="btn btn-primary" id="searchButton">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div class="search-results" id="searchResults">
                            <p class="text-center">Enter a search term to find books</p>
                        </div>
                    </div>

                    <!-- Book Details Section -->
                    <div class="book-details d-none" id="bookDetails">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="" alt="Book Cover" class="book-cover" id="bookCover">
                            </div>
                            <div class="col-md-8 book-info">
                                <h4 id="bookTitle">Book Title</h4>
                                <p><strong>Author:</strong> <span id="bookAuthor"> Name</span></p>
                                <p><strong>ISBN:</strong> <span id="bookIsbn"></span></p>
                                <p><strong>Category:</strong> <span id="bookCategory"></span></p>
                                <p><strong>Publisher:</strong> <span id="bookPublisher"></span></p>
                                <p><strong>Publication Year:</strong> <span id="bookYear"></span></p>
                                <p><strong>Location:</strong> <span id="bookLocation"></span></p> <!-- Book Location Added -->
                                <p style="display: none;"><strong>Book ID:</strong> <span id="bookId"></span></p> <!-- Book ID Added -->
                                <div class="status status-available" id="bookStatus"></div>
                                <div class="mt-4">
                                    <button class="btn btn-info" id="borrowBtn">Borrow Book</button>
                                    <button class="btn btn-return d-none" id="returnBtn">Return Book</button>
                                    <button class="btn btn-secondary" id="backToSearch">Back to Search</button>
                                </div>
                            </div>
                        </div>
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
        function loadBorrowedBooks() {
            const studentId = document.getElementById("studentId").innerText.trim();

            fetch(`backend/get_borrowed_books.php?student_id=${studentId}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById("borrowedBooksList");
                    tbody.innerHTML = '';

                    if (data.success && data.books.length > 0) {
                        data.books.forEach(book => {
                            const row = `
            <tr>
                <td>${book.title}</td>
                <td>${new Date(book.created_at).toLocaleDateString()}</td>
                <td><button class="btn btn-warning btn-sm" onclick="returnBook(${book.book_borrow_id})">Return</button></td>
            </tr>
        `;
                            tbody.innerHTML += row;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3">No borrowed books found.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error('Error loading borrowed books:', err);
                    document.getElementById("borrowedBooksList").innerHTML = '<tr><td colspan="3">Failed to load data.</td></tr>';
                });
        }

        function returnBook(bookBorrowId) {
            Swal.fire({
                title: 'Return Book?',
                text: `Are you sure the student is returning this book with ID: ${bookBorrowId}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('backend/book_return.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `book_borrow_id=${bookBorrowId}`
                        })
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Returned!',
                                    text: `The book was successfully returned.`,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                loadBorrowedBooks(); // Refresh the book list
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to return book.'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred.'
                            });
                        });
                }
            });
        }


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
            // Search books
            $('#searchButton').on('click', function() {
                const query = $('#bookSearchInput').val().trim();

                if (query === '') return;

                $.ajax({
                    url: 'backend/search_books.php',
                    type: 'POST',
                    data: {
                        query
                    },
                    dataType: 'json',
                    success: function(response) {
                        const $results = $('#searchResults');
                        $results.empty();

                        if (response.success && response.books.length > 0) {
                            response.books.forEach(book => {
                                const item = $(`
                            <div class="book-item bg-white border p-3 mb-2 rounded" style="cursor:pointer" data-book-id="${book.book_id}">
                                <h5 class="mb-1" style="color:rgb(63, 76, 82)">${book.title}</h5>
                                <p class="mb-0 text-muted"><strong>Author:</strong> ${book.author}</p>
                                <p class="mb-0 text-muted"><strong>Category:</strong> ${book.category_name || 'Uncategorized'}</p>
                                <p class="mb-0 text-muted" style="display: none;"><strong>Book ID:</strong> ${book.book_id}</p> <!-- Display Book ID -->
                            </div>
                        `);
                                item.on('click', function() {
                                    showBookDetails(book);
                                });
                                $results.append(item);
                            });
                        } else {
                            $results.html('<p class="text-center">No books found.</p>');
                        }
                    },
                    error: function() {
                        $('#searchResults').html('<p class="text-center text-danger">Search failed. Try again later.</p>');
                    }
                });
            });

            // Show book details
            function showBookDetails(book) {
                $('#bookTitle').text(book.title);
                $('#bookAuthor').text(book.author);
                $('#bookIsbn').text(book.isbn);
                $('#bookCategory').text(book.category_name || 'Uncategorized');
                $('#bookPublisher').text(book.publisher);
                $('#bookYear').text(book.publication_year);
                $('#bookLocation').text(book.location || 'Not specified'); // ✅ Set location
                $('#bookStatus')
                    .text(book.available_copies > 0 ? `Available (${book.available_copies})` : 'Not Available')
                    .removeClass('status-available status-unavailable')
                    .addClass(book.available_copies > 0 ? 'status-available' : 'status-unavailable');


                // Display book ID in the book details
                $('#bookId').text(book.book_id); // Display Book ID in the details section

                // Set book cover image or default if none exists
                const bookCover = book.cover_image ? book.cover_image : 'assets/logo/ndk-logo.png';
                $('#bookCover').attr('src', bookCover); // Update book cover image

                // Show bookDetails, hide results
                $('#searchResults').hide();
                $('#bookDetails').removeClass('d-none');
            }



            // Back to Search
            $('#backToSearch').on('click', function() {
                $('#bookDetails').addClass('d-none');
                $('#searchResults').show();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Handle Borrow Book button click
            $('#borrowBtn').on('click', function() {
                // Get book details from the book section
                const bookId = $('#bookId').text();

                // Get student ID from the student info section
                const studentId = $('#studentId').text(); // Get the student ID from the Student Info section

                // Ensure student ID is not empty or undefined
                if (!studentId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Student Not Found',
                        text: 'Student ID is not available. Please try again.',
                        confirmButtonText: 'OK'
                    });
                    return; // Stop the function if student ID is not found
                }

                const quantity = 1; // Assume 1 book is being borrowed
                const status = 'Borrowed'; // Status when borrowed

                // Send data to backend to insert into book_borrow table
                $.ajax({
                    url: 'backend/borrow_book.php', // PHP file that handles the insert
                    type: 'POST',
                    data: {
                        book_id: bookId,
                        student_id: studentId,
                        quantity: quantity,
                        status: status
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Book Borrowed!',
                                text: result.message,
                                confirmButtonText: 'OK'
                            });

                            // Optionally, update the book's availability status
                            $('#bookStatus').text('Not Available').removeClass('status-available').addClass('status-unavailable');

                            // Update the Borrowed Books List
                            const borrowedBooksList = $('#borrowedBooksList');
                            const newRow = `
                        <tr>
                            <td>${result.bookTitle}</td>
                            <td>${result.borrowDate}</td>
                            <td><button class="btn btn-return">Return</button></td>
                        </tr>
                    `;
                            borrowedBooksList.append(newRow); // Add the new borrowed book to the table

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to Borrow',
                                text: result.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred. Please try again later.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Back to Search button logic
            $('#backToSearch').on('click', function() {
                $('#bookDetails').addClass('d-none');
                $('#searchResults').show();
            });
        });
    </script>

</body>

</html>