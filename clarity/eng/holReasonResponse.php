<? include('../config.php');

$vendorSql = mysqli_query($con,"select * from user where userid='".$userid."'");
$vendorSqlResult = mysqli_fetch_assoc($vendorSql);
$vendorId = $vendorSqlResult['vendorid'];
$vendorName = getVendorName($vendorId);


$atmid = $_REQUEST['atmid'];
$dependency = $_REQUEST['dependency'];
$electricalIssue = $_REQUEST['electricalIssue'];
$hardwareDependency = $_REQUEST['hardwareDependency'];
$powerIssue = $_REQUEST['powerIssue'];
$siteid = $_REQUEST['siteid'];
$softwareDependency = $_REQUEST['softwareDependency'];
$upsIssue = $_REQUEST['upsIssue'];
$engineerName = $_REQUEST['engineerName'];
$engineerid = $_REQUEST['engineerid'];
$holdRemark = $_REQUEST['holdRemark'];

$sql = "INSERT INTO holdInstallation (siteid, atmid, vendorId, vendorName, customerDependency,powerIssue,upsIssue,electricalDependency, hardwareDependency,  softwareDependency, engineerId,engineerName,created_at,created_by,status,portal,holdRemark) 
        VALUES ('".$siteid."', '".$atmid."', '".$vendorId."', '".$vendorName."', '".$dependency."','".$powerIssue."','".$upsIssue."','".$electricalIssue."',  '".$hardwareDependency."', '".$softwareDependency."', '".$engineerid."','".$engineerName."','".$datetime."','".$userid."',1,'Eng','".$holdRemark."')" ;




if (mysqli_query($con,$sql)) {
    echo json_encode(200);
    projectTeamInstallationHold($siteid,$atmid,'');

    // $checkMaterialSend = mysqli_query($con, "select * from material_send where atmid='" . $atmid . "' order by id desc");
    // $checkMaterialSendResult = mysqli_fetch_assoc($checkMaterialSend);
    // $sendid = $checkMaterialSendResult['id'];


    // $sendDetailsSql = mysqli_query($con, "Select * from material_send_details where materialSendId='" . $sendid . "' and attribute='". $hardwareDependency ."'");
    // if ($sendDetailsSqlResult = mysqli_fetch_assoc($sendDetailsSql)) {
    //     $serialNumber = $sendDetailsSqlResult['serialNumber'];
    //     $MaterialID = getInventoryIDBySerialNumber($serialNumber);

    //     $faultyStatement = "insert into faultymaterialrequests(MaterialID,MaterialName,MaterialSerialNumber,materialImage,siteid,atmid,RequestedBy,RequestedFor,description,status,created_at,portal,ticketid) 
    //         values('" . $MaterialID . "','" . $hardwareDependency . "','" . $serialNumber . "','','" . $siteid . "','" . $atmid . "','" . $userid . "','" . $RailTailVendorID . "','',1,'" . $datetime . "','Clarity','')";
    //     if (mysqli_query($con, $faultyStatement)) {
    //     } else {
    //         echo "Error: " . $sql . "<br>" . mysqli_error($con);
    //     }
    // }



    
} else {
    echo json_encode(500);
}

$con->close();
