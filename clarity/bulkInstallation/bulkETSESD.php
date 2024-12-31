<?php
include('../header.php');
require '../vendor/autoload.php'; // Include the autoload.php from PhpSpreadsheet



use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;




if(isset($_POST['submit'])){
  
  $date = date('Y-m-d h:i:s a', time());
$only_date = date('Y-m-d');
$user = $_SESSION['logid'];
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

if ($highestRow > 100) {
    echo "You Can't Log more than 100 Calls at a time";
}
if ($highestColumn > 'H') {
    echo "No of Columns are High";
}

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
    'Site Id',
    'Contact Person',
    'Contact Number',
    'Problem or Requirement',
    'Success',
    'Ticket Number'
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



foreach ($rowData as $row) {
 
 
$atmid = $row[0][0];
$esd = $row[0][1];
$asd = $row[0][2];
$engcode = $row[0][3];
$esdFormatted = DateTime::createFromFormat('U', ($esd - 25569) * 86400)->format('Y-m-d H:i:s');

$asdFormatted = DateTime::createFromFormat('U', ($asd - 25569) * 86400)->format('Y-m-d H:i:s');



}
    
    
    
}
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>



<div class="row">
    <div class="col-sm-12 grid-margin" style="    padding: 2%;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">

<p style="text-align:right;"><a href="../excelformats/BulkESDASD.xlsx" download>Excel Format</a></p>
        <h2>Bulk ESD ASD  - Upload Excel File</h2>
        
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="zipFile">Select File:</label>
            <input type="file" name="images" class="form-control" required>
            <hr>
            <input type="submit" value="Upload" name="submit">
        </form>

    </div>
</div>


<?php
include('../footer.php');
?>