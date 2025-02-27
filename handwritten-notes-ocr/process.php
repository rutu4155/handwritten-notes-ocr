<?php
require 'vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

// Ensure upload directory exists
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if a file was uploaded
if (!isset($_FILES['file'])) {
    die("Error: No file uploaded.");
}

// Get the uploaded file
$fileTmpPath = $_FILES['file']['tmp_name'];
$fileName = basename($_FILES['file']['name']);
$targetFilePath = $uploadDir . $fileName;

// Move file to the `uploads` directory
if (!move_uploaded_file($fileTmpPath, $targetFilePath)) {
    die("Error: Failed to upload the file.");
}

// Check if the uploaded file is a PDF
$fileExtension = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
if ($fileExtension === 'pdf') {
    // Convert PDF to Image using pdftoppm (first page only)
    $imagePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
    $command = "pdftoppm -jpeg -singlefile " . escapeshellarg($targetFilePath) . " " . escapeshellarg($imagePath);

    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        die("Error: PDF to Image conversion failed.");
    }
} else {
    // If it's an image, use it directly
    $imagePath = $targetFilePath;
}

// Perform OCR
try {
    $text = (new TesseractOCR($imagePath))->lang('eng')->run();
    echo nl2br(htmlspecialchars($text));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
