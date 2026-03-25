<?php
include 'dbcon.php';

$purchase_id = isset($_GET['purchase_id']) ? intval($_GET['purchase_id']) : 0;

if (!$purchase_id) {
    echo "Invalid receipt.";
    exit;
}

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Get main purchase info
$query = "SELECT pb.*, CONCAT(s.firstname, ' ', s.lastname) AS student_name, s.gradelevel
          FROM purchased_books pb
          JOIN student s ON pb.student_id = s.student_id
          WHERE pb.purchase_id = ?";
$stmt = $con->prepare($query);

if (!$stmt) {
    die("Prepare failed for main query: " . $con->error);
}

$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$purchase = $stmt->get_result()->fetch_assoc();

if (!$purchase) {
    echo "Purchase not found.";
    exit;
}

// Get purchase details
$details_query = "SELECT pd.*, b.title
                  FROM purchase_details pd
                  JOIN book b ON pd.book_id = b.book_id
                  WHERE pd.purchase_id = ?";
$details_stmt = $con->prepare($details_query);

if (!$details_stmt) {
    die("Prepare failed for detail query: " . $con->error);
}

$details_stmt->bind_param("i", $purchase_id);
$details_stmt->execute();
$details_result = $details_stmt->get_result();

// Get school info (you can replace this with actual DB query if available)
$school_name = "St. Lorenzo School of Polomolok";
$school_address = "Polomolok, South Cotabato, Philippines";
$school_contact = "School Library | Tel: (123) 456-7890";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?= htmlspecialchars($purchase['purchase_id']) ?></title>
    <style>
        /* General Styling */
        :root {
            --primary-color: rgb(10, 43, 194);
            /* School blue color */
            --secondary-color: #f8f9fa;
            --border-color: #dee2e6;
            --text-color: #212529;
            --accent-color: #e6eaff;
            /* Light blue accent */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: #f5f5f5;
            line-height: 1.6;
            padding: 20px;
        }

        /* Receipt Container */
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        /* Header */
        .receipt-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .receipt-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .receipt-logo {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .school-info {
            font-size: 14px;
            line-height: 1.4;
        }

        /* Info Section */
        .receipt-info {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .info-column {
            flex: 1;
            min-width: 200px;
            margin-bottom: 10px;
        }

        .info-column h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: var(--primary-color);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .info-item {
            margin-bottom: 5px;
            font-size: 15px;
        }

        .info-item strong {
            display: inline-block;
            width: 70px;
        }

        /* Items Table */
        .receipt-items {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-table th {
            background-color: var(--accent-color);
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }

        .receipt-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .receipt-table tr:last-child td {
            border-bottom: none;
        }

        /* Summary Section */
        .receipt-summary {
            padding: 20px;
            background-color: var(--secondary-color);
            border-bottom: 1px solid var(--border-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            padding-top: 8px;
            margin-top: 8px;
            border-top: 2px solid var(--border-color);
        }

        /* Footer */
        .receipt-footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }

        /* Print Button */
        .print-controls {
            text-align: center;
            margin: 20px 0;
        }

        .print-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .print-btn:hover {
            background-color: rgb(29, 72, 214);
        }

        .print-icon {
            margin-right: 8px;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .receipt-container {
                box-shadow: none;
                max-width: 100%;
            }

            .print-controls,
            .no-print {
                display: none !important;
            }

            .receipt-header {
                background-color: var(--primary-color) !important;
                color: white !important;
            }

            .receipt-table th {
                background-color: var(--accent-color) !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .info-column {
                flex: 100%;
            }

            .receipt-info {
                flex-direction: column;
            }

            .receipt-table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="receipt-logo"><?= htmlspecialchars($school_name) ?></div>
            <div class="school-info">
                <?= htmlspecialchars($school_address) ?><br>
                <?= htmlspecialchars($school_contact) ?>
            </div>
            <h1>Library Book Receipt</h1>
        </div>

        <!-- Receipt Info -->
        <div class="receipt-info">
            <div class="info-column">
                <h3>Receipt Details</h3>
                <div class="info-item">
                    <strong>Receipt:</strong> #<?= htmlspecialchars($purchase['purchase_id']) ?>
                </div>
                <div class="info-item">
                    <strong>Date:</strong> <?= date('F j, Y', strtotime($purchase['created_at'])) ?>
                </div>
                <div class="info-item">
                    <strong>Time:</strong> <?= date('h:i A', strtotime($purchase['created_at'])) ?>
                </div>
            </div>
            <div class="info-column">
                <h3>Student Information</h3>
                <div class="info-item">
                    <strong>Name:</strong> <?= htmlspecialchars($purchase['student_name']) ?>
                </div>
                <div class="info-item">
                    <strong>Grade:</strong> <?= htmlspecialchars($purchase['gradelevel']) ?>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="receipt-items">
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th style="width: 70%;">Book Title</th>
                        <th style="width: 30%;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reset pointer
                    $details_result->data_seek(0);
                    while ($row = $details_result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="receipt-summary">
            <div class="summary-row">
                <span>Total Amount:</span>
                <span>₱<?= number_format($purchase['total_amount'], 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Cash Tendered:</span>
                <span>₱<?= number_format($purchase['cash'], 2) ?></span>
            </div>
            <div class="summary-row total">
                <span>Change:</span>
                <span>₱<?= number_format($purchase['money_change'], 2) ?></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Thank you for your purchase!</p>
            <p>This receipt is your proof of purchase. Please keep it for your records.</p>
            <p>St. Lorenzo School of Polomolok Library - Nurturing young minds through books.</p>
        </div>
    </div>

    <!-- Print Controls (hidden when printing) -->
    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">
            <span class="print-icon">🖨️</span>Print Receipt
        </button>
    </div>

    <script>
        // Auto-focus print dialog when explicitly requested
        if (window.location.hash === '#print') {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>

</html>