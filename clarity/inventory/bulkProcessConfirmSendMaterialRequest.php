<?php include('../config.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$materials = unserialize(html_entity_decode($_POST['materials']));
$vendorId = $_POST['vendorId'];
$contactPersonName = $_POST['contactPersonName'];
$contactPersonNumber = $_POST['contactPersonNumber'];
$address = $_POST['address'];
$pod = $_POST['POD'];
$courier = $_POST['courier'];
$remark = $_POST['remark'];

// Loop through each material set
foreach ($materials as $material) {
    $atmid = $material['atmid'];
    $siteid = $material['siteid'];
    $attributes = $material['attribute'];
    $values = $material['value'];
    $serialNumbers = $material['serialNumber'];

    // Separate attributes with and without serial numbers
    $withSerialAttributes = [];
    $withoutSerialAttributes = [];
    foreach ($attributes as $index => $attribute) {
        $sql = mysqli_query($con, "SELECT * FROM boq WHERE needSerialNumber=1 AND value LIKE '" . trim($attribute) . "'");
        if ($sqlResult = mysqli_fetch_assoc($sql)) {
            $withSerialAttributes[] = $sqlResult['value'];
        } else {
            $withoutSerialAttributes[] = trim($attribute);
        }
    }

    // Insert main material_send record
    $lho = mysqli_fetch_assoc(mysqli_query($con, "SELECT LHO FROM sites WHERE id='" . $siteid . "'"))['LHO'];
    $query = "INSERT INTO material_send (atmid, siteid, vendorId, contactPersonName, contactPersonNumber, address, pod, courier, remark, portal, lho, created_by, challan_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $portal = 'clarity';
    $stmt->bind_param("ssissssssssss", $atmid, $siteid, $vendorId, $contactPersonName, $contactPersonNumber, $address, $pod, $courier, $remark, $portal, $lho, $userid,$pod);
    $stmt->execute();
    $stmt->close();
    $materialSendId = $con->insert_id;

    // Update material_requests status
    mysqli_query($con, "UPDATE material_requests SET status='Material Sent' WHERE siteid='" . $siteid . "' AND isProject=1");

    // Insert details for attributes with serial numbers
    $counter = 0;
    foreach ($withSerialAttributes as $withSerialValue) {
        if ($withSerialValue == 'Router') {
            $routerSerial = $serialNumbers[$counter];
        }
        $query = mysqli_query($con, "INSERT INTO material_send_details (materialSendId, attribute, value, serialNumber) VALUES ('" . $materialSendId . "', '" . $withSerialValue . "', '" . $serialNumbers[$counter] . "', '" . $serialNumbers[$counter] . "')");
        $materialNameAr[] = $withSerialValue;
        $serialNumberAr[] = $serialNumbers[$counter];
        $invUpdate = "UPDATE inventory SET status=0 WHERE serial_no LIKE '" . $serialNumbers[$counter] . "'";
        mysqli_query($con, $invUpdate);
        $counter++;
    }

    // Insert details for attributes without serial numbers
    foreach ($withoutSerialAttributes as $withoutSerialValue) {
        $checkinventory = mysqli_query($con, "SELECT * FROM inventory WHERE material LIKE '%" . $withoutSerialValue . "' AND status=1 ORDER BY id ASC");
        if ($checkinventoryResult = mysqli_fetch_assoc($checkinventory)) {
            $invId = $checkinventoryResult['id'];
            $lowercaseItemName = strtolower($withoutSerialValue);
            $thisNewGeneratedSerialNumber = $routerSerial . '_' . str_replace(' ', '_', $lowercaseItemName);
            $query = mysqli_query($con, "INSERT INTO material_send_details (materialSendId, attribute, value, serialNumber) VALUES ('" . $materialSendId . "', '" . $withoutSerialValue . "', '" . $thisNewGeneratedSerialNumber . "', '" . $thisNewGeneratedSerialNumber . "')");
            $invUpdate = "UPDATE inventory SET serial_no ='" . $thisNewGeneratedSerialNumber . "', status=0 WHERE id='" . $invId . "'";
            mysqli_query($con, $invUpdate);
            $materialNameAr[] = $withoutSerialValue;
            $serialNumberAr[] = $thisNewGeneratedSerialNumber;
        }
    }

    // Send material to vendor
    sendMaterialToVendor($siteid, $atmid, '');

    // Insert into vendorInventory for each serial number
    if (!empty($serialNumberAr)) {
        foreach ($serialNumberAr as $serialNumber) {
            $inventorySql = mysqli_query($con, "SELECT * FROM inventory WHERE serial_no='" . $serialNumber . "'");
            $inventorySqlResult = mysqli_fetch_assoc($inventorySql);

            $material = $inventorySqlResult['material'];
            $material_make = $inventorySqlResult['material_make'];
            $model_no = $inventorySqlResult['model_no'];
            $serial_no = $inventorySqlResult['serial_no'];
            $challan_no = $inventorySqlResult['challan_no'];
            $amount = $inventorySqlResult['amount'];
            $gst = $inventorySqlResult['gst'];
            $amount_with_gst = $inventorySqlResult['amount_with_gst'];

            $vendorInventorySql = "INSERT INTO vendorInventory (vendorId, material, material_make, model_no, serial_no, amount, gst, amount_with_gst, courier_detail, created_at, created_by, status, material_send_id) 
                VALUES ('" . $vendorId . "', '" . $material . "', '" . $material_make . "', '" . $model_no . "', '" . $serial_no . "', '" . $amount . "', '" . $gst . "', '" . $amount_with_gst . "', '" . $courier . "', '" . $datetime . "', '" . $userid . "', 0, '" . $materialSendId . "')";
            $result = mysqli_query($con, $vendorInventorySql);

            if (!$result) {
                $response = ['status' => '500', 'message' => 'Error updating status in the Inventory table'];
                echo json_encode($response);
                exit;
            }
        }
    }
}

$con->close();
$response = ['status' => '200', 'message' => 'Form data saved successfully'];
echo json_encode($response);
?>
