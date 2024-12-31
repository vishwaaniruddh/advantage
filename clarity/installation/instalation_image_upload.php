<?php
include('../config.php');

$imageName = $_REQUEST['imageName'];
$files = $_FILES['files'];

$uploadDir = 'uploads/'; // Directory to save uploaded files
$uploadedFiles = [];

if (!is_array($files['name'])) {
    $files = array_map(function ($file) use ($files) {
        return [$file];
    }, $files);
}

foreach ($files['name'] as $key => $name) {
    $tmpName = $files['tmp_name'][$key];
    $filePath = $uploadDir . basename($name);
    
    if (move_uploaded_file($tmpName, $filePath)) {
        $uploadedFiles[] = $filePath;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload ' . $name]);
        exit;
    }
}

$uploadedFilesStr = implode(',', $uploadedFiles);

// Insert or update the image names in the database
$sql = "UPDATE installationdata SET $imageName = CONCAT_WS(',', $imageName, ?) WHERE id = ?"; // Assuming 'id' is the identifier column
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $uploadedFilesStr, $id); // Bind the parameters (adjust as needed)

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Files uploaded successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database update failed!']);
}
?>
