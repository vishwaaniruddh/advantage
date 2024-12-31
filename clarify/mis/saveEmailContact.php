<?php include('../config.php');


$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
$contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_NUMBER_INT);
$status = 1; 

if (empty($name) || empty($email) || empty($company) || empty($contact)) {
    echo 0; // Error: Required fields are missing
    exit();
}

$sql = "INSERT INTO emailContact (name, email, company, contact, status) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $company, $contact, $status);

if (mysqli_stmt_execute($stmt)) {
    echo 1; // Success
} else {
    echo 0; // Error: Failed to execute the statement
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>