<?php
include('../config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$pod = $_POST['pod'];
$vendorid = $_POST['vendorid'];
$challanNumber = $_POST['challanNumber'];
$receiversName = $_POST['receiversName'];
$receiversNumber = $_POST['receiversNumber'];
$receivedDate = $_REQUEST['receivedDate'];
$receivedTime = $_REQUEST['receivedTime'];

// Function to generate a unique filename
function generateUniqueFileName($originalFileName)
{
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    return uniqid() . '.' . $extension;
}

// Initialize response variables
$responseStatus = 200;
$responseMessage = 'Updated Successfully';

// Create the upload directory path based on year and month
$year = date('Y');
$month = date('m');

// Store errors during the loop
$errors = [];



$materialSql  = mysqli_query($con,"select * from material_send where pod like '".$pod."' and vendorId='" . $RailTailVendorID . "' and portal='clarity'");
while($materialSqlResult = mysqli_fetch_assoc($materialSql)) {
    $id = $materialSqlResult['id'];
    $atmid = $materialSqlResult['atmid'];
    $siteid = $materialSqlResult['siteid'];
    
    
    
    
    
    
    $uploadDir = "uploadData/trackingUpdates/$year/$month/$atmid/";

      if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $errors[] = "Failed to create directory: $uploadDir";
        }
    }
    
    
    
    
    $lrCopyPath = "";
    $deliveryChallanPath = "";
    
    
    if (is_dir($uploadDir) && is_writable($uploadDir)) {

        $lrCopyName = basename($_FILES['lrCopy']['name']);
        $lrCopyPath = $uploadDir . $lrCopyName;
        
        $deliveryChallanName = basename($_FILES['deliveryChallan']['name']);
        $deliveryChallanPath = $uploadDir . $deliveryChallanName;
        
        move_uploaded_file($_FILES['lrCopy']['tmp_name'], $lrCopyPath);
        move_uploaded_file($_FILES['deliveryChallan']['tmp_name'], $deliveryChallanPath);
        
    } else {
        echo "Directory does not exist or is not writable.";
        // Try to create the directory if not exists
        if (!mkdir($uploadDir, 0755, true)) {
            $errors[] = "Failed to create directory: $uploadDir";
        }
    }

        if (empty($errors)) {

            $sql = "INSERT INTO trackingDetailsUpdate (materialSendId, atmid, siteid, challanNumber, receiversName, receiversNumber, lrCopyPath, deliveryChallanPath, portal, status, receivedDate, receivedTime)
                    VALUES ('$id', '$atmid', '$siteid', '$challanNumber', '$receiversName', '$receiversNumber', '$lrCopyPath', '$deliveryChallanPath', 'vendor', 1, '$receivedDate', '$receivedTime')";
        
            if ($con->query($sql) !== TRUE) {
                $errors[] = 'Failed to insert tracking details for ' . $atmid . ': ' . $con->error;
            }
        }

    // Update material_send table only if there are no errors
    if (empty($errors)) {
        mysqli_query($con,"update material_send set isDelivered=1 where id='".$id."'");
        confirmMaterialReceive($siteid, $atmid, '');
    }
}

// Close the database connection
$con->close();

// Set response status based on errors
if (!empty($errors)) {
    $responseStatus = 500;
    $responseMessage = implode(', ', $errors);
}

// Send the final response
$data = ['status' => $responseStatus, 'response' => $responseMessage];
echo json_encode($data);
?>
