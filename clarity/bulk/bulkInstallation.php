<?php
return ;
include('../header.php');
require '../vendor/autoload.php'; // Include the autoload.php from PhpSpreadsheet



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('getVendorID')) {

    function getVendorID($name)
    {
        global $con;
        $sql = mysqli_query($con, "select * from vendor where vendorName ='" . $name . "'");
        $sql_result = mysqli_fetch_assoc($sql);
        return $sql_result['id'];

    }
}



use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['zipFile']) && $_FILES['zipFile']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../PHPExcel/';
        $uploadFile = $uploadDir . basename($_FILES['zipFile']['name']);

        move_uploaded_file($_FILES['zipFile']['tmp_name'], $uploadFile);

        // Read Excel file
        $spreadsheet = IOFactory::load($uploadFile);



        $worksheet = $spreadsheet->getActiveSheet();

        // Get total number of rows
        echo $totalRows = $worksheet->getHighestRow();

        for ($i = 2; $i <= $totalRows; $i++) {


            echo $atmid = $worksheet->getCell('A' . $i)->getValue();
            $scheduleAtmEngineerName = $worksheet->getCell('B' . $i)->getValue();
            $scheduleAtmEngineerNumber = $worksheet->getCell('C' . $i)->getValue();
            $bankPersonName = $worksheet->getCell('D' . $i)->getValue();
            $bankPersonNumber = $worksheet->getCell('E' . $i)->getValue();
            $backRoomKeyPersonName = $worksheet->getCell('F' . $i)->getValue();
            $backRoomKeyPersonNumber = $worksheet->getCell('G' . $i)->getValue();
            $scheduleDate = $worksheet->getCell('H' . $i)->getValue();
            $scheduleTime = $worksheet->getCell('I' . $i)->getValue();
            $sbiTicketId = $worksheet->getCell('J' . $i)->getValue();
            $port = $worksheet->getCell('K' . $i)->getValue();
            $switch = $worksheet->getCell('L' . $i)->getValue();
            $primaryDNS = $worksheet->getCell('M' . $i)->getValue();
            $alternateDNS = $worksheet->getCell('N' . $i)->getValue();




            $atmWorking1 = $worksheet->getCell('O' . $i)->getValue();
            $vendorName = $worksheet->getCell('P' . $i)->getValue();
            $engineerName = $worksheet->getCell('Q' . $i)->getValue();
            $engineerNumber = $worksheet->getCell('R' . $i)->getValue();
            $routerFixed = $worksheet->getCell('S' . $i)->getValue();
            $routerFixedRemarks = $worksheet->getCell('T' . $i)->getValue();
            $routerStatusRemarks = $worksheet->getCell('U' . $i)->getValue();
            $adaptorStatusRemarks = $worksheet->getCell('V' . $i)->getValue();
            $lanCableInstallRemark = $worksheet->getCell('W' . $i)->getValue();
            $lanCableStatusNotWorkingReasons = $worksheet->getCell('X' . $i)->getValue();
            $lanCableStatusRemark = $worksheet->getCell('Y' . $i)->getValue();
            $antennaRemarks = $worksheet->getCell('Z' . $i)->getValue();
            $antennaStatusRemarks = $worksheet->getCell('AA' . $i)->getValue();
            $gpsRemarks = $worksheet->getCell('AB' . $i)->getValue();
            $gpsStatusRemarks = $worksheet->getCell('AC' . $i)->getValue();
            $wifiRemarks = $worksheet->getCell('AD' . $i)->getValue();
            $wifiStatusRemarks = $worksheet->getCell('AE' . $i)->getValue();
            $airtelSimRemarks = $worksheet->getCell('AF' . $i)->getValue();
            $airtelSimStatusRemarks = $worksheet->getCell('AG' . $i)->getValue();
            $vodafoneSimRemarks = $worksheet->getCell('AH' . $i)->getValue();
            $vodafoneSimStatusRemarks = $worksheet->getCell('AI' . $i)->getValue();
            $jioSimRemarks = $worksheet->getCell('AJ' . $i)->getValue();
            $jioSimStatusRemarks = $worksheet->getCell('AK' . $i)->getValue();
            $vendorStamp = $worksheet->getCell('AL' . $i)->getValue();
            $routerStatus = $worksheet->getCell('AM' . $i)->getValue();
            $adaptorInstalled = $worksheet->getCell('AN' . $i)->getValue();
            $adaptorStatus = $worksheet->getCell('AO' . $i)->getValue();
            $lanCableInstalled = $worksheet->getCell('AP' . $i)->getValue();
            $lanCableStatus = $worksheet->getCell('AQ' . $i)->getValue();
            $antennaInstalled = $worksheet->getCell('AR' . $i)->getValue();
            $gpsInstalled = $worksheet->getCell('AS' . $i)->getValue();
            $gpsStatus = $worksheet->getCell('AT' . $i)->getValue();
            $wifiInstalled = $worksheet->getCell('AU' . $i)->getValue();
            $wifiStatus = $worksheet->getCell('AV' . $i)->getValue();
            $airtelSimInstalled = $worksheet->getCell('AW' . $i)->getValue();
            $airtelSimStatus = $worksheet->getCell('AX' . $i)->getValue();
            $vodafoneSimInstalled = $worksheet->getCell('AY' . $i)->getValue();
            $vodafoneSimStatus = $worksheet->getCell('AZ' . $i)->getValue();

            $jioSimInstalled = $worksheet->getCell('BA' . $i)->getValue();
            $jioSimStatus = $worksheet->getCell('BB' . $i)->getValue();
            $antennaStatus = $worksheet->getCell('BC' . $i)->getValue();


            $vendorID = getVendorID($vendorName);



            if ($atmid) {





                $sitesql = mysqli_query($con, "select * from sites where atmid='" . $atmid . "'");
                if ($sitesqlResult = mysqli_fetch_assoc($sitesql)) {

                    $siteid = $sitesqlResult['id'];
                    $address = $sitesqlResult['address'];
                    $city = $sitesqlResult['city'];
                    $state = $sitesqlResult['state'];
                    $lho = $sitesqlResult['LHO'];


                    if (is_int($scheduleDate)) {
                        $excelDatetime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($scheduleDate);
                        $scheduleDate = $excelDatetime->format('Y-m-d');
                    } elseif (is_string($scheduleDate)) {
                        $scheduleDate = $scheduleDate;

                    }
                    $excelTime = $scheduleTime;
                    $secondsInADay = 24 * 60 * 60;
                    $phpTimeInSeconds = $excelTime * $secondsInADay;
                    $phpTime = gmdate("H:i:s", $phpTimeInSeconds);




                    $sql = "insert into projectinstallation(siteid,atmid,status,created_at,created_by,isDone,remark,vendor,portal,isSentToEngineer,scheduleAtmEngineerName,scheduleAtmEngineerNumber,bankPersonName,bankPersonNumber,backRoomKeyPersonName,backRoomKeyPersonNumber,scheduleDate,scheduleTime,sbiTicketId,port,switch,primaryDNS,alternateDNS,isDoneDate) 
                    values('" . $siteid . "','" . $atmid . "','1','" . $datetime . "','" . $userid . "','1','Bulk','" . $vendorID . "','clarity','1','" . $scheduleAtmEngineerName . "','" . $scheduleAtmEngineerNumber . "','" . $bankPersonName . "','" . $bankPersonNumber . "','" . $backRoomKeyPersonName . "','" . $backRoomKeyPersonNumber . "','" . $scheduleDate . "','" . $phpTime . "','" . $sbiTicketId . "','" . $port . "','" . $switch . "','" . $primaryDNS . "','" . $alternateDNS . "','" . $scheduleDate . "')";



                    if (mysqli_query($con, $sql)) {

                        $materialSql = mysqli_query($con, "select * from material_send where siteid='" . $siteid . "' and atmid='" . $atmid . "' order by id desc");
                        $materialSql_result = mysqli_fetch_assoc($materialSql);

                        $materialSendId = $materialSql_result['id'];


                        $material_send_detailsSql = mysqli_query($con, "select * from material_send_details where materialSendId = '" . $materialSendId . "' and attribute like 'Router'");
                        $material_send_detailsSqlResult = mysqli_fetch_assoc($material_send_detailsSql);
                        $serialNumber = $material_send_detailsSqlResult['serialNumber'];



                        $inv_sql = mysqli_query($con, "SELECT * FROM `inventory` WHERE `serial_no` LIKE '" . $serialNumber . "'");
                        $inv_sql_result = mysqli_fetch_assoc($inv_sql);
                        $material_make = $inv_sql_result['material_make'];
                        $model_no = $inv_sql_result['model_no'];

                        $installationSql = "
insert into installationdata(atmId,address,city,location,lho,state,atmWorking1,vendorName,engineerName,
engineerNumber,routerSerial,routerMake,routerModel,routerFixed,routerFixedRemarks,routerStatusRemarks,
adaptorStatusRemarks,lanCableInstallRemark,lanCableStatusNotWorkingReasons,lanCableStatusRemark,
antennaRemarks,antennaStatusRemarks,gpsRemarks,gpsStatusRemarks,wifiRemarks,wifiStatusRemarks,
airtelSimRemarks,airtelSimStatusRemarks,vodafoneSimRemarks,vodafoneSimStatusRemarks,jioSimRemarks,
jioSimStatusRemarks,vendorStamp,routerStatus,adaptorInstalled,adaptorStatus,lanCableInstalled,
lanCableStatus,antennaInstalled,gpsInstalled,gpsStatus,wifiInstalled,wifiStatus,airtelSimInstalled,
airtelSimStatus,vodafoneSimInstalled,vodafoneSimStatus,jioSimInstalled,jioSimStatus,antennaStatus,created_at)

values('" . $atmid . "','" . $address . "','" . $city . "','" . $address . "','" . $lho . "','" . $state . "','" . $atmWorking1 . "','" . $vendorName . "','" . $engineerName . "','" . $engineerNumber . "','" . $serialNumber . "','" . $material_make . "','" . $model_no . "',
'" . $routerFixed . "','" . $routerFixedRemarks . "','" . $routerStatusRemarks . "','" . $adaptorStatusRemarks . "','" . $lanCableInstallRemark . "','" . $lanCableStatusNotWorkingReasons . "','" . $lanCableStatusRemark . "','" . $antennaRemarks . "',
'" . $antennaStatusRemarks . "','" . $gpsRemarks . "','" . $gpsStatusRemarks . "','" . $wifiRemarks . "','" . $wifiStatusRemarks . "','" . $airtelSimRemarks . "',
'" . $airtelSimStatusRemarks . "','" . $vodafoneSimRemarks . "','" . $vodafoneSimStatusRemarks . "','" . $jioSimRemarks . "','" . $jioSimStatusRemarks . "','" . $vendorStamp . "','" . $routerStatus . "',
'" . $adaptorInstalled . "','" . $adaptorStatus . "','" . $lanCableInstalled . "','" . $lanCableStatus . "','" . $antennaInstalled . "','" . $gpsInstalled . "','" . $gpsStatus . "','" . $wifiInstalled . "','" . $wifiStatus . "',
'" . $airtelSimInstalled . "','" . $airtelSimStatus . "','" . $vodafoneSimInstalled . "','" . $vodafoneSimStatus . "','" . $jioSimInstalled . "','" . $jioSimStatus . "','" . $antennaStatus . "','".$datetime."'
)
";

                        mysqli_query($con, $installationSql);
                    }
                }
            }
        }

        ?>
        <script>
            alert('data Recorded !');
            window.location.href = '../installation/completedInstallation.php'
        </script>
    <?
    } else {
        echo 'Error uploading file.';
    }
}
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">

        <h2>Upload Excel File</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="zipFile">Select File:</label>
            <input type="file" name="zipFile" required>
            <br>
            <input type="submit" value="Upload">
        </form>

    </div>
</div>

<?php
include('../footer.php');
?>