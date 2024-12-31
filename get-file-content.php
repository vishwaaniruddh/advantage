<?php
if (isset($_GET['file'])) {
    $filePath = $_GET['file'];

    // Validate the file path to prevent directory traversal
    $basePath = __DIR__; // Set your base directory here
    $fullPath = realpath($basePath . '/' . $filePath);

    if (strpos($fullPath, $basePath) === 0 && file_exists($fullPath)) {
        // Read the content of the file using file_get_contents
        $fileContent = file_get_contents($fullPath);
        echo $fileContent;
    } else {
        // Invalid file path
        http_response_code(400);
        echo 'Invalid file path.';
    }
} else {
    // File parameter not provided
    http_response_code(400);
    echo 'File parameter not provided.';
}
?>
