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
    <style>
        .book-details {
            background-color: #e9f7fd;
            /* Light blue */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body>

    <?php
    include('inc/navbar.php'); // Include the navigation bar
    ?>

    <main class="container main">
        <div class="feature-card mb-4">
            <h3 class="card-title">Search Book</h3>
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
                <p class="text-center">Enter a search term to find book and location</p>
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
                    <div class="status status-available" id="bookStatus"></div>
                    <div class="mt-4">
                        <button class="btn btn-secondary" id="backToSearch">Back to Search</button>
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



            // Back to Search (refreshes the page)
            $('#backToSearch').on('click', function() {
                location.reload();
            });

        });
    </script>

</body>

</html>