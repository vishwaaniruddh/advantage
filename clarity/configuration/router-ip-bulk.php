<?php include ('../header.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="two_end">
                    <h5>Bulk Router + IP <span style="font-size:12px; color:red;">(Bulk Upload)</span></h5>
                    <a class="btn btn-primary" href="../excelformats/ROUTER-ip_bulk.xlsx" download>
                        BULK ROUTER-IP BIND FORMAT
                    </a>
                </div>
                <hr>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="file" name="images" required>
                        </div>
                        <div class="col-sm-4">
                            <br />
                            <input type="submit" name="submit" value="upload" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    ini_set('memory_limit', '-1');
    if (isset($_POST['submit'])) {
        ?>
        <div class="col-sm-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <?php

                    $date = date('Y-m-d h:i:s a', time());
                    $only_date = date('Y-m-d');
                    $target_dir = '../PHPExcel/';
                    $file_name = $_FILES["images"]["name"];
                    $file_tmp = $_FILES["images"]["tmp_name"];
                    $file = $target_dir . '/' . $file_name;
                    $created_at = date('Y-m-d H:i:s');

                    move_uploaded_file($file_tmp, $file);
                    $inputFileName = $file;

                    try {
                        $reader = new Xlsx();
                        $spreadsheet = $reader->load($inputFileName);
                    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                    }

                    $sheet = $spreadsheet->getActiveSheet();
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    $rowData = [];
                    for ($row = 1; $row <= $highestRow; $row++) {
                        $rowData[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                    }

                    $row = $highestRow - 1;
                    $error = '0';
                    $contents = '';

                    for ($i = 1; $i <= $row; $i++) {
                        $networkip = $rowData[$i][0][1];
                        $routerip = $rowData[$i][0][2];
                        $atmip = $rowData[$i][0][3];
                        $subnetmask = $rowData[$i][0][4];

                        $sql = mysqli_query($con, "SELECT * FROM ips WHERE network_ip='" . $networkip . "' AND router_ip='" . $routerip . "' AND atm_ip='" . $atmip . "' AND status=1");
                        if ($sql_result = mysqli_fetch_assoc($sql)) {
                            echo 'Network IP = ' . $networkip .' record already exists';
echo '<br />';
                        } else {
                            $statement = "INSERT INTO ips SET status=1 , isLocked=0, network_ip='" . $networkip . "', router_ip='" . $routerip . "', atm_ip='" . $atmip . "', subnet_ip='" . $subnetmask . "', isAssign=0, created_at='" . $created_at . "', created_by='" . $userid . "'";
                            mysqli_query($con, $statement);
                        }
                    }

                    echo '<hr>';

                    $count = 1;
                    for ($i = 1; $i <= $row; $i++) {

                        $serialnumber = $rowData[$i][0][0];
                        $networkip = $rowData[$i][0][1];
                        $routerip = $rowData[$i][0][2];
                        $atmip = $rowData[$i][0][3];
                        $subnetmask = $rowData[$i][0][4];


                        $sql = mysqli_query($con, "SELECT * FROM ipconfuration WHERE serial_no='" . $serialnumber . "' and network_ip='" . $networkip . "' AND router_ip='" . $routerip . "' AND atm_ip='" . $atmip . "' AND status=1");
                        if ($sql_result = mysqli_fetch_assoc($sql)) {

                            echo $count . ')' . $serialnumber . ' Already Configured ';

                        } else {


                            $ipsql = mysqli_query($con, "SELECT * FROM ips WHERE network_ip='" . $networkip . "' AND router_ip='" . $routerip . "' AND atm_ip='" . $atmip . "' AND status=1");
                            $ipsqlResult = mysqli_fetch_assoc($ipsql);

                            $ipID = $ipsqlResult['id'];



                            // insert 
                             $query = "INSERT INTO ipconfuration(ipID,serial_no,network_ip,router_ip,atm_ip,subnet_ip,created_at,created_by,status,remarks) 
                                      VALUES('" . $ipID . "','" . $serialnumber . "','" . $networkip . "','" . $routerip . "','" . $atmip . "','" . $subnetmask . "','" . $datetime . "','" . $userid . "',1,'Bulk Upload')";

                            mysqli_query($con, $query);
                            echo $count . ') ' . $serialnumber . ' Configuration setting successsfully applied ! ';


                        }

                        echo '<br />';


                        $count++;
                    }






                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include ('../footer.php'); ?>