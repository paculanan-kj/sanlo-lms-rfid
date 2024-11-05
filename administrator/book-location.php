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
            <h1>Location</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">Books</li>
                    <li class="breadcrumb-item active">Location</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Search for a Book</h5>
                            </div>
                            <div class="mb-3 position-relative">
                                <input type="text" id="searchBook" class="form-control"
                                    placeholder="Enter book title..." autocomplete="off" />
                                <div id="suggestions" class="suggestions-list position-absolute" style="z-index: 1000;">
                                </div>
                            </div>
                            <button id="searchButton" class="btn btn-primary mt-2">Search</button>
                            <!-- Search button -->
                            <div id="searchResults" class="mt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
    let selectedBookTitle = ''; // Variable to store the selected book title

    document.getElementById('searchBook').addEventListener('input', function() {
        const bookTitle = this.value;

        // Fetch suggestions based on user input
        if (bookTitle.length > 0) {
            fetch('backend/search-suggestions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: bookTitle
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const suggestionsDiv = document.getElementById('suggestions');
                    suggestionsDiv.innerHTML = ''; // Clear previous suggestions

                    if (data.success && data.suggestions.length > 0) {
                        suggestionsDiv.style.display = 'block'; // Show dropdown
                        data.suggestions.forEach(suggestion => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.classList.add('suggestion-item');
                            suggestionItem.textContent = suggestion.title; // Display book title
                            suggestionItem.addEventListener('click', function() {
                                document.getElementById('searchBook').value = suggestion
                                    .title; // Set input to clicked suggestion
                                selectedBookTitle = suggestion
                                .title; // Store the selected book title
                                suggestionsDiv.innerHTML = ''; // Clear suggestions
                                suggestionsDiv.style.display = 'none'; // Hide dropdown
                            });
                            suggestionsDiv.appendChild(suggestionItem);
                        });
                    } else {
                        suggestionsDiv.style.display = 'none'; // Hide dropdown if no suggestions
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('suggestions').innerHTML =
                        '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                });
        } else {
            document.getElementById('suggestions').style.display = 'none'; // Hide dropdown if input is empty
        }
    });

    // Handle search button click
    document.getElementById('searchButton').addEventListener('click', function() {
        if (selectedBookTitle) {
            fetchBookLocation(selectedBookTitle); // Fetch and display location for the selected book
        } else {
            alert(
            'Please select a book from the suggestions before searching.'); // Alert if no book is selected
        }
    });

    // Function to fetch book location after selection
    function fetchBookLocation(bookTitle) {
        fetch('backend/search-book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title: bookTitle
                })
            })
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchResults');
                resultsDiv.innerHTML = ''; // Clear previous results

                if (data.success) {
                    const bookDetails = document.createElement('div');
                    bookDetails.classList.add('alert', 'alert-info');
                    bookDetails.innerHTML = `<strong>Title:</strong> ${data.book.title}<br>
                                     <strong>Location:</strong> ${data.book.location}`;
                    resultsDiv.appendChild(bookDetails);
                } else {
                    resultsDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            });
    }
    </script>

</body>

</html>