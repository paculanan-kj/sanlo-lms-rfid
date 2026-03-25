<?php
// Set headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=student_format.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Add the column headers
fputcsv($output, ['RFID', 'First Name', 'Middle Name', 'Last Name', 'Grade', 'Strand', 'Section']);

// Close output stream
fclose($output);
