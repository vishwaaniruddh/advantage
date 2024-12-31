<?php
include('../header.php');
require '../vendor/autoload.php'; // Include the autoload.php from PhpSpreadsheet



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
use PhpOffice\PhpSpreadsheet\IOFactory;

?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">

        <?php

        // Function to recursively extract nested zip files
        function extractNestedZips($zipPath, $extractedDir)
        {
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                // Extract the contents of the zip file
                $zip->extractTo($extractedDir);
                $zip->close();

                // Check for nested zip files in the extracted directory
                $extractedFiles = glob($extractedDir . "*");
                foreach ($extractedFiles as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) == "zip") {
                        // Recursively extract nested zip files
                        $nestedZipDir = $extractedDir . pathinfo($file, PATHINFO_FILENAME) . "/";
                        if (!is_dir($nestedZipDir)) {
                            mkdir($nestedZipDir, 0755, true);
                        }
                        extractNestedZips($file, $nestedZipDir);
                    }
                }
            } else {
                echo "Error extracting zip file: $zipPath";
            }
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Define the target directory
            $targetDirectory = "./feasibility/";

            // Get the uploaded file details
            $originalFileName = basename($_FILES["zipFile"]["name"]);

            // Append current date and time to create a unique file name
            $currentDateTime = date('YmdHis');
            $fileName = $currentDateTime . '_' . $originalFileName;

            $targetFilePath = $targetDirectory . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Check if the file is a zip file
            if ($fileType == "zip") {

                // Check if the file already exists
                if (file_exists($targetFilePath)) {
                    echo "File already exists. Please choose a different file.";
                } else {

                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($_FILES["zipFile"]["tmp_name"], $targetFilePath)) {

                        // Extract the main zip file
                        $mainExtractedDir = $targetDirectory . pathinfo($fileName, PATHINFO_FILENAME) . "/";
                        if (!is_dir($mainExtractedDir)) {
                            mkdir($mainExtractedDir, 0755, true);
                        }
                        extractNestedZips($targetFilePath, $mainExtractedDir);

                        // Read the first row of the Excel file
                        $excelFilePath = $mainExtractedDir . 'Feasibility Data format.xlsx';
                        $spreadsheet = IOFactory::load($excelFilePath);
                        $worksheet = $spreadsheet->getActiveSheet();

                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();
                        $lastColumnIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                        // Define variables for each column
                        $atmName = array();
                        $columnName2 = array(); // Replace columnName2 with the actual name of the second column
                        // Add more variables as needed
        
                        for ($row = 2; $row <= $highestRow; $row++) {
                            // Iterate over each column
                            for ($colIndex = 1; $colIndex <= $lastColumnIndex; $colIndex++) {
                                $colLetter = PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                                $cellValue = $worksheet->getCell($colLetter . $row)->getValue();

                                // Assign the value to the corresponding variable based on column name
                                switch ($colLetter) {
                                    case 'B':
                                        $feasibilityDone[] = $cellValue;
                                        break;
                                    case 'C':
                                        $noOfAtm[] = $cellValue;
                                        break;
                                    case 'D':
                                        $ATMID1[] = $cellValue;
                                        break;
                                    case 'E':
                                        $address[] = $cellValue;
                                        break;
                                    case 'F':
                                        $city[] = $cellValue;
                                        break;
                                    case 'H':
                                        $location[] = $cellValue;
                                        break;
                                    case 'I':
                                        $LHO[] = $cellValue;
                                        break;
                                    case 'J':
                                        $state[] = $cellValue;
                                        break;
                                    case 'K':
                                        $atm1status[] = $cellValue;
                                        break;
                                    case 'M':
                                        $operator[] = $cellValue;
                                        break;


                                    case 'N':
                                        $signalStatus[] = $cellValue;
                                        break;
                                    case 'O':
                                        $operator2[] = $cellValue;
                                        break;
                                    case 'P':
                                        $signalStatus2[] = $cellValue;
                                        break;
                                    case 'Q':
                                        $backroomNetworkRemark[] = $cellValue;
                                        break;
                                    case 'R':
                                        $AntennaRoutingdetail[] = $cellValue;
                                        break;
                                    case 'S':
                                        $EMLockPassword[] = $cellValue;
                                        break;
                                    case 'T':
                                        $EMlockAvailable[] = $cellValue;
                                        break;
                                    case 'U':
                                        $NoOfUps[] = $cellValue;
                                        break;
                                    case 'V':
                                        $PasswordReceived[] = $cellValue;
                                        break;
                                    case 'W':
                                        $Remarks[] = $cellValue;
                                        break;

                                    case 'X':
                                        $UPSAvailable[] = $cellValue;
                                        break;
                                    case 'Y':
                                        $UPSBateryBackup[] = $cellValue;
                                        break;
                                    case 'Z':
                                        $UPSWorking1[] = $cellValue;
                                        break;
                                    case 'AA':
                                        $UPSWorking2[] = $cellValue;
                                        break;


                                    case 'AB':
                                        $UPSWorking3[] = $cellValue;
                                        break;
                                    case 'AC':
                                        $backroomDisturbingMaterial[] = $cellValue;
                                        break;
                                    case 'AD':
                                        $backroomDisturbingMaterialRemark[] = $cellValue;
                                        break;
                                    case 'AE':
                                        $backroomKeyName[] = $cellValue;
                                        break;
                                    case 'AF':
                                        $backroomKeyNumber[] = $cellValue;
                                        break;
                                    case 'AG':
                                        $backroomKeyStatus[] = $cellValue;
                                        break;
                                    case 'AH':
                                        $earthing[] = $cellValue;
                                        break;
                                    case 'AI':
                                        $earthingVltg[] = $cellValue;
                                        break;
                                    case 'AJ':
                                        $frequentPowerCut[] = $cellValue;
                                        break;
                                    case 'AK':
                                        $frequentPowerCutFrom[] = $cellValue;
                                        break;

                                    case 'AL':
                                        $frequentPowerCutRemark[] = $cellValue;
                                        break;
                                    case 'AM':
                                        $frequentPowerCutTo[] = $cellValue;
                                        break;

                                    case 'AN':
                                        $nearestShopDistance[] = $cellValue;
                                        break;
                                    case 'AO':
                                        $nearestShopName[] = $cellValue;
                                        break;


                                    case 'AP':
                                        $nearestShopNumber[] = $cellValue;
                                        break;
                                    case 'AQ':
                                        $powerFluctuationEN[] = $cellValue;
                                        break;

                                    case 'AR':
                                        $powerFluctuationPE[] = $cellValue;
                                        break;
                                    case 'AS':
                                        $powerFluctuationPN[] = $cellValue;
                                        break;
                                    case 'AT':
                                        $powerSocketAvailability[] = $cellValue;
                                        break;
                                    case 'AU':
                                        $routerAntenaPosition[] = $cellValue;
                                        break;
                                    case 'AV':
                                        $created_at_date[] = $cellValue;
                                        break;
                                    case 'AW':
                                        $created_at_datetime[] = $cellValue;
                                        break;


                                    case 'AX':
                                        $routerAntenaSnap[] = $cellValue;
                                        break;
                                    case 'AY':
                                        $AntennaRoutingSnap[] = $cellValue;
                                        break;
                                    case 'AZ':
                                        $UPSAvailableSnap[] = $cellValue;
                                        break;

                                    case 'BA':
                                        $NoOfUpsSnap[] = $cellValue;
                                        break;
                                    case 'BB':
                                        $upsWorkingSnap[] = $cellValue;
                                        break;
                                    case 'BC':
                                        $powerSocketAvailabilitySnap[] = $cellValue;
                                        break;
                                    case 'BD':
                                        $earthingSnap[] = $cellValue;
                                        break;
                                    case 'BE':
                                        $powerFluctuationSnap[] = $cellValue;
                                        break;
                                    case 'BF':
                                        $remarksSnap[] = $cellValue;
                                        break;
                                    case 'BG':
                                        $ATMID1Snap[] = $cellValue;
                                        break;
                                    case 'BH':
                                        $backroomNetworkSnap[] = $cellValue;
                                        break;
                                    case 'BI':
                                        $backroomNetworkSnap2[] = $cellValue;
                                        break;
                                    case 'BJ':
                                        $powerSocketAvailabilityUPSSnap[] = $cellValue;
                                        break;
                                    case 'BK':
                                        $backroomDisturbingMaterialSnap[] = $cellValue;
                                        break;
                                    case 'BL':
                                        $routerPositionSnap[] = $cellValue;
                                        break;
                                }
                            }
                        }



                        $counter = 0;
                        foreach ($ATMID1 as $key => $val) {



                            $checksql = mysqli_query($con, "select * from sites where atmid like '" . $val . "'");
                            if ($checksql_result = mysqli_fetch_assoc($checksql)) {
                                $siteid = $checksql_result['id'];

                                $rawDatetime = $created_at_datetime[$counter];

                                if (is_int($rawDatetime)) {
                                    $excelDatetime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDatetime);
                                    $mysqlDatetime = $excelDatetime->format('Y-m-d H:i:s');
                                } elseif (is_string($rawDatetime)) {
                                    $mysqlDatetime = $rawDatetime;
                                }






                                $mainExtractedDir . $ATMID1[$counter] . '/' . $ATMID1[$counter] . '/' . $routerAntenaSnap[$counter];


                                if (
                                    isset($noOfAtm[$counter]) && isset($ATMID1[$counter]) && isset($city[$counter]) && isset($location[$counter]) && isset($LHO[$counter]) && isset($state[$counter]) &&
                                    !empty($noOfAtm[$counter]) && !empty($ATMID1[$counter]) && !empty($city[$counter]) && !empty($location[$counter]) && !empty($LHO[$counter]) && !empty($state[$counter])
                                ) {



                                    echo $sql = "insert into feasibilityCheck(siteid,ATMID1,ATMID2,ATMID3,AntennaRoutingdetail,EMLockPassword,EMlockAvailable,LHO,NoOfUps,PasswordReceived,Remarks,UPSAvailable,
                                UPSBateryBackup,UPSWorking1,UPSWorking2,UPSWorking3,address,atm1Status,atm2Status,atm3Status,backroomDisturbingMaterial,backroomDisturbingMaterialRemark,
                                backroomKeyName,backroomKeyNumber,backroomKeyStatus,backroomNetworkRemark,backroomNetworkRemark2,city,earthing,earthingVltg,frequentPowerCut,frequentPowerCutFrom,frequentPowerCutRemark,
                                frequentPowerCutTo,location,nearestShopDistance,nearestShopName,nearestShopNumber,noOfAtm,operator,operator2,powerFluctuationEN,powerFluctuationPE,powerFluctuationPN,
                                powerSocketAvailability,routerAntenaPosition,signalStatus,signalStatus2,state,status,created_at,powerSocketAvailabilityUPS,created_by,feasibilityDone,isVendor,routerPosition) values(
                                '" . $siteid . "','" . $ATMID1[$counter] . "','','','" . $AntennaRoutingdetail[$counter] . "','" . $EMLockPassword[$counter] . "','" . $EMlockAvailable[$counter] . "','" . $LHO[$counter] . "','" . $NoOfUps[$counter] . "','" . $PasswordReceived[$counter] . "',
                                '" . $Remarks[$counter] . "','" . $UPSAvailable[$counter] . "','" . $UPSBateryBackup[$counter] . "','" . $UPSWorking1[$counter] . "','" . $UPSWorking2[$counter] . "','" . $UPSWorking3[$counter] . "','" . $address[$counter] . "','" . $atm1Status[$counter] . "','',
                                '','" . $backroomDisturbingMaterial[$counter] . "','" . $backroomDisturbingMaterialRemark[$counter] . "','" . $backroomKeyName[$counter] . "','" . $backroomKeyNumber[$counter] . "',
                                '" . $backroomKeyStatus[$counter] . "','" . $backroomNetworkRemark[$counter] . "','" . $backroomNetworkRemark2[$counter] . "','" . $city[$counter] . "','" . $earthing[$counter] . "','" . $earthingVltg[$counter] . "','" . $frequentPowerCut[$counter] . "','" . $frequentPowerCutFrom[$counter] . "',
                                '" . $frequentPowerCutRemark[$counter] . "','" . $frequentPowerCutTo[$counter] . "','" . $location[$counter] . "','" . $nearestShopDistance[$counter] . "','" . $nearestShopName[$counter] . "','" . $nearestShopNumber[$counter] . "','" . $noOfAtm[$counter] . "',
                                '" . $operator[$counter] . "','" . $operator2[$counter] . "','" . $powerFluctuationEN[$counter] . "','" . $powerFluctuationPE[$counter] . "','" . $powerFluctuationPN[$counter] . "','" . $powerSocketAvailability[$counter] . "','" . $routerAntenaPosition[$counter] . "',
                                '" . $signalStatus[$counter] . "','" . $signalStatus2[$counter] . "','" . $state[$counter] . "',1,'" . $mysqlDatetime . "','" . $powerSocketAvailabilityUPS[$counter] . "','" . $userid . "','" . $feasibilityDone[$counter] . "','1','" . $routerPosition[$counter] . "'
                                )";


                                    if (mysqli_query($con, $sql)) {
                                        $insertid = $con->insert_id;
                                    }

                                    $currentYear = date('Y');
                                    $currentMonth = date('m');

                                    $singalrouterAntenaSnap = '../bulk/' . $mainExtractedDir . $val . '/' . $val . '/'  . $routerAntenaSnap[$counter];

                                    $singalAntennaRoutingSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val . '/'  . $AntennaRoutingSnap[$counter];
                                    $singalUPSAvailableSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val . '/' . $UPSAvailableSnap[$counter];
                                    $singalNoOfUpsSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val . '/'  . $NoOfUpsSnap[$counter];
                                    $singalupsWorkingSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val . '/' . $upsWorkingSnap[$counter];
                                    $singalpowerSocketAvailabilitySnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val . '/' . $powerSocketAvailabilitySnap[$counter];
                                    $singalearthingSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $earthingSnap[$counter];
                                    $singalpowerFluctuationSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $powerFluctuationSnap[$counter];
                                    $singalremarksSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $remarksSnap[$counter];
                                    $singalATMID1Snap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $ATMID1Snap[$counter];
                                    $singalbackroomNetworkSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $backroomNetworkSnap[$counter];
                                    $singalbackroomNetworkSnap2 = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $backroomNetworkSnap2[$counter];
                                    $singalpowerSocketAvailabilityUPSSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $powerSocketAvailabilityUPSSnap[$counter];
                                    $singalbackroomDisturbingMaterialSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $backroomDisturbingMaterialSnap[$counter];
                                    $singalrouterPositionSnap = '../bulk/' . $mainExtractedDir .$val . '/' . $val .  '/' . $routerPositionSnap[$counter];

                                    echo '<br />';
                                    echo '<br />';
                                    echo $update = "UPDATE feasibilityCheck SET 
                                                        backroomNetworkSnap = '" . $singalbackroomNetworkSnap . "',
                                                        routerAntenaSnap = '" . $singalrouterAntenaSnap . "',
                                                        AntennaRoutingSnap = '" . $singalAntennaRoutingSnap . "',
                                                        UPSAvailableSnap = '" . $singalUPSAvailableSnap . "',
                                                        NoOfUpsSnap = '" . $singalNoOfUpsSnap . "',
                                                        upsWorkingSnap = '" . $singalupsWorkingSnap . "',
                                                        powerSocketAvailabilitySnap = '" . $singalpowerSocketAvailabilitySnap . "',
                                                        earthingSnap = '" . $singalearthingSnap . "',
                                                        powerFluctuationSnap = '" . $singalpowerFluctuationSnap . "',
                                                        remarksSnap = '" . $singalremarksSnap . "',
                                                        powerSocketAvailabilityUPSSnap = '" . $singalpowerSocketAvailabilityUPSSnap . "',
                                                        backroomNetworkSnap2 = '" . $singalbackroomNetworkSnap2 . "', 
                                                        ATMID1Snap = '" . $singalATMID1Snap . "',
                                                        backroomDisturbingMaterialSnap = '" . $singalbackroomDisturbingMaterialSnap . "', 
                                                        routerPositionSnap = '" . $singalrouterPositionSnap . "'
                                                        WHERE id = '" . $insertid . "'";
                                    echo '<br />';

                                    if (mysqli_query($con, $update)) {

                                        mysqli_query($con, "update sites set isFeasibiltyDone=1 where atmid='" . $val . "' and status=1");
                                        mysqli_query($con, "update delegation set isFeasibilityDone=1 where siteid='" . $siteid . "'");

                                        projectTeamFeasibilityCheck($siteid, $val, '');


                                    }

                                } else {
                                    echo 'Issue Occured !';
                                }

                            }
                            $counter++;
                        }
                        ?>

                        <script>
                            alert('data Recorded !');
                            window.location.href = '../feasibility/feasibilityData.php'
                        </script>
                        <?php


                    } else {
                        echo "Error uploading file.";
                    }
                }
            } else {
                echo "Only zip files are allowed.";
            }


        }
        ?>

<div class="row">
    <div class="col-sm-12 grid-margin" style="    padding: 2%;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">

<p style="text-align:right;"><a href="../excelformats/Feasibility Data format (1).xlsx" download>Excel Format</a></p>
        <h2>Upload Excel File</h2>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="zipFile">Select File:</label>
            <input type="file" name="zipFile" class="form-control" required>
            <hr>
            <input type="submit" value="Upload">
        </form>

    </div>
</div>
<br><br>
<div class="row">
<div class="col-sm-12 grid-margin" style="    padding: 2%;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
        <b>Instruction to make data</b></div>
        
        <img src="./Screenshot (98).png">
    
</div>

     
    </div>
</div>

<?php
include('../footer.php');
?>