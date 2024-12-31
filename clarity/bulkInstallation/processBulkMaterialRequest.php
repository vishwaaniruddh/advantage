<?php include('../config.php');
include('../vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;



?>



  <input type="hidden" name="atmid" value="<?php echo $atmid; ?>">
                        <input type="hidden" name="siteid" value="<?php echo $siteid; ?>">
                        <input type="hidden" name="vendorId" value="<?php echo $vendorId; ?>">
                        <input type="hidden" name="attribute" value="<?php echo htmlentities(serialize($attributes)); ?>">
                        <input type="hidden" name="values" value="<?php echo htmlentities(serialize($values)); ?>">
                        <input type="hidden" name="serialNumbers"
                            value="<?php echo htmlentities(serialize($serialNumbers)); ?>">
                            
                            
                            
                            
                            <?


$target_dir = '../PHPExcel/';
$file_name = $_FILES["images"]["name"];
$file_tmp = $_FILES["images"]["tmp_name"];
$file = $target_dir . '/' . $file_name;

move_uploaded_file($file_tmp, $file);

try {
    $spreadsheet = IOFactory::load($file);
} catch (Exception $e) {
    die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
}

$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();



$rowData = [];

for ($row = 2; $row <= $highestRow; $row++) {
    $rowData[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
}


$headerStyles = [
    'font' => [
        'bold' => true, // Make the text bold
        'color' => ['rgb' => 'FFFFFF'], // Font color (white)
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0070C0'], // Background color (blue)
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Border color (black)
        ],
    ],
];


$headers = array(
    'SR NO',
    'ATMID',
    'Material Name',
    'Serial Number (if any)',
    'qty',
    'Success',
);





foreach ($headers as $index => $header) {
    $column = chr(65 + $index); // A, B, C, ...
    $sheet->setCellValue($column . '1', $header);
    $sheet->getStyle($column . '1')->applyFromArray($headerStyles); // Apply styles to the header cell
    $sheet->getColumnDimension($column)->setAutoSize(true); // Auto-fill column width
}


// Initialize the row counter
$i = 2; // Start from row 2 for data
$serial_number = 1; // Initialize the serial number


// var_dump($_REQUEST);



$vendor = $_POST['vendor'];
$contactPersonName = $_POST['contactPersonName'];
$contactPersonNumber = $_POST['contactPersonNumber'];
$address = $_POST['address'];
$POD= $_POST['POD'];
$courier = $_POST['courier'];
$remark = $_POST['remark'];

foreach ($rowData as $row) {
    
                        
                        
    $atmid = $row[0][0];
    if(isset($atmid)){
        
        $getSites = mysqli_query($con,"Select * from sites where atmid='".$atmid."'");
        if($getSitesResult = mysqli_fetch_assoc($getSites)){
        $siteid = $getSitesResult['id'];


        $lho = mysqli_fetch_assoc(mysqli_query($con, "select LHO from sites where atmid='" . $atmid . "'"))['LHO'];
        
        $query = "INSERT INTO material_send (atmid, siteid, vendorId, contactPersonName, contactPersonNumber, address, pod, courier, remark,portal,lho,created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $portal = 'clarity';
        $stmt->bind_param("ssisssssssss", $atmid, $siteid, $vendorId, $contactPersonName, $contactPersonNumber, $address, $pod, $courier, $remark, $portal, $lho, $userid);
        // $stmt->bind_param("ssisssssss", $atmid, $siteid, $vendorId, $contactPersonName, $contactPersonNumber, $address, $pod, $courier, $remark,$portal,$lho);
        $stmt->execute();
        $stmt->close();
        
        $materialSendId = $con->insert_id;
        mysqli_query($con, "update material_requests set status='Material Sent' where siteid='" . $siteid . "' and isProject=1");
        
        
        }else{
            echo 'ATMID ' . $atmid . ' Not exists !' ; 
        }
        
    }




    
                //   $checkIfInstalledSite = mysqli_query($con,"select * from projectInstallation where atmid='".$atmid."'");
                //     if($checkIfInstalledSiteResult = mysqli_fetch_assoc($checkIfInstalledSite)){
                //         echo 'Cannot Send Material for ATMID <span style="color:green">' . $atmid . '</span> . Its a live active site ! <br />' ;
                //     }else{
                
                
                        echo $material = $row[0][1];
                        echo $serialNumber = $row[0][2];
                        echo $qty = $row[0][3];
                        echo '<br>';
                        
                        
                        
                        
                        
                        // $sheet->setCellValue('A' . $i , $serial_number ) ; 
                        // $sheet->setCellValue('B' . $i , $atmid ? $atmid : '' ) ;  
                        // $sheet->setCellValue('C' . $i , $material ) ; 
                        // $sheet->setCellValue('D' . $i , $serialNumber ? $serialNumber : '' ) ;
                        // $sheet->setCellValue('E' . $i , $qty ) ; 
                        // $sheet->setCellValue('F' . $i , 'okay' ) ; 
                        
                        // if(!isset($alert_id)){
                        //     $sheet->setCellValue('G' . $i , 'No' ) ; 
                        // } else {
                        //     $sheet->setCellValue('G' . $i , $createdby ) ;
                        // }
                        
                        
                        

                        
                        
                        
                    // }
    
                        $i++;
                        $serial_number++;
                        
    
    
    
        


}






return ; 



$writer = new Xlsx($spreadsheet);

$tempFile = tempnam(sys_get_temp_dir(), 'Inventory');
$writer->save($tempFile);

// Provide the file as a download to the user
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Bulk_pmcall.xlsx"');
header('Cache-Control: max-age=0');
readfile($tempFile);
mysqli_close($con);
unlink($tempFile);

















?>