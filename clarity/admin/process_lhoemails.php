<?php
include('../config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data


    $lho = $_POST['lho'];
    $contactPersonName = $_REQUEST['contactPersonName'];
    $contactPersonmob = $_REQUEST['contactPersonmob'];

    $lhosql = mysqli_query($con, "select * from lho where id='".$lho."'");
    $lhosql_result = mysqli_fetch_assoc($lhosql);
    $lhoName = $lhosql_result['lho'];

    $emailType = $_POST['emailType'];
    $emailId = $_POST['emailId'];

    // Validate form data (You can add more validation if needed)

    // Database connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // Escape special characters to prevent SQL injection
    $lho = mysqli_real_escape_string($con, $lho);
    $emailType = mysqli_real_escape_string($con, $emailType);
    $emailId = mysqli_real_escape_string($con, $emailId);

    // Insert data into the table

    $sql = "INSERT INTO lhoemails (lhoid, lhoname,contactPersonName,contactPersonMob, emailType,email, status, created_by) 
            VALUES ('$lho', '$lhoName','".$contactPersonName."','".$contactPersonmob."' ,'$emailType', '".$emailId."', 1, '".$userid."')";

    if (mysqli_query($con, $sql)) {
        // Show alert and redirect
        echo "<script>alert('Record added successfully!'); window.location.href = 'lhomails.php';</script>";
        exit(); // Make sure to exit after redirection
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }

    // Close database connection
    mysqli_close($con);
} else {
    // If the form is not submitted, redirect or handle accordingly
    echo "Form not submitted!";
}
?>
