<?php
include('../config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form variables are set
    if (isset($_REQUEST['installationid'])) {
        $installationid = $_REQUEST['installationid'];
        $atmid = $_REQUEST['atmid'];

        // Query to get column names
        $statement = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'installationcheckpoints' AND COLUMN_NAME LIKE 'isApproved%' AND TABLE_SCHEMA = 'sarmicrosystems_advantage'";
        $sql = mysqli_query($con, $statement);

        // Check for query errors
        if (!$sql) {
            die('Query Error: ' . mysqli_error($con));
        }

        while ($sql_result = mysqli_fetch_assoc($sql)) {
            $approval_status = $sql_result['COLUMN_NAME'];
            $approval_column = str_replace('isApproved', '', $approval_status) . 'ApprovedBy';

$updatesql = "update installationcheckpoints set $approval_status='1', $approval_column='".$userid."' where installationID='".$installationid."'";
            mysqli_query($con,$updatesql);
// echo '<br>';
            echo "<script>alert('Record Approved successfully!'); window.location.href = 'installationInfo.php?atmid=$atmid';</script>";

        }
    }
}
?>
