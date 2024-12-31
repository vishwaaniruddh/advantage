<?php
include ('../config.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

$response = array();
$response['success'] = false;
$response['message'] = "";
$id = $_POST['mis_id'];
$actionDate = $_POST['actionDate'];
$actionTime = $_POST['actionTime'];

$mis_sql = mysqli_query($con, "select * from mis where id='" . $id . "'");
$mis_sql_result = mysqli_fetch_assoc($mis_sql);

$subject = $mis_sql_result['subject'];
$atmid = $mis_sql_result['atmid'];

$detailSql = mysqli_query($con, "select * from mis_details where mis_id='" . $id . "'");
$detailSqlResult = mysqli_fetch_assoc($detailSql);

$ticket_id = $detailSqlResult['ticket_id'];


if (isset($subject) && $subject != '') {

} else {

    $subject = 'Docket Number ' . $ticket_id . ' ATM ID : ' . $atmid;
    mysqli_query($con, "update mis set subject='" . $subject . "' where id='" . $id . "'");

}

// $lastEmail = mysqli_fetch_assoc(mysqli_query($con, "Select content_body,sent_date,from_email from emails where mis_id = '" . $id . "' order by email_id desc"));

// $lastcontent_body = $lastEmail['content_body'];
// $sent_date = $lastEmail['sent_date'];
// $lastfrom_email = $lastEmail['from_email'];

// $date = DateTime::createFromFormat('Y-m-d H:i:s', $sent_date);
// $formattedDate = $date->format('D, M j, Y \a\t g:i A');


// $lastcontent_body = '<hr/> <br>' .
//     'On ' . $formattedDate . ' ' . $lastfrom_email . ' wrote: <br/>' .
//     $lastcontent_body;




$emailThread = '';
// echo "select * from emails where mis_id = '" . $id . "' order by email_id desc" ;
$emailTrailSql = mysqli_query($con, "select * from emails where mis_id = '" . $id . "' order by email_id desc");
while ($emailTrailSqlResult = mysqli_fetch_assoc($emailTrailSql)) {

    $emailId = $emailTrailSqlResult['email_id'];

    $fromMail = $emailTrailSqlResult['from_email'];
    $sent_date = $emailTrailSqlResult['sent_date'];

    $recToSql = mysqli_query($con, "SELECT * FROM recipients WHERE email_id='" . $emailId . "' AND recipient_type='To'");
    $torecipient_emailAr = [];
    while ($recToSqlResult = mysqli_fetch_assoc($recToSql)) {
        $torecipient_emailAr[] = $recToSqlResult['recipient_email'];
    }

    // Fetch 'Cc' recipients from the database
    $recCcSql = mysqli_query($con, "SELECT * FROM recipients WHERE email_id='" . $emailId . "' AND recipient_type='Cc'");
    $ccrecipient_emailAr = [];
    while ($recCcSqlResult = mysqli_fetch_assoc($recCcSql)) {
        $ccrecipient_emailAr[] = $recCcSqlResult['recipient_email'];
    }

    // Check if 'To' recipients exist
    $torecipient_emailStr = '';
    if (!empty($torecipient_emailAr)) {
        $torecipient_emailStr = implode(',', $torecipient_emailAr);
    }

    // Check if 'Cc' recipients exist
    $ccrecipient_emailStr = '';
    if (!empty($ccrecipient_emailAr)) {
        $ccrecipient_emailStr = implode(',', $ccrecipient_emailAr);
    }


    $emailSubject = $emailTrailSqlResult['subject'];
    $content_body = $emailTrailSqlResult['content_body'];


    $emailThread .= '
    
    <hr />
' . PHP_EOL . '
    <p><b>From : </b> ' . $fromMail . ' </p>
    ' . PHP_EOL . '
    <p><b>Sent : </b> ' . $sent_date . ' </p>
    <p><b>To : </b>  ' . $torecipient_emailStr . ' </p>
    <p><b>Cc : </b>  ' . $ccrecipient_emailStr . ' </p>
    <p><b>Subject : </b>  ' . $emailSubject . ' </p>
    <br/>
    <p> ' . $content_body . ' </p>
    
    <br />
<div>
    <hr />
    
    
    
    ';

}






$date = date('Y-m-d');
// Assuming you have already retrieved the original Message ID
$originalMessageID = $_REQUEST['message_id'];
// $subject = $_REQUEST['subject'];
// $toEmail = $_POST['ccemailtoSendVal'];
// $ccEmail = $_POST['toemailtoSendVal'];


$ccEmail = filter_var($_POST['toemailtoSendVal'], FILTER_SANITIZE_STRING);
$toEmail = filter_var($_POST['ccemailtoSendVal'], FILTER_SANITIZE_STRING);


$remarks = $_POST['emailbody'];


$remarks = str_replace("'", "", $remarks);


$username = $hostusername = 'noc@advantagesb.com';
$password = $hostPassword = 'Adv$2435%';
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();


// $emailServer = 'webmail.advantagesb.com';


$emailServer = 'mail.advantagesb.com';
$mail->Host = $emailServer;
$mail->SMTPAuth = true;
$mail->Username = $hostusername;
$mail->Password = $hostPassword;
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->addReplyTo('noc@advantagesb.com');
$mail->setFrom($hostusername, 'NOC Advantaesb Team');

$to = explode(',', $toEmail);
foreach ($to as $valto) {
    $mail->addAddress($valto);
}

$cc = explode(',', $ccEmail);
foreach ($cc as $valcc) {
    $mail->addCC($valcc);
}

$type = 'Mail Update';
$status = $_POST['status'];

$year = date('Y');
$month = date('m');
$atmid = $_POST['atmid'];

$site_sql = mysqli_query($con, "Select * from sites where atmid='" . $atmid . "'");
$site_sql_result = mysqli_fetch_assoc($site_sql);
$siteid = $site_sql_result['id'];
$lho = $site_sql_result['LHO'];


if ($status == 'close') {

    $closeRemark = $_POST['closeRemark'];
    $target_dir = 'mis_images/close_uploads/' . $year . '/' . $month . '/' . $atmid;
    $link = "";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Set appropriate permissions (modify as needed)
    }

    $image = $_FILES['image']['name'];
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . '/' . $image)) {
        $link = $target_dir . '/' . $image;
    }

    $engineer = $_POST['engineer'];
    $oldMaterialDetails = $_POST['oldMaterialDetails'];
    $statement = "insert into mis_history (mis_id,type,attachment,remark,status,created_at,created_by,remark2,lho,actionDate,actionTime) 
                    values('" . $id . "','" . $status . "','" . $link . "','" . $remarks . "','1','" . $datetime . "','" . $userid . "','" . $closeRemark . "','" . $lho . "','".$actionDate."','".$actionTime."')";

    mysqli_query($con, "update mis_details set status = '" . $status . "', close_date = '" . $date . "' where mis_id = '" . $id . "'");
    mysqli_query($con, "update mis set status = '" . $status . "' where id = '" . $id . "'");



    $statusRemark = '
    <hr />
    <br/>
    <table border="1" width="100%">
        
        <tr>
            <th style="background: red;color: white;" scope="row"> Updated Status </th>
            <td> Close </td>
        </tr>
        <tr>
            <th style="background: red;color: white;" scope="row"> Updated  </th>
            <td>' . $closeRemark . '</td>
        </tr>
    </table>
    <br/>
    <hr />
    ';


} else if ($status == 'material_requirement') {

    $requiredMaterials = $_REQUEST['requiredMaterial'];
    $requiredMaterial = implode(', ', $requiredMaterials);

    $material_condition = array_filter($_REQUEST['material_condition']);
    $material_qty = array_filter($_REQUEST['material_quantity']);
    $material_condition = array_values($material_condition);
    $material_qty = array_values($material_qty);

    $material_conditionStr = implode(',', $material_condition);
    $material_conditionStr = rtrim($material_conditionStr, ',');

    $totalMaterialCount = count($material_condition);
    $year = date('Y');
    $month = date('m');
    $targetDir = 'materialRequirement/' . $year . '/' . $month . '/' . $atmid;
    $link = "";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Set appropriate permissions (modify as needed)
    }

    $checkMaterialSend = mysqli_query($con, "select * from material_send where atmid='" . $atmid . "' order by id desc");
    $checkMaterialSendResult = mysqli_fetch_assoc($checkMaterialSend);
    $sendid = $checkMaterialSendResult['id'];

    $faultyMaterialRequestSql = "insert into generatefaultymaterialrequest(siteid,atmid,requestBy,requestByPortal,requestFor,requestForPortal,materialRequestLevel,description,created_at,created_by,status,ticketId,materialRequestType,mis_id,engineer)
        values('" . $siteid . "','" . $atmid . "','" . $userid . "','Clarify','" . $RailTailVendorID . "','vendor',3,'','" . $datetime . "','" . $userid . "',1,'" . $ticketid . "','clarify','" . $id . "','" . $userid . "');
        ";

    if (mysqli_query($con, $faultyMaterialRequestSql)) {
        $faultyRequestID = $con->insert_id;

        $statusRemark = '
        <hr />
        <h2> Material Requirement </h2>

        <table border="1" width="100%">
            <tr>
                <th>Required Material</th>
                <th>Qty</th>
                <th>Reason</th>
                <th>Attachement Given</th>
            </tr>';




        for ($i = 0; $i < count($requiredMaterials); $i++) {

            $imageFileName = uniqid() . "_" . $_FILES['material_requirement_images']['name'][$i];
            $imagePath = $targetDir . '/' . $imageFileName;

            move_uploaded_file($_FILES['material_requirement_images']['tmp_name'][$i], $imagePath);

            $statusRemark .= '<tr>
            <td>' . $requiredMaterials[$i] . '</td>
            <td>' . $material_qty[$i] . '</td>
            <td>' . $material_condition[$i] . '</td>
            <td><a src="' . $base_url . 'mis/' . $imagePath . '" download >View attachment</a> </td>
            </tr>';

            $sendDetailsSql = mysqli_query($con, "Select * from material_send_details where materialSendId='" . $sendid . "' and attribute='" . $requiredMaterials[$i] . "'");
            if ($sendDetailsSqlResult = mysqli_fetch_assoc($sendDetailsSql)) {
                $serialNumber = $sendDetailsSqlResult['serialNumber'];
                $MaterialID = getInventoryIDBySerialNumber($serialNumber);

                $faultyDetailsSql = "insert into generatefaultymaterialrequestdetails(requestId, MaterialID, MaterialName, MaterialSerialNumber, materialImage, created_at, created_by, status , materialRequestType,mis_id,materialCondition,material_qty)
                values('" . $faultyRequestID . "','" . $MaterialID . "','" . $requiredMaterials[$i] . "','" . $serialNumber . "','" . $imagePath . "','" . $datetime . "','" . $userid . "',1,'clarify','" . $id . "','" . $material_condition[$i] . "','" . $material_qty[$i] . "')";
                if (mysqli_query($con, $faultyDetailsSql)) {
                    mysqli_query($con, "insert into vendormaterialrequest(vendorId, vendorName, siteid, atmid, engineerId, engineerName, materialName, materialCondition, created_at,  created_by, createdByPortal, status,material_qty) 
                    values('" . $RailTailVendorID . "','" . getVendorName($RailTailVendorID) . "','" . $siteid . "','" . $atmid . "','" . $userid . "','" . $SERVICE_email . "','" . $requiredMaterials[$i] . "','" . $material_condition[$i] . "','" . $datetime . "','" . $userid . "','Clarify',1,'" . $material_qty[$i] . "')");
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                }
            } else {
                $faultyDetailsSql = "insert into generatefaultymaterialrequestdetails(requestId, MaterialID, MaterialName, MaterialSerialNumber, materialImage, created_at, created_by, status , materialRequestType,mis_id,materialCondition,material_qty)
                values('" . $faultyRequestID . "','','" . $requiredMaterials[$i] . "','','" . $imagePath . "','" . $datetime . "','" . $userid . "',1,'clarify','" . $id . "','" . $material_condition[$i] . "','" . $material_qty[$i] . "')";
                if (mysqli_query($con, $faultyDetailsSql)) {

                    mysqli_query($con, "insert into vendormaterialrequest(vendorId, vendorName, siteid, atmid, engineerId, engineerName, materialName, materialCondition, created_at,  created_by, createdByPortal, status,material_qty) 
                    values('" . $RailTailVendorID . "','" . $_SESSION['ADVANTAGE_username'] . "','" . $siteid . "','" . $atmid . "','" . $userid . "','" . $SERVICE_email . "','" . $requiredMaterials[$i] . "','" . $material_condition[$i] . "','" . $datetime . "','" . $userid . "','Clarify',1,'" . $material_qty[$i] . "')");
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                }
            }
        }
    }




    mysqli_query($con, "update mis set status='material_requirement' WHERE id = $id");




    $statusRemark .= '
                    </table>
                    <br/>
                    <hr />
                    ';



    $statement = "insert into mis_history (mis_id,type,material,material_condition,remark,status,created_at,created_by,dependency,lho,actionDate,actionTime) 
                    values('" . $id . "','" . $status . "','" . $requiredMaterial . "','" . $material_conditionStr . "','" . $remarks . $statusRemark . "','1','" . $datetime . "','" . $userid . "','Advantage','" . $lho . "','".$actionDate."','".$actionTime."')";







} else if ($status == 'reassign') {

    mysqli_query($con, "update mis set status='reassign' WHERE id = $id");



    $ProblemOccurs = $_REQUEST['noProblemOccurs'];
    $ProblemOccursStr = implode(',', $ProblemOccurs);
    $year = date('Y');
    $month = date('m');
    $target_dir = 'reassign_uploads/' . $year . '/' . $month . '/' . $atmid;
    $link = "";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Set appropriate permissions (modify as needed)
    }


    $image = $_FILES['image']['name'];
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . '/' . $image)) {
        $link = $target_dir . '/' . $image;
    }

    $statement = "insert into mis_history (mis_id,type,attachment,remark,status,created_at,created_by,dependency,ProblemOccurs,lho,actionDate,actionTime) 
                    values('" . $id . "','" . $status . "','" . $link . "','" . $remarks . "','1','" . $datetime . "','" . $userid . "','Bank','" . $ProblemOccursStr . "','" . $lho . "','".$actionDate."','".$actionTime."')";



    $statusRemark = '
    <hr />
    <br/>
    <table border="1" width="100%">
        <tr>
            <th style="background: red;color: white;" scope="row"> Updated Status </th>
            <td> Bank Dependency </td>
        </tr>
        <tr>
            <th style="background: red;color: white;" scope="row"> Updated  </th>
            <td>' . $ProblemOccursStr . '</td>
        </tr>
    </table>
    <br/>
    <hr />
    ';


} else if ($status == 'replace_with_available') {

    $materialToReplace = $_REQUEST['materialToReplace'];
    foreach ($materialToReplace as $materialToReplaceKey => $materialToReplaceValue) {

        echo '$materialToReplaceValue = ' . $materialToReplaceValue;
        echo '<br>';
        // $serialNumberValidator = mysqli_query($con,"select * from inventory where ");    

    }
}


if ($statement) {
    if (mysqli_query($con, $statement)) {
        if ($status == 'reopen') {
            $status = 'open';
        } else {
            $status = $_POST['status'];
        }


        // echo '123'; 

        mysqli_query($con, "update mis_details  set status = '" . $status . "' where mis_id = '" . $id . "'");

        $messageId = '<' . uniqid() . '@clarify.advantagesb.com>'; // Generate a unique ID
        $mail->MessageID = $messageId;

        $emailQuery = "INSERT INTO emails (subject, content_body, from_email, is_reply,message_id,`references`,created_at,mis_id) 
        VALUES ('" . $subject . "', '" . $remarks . $statusRemark . "', 'noc@advantagesb.com', 0,'" . $messageId . "','" . $originalMessageID . "','" . $datetime . "','" . $id . "' )";

        if (mysqli_query($con, $emailQuery)) {

            $emailid = $con->insert_id;

            $attachmentQuery = "INSERT INTO attachments (email_id, file_name, file_path) 
            VALUES ('" . $emailid . "', '" . $link . "', '" . $link . "')";
            mysqli_query($con, $attachmentQuery);

            $ccemailtoSendValAR = explode(',', $ccEmail);
            foreach ($ccemailtoSendValAR as $ccemailtoSendValKey => $ccemailtoSendValval) {
                $recipients_sql = "insert into recipients(email_id,recipient_type,recipient_email)
                values('" . $emailid . "','Cc','" . $ccemailtoSendValval . "')
                ";
                mysqli_query($con, $recipients_sql);
            }

            $toemailtoSendValAR = explode(',', $toEmail);
            foreach ($toemailtoSendValAR as $toemailtoSendValARKey => $toemailtoSendValARval) {
                $recipients_sql = "insert into recipients(email_id,recipient_type,recipient_email)
                values('" . $emailid . "','To','" . $toemailtoSendValARval . "')
                ";
                mysqli_query($con, $recipients_sql);

            }
            
            

        }

        $statusRemark = $statusRemark ? $statusRemark : '';

        $mail->Subject = 'Re: ' . $subject;
        $mail->isHTML(true);
        $mail->Body = $remarks . $statusRemark . $emailThread;
        $mail->addCustomHeader('In-Reply-To', '<' . $originalMessageID . '>');



        $attachmentPath = $link;
        $mail->addAttachment($attachmentPath);
        $mail->addCC('noc@advantagesb.com');
       // $mail->addCC('vishwaaniruddh@gmail.com');
        $mail->send();
        
        $response['email_status'] = '1';
        $response['success'] = true;





    }

} else {
    $response['email_status'] = 0;
    $response['success'] = false;
    $response['error'] = $mail->ErrorInfo;
    $response['sql'] = $statement;



}


header('Content-Type: application/json');
echo json_encode($response);