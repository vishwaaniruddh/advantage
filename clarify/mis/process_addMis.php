<?php 
include('../config.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$response = array();
$response['success'] = false;
$response['message'] = "";


$status = 'open';
$created_by = $userid;
$created_at = $datetime;


$atmid = $_POST['atmid'];
$bank = $_POST['bank'];
$customer = $_POST['customer'];
$zone = $_POST['zone'];
$city = $_POST['city'];
$state = $_POST['state'];
$location = $_POST['location'];
$branch = $_POST['branch'];
$bm = $_POST['bm'];

// $remarks = htmlspecialchars($_POST['emailbody']);
$remarks = filter_var($_POST['emailbody'], FILTER_SANITIZE_STRING);
$ccEmail = filter_var($_POST['ccemailtoSendVal'], FILTER_SANITIZE_STRING);
$toEmail = filter_var($_POST['toemailtoSendVal'], FILTER_SANITIZE_STRING);

// $toEmail = $_POST['ccemailtoSendVal'];
// $ccEmail = $_POST['toemailtoSendVal'];

$comp = $_POST['comp'];
$subcomp = $_POST['subcomp'];
$docket_no = $_POST['docket_no'];
$call_type = $_REQUEST['call_type'];
$subject = $_REQUEST['subject'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$engineer_user_id = '';

$serviceExecutive = $_SESSION['SERVICE_username'];
$lho = $_REQUEST['lho'];

if ($_SESSION['SERVICE_level'] == 5) {
    $call_receive = 'Customer / Bank';
} else {
    $call_receive = $_POST['call_receive'];
}

if (isset($_REQUEST['noProblemOccurs'])) {
    $noProblemOccurs = $_REQUEST['noProblemOccurs'];
    $noProblemOccursStr = implode(',', $noProblemOccurs);
} else {
    $noProblemOccursStr = '';
}



function generateUniqueReferenceCode() {
    $length = 10;
    $characters = '0123456789abcdefghijlmnopqrstuvwxyz';

    do {
        // Generate a random alphanumeric string
        $randomCode = '';
        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Check if the generated code already exists in the mis table
        $isUnique = isReferenceCodeUnique($randomCode);

    } while (!$isUnique);

    return $randomCode;
}

function isReferenceCodeUnique($code) {
    global $con;
    $query = "SELECT COUNT(*) as count FROM mis WHERE reference_code = '$code'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    // If count is 0, the code is unique; otherwise, it already exists
    return $row['count'] == 0;
}

// Example usage
$uniqueReferenceCode = generateUniqueReferenceCode();




$sitesql = mysqli_query($con,"select * from sites where atmid='".$atmid."'");
$sitesql_result = mysqli_fetch_assoc($sitesql);
$delegatedToVendorName = $sitesql_result['delegatedToVendorName'];
$delegatedToVendorId = $sitesql_result['delegatedToVendorId'];


$statement = "INSERT INTO mis(reference_code,atmid, bank, customer, zone, city, state, location, call_receive_from, remarks, status, created_by, created_at, branch, bm, call_type,
serviceExecutive,lho,noProblemOccurs,toEmail,ccEmail,subject,vendorId,vendorName) 
VALUES ('".$uniqueReferenceCode."','" . $atmid . "','" . $bank . "','" . $customer . "','" . $zone . "','" . $city . "','" . $state . "','" . $location . "','" . $call_receive . "','" . $remarks . "','open','" . $created_by . "','" . $created_at . "','" . $branch . "','" . $bm . "','" . $call_type . "','" . $serviceExecutive . "','" . $lho . "','" . $noProblemOccursStr . "','" . $toEmail . "','" . $ccEmail . "','".$subject."'
,'".$delegatedToVendorId."','".$delegatedToVendorName."')";

if (mysqli_query($con, $statement)) {
    $mis_id = $con->insert_id;

    $last_sql = mysqli_query($con, "select id from mis_details order by id desc");
    $last_sql_result = mysqli_fetch_assoc($last_sql);
    $last = $last_sql_result['id'];

    if (!$last) {
        $last = 0;
    }
    $ticket_id = mb_substr(date('M'), 0, 1) . date('Y') . date('m') . date('d') . sprintf('%04u', $last);
    $detail_statement = "insert into mis_details(mis_id,atmid,component,subcomponent,engineer,docket_no,status,created_at,ticket_id,
             mis_city,zone,call_type,case_type,branch,noProblemOccurs) 
             values('" . $mis_id . "','" . $atmid . "','" . $comp . "','" . $subcomp . "','" . $engineer_user_id . "','" . $docket_no[$i] . "','" . $status . "','" . $created_at . "','" . $ticket_id . "','" . $city . "','" . $zone . "','Service','" . $call_receive . "','" . $branch . "','" . $noProblemOccursStr . "')";
    if (mysqli_query($con, $detail_statement)) {

        $subject = 'Docket Number ' . $ticket_id . ' ATM ID : ' . $atmid;

        mysqli_query($con,"update mis set subject ='".$subject."' where id ='".$mis_id."' ");

        // SendEmail Here

        include('./emailtemp.php');

            // Success
            $response['success'] = true;
            $response['message'] = "Call Logged successfully ! TICKET ID : " . $ticket_id;






    }

} else {
    echo mysqli_error($con);
    $response['message'] = "An error occurred while saving the form data.";
}

// Output response as JSON
header('Content-Type: application/json');
echo json_encode($response);