<?php include ('../config.php');
function uploadImage($tmpFile, $columnName, $directory)
{
    $year = date('Y');
    $month = date('m');
    $uploadDir = "data/$year/$month/$directory/";

    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            error_log("Failed to create directory: $uploadDir");
            return false;
        }
    }
    $filename = uniqid() . '.png';
    $filePath = $uploadDir . $filename;

    if (move_uploaded_file($tmpFile, $filePath)) {
        return $filePath;
    } else {
        return false;
    }
}



$data = $_REQUEST;
function escapeData($data)
{
    global $con;
    return mysqli_real_escape_string($con, $data);
}


$data = $_REQUEST ; 

// echo $data ; 

var_dump($data);

echo '<br />';
echo '<br />';

$routerFixed = $data['routerFixed'];
$routerStatus = $data['routerStatus'];
$adaptorInstalled = $data['adaptorInstalled'];
$adaptorStatus = $data['adaptorStatus'];
$lanCableInstalled = $data['lanCableInstalled'];
$lanCableStatus = $data['lanCableStatus'];
$antennaInstalled = $data['antennaInstalled'];
$antennaStatus = $data['antennaStatus'];
$gpsInstalled = $data['gpsInstalled'];
$gpsStatus = $data['gpsStatus'];
$wifiInstalled = $data['wifiInstalled'];
$wifiStatus = $data['wifiStatus'];
$airtelSimInstalled = $data['airtelSimInstalled'];
$airtelSimStatus = $data['airtelSimStatus'];
$vodafoneSimInstalled = $data['vodafoneSimInstalled'];
$vodafoneSimStatus = $data['vodafoneSimStatus'];
$jioSimInstalled = $data['jioSimInstalled'];
$jioSimStatus = $data['jioSimStatus'];







$data = array_map('trim', $data);
$data = array_map('escapeData', $data);

$siteid = $_REQUEST['siteid'];
$atmid = $_REQUEST['atmid'];

echo $query = "INSERT INTO installationData 
(atmId, address, city, location, lho, state, atmWorking1, vendorName, engineerName, engineerNumber, routerSerial, routerMake, routerModel, 
routerFixedRemarks, routerStatusRemarks, adaptorStatusRemarks, lanCableInstallRemark, 
lanCableStatusNotWorkingReasons, lanCableStatusRemark, antennaRemarks, antennaStatusRemarks, 
gpsRemarks, gpsStatusRemarks, wifiRemarks, wifiStatusRemarks, airtelSimRemarks, airtelSimStatusRemarks, 
vodafoneSimRemarks, vodafoneSimStatusRemarks, jioSimRemarks, jioSimStatusRemarks,  signatureImage,

routerFixed,routerStatus,adaptorInstalled,adaptorStatus,lanCableInstalled,lanCableStatus,antennaInstalled,
antennaStatus,gpsInstalled,gpsStatus,wifiInstalled,wifiStatus,airtelSimInstalled,airtelSimStatus,
vodafoneSimInstalled,vodafoneSimStatus,jioSimInstalled,jioSimStatus

) 
VALUES 
('$data[atmId]', '$data[address]', '$data[city]', '$data[location]',
 '$data[lho]', '$data[state]', '$data[atmWorking1]', '$data[vendorName]', '$data[engineerName]', 
 '$data[engineerNumber]', '$data[routerSerial]', 
 '$data[routerMake]', '$data[routerModel]', '$data[routerFixedRemarks]', '$data[routerStatusRemarks]',
  '$data[adaptorStatusRemarks]', '$data[lanCableInstallRemark]', '$data[lanCableStatusNotWorkingReasons]',
   '$data[lanCableStatusRemark]', '$data[antennaRemarks]', '$data[antennaStatusRemarks]',
    '$data[gpsRemarks]', '$data[gpsStatusRemarks]', '$data[wifiRemarks]', '$data[wifiStatusRemarks]',
     '$data[airtelSimRemarks]', '$data[airtelSimStatusRemarks]', '$data[vodafoneSimRemarks]', 
     '$data[vodafoneSimStatusRemarks]', '$data[jioSimRemarks]', '$data[jioSimStatusRemarks]', 
     '$data[signatureImage]',

'" . $routerFixed . "','" . $routerStatus . "','" . $adaptorInstalled . "','" . $adaptorStatus . "','" . $lanCableInstalled . "','" . $lanCableStatus . "','" . $antennaInstalled . "',
'" . $antennaStatus . "','" . $gpsInstalled . "','" . $gpsStatus . "','" . $wifiInstalled . "','" . $wifiStatus . "','" . $airtelSimInstalled . "','" . $airtelSimStatus . "',
'" . $vodafoneSimInstalled . "','" . $vodafoneSimStatus . "','" . $jioSimInstalled . "','" . $jioSimStatus . "'


)";


return ; 
// Images Insert 
if (mysqli_query($con, $query)) {
    $insertId = mysqli_insert_id($con);

    $fileInputs =   $imageColumns = [
        'vendorStamp',
        'lanCableInstallSnap',
        'lanCableStatusSnap',
        'routerFixedSnaps',
        'routerStatusSnaps',
        'adaptorSnaps',
        'adaptorStatusSnaps',
        'antennaSnaps',
        'antennaStatusSnaps',
        'gpsSnaps',
        'gpsStatusSnaps',
        'wifiSnaps',
        'wifiStatusSnaps',
        'airtelSimSnaps',
        'airtelSimStatusSnaps',
        'vodafoneSimSnaps',
        'vodafoneSimStatusSnaps',
        'jioSimSnaps',
        'jioSimStatusSnaps'
    ];


    foreach ($fileInputs as $inputName) {
        // Check if $_FILES[$inputName]["tmp_name"] is empty or contains only empty strings
        if (!isset($_FILES[$inputName]["tmp_name"]) || (is_array($_FILES[$inputName]["tmp_name"]) && array_filter($_FILES[$inputName]["tmp_name"], 'strlen') === array())) {
            // Handle case when no file is uploaded
            // echo "SELECT $inputName FROM feasibilityCheck WHERE id = '" . $feasibiltyid . "'";
            $getimage = mysqli_query($con, "UPDATE installationData SET $columnName = '$imagePath' WHERE id = '".$insertId."'");

            // $getimage = mysqli_query($con, "SELECT $inputName FROM feasibilityCheck WHERE id = '" . $feasibiltyid . "'");
            $getimageResult = mysqli_fetch_assoc($getimage);
            $uploadedFiles[$inputName] = $getimageResult[$inputName] ? $getimageResult[$inputName] : [];
        } else {
            // Handle case when file is uploaded successfully
            $img = handleUploads($_FILES[$inputName], $targetDir);
            $img = implode(',', $img);
            $uploadedFiles[$inputName] = $img;
        }
    }


    // foreach ($imageColumns as $columnName) {
    //     if (!empty($_FILES[$columnName]['tmp_name'])) {
    //         $imagePath = uploadImage($_FILES[$columnName]['tmp_name'], $columnName, $insertId);

    //         if ($imagePath !== false) {
    //             $updateQuery = "UPDATE installationData SET $columnName = '$imagePath' WHERE id = $insertId";
    //             mysqli_query($con, $updateQuery);
    //         }
    //     }
    // }

    echo json_encode(200);
    // projectTeamInstallation($siteid, $atmid, '');
    // mysqli_query($con, "update projectInstallation set isDone=1 , isDoneDate='" . $date . "' where siteid='" . $siteid . "' and atmid='" . $atmid . "'");
    // mysqli_query($con, "update sites set isDone=1 where atmid='" . $atmid . "'");
    // mysqli_query($con, "update assignedInstallation set isDone=1 where siteid='" . $siteid . "' and atmid='" . $atmid . "'");

} else {
    echo json_encode(500);
}
mysqli_close($con);
?>