<?php
include ('../config.php');

$response = array();
$response['openCall'] = false;

if (isset($_POST['atmid'])) {
    $atmid = $_POST['atmid'];

    // Check ATMID Exixts and in live



    $sql = mysqli_query($con, "select * from projectinstallation where atmid='" . $atmid . "' and status=1 and isDone=1");
    if ($sql_result = mysqli_fetch_assoc($sql)) {


        // Check if there is an open call for the given atmid
        $openCallQuery = "SELECT id,reference_code FROM mis WHERE atmid = '" . $atmid . "' AND status != 'close' order by id desc";
        $openCallResult = mysqli_query($con, $openCallQuery);
        $openCallData = mysqli_fetch_assoc($openCallResult);
        $openCalls = $openCallData['reference_code'];

        if ($openCalls > 0) {
            $response['openCall'] = $openCalls;
        }
    }
}

// Output response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>