<?php include('../config.php');
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style;

// Fetch records from the database

$statement = $_REQUEST['exportSql']; 
$sql = mysqli_query($con, $statement);

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
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

// Set column header values
$sheet->setCellValue('A1', 'Sr No');
$sheet->setCellValue('B1', 'Network IP');
$sheet->setCellValue('C1', 'Router IP');
$sheet->setCellValue('D1', 'ATM IP');
$sheet->setCellValue('E1', 'Is Assigned');
$sheet->setCellValue('F1', 'Created At');

// Populate data from database
$row = 2;
while ($sqlResult = mysqli_fetch_assoc($sql)) {
    $sheet->setCellValue('A' . $row, $row - 1);
    $sheet->setCellValue('B' . $row, $sqlResult['network_ip']);
    $sheet->setCellValue('C' . $row, $sqlResult['router_ip']);
    $sheet->setCellValue('D' . $row, $sqlResult['atm_ip']);
    $sheet->setCellValue('E' . $row, ($sqlResult['isAssign'] == 1 ? 'Assigned' : 'Not Assigned'));
    $sheet->setCellValue('F' . $row, $sqlResult['created_at']);
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
