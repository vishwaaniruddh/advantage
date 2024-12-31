<?php include ('header.php');


require_once './PHPMailer/src/PHPMailer.php';
require_once './PHPMailer/src/SMTP.php';
require_once './PHPMailer/src/Exception.php';


$hostusername = 'noc@advantagesb.com';
$hostPassword = '4mPZJcl^X@XB';
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();
$emailServer = 'server1.advantagesb.com';
$mail->Host = $emailServer;
$mail->SMTPAuth = true;
$mail->Username = $hostusername;
$mail->Password = $hostPassword;
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->addReplyTo('noc@advantagesb.com');
$mail->setFrom($hostusername, 'NOC Advantaesb Team');



?>

<style>
    .border-checkbox-section .border-checkbox-group .border-checkbox-label {
        width: 50%;
    }
    .table tbody tr th {
    white-space: nowrap;
}
 .table tbody tr td {
    white-space: normal;
}
</style>

<link rel="stylesheet" type="text/css" href="./datatable/dataTables.bootstrap.css">
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">
                            <h5>SITE INFORMATION</h5>
                            <hr>
                            <?
                            $id = $_GET['id'];
                            $sql = mysqli_query($con, "select * from mis  where id= '" . $id . "'");
                            $sql_result = mysqli_fetch_assoc($sql);
                            $mis_id = $sql_result['id'];
                            $atmid = $sql_result['atmid'];
                            
                            $branch = $sql_result['branch'];
                            $subject = $sql_result['subject'];
                            $originalMessageID = $sql_result['message_id'];
                            $location = $sql_result['location'];
                            $bank = $sql_result['bank'];
                            $city = $sql_result['city'];
                            $state = $sql_result['state'];
                            $status = $sql_result['status'];
                            $zone = $sql_result['zone'];




                            $sitesSql = mysqli_query($con, "select * from sites where atmid='" . $atmid . "' order by id desc");
                            $sitesSqlResult = mysqli_fetch_assoc($sitesSql);
                            $siteid = $sitesSqlResult['id'];

                            $date = date('Y-m-d');



                            $detail_history = mysqli_query($con, "select * from mis_history  where mis_id = '" . $id . "' ");
                            $fetch_detail_history = mysqli_fetch_assoc($detail_history);

                            // echo "select * from mis where id = '" . $mis_id . "'";
                            $sql1 = mysqli_query($con, "select * from mis_details where mis_id = '" . $mis_id . "'");
                            $sql1_result = mysqli_fetch_assoc($sql1);
                            $ticketid = $sql1_result['ticket_id'];

                            




$originalMISID = $mis_id ; 
                            $emailThread = '';
// echo "select * from emails where mis_id = '" . $id . "' order by email_id desc" ;
$emailTrailSql = mysqli_query($con, "select * from emails where mis_id = '" . $mis_id . "' order by email_id desc");
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
'.PHP_EOL.'
    <p><b>From : </b> ' . $fromMail . ' </p>
    '.PHP_EOL.'
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






                            mysqli_query($con, "update mis set isRead='read' where id='" . $mis_id . "'");
                            ?>
                            <div class="view-info">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="general-info">
                                            <div class="row">
                                                <div class="col-lg-12 col-xl-6">
                                                    <div class="table-responsive">
                                                        <table class="table m-0">
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">Ticket ID </th>
                                                                    <td>
                                                                        <? echo $ticketid; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">ATM ID</th>
                                                                    <td>
                                                                        <span>
                                                                            <? echo $atmid ; ?>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Bank</th>
                                                                    <td>
                                                                        <? echo $bank ; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Location</th>
                                                                    <td>

                                                                    <?php 
                                                        $location = breakStringIntoLines($location);
                                                        echo $location; ?>
                                                        
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end of table col-lg-6 -->
                                                <div class="col-lg-12 col-xl-6">
                                                    <div class="table-responsive">
                                                        <table class="table m-0">
                                                            <tbody>
                                                                <tr>
                                                                <tr>
                                                                    <th scope="row">City</th>
                                                                    <td>
                                                                        <? echo $city; ?>
                                                                    </td>
                                                                </tr>

                                                                <th scope="row">State</th>
                                                                <td>
                                                                    <? echo $state; ?>
                                                                </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Zone</th>
                                                                    <td>
                                                                        <? echo $zone; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Status</th>
                                                                    <td>
                                                                        <? echo $status ; ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end of table col-lg-6 -->
                                            </div>
                                            <!-- end of row -->
                                        </div>
                                        <!-- end of general info -->
                                    </div>
                                    <!-- end of col-lg-12 -->
                                </div>

                                <!-- end of row -->
                            </div>
                        </div>
                    </div>



                    <div class="card">
                        <div class="card-block">

                            <h5>CALL INFORMATION</h5>
                            <hr>
                            <?
                            $id = $_GET['id'];
                            $sql = mysqli_query($con, "select * from mis_details  where id= '" . $id . "'");
                            $sql_result = mysqli_fetch_assoc($sql);

                            $mis_id = $sql_result['mis_id'];

                            $mis_status = $sql_result['status'];
                            $status_view = 0;
                            if ($mis_status == 'material_in_process') {
                                $status_view = 1;
                            }

                            $sql1 = mysqli_query($con, "select * from mis where id = '" . $mis_id . "'");
                            $sql1_result = mysqli_fetch_assoc($sql1);

                            $date = date('Y-m-d H:i:s');
                            $date1 = date('Y-m-d');
                            $date1 = date_create($date1);
                            $date2 = date_create($sql_result['created_at']);
                            $diff = date_diff($date1, $date2);



                            $his_sql = mysqli_query($con, "select * from mis_history  where mis_id ='" . $id . "' order by id desc");
                            if($his_sql_result = mysqli_fetch_assoc($his_sql)){

                                $date1_real = $his_sql_result['created_at'];
                                $date1 = date_create($date1_real);
                            }else {
                                $date1_real = date('Y-m-d H:i:s');
                                $date1 = date_create($date1_real);
                            }


                            $date2 = date_create($sql1_result['created_at']);

                            $diff = date_diff($date1, $date2);




                            ?>
                            <div class="view-info">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="general-info">
                                            <div class="row">
                                                <div class="col-lg-12 col-xl-6">
                                                    <div class="table-responsive">
                                                        <table class="table m-0">
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">Ticket ID </th>
                                                                    <td>
                                                                        <? echo $ticketid; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Current Status</th>
                                                                    <td>
                                                                        <? echo $sql_result['status']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Component</th>
                                                                    <td>
                                                                        <? echo $sql_result['component']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Sub Component</th>
                                                                    <td>
                                                                        <? echo $sql_result['subcomponent']; ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end of table col-lg-6 -->
                                                <div class="col-lg-12 col-xl-6">
                                                    <div class="table-responsive">
                                                        <table class="table m-0">
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">Created On</th>
                                                                    <td>
                                                                        <span>
                                                                            <? echo $sql_result['created_at']; ?>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <th scope="row">Created By</th>
                                                                <td>
                                                                    <? echo $sql1_result['serviceExecutive']; ?>
                                                                </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Down Time </th>
                                                                    <td>
                                                                    <?php
                                                                        $formattedDiff = $diff->format("%a days, %h hours, %i minutes, %s seconds");
                                                                        echo $formattedDiff;
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Remark</th>
                                                                    <td>
                                                                        <? echo $sql1_result['remarks']; ?>
                                                                    </td>
                                                                </tr>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end of table col-lg-6 -->
                                            </div>
                                            <!-- end of row -->
                                        </div>
                                        <!-- end of general info -->
                                    </div>
                                    <!-- end of col-lg-12 -->
                                </div>
                                <!-- end of row -->
                            </div>


                        </div>
                    </div>

                    <div class="card" style="display:none;">
                        <div class="card-block">
                            <h5>Change Status</h5>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control" name="status" id="status">

                                        <?php if ($mis_status == 'open' || $mis_status == 'Open') { ?>
                                            <option value="">Select</option>
                                            <option value="reassign"> Bank Dependency </option>
                                            <option value="material_requirement">Material Requirement</option>
                                            <option value="close">Close</option>
                                        <?php }

                                        if ($mis_status == 'material_requirement') { ?>
                                            <option value="">Select</option>
                                            <option value="schedule">Schedule</option>
                                            <option value="material_dispatch">Material Dispatch</option>
                                            <option value="material_in_process">Material in Process</option>
                                            <option value="replace_with_available">Replace With Available Material</option>
                                            <option value="close">Close</option>
                                        <?php }

                                        if ($mis_status == 'replace_with_available') { ?>
                                            <option value="">Select</option>
                                            <option value="close">Close</option>
                                        <? }

                                        if ($mis_status == 'reassign') { ?>
                                            <option value="">Select</option>
                                            <option value="close">Close</option>
                                        <? }



                                        ?>
                                    </select>
                                </div>
                            </div>

                            <hr>




                            <style>
                                html {
                                    /* text-transform: inherit !important; */
                                }
                            </style>
                            <?

                            if (isset($_POST['status'])) {

                                $status = $_POST['status'];
                                if ($status == 'close') {
                                    $remark2 = $_POST['remark2'];
                                    $year = date('Y');
                                    $month = date('m');
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
                                    $remark = $_POST['remark'];
                                    $oldMaterialDetails = $_POST['oldMaterialDetails'];
                                    $statement = "insert into mis_history (mis_id,type,attachment,remark,status,created_at,created_by,remark2) 
                                                values('" . $id . "','" . $status . "','" . $link . "','" . $remark . "','1','" . $date . "','" . $userid . "','" . $remark2 . "')";

                                    mysqli_query($con, "update mis_details set close_date = '" . $date . "' where id = '" . $id . "'");
                                    mysqli_query($con, "update mis set status = '" . $status . "' where id = '" . $mis_id . "'");


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
                                            <td>' . $remark2 . '</td>
                                        </tr>
                                    </table>
                                    <br/>
                                    <hr />
                                    ';



                             





                                } else if ($status == 'material_requirement') {
                                    $remark = $_POST['remark'];
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
                                    values('" . $siteid . "','" . $atmid . "','" . $SERVICE_email . "','Clarify','" . $RailTailVendorID . "','vendor',3,'','" . $datetime . "','" . $userid . "',1,'" . $ticketid . "','clarify','" . $mis_id . "','" . $userid . "');
                                    ";

                                    if (mysqli_query($con, $faultyMaterialRequestSql)) {
                                        $faultyRequestID = $con->insert_id;

                                        for ($i = 0; $i < count($requiredMaterials); $i++) {
                                            $imageFileName = uniqid() . "_" . $_FILES['material_requirement_images']['name'][$i];
                                            $imagePath = $targetDir . '/' . $imageFileName;

                                            move_uploaded_file($_FILES['material_requirement_images']['tmp_name'][$i], $imagePath);



                                            $sendDetailsSql = mysqli_query($con, "Select * from material_send_details where materialSendId='" . $sendid . "' and attribute='" . $requiredMaterials[$i] . "'");
                                            if ($sendDetailsSqlResult = mysqli_fetch_assoc($sendDetailsSql)) {
                                                $serialNumber = $sendDetailsSqlResult['serialNumber'];
                                                $MaterialID = getInventoryIDBySerialNumber($serialNumber);

                                                $faultyDetailsSql = "insert into generatefaultymaterialrequestdetails(requestId, MaterialID, MaterialName, MaterialSerialNumber, materialImage, created_at, created_by, status , materialRequestType,mis_id,materialCondition,material_qty)
                                            values('" . $faultyRequestID . "','" . $MaterialID . "','" . $requiredMaterials[$i] . "','" . $serialNumber . "','" . $imagePath . "','" . $datetime . "','" . $userid . "',1,'clarify','" . $mis_id . "','" . $material_condition[$i] . "','" . $material_qty[$i] . "')";
                                                if (mysqli_query($con, $faultyDetailsSql)) {
                                                    mysqli_query($con, "insert into vendormaterialrequest(vendorId, vendorName, siteid, atmid, engineerId, engineerName, materialName, materialCondition, created_at,  created_by, createdByPortal, status,material_qty) 
                                                values('" . $RailTailVendorID . "','" . $getVendorName . "','" . $siteid . "','" . $atmid . "','" . $userid . "','" . $SERVICE_email . "','" . $requiredMaterials[$i] . "','" . $material_condition[$i] . "','" . $datetime . "','" . $userid . "','Clarify',1,'" . $material_qty[$i] . "')");
                                                } else {
                                                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                                                }
                                            } else {
                                                $faultyDetailsSql = "insert into generatefaultymaterialrequestdetails(requestId, MaterialID, MaterialName, MaterialSerialNumber, materialImage, created_at, created_by, status , materialRequestType,mis_id,materialCondition,material_qty)
                                            values('" . $faultyRequestID . "','','" . $requiredMaterials[$i] . "','','" . $imagePath . "','" . $datetime . "','" . $userid . "',1,'clarify','" . $mis_id . "','" . $material_condition[$i] . "','" . $material_qty[$i] . "')";
                                                if (mysqli_query($con, $faultyDetailsSql)) {

                                                    mysqli_query($con, "insert into vendormaterialrequest(vendorId, vendorName, siteid, atmid, engineerId, engineerName, materialName, materialCondition, created_at,  created_by, createdByPortal, status,material_qty) 
                                                values('" . $RailTailVendorID . "','" . $getVendorName . "','" . $siteid . "','" . $atmid . "','" . $userid . "','" . $SERVICE_email . "','" . $requiredMaterials[$i] . "','" . $material_condition[$i] . "','" . $datetime . "','" . $userid . "','Clarify',1,'" . $material_qty[$i] . "')");
                                                } else {
                                                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                                                }

                                            }
                                        }
                                    }




                                    mysqli_query($con, "update mis set status='material_requirement' WHERE id = $id");

                                    $statement = "insert into mis_history (mis_id,type,material,material_condition,remark,status,created_at,created_by,dependency) 
                                                values('" . $id . "','" . $status . "','" . $requiredMaterial . "','" . $material_conditionStr . "','" . $remark . "','1','" . $date . "','" . $userid . "','Advantage')";
                                } else if ($status == 'reassign') {

                                    $remark = $_REQUEST['remark'];
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

                                    $statement = "insert into mis_history (mis_id,type,attachment,remark,status,created_at,created_by,dependency,ProblemOccurs) 
                                                values('" . $id . "','" . $status . "','" . $link . "','" . $remark . "','1','" . $date . "','" . $userid . "','Bank','" . $ProblemOccursStr . "')";
                                
                                
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

                                if (mysqli_query($con, $statement)) {
                                    if ($status == 'reopen') {
                                        $status = 'open';
                                    } else {
                                        $status = $_POST['status'];
                                    }

                                    mysqli_query($con, "update mis_details  set status = '" . $status . "' where id = '" . $id . "'");





                                    $messageId =  uniqid() . '@clarify.advantagesb.com'; // Generate a unique ID
                                    $mail->MessageID = $messageId;

                                   $emailQuery = "INSERT INTO emails (subject, content_body, from_email, is_reply,message_id,`references`,created_at,mis_id) 
                                        VALUES ('Re: " . $subject . "', '" . $remark . $statusRemark . "', 'noc@advantagesb.com', 0,'" . $messageId . "','" . $originalMessageID . "','" . $datetime . "','" . $originalMISID . "' )";

                                        mysqli_query($con,$emailQuery);
                                        $emailID = $con->insert_id ; 

                                        // echo "update emails set mis_id = '".$originalMISID."' where email_id='".$emailID."'" ; 
                                        mysqli_query($con,"update emails set mis_id = '".$originalMISID."' where email_id='".$emailID."'");





                                    $statusRemark = $statusRemark ? $statusRemark : '';

                                    $mail->Subject = 'Re: ' . $subject;
                                    $mail->isHTML(true);
                                    $mail->Body = $remarks . $statusRemark . $emailThread;
                                    $mail->addCustomHeader('In-Reply-To', '<' . $originalMessageID . '>');
                            
                            
                            
                                    $attachmentPath = $link;
                                    $mail->addAttachment($attachmentPath);
                                    $mail->addCC('noc@advantagesb.com');

                                    $mail->send();                                    



                                    ?>

                                    <script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Call Updated Successfully !',
                                            confirmButtonText: 'OK'
                                        }).then(function () {
                                             window.location.href = "mis_details.php?id=<? echo $id; ?>";
                                        });
                                    </script>
                                <? } else {
                                    echo mysqli_error($con);
                                    ?>
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops !", "Call Updated Error !',
                                            confirmButtonText: 'OK'
                                        }).then(function () {
                                             window.location.href = "mis_details.php?id=<? echo $id; ?>";
                                        });
                                    </script>

                                <? }
                            }

                            ?>

                            <form action="<? echo $_SERVER['PHP_SELF']; ?>?id=<? echo $id; ?>" method="POST"
                                enctype="multipart/form-data">
                                <div class="row extra_highlight" id="status_col"></div>
                            </form>


                        </div>
                    </div>




                    <div class="card">
                        <div class="card-block" style="overflow:scroll;">
                            <h5>CALL DISPATCH INFORMATION</h5>

                            <hr>
                            <table id="example"
                                class="table table-bordered table-striped table-hover dataTable js-exportable no-footer"
                                style="width:100%">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Sn No</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Material Condition</th>

                                        <th>Date</th>
                                        <th>Schedule Date</th>
                                        <th>Require Material Name </th>
                                        <th>Engineer</th>
                                        <th>POD</th>
                                        <th>Action By</th>
                                        <th>Attchement</th>
                                        <th>Attchement 2</th>
                                        <th>Material Delivered Date</th>
                                        <th>Address (Material Requirement)</th>
                                        <th>Serial Number</th>
                                        <th>Dependency</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?
                                    // echo "select * from mis_history  where mis_id ='".$id."'"; 
                                    
                                    $his_sql = mysqli_query($con, "select * from mis_history  where mis_id ='" . $id . "' order by id desc");
                                    $i = 1;
                                    
                                    while ($his_sql_result = mysqli_fetch_assoc($his_sql)) {
                                        $is_material_dept = $his_sql_result['is_material_dept'];
                                        ?>
                                        <tr <? if ($is_material_dept == 1) { ?> style="background-color: #404e67;color:white;"
                                            <? } ?>>
                                            <td>
                                                <? echo $i; ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['type']; ?>
                                            </td>
                                            <td>
                                                
                            <?
                            if ($his_sql_result['type'] == 'Mail Update') {
                                echo 'Auto Update On ticket';
                            } else {
                                echo $his_sql_result['remark'];


                                
                                
                            }
                            // echo $his_sql_result['remark'];

                            ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['material_condition']; ?>
                                            </td>

                                            <td>
                                                <? echo $his_sql_result['created_at']; ?>
                                            </td>
                                            <td>
                                                <? if ($his_sql_result['schedule_date'] != '0000-00-00') {
                                                    echo $his_sql_result['schedule_date'];
                                                } ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['material']; ?>
                                            </td>
                                            <td>
                                                <? echo getUsername($his_sql_result['engineer'], true); ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['pod']; ?>
                                            </td>

                                            <td>
                                                <? echo getUsername($his_sql_result['created_by'], true); ?>
                                            </td>
                                            <td>
                                                <? if ($his_sql_result['attachment']) { ?><a
                                                        href="<? echo 'mis/'.$his_sql_result['attachment']; ?>">View
                                                        Attchment</a>
                                                <? } ?>
                                            </td>
                                            <td>
                                                <? if ($his_sql_result['attachment2']) { ?><a
                                                        href="<? echo 'mis/'.$his_sql_result['attachment2']; ?>">View
                                                        Attchment</a>
                                                <? } ?>
                                            </td>

                                            <td>
                                                <? if ($his_sql_result['delivery_date'] != '0000-00-00') {
                                                    echo $his_sql_result['delivery_date'];
                                                } ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['delivery_address']; ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['serial_number']; ?>
                                            </td>
                                            <td>
                                                <? echo $his_sql_result['dependency']; ?>
                                            </td>


                                        </tr>
                                        <? $i++;
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>





<script>
    console.clear();

    function handleCheckboxChange(checkbox, matLoopCount) {
        // Get the corresponding select and input elements based on the matLoopCount
        var selectElement = document.getElementById("select_" + matLoopCount);
        var inputElement = document.getElementById("input_" + matLoopCount);
        var input_qty = document.getElementById("input_qty_" + matLoopCount);



        // Check if the select and input elements exist before setting the 'required' attribute
        if (selectElement && inputElement) {
            if (checkbox.checked) {
                selectElement.required = true;
                inputElement.required = true;
                input_qty.required = true;

            } else {
                selectElement.required = false;
                inputElement.required = false;
                input_qty.required = false;

            }
        }
    }


    $(document).ready(function () {

        $(document).on('change', '#status', function () {

            console.log("Checkbox:", this);

            var status = $(this).val();
            $("#status_col").html('');

            if (status == 'close') {
                var html = `<input type="hidden" name="status" value="close">
                <div class="col-sm-12"><label>Snap</label><br /><input type="file" name="image" required></div>
            <div class="col-sm-12"><br><label>Remark</label><input type="text" name="remark" class="form-control"></div>
            <div class="col-sm-12"><br><label>Resolution</label>
                <select name="remark2" class="form-control" required>
                <option value="Spares Replaced">Spares Replaced</option>
                <option value="Antena relocated">Antenna relocated</option>
                    <option value="Loose connection fixed">Loose connection fixed</option>
                    <option value="Power turned on">Power turned on</option>
                    <option value="Router rebooted">Router rebooted</option>
                    <option value="LAN cable replaced or label fixed (if damaged).">LAN cable replaced or label fixed (if damaged).</option>
                    <option value="Electrical wiring done">Electrical wiring done</option>
                    <option value="SIM replaced">SIM replaced</option>
                    <option value="SIM re-inserted">SIM re-inserted</option>
                    <option value="No issue found">No issue found</option>
                    <option value="VPN Restore">VPN Restore</option>
                    
                    <?
                    $boqSql = mysqli_query($con, "select * from boq where status=1");
                    while ($boqSqlResult = mysqli_fetch_assoc($boqSql)) {
                        $boqValue = $boqSqlResult['value'];
                        ?>
                <option value="<?= $boqValue; ?> Replaced"><?= $boqValue; ?> Replaced</option>
<?


                    }
                    ?>



                </select>
                </div>


            <div class="col-sm-12 oldMaterialDetails" style="display:none;">
            <br />
                <label>Old Material Details</label>
                <select name="oldMaterialDetails" id="oldMaterialDetails" class="form-control">
                  <option>-- Select --</option>
                  <option value="Old Material with Engineer">Old Material with Engineer</option>
                  <option value="Old Material Missing">Old Material Missing</option>
                  <option value="Old Material Scrap">Old Material Scrap</option>
                  <option value="Old Material in Service Center">Old Material in Service Center</option>
                  <option value="Old Material in Branch Office">Old Material in Branch Office</option>
                  <option value="Old Material in Dispached to Mumbai">Old Material in Dispached to Mumbai</option>
                </select>  
                </div>

            <div class="col-sm-12"><br><br><input class="btn btn-primary" value="Close" type="submit" name="submit"></div>`;
            } else if (status == 'material_requirement') {
                var html = `
                <input type="hidden" name="status" value="material_requirement">
                <div class="col-sm-12">
                    <label>Please Select Material </label>
                    <br />
                    <div class="border-checkbox-section" style="margin: auto 40px;">
                    <?
                    $matLoopCount = 1;
                    $mat_sql = mysqli_query($con, "select * from boq where status=1");
                    while ($mat_sqlResult = mysqli_fetch_assoc($mat_sql)) {
                        $value = $mat_sqlResult['value']; ?>
                                                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                                                <input class="border-checkbox" name="requiredMaterial[]" type="checkbox" id="checkbox<?= $matLoopCount; ?>" value="<?= trim($value); ?>">

                                                                                <label class="border-checkbox-label" for="checkbox<?= $matLoopCount; ?>"><?= trim($value); ?></label>

                                                                                <input id="input_qty_<?= $matLoopCount; ?>" type="text" name="material_quantity[]" style="width: 50px;" placeholder="QTY" />
                                                                                <select id="select_<?= $matLoopCount; ?>" name="material_condition[]">
                                                                                    <option value="">Select</option>
                                                                                    <option value="Missing">Missing</option>
                                                                                    <option value="Faulty">Faulty</option>
                                                                                    <option value="Not Installed">Not Installed</option>
                                                                                    <option value="Power Fluctuation">Power Fluctuation</option>
                                                                                </select>

                                                                                <input id="input_<?= $matLoopCount; ?>" type="file" name="material_requirement_images[]" />
                                                                            </div>
                        
                                                                            <? $matLoopCount++;
                    } ?>
                    </div>

                    <div class="col-sm-12">
                        <label>Remarks</label>
                        <input type="text" name="remark" class="form-control" required />
                    </div>
                    <div class="col-sm-12">
                        <br />
                        <input type="submit" name="submit" class="btn btn-primary" />
                    </div>
                    
                </div>
            `;
            } else if (status == 'reassign') {
                var html = `<input type="hidden" name="status" value="reassign">


            <div class="border-checkbox-section highlight" style="width:75%">
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                                    id="checkbox1" value="Ups Wroking">
                                                <label class="border-checkbox-label" for="checkbox1">Ups Not Wroking</label>
                                            </div>
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                                    id="checkbox2" value="No Power Outage">
                                                <label class="border-checkbox-label" for="checkbox2">Power Outage</label>
                                            </div>
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                                    id="checkbox3" value="No Problen in Machine Hardware">
                                                <label class="border-checkbox-label" for="checkbox3">Machine Hardware Issue</label>
                                            </div>

                                        </div>


            <div class="col-sm-12">
                <label>Snap</label><br />
                <input type="file" name="image" required>
            </div>
            <div class="col-sm-12">
            <br />
                <label>Remark</label>
                <input type="text" name="remark" class="form-control">
            </div>

            <div class="col-sm-12">
                        <br />
                        <input type="submit" name="submit" class="btn btn-primary" />
                    </div>
                    
            `;
            } else if (status == 'schedule') {
                var html = `<input type="hidden" name="status" value="schedule">
            <div class="col-sm-4">
            <label>Engineer</label>
            <select name="engineer" class="form-control">
            <option value="">Select</option>
            <? $eng_sql = mysqli_query($con, "select * from vendorusers where level=3 order by name asc");
            while ($eng_sql_result = mysqli_fetch_assoc($eng_sql)) { ?> 
                                                                <option value="<? echo $eng_sql_result['id']; ?>">
                                                                <?= ucwords(strtolower($eng_sql_result['name'])); ?>
                                                                </option> <? } ?>
            
            </select>
            </div>
            <div class="col-sm-4"><label>Remark</label><input type="text" name="remark" class="form-control"></div>
            <div class="col-sm-4"><label>Schedule Date</label><input type="date" name="schedule_date" class="form-control"></div>
            <div class="col-sm-4"><br><input class="btn btn-success" type="submit" name="submit"></div>`;
            } else if (status == 'material_dispatch') {
                var html = `
            <input type="hidden" name="status" value="material_dispatch">
            <div class="col-sm-3">
            <label>Courier Agency</label>
            <input type="text" name="courier" class="form-control">
            </div>
            <div class="col-sm-3">
            <label>POD</label>
            <input type="text" name="pod" class="form-control">
            </div>
            <div class="col-sm-3">
            <label>Dispatch Date</label>
            <input type="date" name="dispatch_date" class="form-control">
            </div>
            <div class="col-sm-3">
            <label>Remark</label>
            <input type="text" name="remark" class="form-control">
            </div>
            <div class="col-sm-4">
            <br>
            <input class="btn btn-primary" type="submit" value="Update" name="submit">
            </div>`;
            } else if (status == 'replace_with_available') {
                var html = `
            <input type="hidden" name="status" value="replace_with_available">
            <div class="col-sm-6">
            <label> <h5>Material</h5> </label>
            </div>

            <div class="col-sm-6">
            <label> <h5>Serial Number</h5> </label>
            </div>
            <?

            $matLoopCount = 1;
            $faultySql = mysqli_query($con, "select * from generatefaultymaterialrequest where mis_id='" . $mis_id . "' and materialRequestType='clarify' and materialRequestLevel=3");
            if ($faultySqlResult = mysqli_fetch_assoc($faultySql)) {
                $generatefaultymaterialrequestID = $faultySqlResult['id'];
                $mat_sql = mysqli_query($con, "select * from generatefaultymaterialrequestdetails where requestId='" . $generatefaultymaterialrequestID . "'");
                while ($mat_sqlResult = mysqli_fetch_assoc($mat_sql)) {
                    $value = $mat_sqlResult['MaterialName'];
                    ?>                     
                                                                            <div class="col-sm-6">
                                                                                <input type="checkbox" name="materialToReplace[]" value="<?= $value; ?>" required>  <?= $value; ?>
                                                                            </div>  
                                                                            <div class="col-sm-6">
                                                                                <input class="form-control" type="text" name="serial_number[]" required>  
                                                                            </div>
                                                                            <br />
                    
                                                                                <?
                }
            }
            ?>
            



            <div class="col-sm-4">
            <br>
            <input class="btn btn-primary" type="submit" value="Update" name="submit">
            </div>`;
            }


            $("#status_col").html(html);
        });

        $(document).on('change', '.border-checkbox', function () {
            // Get the matLoopCount from the checkbox's ID
            var matLoopCount = this.id.replace('checkbox', '');
            handleCheckboxChange(this, matLoopCount);
        });
    });

    function throttle(f, delay) {
        var timer = null;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = window.setTimeout(function () {
                f.apply(context, args);
            },
                delay || 1000);
        };
    }


    $(document).ready(function () {
        $(".js-example-basic-single").select2();
    });


    function setaddress() {
        var address_type = $('#address_type').val();
        if (address_type == 'Branch') {
            $('#address').val('Branch');
            $('#address').attr('readonly', true);
            $('#Contactperson_name').hide();
            $('#Contactperson_mob').hide();
            $('#Contactperson_name_text').attr('required', false);
            $('#Contactperson_mob_text').attr('required', false);
            $('#address').show();
        }
        if (address_type == 'Other') {
            $('#address').val('');
            $('#address').attr('readonly', false);
            $('#Contactperson_name').show();
            $('#Contactperson_mob').show();
            $('#Contactperson_name_text').attr('required', true);
            $('#Contactperson_mob_text').attr('required', true);
            //  $('#address').show();
        }
    }




    $(document).on('keyup', '#address', throttle(function () {
        $("#item_name").html('');
        add = $(this).val();
        $.ajax({
            type: "POST",
            url: 'suggested_address.php',
            data: 'address=' + add,
            success: function (msg) {

                $("#item_name").append(msg);


            }
        });
        //   alert(add);
    }));
</script>






<? include ('footer.php'); ?>

<script src="./datatable/jquery.dataTables.js"></script>
<script src="./datatable/dataTables.bootstrap.js"></script>
<script src="./datatable/dataTables.buttons.min.js"></script>
<script src="./datatable/buttons.flash.min.js"></script>
<script src="./datatable/jszip.min.js"></script>

<script src="./datatable/pdfmake.min.js"></script>
<script src="./datatable/vfs_fonts.js"></script>
<script src="./datatable/buttons.html5.min.js"></script>
<script src="./datatable/buttons.print.min.js"></script>
<script src="./datatable/jquery-datatable.js"></script>