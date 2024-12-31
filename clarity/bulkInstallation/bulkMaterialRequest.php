<?php
include ('../header.php');
require '../vendor/autoload.php'; // Include the autoload.php from PhpSpreadsheet


// return;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (!function_exists('getVendorID')) {

    function getVendorID($name)
    {
        global $con;
        $sql = mysqli_query($con, "select * from vendor where vendorName like '%" . $name . "' and status=1");
        $sql_result = mysqli_fetch_assoc($sql);
        return $sql_result['id'];

    }
}



use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    ?>
    <a href="../inventory/materialSent.php" class="btn btn-primary">Go back</a>

    <?

    // Handle file upload
    if (isset($_FILES['zipFile']) && $_FILES['zipFile']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../PHPExcel/';
        $uploadFile = $uploadDir . basename($_FILES['zipFile']['name']);

        move_uploaded_file($_FILES['zipFile']['tmp_name'], $uploadFile);

        // Read Excel file
        $spreadsheet = IOFactory::load($uploadFile);



        $worksheet = $spreadsheet->getActiveSheet();

        // Get total number of rows
        $totalRows = $worksheet->getHighestRow();





        $sql = mysqli_query($con, "insert into sendmaterialinbatch(clarityStatus,vendorStatus,status,created_at,created_by) values('Material Send Status Initiated','',1,'" . $datetime . "','" . $userid . "')");
        $batchId = $con->insert_id;


        for ($i = 2; $i <= $totalRows; $i++) {


            $atmid = $worksheet->getCell('A' . $i)->getValue();
            $send_date = $worksheet->getCell('B' . $i)->getValue();
            $material = $worksheet->getCell('C' . $i)->getValue();

            $serial_number = $worksheet->getCell('D' . $i)->getValue();

            $qty = $worksheet->getCell('E' . $i)->getValue();
            $challan_no = $worksheet->getCell('F' . $i)->getValue();
            $vendorName = $worksheet->getCell('G' . $i)->getValue();

            $vendorId = getVendorID($vendorName);

            $contactPersonName = $worksheet->getCell('H' . $i)->getValue();
            $contactPersonNumber = $worksheet->getCell('I' . $i)->getValue();
            $address = $worksheet->getCell('J' . $i)->getValue();
            $pod = $worksheet->getCell('K' . $i)->getValue();
            if ($pod == 0) {
                $pod = 'By Hand';
            }
            $courier = $worksheet->getCell('L' . $i)->getValue();
            $remarks = $worksheet->getCell('M' . $i)->getValue();
            $owner = $worksheet->getCell('N' . $i)->getValue();

            $uploadType = 'Bulk';


            //check previously send material

            // echo "select * from material_send where atmid='" . $atmid . "' and portal in ('Clarity','clarity')" ; 
            $checksql = mysqli_query($con, "select * from material_send where atmid='" . $atmid . "' and portal in ('Clarity','clarity')");
            if ($checksql_result = mysqli_fetch_assoc($checksql)) {
                $id = $checksql_result['id'];
                mysqli_query($con, "delete from material_send_details where materialSendId='" . $id . "'");
                echo "delete from material_send where id='" . $id . "'" ; 
                echo '<br />';
                mysqli_query($con, "delete from material_send where id='" . $id . "'");
            } else {


                if ($atmid && $vendorId) {
                    $sql = mysqli_query($con, "select * from sites where atmid='" . $atmid . "'");
                    $sql_result = mysqli_fetch_assoc($sql);
                    $siteid = $sql_result['id'];
                    $lho = $sql_result['LHO'];



                    $materialInsert_sql = "insert into material_send(atmid, siteid, vendorId, contactPersonName, contactPersonNumber, address, pod, courier, remark, 
                created_at, isDelivered,portal,lho,created_by,uploadType,owner,batchId,challan_no,status)
                values('" . $atmid . "', '" . $siteid . "', '" . $vendorId . "', '" . $contactPersonName . "', '" . $contactPersonNumber . "', '" . $address . "', 
                '" . $pod . "', '" . $courier . "', '" . $remarks . "', '" . $datetime . "', '0','Clarity','" . $lho . "','" . $userid . "',
                '" . $uploadType . "','" . $owner . "','" . $batchId . "','" . $challan_no . "',1)
                ";

                    mysqli_query($con, $materialInsert_sql);
                    $materialSendId = $con->insert_id;
                }
                if ($material) {

                    if ($serial_number == 0) {
                        $checkinventoryqty = mysqli_fetch_assoc(mysqli_query($con, "select count(1) as total from inventory where material='" . $material . "' limit $qty"))['total'];
                        // $checkinventoryqty = mysqli_fetch_assoc(mysqli_query($con, "select count(1) as total from inventory where material='" . $material . "' and status=1 limit $qty"))['total'];
                        // echo $material . ' ' . $checkinventoryqty;

                        if ($checkinventoryqty >= $qty) {
                            // $inv_sql = mysqli_query($con, "select * from inventory where material like '%" . $material . "%' and status=1 limit $qty");

                            $inv_sql = mysqli_query($con, "select * from inventory where material like '%" . $material . "%'  limit $qty");
                            while ($inv_sql_result = mysqli_fetch_assoc($inv_sql)) {
                                $invID = $inv_sql_result['id'];

                                $send_material_detail_sql = "insert into material_send_details(materialSendId, attribute, value,serialNumber,material_qty,invID,uploadType,created_at) 
                            values('" . $materialSendId . "','" . $material . "','" . $serial_number . "','" . $serial_number . "',1,'" . $invID . "','Bulk','" . $datetime . "')";

                                mysqli_query($con, $send_material_detail_sql);
                                mysqli_query($con, "update inventory set status=0 where id='" . $invID . "'");


                            }

                        } else {
                            echo 'Material : ' . $material . ' Not in Stock !';
                        }


                    } else {
                        $checkinventoryqty = mysqli_fetch_assoc(mysqli_query($con, "select count(1) as total from inventory where material='" . $material . "' and serial_no like '%" . $serial_number . "%' and status=1 limit $qty"))['total'];
                        // echo $material . ' ' . $checkinventoryqty;


                        if ($checkinventoryqty >= $qty) {
                            $inv_sql = mysqli_query($con, "select * from inventory where material like '%" . $material . "%' and serial_no like '%" . $serial_number . "%' and status=1 limit $qty");
                            if ($inv_sql_result = mysqli_fetch_assoc($inv_sql)) {
                                $invID = $inv_sql_result['id'];
                                $send_material_detail_sql = "insert into material_send_details(materialSendId, attribute, value,serialNumber,material_qty,invID,uploadType,created_at) 
                            values('" . $materialSendId . "','" . $material . "','" . $serial_number . "','" . $serial_number . "',1,'" . $invID . "','Bulk','" . $datetime . "')";

                                mysqli_query($con, $send_material_detail_sql);
                                mysqli_query($con, "update inventory set status=0 where id='" . $invID . "'");

                            } else {
                                echo 'Material : ' . $material . ' With Serial Number : ' . $serial_number . ' is not Found !';

                            }

                        } else {
                            echo 'Material : ' . $material . ' With Serial Number : ' . $serial_number . ' is not available !';
                        }
                    }

                    echo '<br />';
                }



            }

        }


    } else {
        echo 'Error uploading file.';
    }

    ?>
    <a href="../inventory/materialSent.php" class="btn btn-primary">Go back</a>
<?
}
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin"
        style="    padding: 2%;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">

        <p style="text-align:right;"><a href="../excelformats/BulkMaterialDispatchFormatVendor.xlsx" download>Excel
                Format</a></p>
        <h2>Upload Excel File</h2>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="zipFile">Select File:</label>
            <input type="file" name="zipFile" class="form-control" required>
            <hr>
            <input type="submit" value="Upload">
        </form>

    </div>
</div>

<?php
include ('../footer.php');
?>