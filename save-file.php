<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the file path and content from the POST data
    $filePath = $_POST["file"];
    $fileContent = $_POST["content"];

    // Sanitize the file path to prevent directory traversal attacks
    $filePath = realpath(__DIR__ . '/' . $filePath);

    // Check if the file path is valid and the file exists
    if ($filePath && is_file($filePath)) {
        // Attempt to open the file for writing
        $fileHandle = fopen($filePath, 'w');

        if ($fileHandle) {
            // Write the content to the file
            fwrite($fileHandle, $fileContent);

            // Close the file handle
            fclose($fileHandle);

            // Send a success response
            http_response_code(200);
            echo "File saved successfully!";
        } else {
            // Send an error response if unable to open the file
            http_response_code(500);
            echo "Failed to open the file for writing.";
        }
    } else {
        // Send an error response if the file path is invalid
        http_response_code(404);
        echo "File not found.";
    }
} else {
    // Send an error response for unsupported request methods
    http_response_code(405);
    echo "Method not allowed.";
}
?>
