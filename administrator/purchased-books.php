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
    include 'backend/dbcon.php'; // Include your database connection

    // Query to fetch users from the database
    $sql = "SELECT user_id, firstname, middlename, lastname, email, username FROM user";
    $result = $con->query($sql);
    ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Purchase Book</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active">Purchase Book</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Purchase Books</h5>
                                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#borrowModal">
                                    <i class="bx bx-plus me-1"></i>Purchase
                                </button>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Book</th>
                                        <th>Student Name</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Money Paid</th>
                                        <th>Change</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <?php
                                require('backend/dbcon.php'); // Ensure this file contains your database connection logic

                                // Ensure the user_id is set correctly before executing the query
                                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Retrieve user_id from session

                                $query = "
                                        SELECT 
                                            pb.purchase_id, 
                                            b.title,
                                            pd.quantity, 
                                            pb.total_amount, 
                                            pb.cash AS student_money, 
                                            pb.money_change, 
                                            pb.created_at, 
                                            CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_name,  -- Combine first, middle, and last name
                                            s.picture
                                        FROM purchased_books pb 
                                        LEFT JOIN purchase_details pd ON pb.purchase_id = pd.purchase_id
                                        LEFT JOIN book b ON pd.book_id = b.book_id
                                        LEFT JOIN book_categories c ON b.category_id = c.category_id
                                        LEFT JOIN student s ON pb.student_id = s.student_id
                                        WHERE pb.user_id = ?
                                        ORDER BY pb.created_at DESC
                                    ";

                                // Prepare the query
                                $stmt = $con->prepare($query);

                                // Check if the statement was prepared successfully
                                if ($stmt === false) {
                                    die("Error preparing the SQL query: " . $con->error);
                                }

                                // Bind the parameters
                                $stmt->bind_param("i", $user_id);

                                // Execute the statement
                                $stmt->execute();

                                // Get the result
                                $result = $stmt->get_result();

                                if ($result === false) {
                                    die("Error executing the query: " . $con->error);
                                }
                                ?>

                                <tbody>
                                    <?php
                                    // Fetch and display each book purchase
                                    while ($row = $result->fetch_assoc()) {
                                        $picturePath = 'uploads/' . $row['picture'];
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>"; // Title
                                        echo "<td>";
                                        echo "<div class='d-flex align-items-center'>";
                                        echo "<img src='" . htmlspecialchars($picturePath) . "' alt='Student Image' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;'>";
                                        echo "<span>" . htmlspecialchars($row['student_name']) . "</span>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "<td><span class='badge bg-warning text-dark'>" . htmlspecialchars($row['quantity']) . "</span></td>"; // Quantity
                                        echo "<td>₱" . number_format($row['total_amount'], 2) . "</td>"; // Total Amount
                                        echo "<td>₱" . number_format($row['student_money'], 2) . "</td>"; // Money Paid
                                        echo "<td>₱" . number_format($row['money_change'], 2) . "</td>"; // Change

                                        // Action Buttons: Edit and Delete
                                        echo "<td>";
                                        echo "<button class='btn btn-primary btn-sm' onclick='editPurchase(" . $row['purchase_id'] . ")'>Edit</button> ";
                                        echo "<button class='btn btn-danger btn-sm' onclick='deletePurchase(" . $row['purchase_id'] . ")'>Delete</button>";
                                        echo "</td>";

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

        <!-- Add Book Purchase Modal -->
        <div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="addBookBorrowForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrowModalLabel">Purchase Book</h5>
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
                                        <input type="text" class="form-control" id="BorrowBook" name="book">
                                        <div id="bookSuggestions" class="suggestions-container"
                                            style="border: 1px solid #ccc; display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="bookQuantity" class="form-label">Quantity</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="bookQuantity" name="quantity">
                                            <button type="button" class="btn btn-success btn-sm" id="addBookBtn">
                                                <i class="bx bx-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Visible area to display the amount -->
                            <div id="displayAmount" class="mt-2"></div>

                            <!-- Table to display selected books and "Student's Money" input fields -->
                            <div id="bookDetailsSection" style="display: none;">
                                <!-- Table to display selected books -->
                                <div class="mt-3">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Book Title</th>
                                                <th>Quantity</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bookList">
                                            <!-- Dynamic rows will appear here -->
                                        </tbody>
                                    </table>
                                    <div class="text-right">
                                        <strong>Total Amount: </strong><span id="totalAmount">0</span>
                                    </div>
                                </div>

                                <!-- Student's Money Amount Input -->
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <label for="studentMoney" class="form-label">Student's Money</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" id="studentMoney" name="student_money" required min="0" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="changeAmount" class="form-label">Change</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="text" class="form-control" id="changeAmount" name="change" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="userId" name="user_id">

                            <input type="hidden" class="form-control" id="studentId" name="student_id" required readonly>
                            <input type="hidden" id="bookId" name="book_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Purchase Modal -->
        <div class="modal fade" id="editPurchaseModal" tabindex="-1" aria-labelledby="editPurchaseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPurchaseModalLabel">Edit Purchase</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="editPurchaseForm">
                        <div class="modal-body">
                            <!-- Student Picture -->
                            <div class="text-center mb-3">
                                <img id="studentPicture" src="" alt="Student Image" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                            </div>
                            <input type="hidden" id="purchase_id" name="purchase_id">
                            <div class="mb-3">
                                <label for="title" class="form-label">Book Title</label>
                                <input type="text" class="form-control" id="title" name="title" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity">
                            </div>
                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Total Amount</label>
                                <input type="text" class="form-control" id="total_amount" name="total_amount">
                            </div>
                            <div class="mb-3">
                                <label for="student_money" class="form-label">Money Paid</label>
                                <input type="text" class="form-control" id="student_money" name="student_money">
                            </div>
                            <div class="mb-3">
                                <label for="money_change" class="form-label">Change</label>
                                <input type="text" class="form-control" id="money_change" name="money_change">
                            </div>
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
    <script src="assets/sweet-alert/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.min.js"></script>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get user_id from URL
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('user_id');

            // Set user_id in the hidden input field if it exists
            if (userId) {
                document.getElementById('userId').value = userId;
            }

            // Existing variables
            const borrowBookInput = document.getElementById("BorrowBook");
            const bookIdInput = document.getElementById("bookId");
            const bookSuggestions = document.getElementById("bookSuggestions");
            const bookQuantityInput = document.getElementById("bookQuantity");
            const addBookBtn = document.getElementById("addBookBtn");
            const bookListTable = document.getElementById("bookList");
            const totalAmountSpan = document.getElementById("totalAmount");
            const studentMoneyInput = document.getElementById("studentMoney");
            const changeAmountInput = document.getElementById("changeAmount");
            const bookDetailsSection = document.getElementById("bookDetailsSection");

            let selectedBookAmount = 0;
            let selectedBookId = null;

            // Fetch book suggestions
            borrowBookInput.addEventListener("input", function() {
                const searchTerm = this.value;
                if (searchTerm.length >= 2) {
                    fetch(`backend/getBookSuggestions.php?term=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            bookSuggestions.innerHTML = '';
                            bookSuggestions.style.display = 'block';

                            data.forEach(book => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item p-2 hover:bg-gray-100 cursor-pointer';
                                div.textContent = book.title;
                                div.addEventListener('click', () => {
                                    borrowBookInput.value = book.title;
                                    selectedBookId = book.book_id;
                                    selectedBookAmount = parseFloat(book.amount);
                                    bookIdInput.value = book.book_id;
                                    bookSuggestions.style.display = 'none';

                                    document.getElementById("displayAmount").textContent = `Amount: ₱${selectedBookAmount.toFixed(2)}`;
                                });
                                bookSuggestions.appendChild(div);
                            });
                        })
                        .catch(error => console.error("Error fetching suggestions:", error));
                } else {
                    bookSuggestions.style.display = 'none';
                }
            });

            // Update the change amount
            function updateChange() {
                const totalAmount = parseFloat(totalAmountSpan.textContent) || 0;
                const studentMoney = parseFloat(studentMoneyInput.value) || 0;
                const change = studentMoney - totalAmount;
                changeAmountInput.value = change >= 0 ? `₱${change.toFixed(2)}` : "Insufficient funds";
            }

            // Add book to the list
            addBookBtn.addEventListener("click", function() {
                if (!selectedBookId || !borrowBookInput.value) {
                    alert("Please select a book from the suggestions.");
                    return;
                }

                const quantity = parseInt(bookQuantityInput.value);
                if (isNaN(quantity) || quantity <= 0) {
                    alert("Please enter a valid quantity.");
                    return;
                }

                const totalForBook = quantity * selectedBookAmount;

                const newRow = document.createElement("tr");
                newRow.innerHTML = `
            <td>${borrowBookInput.value}</td>
            <td>${quantity}</td>
            <td>₱${totalForBook.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-book">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;
                bookListTable.appendChild(newRow);

                bookDetailsSection.style.display = 'block';
                const currentTotal = parseFloat(totalAmountSpan.textContent) || 0;
                totalAmountSpan.textContent = (currentTotal + totalForBook).toFixed(2);

                borrowBookInput.value = '';
                bookQuantityInput.value = '';
                document.getElementById("displayAmount").textContent = '';
                selectedBookId = null;
                selectedBookAmount = 0;

                updateChange();
            });

            // Remove book from list
            bookListTable.addEventListener('click', function(e) {
                if (e.target.closest('.remove-book')) {
                    const row = e.target.closest('tr');
                    const amount = parseFloat(row.cells[2].textContent.replace('₱', ''));
                    const currentTotal = parseFloat(totalAmountSpan.textContent);
                    totalAmountSpan.textContent = (currentTotal - amount).toFixed(2);
                    row.remove();
                    updateChange();

                    if (!bookListTable.querySelector('tr')) {
                        bookDetailsSection.style.display = 'none';
                    }
                }
            });

            studentMoneyInput.addEventListener("input", updateChange);

            document.addEventListener('click', function(e) {
                if (!borrowBookInput.contains(e.target) && !bookSuggestions.contains(e.target)) {
                    bookSuggestions.style.display = 'none';
                }
            });

            // Form Submission Handling
            const form = document.getElementById('addBookBorrowForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(form);
                formData.append('total_amount', totalAmountSpan.textContent);
                formData.append('change', changeAmountInput.value);

                fetch('backend/insert-purchase-book.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "error") {
                            Swal.fire({
                                icon: "error",
                                title: "Stock Alert",
                                text: data.message
                            });
                        } else if (data.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: data.message
                            });
                            form.reset();
                            bookListTable.innerHTML = '';
                            totalAmountSpan.textContent = '0';
                            bookDetailsSection.style.display = 'none';
                        }
                    })
                    .catch(error => console.error("Error submitting form:", error));
            });
        });
    </script>
    <script>
        // Form Submission Handling
        const form = document.getElementById('addBookBorrowForm');
        const totalAmountSpan = document.getElementById('totalAmount');
        const changeAmountInput = document.getElementById('changeAmount');
        const bookListTable = document.getElementById('bookList');
        const bookDetailsSection = document.getElementById('bookDetailsSection');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Collect all book rows data
            const bookRows = [];
            document.querySelectorAll('#bookList tr').forEach(row => {
                bookRows.push({
                    title: row.cells[0].textContent,
                    quantity: parseInt(row.cells[1].textContent),
                    amount: parseFloat(row.cells[2].textContent.replace('₱', ''))
                });
            });

            // Log data for debugging
            console.log('Book Rows:', bookRows);
            console.log('Total Amount:', totalAmountSpan.textContent);
            console.log('Change:', changeAmountInput.value);

            const formData = new FormData(form);
            formData.append('bookRows', JSON.stringify(bookRows));
            formData.append('total_amount', totalAmountSpan.textContent);
            formData.append('change', changeAmountInput.value.replace('₱', ''));

            // Send data to PHP script
            fetch('backend/insert-purchase-book.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response Data:', data); // Log response for debugging
                    if (data.status === "error") {
                        Swal.fire({
                            icon: "error",
                            title: "Purchase Error",
                            text: data.message
                        });
                    } else if (data.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: data.message
                        }).then(() => {
                            // Clear the form and reset the table
                            form.reset();
                            bookListTable.innerHTML = '';
                            totalAmountSpan.textContent = '₱0.00';
                            bookDetailsSection.style.display = 'none';

                            // Reload the page
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    console.error("Error submitting form:", error);
                    Swal.fire({
                        icon: "error",
                        title: "System Error",
                        text: "An unexpected error occurred. Please try again."
                    });
                });
        });
    </script>

    <script>
        // Edit Purchase function (modify this to show an edit form or open a modal)
        function editPurchase(purchaseId) {
            Swal.fire({
                title: 'Edit Purchase',
                text: 'Edit Purchase ID: ' + purchaseId,
                icon: 'info',
                confirmButtonText: 'Okay'
            });
            // Add logic here to load the edit form or open a modal if needed
        }

        // Delete Purchase function with SweetAlert
        function deletePurchase(purchaseId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this purchase?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an AJAX call to delete the purchase from the database
                    fetch('backend/delete-purchase.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                purchase_id: purchaseId
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Purchase has been deleted.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload(); // Reload the page after deletion
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an error deleting the purchase.',
                                    icon: 'error',
                                    confirmButtonText: 'Try Again'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while deleting the purchase.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        });
                }
            });
        }
    </script>

    <script>
        function editPurchase(purchaseId) {
            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('editPurchaseModal'));
            modal.show();

            // Fetch the purchase details via AJAX
            fetch('backend/fetch-purchase.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        purchase_id: purchaseId
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Populate modal fields with fetched data
                    document.getElementById('purchase_id').value = data.purchase_id;
                    document.getElementById('title').value = data.title;
                    document.getElementById('student_name').value = data.student_name;
                    document.getElementById('quantity').value = data.quantity;
                    document.getElementById('total_amount').value = data.total_amount;
                    document.getElementById('student_money').value = data.student_money;
                    document.getElementById('money_change').value = data.money_change;

                    // Display the student picture
                    document.getElementById('studentPicture').src = 'uploads/' + data.picture;
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    </script>

    <script>
        document.getElementById('editPurchaseForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting traditionally

            // Collect updated data from the form fields
            const purchaseId = document.getElementById('purchase_id').value;
            const quantity = document.getElementById('quantity').value;
            const totalAmount = document.getElementById('total_amount').value;
            const studentMoney = document.getElementById('student_money').value;
            const moneyChange = document.getElementById('money_change').value;

            // Send the updated data to the server
            fetch('backend/update-purchase.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        purchase_id: purchaseId,
                        quantity: quantity,
                        total_amount: totalAmount,
                        student_money: studentMoney,
                        money_change: moneyChange
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload(); // Reload the page to see the changes
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error updating purchase:', error));
        });
    </script>


</body>

</html>