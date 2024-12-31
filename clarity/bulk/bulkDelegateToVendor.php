<? 
return ;
include ('../header.php');


require '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excel_file"])) {
    $file_tmp = $_FILES["excel_file"]["tmp_name"];

    // Check if file is uploaded without errors
    if ($file_tmp) {
        
        $spreadsheet = IOFactory::load($file_tmp);
        $worksheet = $spreadsheet->getActiveSheet();

        $columnMapping = [
            'atmid' => 'A',
            'city' => 'B',
        ];

        $columnData = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($columnMapping as $columnName => $columnIndex) {
                $cellValue = $worksheet->getCell($columnIndex . $row->getRowIndex())->getValue();
                $rowData[$columnName] = $cellValue;
            }
            $columnData[] = $rowData;
        }
        foreach ($columnMapping as $columnName => $columnIndex) {
            echo "<th>$columnName</th>";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<h1>Upload Excel File</h1>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="excel_file" accept=".xls, .xlsx">
    <input type="submit" value="Upload">
</form>

<? include ('../footer.php'); ?>