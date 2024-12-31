<?php
ob_start(); // Start output buffering

include('../config.php');
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style;

// Set memory and execution time limits
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300); // 5 minutes

// Fetch records from the database
$statement = $_REQUEST['exportSql'];
$sql = mysqli_query($con, $statement);

if (!$sql) {
    die('Error: ' . mysqli_error($con));
}

// Create a new PhpSpreadsheet instance
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column headers with blue background color and white text color
$headerStyle = [
    'fill' => [
        'fillType' => Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF4287f5'],
    ],
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Style\Border::BORDER_THIN,
        ],
    ],
];

$sheet->getStyle('A1:R1')->applyFromArray($headerStyle);

$sheet->setCellValue('A1', 'Sr No');
$sheet->setCellValue('B1', 'Material');
$sheet->setCellValue('C1', 'Material Make');
$sheet->setCellValue('D1', 'Model No');
$sheet->setCellValue('E1', 'Serial No');
$sheet->setCellValue('F1', 'Challan No');
$sheet->setCellValue('G1', 'Amount');
$sheet->setCellValue('H1', 'GST');
$sheet->setCellValue('I1', 'Amount With GST');
$sheet->setCellValue('J1', 'Courier Detail');
$sheet->setCellValue('K1', 'Tracking Details');
$sheet->setCellValue('L1', 'Date Of Receiving');
$sheet->setCellValue('M1', 'Receiver Name');
$sheet->setCellValue('N1', 'Vendor Name');
$sheet->setCellValue('O1', 'Vendor Contact');
$sheet->setCellValue('P1', 'PO Date');
$sheet->setCellValue('Q1', 'PO Number');
$sheet->setCellValue('R1', 'Type');

// Initialize the row counter
$row = 2;

while ($sqlResult = mysqli_fetch_assoc($sql)) {
    // Set the serial number in the first column
    $sheet->setCellValue('A' . $row, $row - 1);

    // Set the data in the remaining columns
    $sheet->setCellValue('B' . $row, $sqlResult['material']);
    $sheet->setCellValue('C' . $row, $sqlResult['material_make']);
    $sheet->setCellValue('D' . $row, $sqlResult['model_no']);
    $sheet->setCellValue('E' . $row, $sqlResult['serial_no']);
    $sheet->setCellValue('F' . $row, $sqlResult['challan_no']);
    $sheet->setCellValue('G' . $row, $sqlResult['amount']);
    $sheet->setCellValue('H' . $row, $sqlResult['gst']);
    $sheet->setCellValue('I' . $row, $sqlResult['amount_with_gst']);
    $sheet->setCellValue('J' . $row, $sqlResult['courier_detail']);
    $sheet->setCellValue('K' . $row, $sqlResult['tracking_details']);
    $sheet->setCellValue('L' . $row, $sqlResult['date_of_receiving']);
    $sheet->setCellValue('M' . $row, $sqlResult['receiver_name']);
    $sheet->setCellValue('N' . $row, $sqlResult['vendor_name']);
    $sheet->setCellValue('O' . $row, $sqlResult['vendor_contact']);
    $sheet->setCellValue('P' . $row, $sqlResult['po_date']);
    $sheet->setCellValue('Q' . $row, $sqlResult['po_number']);
    $sheet->setCellValue('R' . $row, $sqlResult['inventoryType']);

    $row++;
}

// Apply borders to all cells
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Style\Border::BORDER_THIN,
        ],
    ],
];
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray($styleArray);

// Set auto width for all columns
foreach (range('A', $highestColumn) as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Clear the output buffer before sending the file
ob_end_clean();

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="exported_data.xlsx"');
header('Cache-Control: max-age=0');

// Instantiate PhpSpreadsheet Writer
$writer = new Xlsx($spreadsheet);

// Save the file to output
$writer->save('php://output');

// Exit script
exit();
