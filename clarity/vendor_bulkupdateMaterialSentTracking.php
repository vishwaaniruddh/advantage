<?php include ('./config.php');

// Function to generate unique file names
function generateUniqueFileName($originalFileName)
{
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    return uniqid() . '.' . $extension;
}

$challan_no = $_REQUEST['challan'];
$vendor = $_REQUEST['vendor'];

// Check if form is submitted
if (isset($_REQUEST['submit'])) {

    $responseStatus = 200;
    $responseMessage = 'Updated Successfully';

    // Create the upload directory path based on year and month
    $year = date('Y');
    $month = date('m');

    // Store errors during the loop
    $errors = [];

    // Retrieve form data
    $receiversName = $_REQUEST['receiversName'];
    $receiversNumber = $_REQUEST['receiversNumber'];
    $receivedDate = $_REQUEST['receivedDate'];
    $receivedTime = $_REQUEST['receivedTime'];
    $lrCopy = $_FILES['lrCopy'];
    $deliveryChallan = $_FILES['deliveryChallan'];

    // Start transaction
    $con->begin_transaction();

    // echo "SELECT * FROM material_send WHERE challan_no LIKE '$challan_no' AND vendorId='$vendor' AND portal='clarity'";
    // Fetch material_send records based on challan_no and vendorId
    // return;
    $materialSql = mysqli_query($con, "SELECT * FROM material_send WHERE challan_no LIKE '$challan_no' AND vendorId='$vendor' AND portal='clarity'");

    if (!$materialSql) {
        $errors[] = "Failed to fetch material send records: " . $con->error;
    }

    // Generate unique file names and move uploaded files to a temporary location
    $tempDir = sys_get_temp_dir() . '/trackingUpdates/';
    if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
        $errors[] = "Failed to create temporary directory: $tempDir";
    }

    $lrCopyPathTemp = $tempDir . generateUniqueFileName($lrCopy['name']);
    $deliveryChallanPathTemp = $tempDir . generateUniqueFileName($deliveryChallan['name']);

    if (!move_uploaded_file($lrCopy['tmp_name'], $lrCopyPathTemp)) {
        $errors[] = "Failed to upload LR Copy to temporary directory";
    }

    if (!move_uploaded_file($deliveryChallan['tmp_name'], $deliveryChallanPathTemp)) {
        $errors[] = "Failed to upload Delivery Challan to temporary directory";
    }

    while ($materialSqlResult = mysqli_fetch_assoc($materialSql)) {

        $deliveryChallanPath = '';
        $lrCopyPath = '';

        $id = $materialSqlResult['id'];
        $atmid = $materialSqlResult['atmid'];
        $siteid = $materialSqlResult['siteid'];

        // Define upload directory
        $uploadDir = "inventory/uploadData/trackingUpdates/$year/$month/$atmid/";

        // Create upload directory if it does not exist
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            $errors[] = "Failed to create directory: $uploadDir";
            break;
        }

        $lrCopyPath = $uploadDir . basename($lrCopyPathTemp);
        $deliveryChallanPath = $uploadDir . basename($deliveryChallanPathTemp);

        // Copy files from temporary location to final destination
        if (!copy($lrCopyPathTemp, $lrCopyPath)) {
            $errors[] = "Failed to copy LR Copy to $lrCopyPath";
            break;
        }

        if (!copy($deliveryChallanPathTemp, $deliveryChallanPath)) {
            $errors[] = "Failed to copy Delivery Challan to $deliveryChallanPath";
            break;
        }

        // Insert tracking details into trackingDetailsUpdate table if no errors
        if (empty($errors)) {
            $sql = "INSERT INTO trackingDetailsUpdate (materialSendId, atmid, siteid, challanNumber, receiversName, receiversNumber, lrCopyPath, deliveryChallanPath, portal, status, receivedDate, receivedTime)
                    VALUES ('$id', '$atmid', '$siteid', '$challan_no', '$receiversName', '$receiversNumber', '$lrCopyPath', '$deliveryChallanPath', 'vendor', 1, '$receivedDate', '$receivedTime')";

            if ($con->query($sql) !== TRUE) {
                $errors[] = 'Failed to insert tracking details for ' . $atmid . ': ' . $con->error;
                break;
            }
        }

        // Update material_send table only if there are no errors
        if (empty($errors)) {
            if (mysqli_query($con, "UPDATE material_send SET isDelivered=1 WHERE id='$id'") !== TRUE) {
                $errors[] = 'Failed to update material_send for ' . $atmid . ': ' . $con->error;
                break;
            } else {

                $detailSql = mysqli_query($con, "select * from material_send_details where materialSendId='" . $id . "'");
                while ($detailSqlResult = mysqli_fetch_assoc($detailSql)) {
                    $attribute = $detailSqlResult['attribute'];
                    $serialNumber = $detailSqlResult['serialNumber'];
                    $invID = $detailSqlResult['invID'];


                    $vendorEntrysql = "INSERT INTO vendorinventory(vendorId,material,serial_no,date_of_receiving,receiver_name,created_at,created_by,status,material_send_id) 
                    VALUES('" . $vendor . "','" . $attribute . "','" . $serialNumber . "','" . $receivedDate . "','" . $receiversName . "','" . $datetime . "','" . $userid . "',1,'" . $id . "')";
                    mysqli_query($con, $vendorEntrysql);

                }



            }
        }
    }

    // If no errors, commit the transaction
    if (empty($errors)) {
        $con->commit();
    } else {
        // If there are errors, rollback the transaction
        $con->rollback();
        $responseStatus = 500;
        $responseMessage = implode(", ", $errors);
    }

    // Clean up temporary files
    if (file_exists($lrCopyPathTemp)) {
        unlink($lrCopyPathTemp);
    }

    if (file_exists($deliveryChallanPathTemp)) {
        unlink($deliveryChallanPathTemp);
    }

    $con->close();
    $response = ['status' => $responseStatus, 'message' => $responseMessage];
    echo json_encode($response);
    exit;
}
?>

<div class="card">
    <div class="card-block">
        <h5 style="font-weight: 600;">Update Tracking Status: <span style="color:red;"><?php echo $challan_no; ?></span>
        </h5>
        <hr>
        <form
            action="<?php echo $_SERVER['PHP_SELF']; ?>?challan=<?php echo $challan_no; ?>&vendor=<?php echo $vendor; ?>"
            method="POST" enctype="multipart/form-data">
            <input type="hidden" name="challan_no" value="<?php echo $challan_no; ?>" />
            <input type="hidden" name="vendorid" value="<?php echo $vendor; ?>" />
            <div class="row">
                <div class="grid-margin col-sm-12 extra_highlight">
                    <label>LR Copy</label><br />
                    <input type="file" name="lrCopy" required />
                </div>
                <div class="grid-margin col-sm-12 extra_highlight">
                    <label>Delivery Challan</label><br />
                    <input type="file" name="deliveryChallan" required />
                </div>
                <div class="grid-margin col-sm-12">
                    <label>Challan Number</label>
                    <input type="text" name="challanNumber" class="form-control" required />
                </div>
            </div>
            <div class="row">
                <div class="grid-margin col-sm-12">
                    <label>Receivers Name</label>
                    <input type="text" name="receiversName" value="<?php echo $_SESSION['ADVANTAGE_username']; ?>"
                        class="form-control" required />
                </div>
                <div class="grid-margin col-sm-12">
                    <label>Receivers Number</label>
                    <input type="text" name="receiversNumber" value="<?php echo $_SESSION['contact']; ?>"
                        class="form-control" required />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>Received Date</label>
                    <input type="date" class="form-control esd-datetime-input" name="receivedDate"
                        value="<?php echo date('Y-m-d'); ?>" required />
                </div>
                <div class="col-sm-6">
                    <label>Received Time</label>
                    <input type="time" name="receivedTime" class="form-control" required />
                </div>
            </div>
            <div class="row">
                <div class="grid-margin col-sm-12">
                    <br />
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary" />
                </div>
            </div>
        </form>
    </div>
</div>