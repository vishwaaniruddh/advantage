<?php include('../config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$serializedAttributes = $_POST['attribute'];
$serializedValues = $_POST['values'];
$serializedSerialNumbers = $_POST['serialNumbers'];

$attributes = unserialize($serializedAttributes);
// var_dump($attributes);

$values = unserialize($serializedValues);
$serialNumbers = unserialize($serializedSerialNumbers);

$atmid = $_POST['atmid'];
$siteid = $_POST['siteid'];
$vendorId = $_POST['vendorId'];
$contactPersonName = $_POST['contactPersonName'];
$contactPersonNumber = $_POST['contactPersonNumber'];
$address = $_POST['address'];
$pod = $_POST['POD'];
$courier = $_POST['courier'];
$remark = $_POST['remark'];
$portal = 'vendor';

$prematerialSendId = $_REQUEST['materialSendID'] ; 



// $withoutSerialAttributes = $withSerialAttributes = array();

// foreach ($attributes as $attributesKey => $attributesVal) {
//     $sql = mysqli_query($con, "Select * from boq where needSerialNumber=1 and value like '" . trim($attributesVal) . "'");
//     if ($sqlResult = mysqli_fetch_assoc($sql)) {
//         $withSerialAttributes[] = $sqlResult['value'];
//     } else {
//         $withoutSerialAttributes[] = trim($attributesVal);
//     }
// }

// foreach ($serialNumbers as $serialNumbersKey => $serialNumbersVal) {
//     $serialNumbersValAr[] = $serialNumbersVal;
// }

$lho = mysqli_fetch_assoc(mysqli_query($con, "select LHO from sites where id='" . $siteid . "'"))['LHO'];

// $query = "INSERT INTO material_send (atmid, siteid, vendorId, contactPersonName, contactPersonNumber, address, pod, courier, remark, portal, lho, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// $stmt = $con->prepare($query);

// $stmt->bind_param("sissssssssss", $atmid, $siteid, $vendorId, $contactPersonName, $contactPersonNumber, $address, $pod, $courier, $remark, $portal, $lho, $userid);
// $stmt->execute();
// $stmt->close();



// $materialSendId = $con->insert_id;



$vendorsql = "insert into vendorMaterialSend(atmid,siteid,vendorId,isService,status,materialSendId,isProject,contactPersonName,
contactPersonNumber) 
values('" . $atmid . "','" . $siteid . "','" . $vendorId . "',0,1,'" . $prematerialSendId . "',1,'".$contactPersonName."',
'".$contactPersonNumber."')" ;

if(mysqli_query($con,$vendorsql)){
    $vendorMaterialSendId = $con->insert_id;


    $detailsql = mysqli_query($con,"select * from material_send_details where materialSendId='".$prematerialSendId."'");
    while($detailsql_result = mysqli_fetch_assoc($detailsql)){

        $attribute = $detailsql_result['attribute'];
        $serialNumber = $detailsql_result['serialNumber'];
        
        mysqli_query($con, "insert into vendorMaterialSenddetails(materialSendId,attribute,value,serialNumber) 
        values('" . $vendorMaterialSendId . "','" . $attribute . "','','" . $serialNumber . "')");

        mysqli_query($con,"insert into enginventory(eng_userid, material, serial_no,  courier_detail, tracking_details,  created_at, created_by, status,material_send_id) 
        values('" . $contactPersonName . "','" . $attribute . "', '" . $serialNumber . "', '" . $courier . "', '" . $pod . "', '" . $datetime . "', '" . $userid . "',1,'" . $vendorMaterialSendId . "')");
    }
    
        $response = ['status' => '200', 'message' => 'Form data saved successfully'];
        mysqli_query($con, "update material_requests set status='Material Sent' where siteid='" . $siteid . "' and isProject=1");
    
}else{
    $response = ['status' => '500', 'message' => 'Error updating status in the Inventory table'];    
}

echo json_encode($response);
