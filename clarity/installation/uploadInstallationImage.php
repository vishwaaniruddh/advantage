<?php include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['files'])) {
    $atmid = $_POST['atmid'];
    $image_name = str_replace('[]','',$_POST['image_name']);
    
    $installationId = $_POST['installationId'];
    $year = date('Y');
    $month = date('m');
    $uploadDirectory = "../uploads/$year/$month/$atmid/";

    // Create the directory if it doesn't exist
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $uploadedFiles = [];

    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['files']['name'][$key];
        $file_tmp = $_FILES['files']['tmp_name'][$key];
        $file_path = $uploadDirectory . $file_name;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            $uploadedFiles[] = $file_path;
        } else {
            echo "Failed to upload file {$file_name}.";
        }
    }

    // Join the uploaded file paths into a comma-separated string
    $uploadedFilesString = implode(',', $uploadedFiles);

    $updatesql = "update installationdata set $image_name = '".$uploadedFilesString."' where id='".$installationId."'" ;

    if(mysqli_query($con,$updatesql)){
        echo "Files uploaded successfully: $uploadedFilesString";
    }

} else {
    echo "No files uploaded.";
}
?>
