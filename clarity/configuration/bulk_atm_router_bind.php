<? include('../header.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">

    <div class="col-sm-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="two_end">
                    <h5>Bulk ROUTER-ATM BIND <span style="font-size:12px; color:red;">(Bulk Upload)</span>
                    </h5>
                    <a class="btn btn-primary" href="../excelformats/ROUTER-ATM_bulk.xlsx" download>
                        BULK ROUTER-ATM BIND FORMAT</a>
                </div>
                <hr>

                <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group row">

                        <div class="col-sm-12">
                            <input type="file" name="images" required>
                            <!--<input type="file" name="images" required>-->
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




    <?
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    ini_set('memory_limit', '-1');
    if (isset($_POST['submit'])) {

        $created_by_name = getUsername($userid);

        echo '<div class="col-sm-12 grid-margin">';
        echo '<div class="card">
                                <div class="card-body">';



        $date = date('Y-m-d h:i:s a', time());
        $only_date = date('Y-m-d');
        $target_dir = '../PHPExcel/';
        $file_name = $_FILES["images"]["name"];
        $file_tmp = $_FILES["images"]["tmp_name"];
        $file = $target_dir . '/' . $file_name;
        $created_at = date('Y-m-d H:i:s');




        move_uploaded_file($file_tmp = $_FILES["images"]["tmp_name"], $target_dir . '/' . $file_name);
        include('../PHPExcel/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');
        $inputFileName = $file;

        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' .
                $e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData[] = $sheet->rangeToArray(
                'A' . $row . ':' . $highestColumn . $row,
                null,
                true,
                false
            );
        }

        $row = $row - 2;
        $error = '0';
        $contents = '';

        for ($i = 1; $i <= $row; $i++) {

            $atmid = $rowData[$i][0][0];
            $serial_no = $rowData[$i][0][1];
            $seal_no = $rowData[$i][0][2];


            $operatorOneName = $rowData[$i][0][3];
            $operatorOneSerialNumber = $rowData[$i][0][4];
            $operatorTwoName = $rowData[$i][0][5];
            $operatorTwoSerialNumber = $rowData[$i][0][6];


            $sql = "SELECT * FROM sites WHERE atmid = '" . $atmid . "'";
            $checkQuery = mysqli_query($con, $sql);
            if ($checkQueryResult = mysqli_fetch_assoc($checkQuery)) {
                $insertQuery = "INSERT INTO routerConfiguration (atmid, serialNumber, sealNumber, status, created_at, created_by)
                                                VALUES ('" . $atmid . "', '" . $serial_no . "', '" . $seal_no . "', '1', '" . $datetime . "', '" . $userid . "')";


                $configurationsql1 = "INSERT into ccidconfiguration(serialNumber,atmid,operator,ccid,status,created_at,created_by,created_by_name) 
VALUES('" . $serial_no . "','" . $atmid . "','" . $operatorOneName . "','" . $operatorOneSerialNumber . "',1,'" . $datetime . "','" . $userid . "','" . $created_by_name . "')";

                mysqli_query($con, $configurationsql1);

                $configurationsql2 = "INSERT into ccidconfiguration(serialNumber,atmid,operator,ccid,status,created_at,created_by,created_by_name) 
VALUES('" . $serial_no . "','" . $atmid . "','" . $operatorTwoName . "','" . $operatorTwoSerialNumber . "',1,'" . $datetime . "','" . $userid . "','" . $created_by_name . "')";

                mysqli_query($con, $configurationsql2);

                try {
                    mysqli_query($con, $insertQuery);
                    echo '
                                    <button class="btn btn-success btn-icon">✔</button>
                                    &nbsp;&nbsp;&nbsp; Serial Number : ' . $serial_no . ' Successfully bind with ATMID : ' . $atmid . '</br>';

                } catch (PDOException $e) {
                    echo '
                                    <i class="feather icon-check bg-simple-c-yellow  update-icon"></i>
                                    &nbsp;&nbsp;&nbsp;Error Serial Number : ' . $serial_no . ' not bind with ATMID : ' . $atmid . '</br>';

                }
            } else {
                echo '
                                    <button class="btn btn-danger btn-icon">✖</button>
                                    &nbsp;&nbsp;&nbsp;Error Serial Number : ' . $serial_no . ' not bind with ATMID : ' . $atmid . '</br>';
            }
        }


        echo '</div>
        </div></div>';
    }
    ?>



    <?

    $sqlappCount = "select count(1) as total from routerConfiguration where 1 ";
    $atm_sql = "select * from routerConfiguration where 1 ";


    if (isset($_REQUEST['serial_no']) && $_REQUEST['serial_no'] != '') {
        $serial_no = $_REQUEST['serial_no'];
        $atm_sql .= "and serialNumber like '%" . $serial_no . "%'";
        $sqlappCount .= "and serialNumber like '%" . $serial_no . "%'";
    }
    if (isset($_REQUEST['atmid']) && $_REQUEST['atmid'] != '') {
        $atmid = $_REQUEST['atmid'];
        $atm_sql .= "and atmid like '%" . $atmid . "%'";
        $sqlappCount .= "and atmid like '%" . $atmid . "%'";
    }



    $atm_sql .= " and status=1 order by id desc";
    $sqlappCount .= " and status=1";

    $page_size = 10;
    $result = mysqli_query($con, $sqlappCount);
    $row = mysqli_fetch_assoc($result);
    $total_records = $row['total'];
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($current_page - 1) * $page_size;
    $total_pages = ceil($total_records / $page_size);
    $window_size = 10;
    $start_window = max(1, $current_page - floor($window_size / 2));
    $end_window = min($start_window + $window_size - 1, $total_pages);
    $sql_query = "$atm_sql LIMIT $offset, $page_size";

    // echo $sql_query ; 
    
    ?>
    <div class="col-sm-12 grid-margin">
        <div class="card">
            <div class="card-header">
                <h5>Total Records: <strong class="record-count">
                        <? echo $total_records; ?>
                    </strong></h5>

                <hr>
                <form action="export_ROUTER_CONFIGURATION.php" method="POST">
                    <input type="hidden" name="exportSql" value="<?= $atm_sql; ?>">
                    <input type="submit" name="exportsites" class="btn btn-primary" value="Export">
                </form>

            </div>



            <div class="card-body" style="overflow:auto;">

                <?
                $i = 1;
                // echo "select * from routerConfiguration where status=1 order by id desc" ; 
                $sql = mysqli_query($con, "select * from routerConfiguration where status=1 order by id desc");
                if (mysqli_num_rows($sql) > 0) {

                    echo '<table class="table table-hover table-styling table-xs" style="width:100%;">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Sr No</th>
                                        <th>Atm id</th>
                                        <th>Serial Number</th>
                                        <th>Network IP</th>
                                        <th>Router IP</th>
                                        <th>ATM IP</th>
                                        <th>Created At</th>
                                        <th>Created By</th>
                                        <th>ATMID ROUTER UNBIND</th>
                                    </tr>
                                </thead>
                                <tbody>';

                    $counter = ($current_page - 1) * $page_size + 1;
                    $sql_app = mysqli_query($con, $sql_query);
                    while ($sql_result = mysqli_fetch_assoc($sql_app)) {

                        $id = $sql_result['id'];
                        $atmid = $sql_result['atmid'];
                        $serialNumber = $sql_result['serialNumber'];
                        $sealNumber = $sql_result['sealNumber'];
                        $created_at = $sql_result['created_at'];

                        $ipconfurationSql = mysqli_query($con, "select * from ipconfuration where serial_no ='" . $serialNumber . "' and status=1 order by id desc");
                        $ipconfurationSqlResult = mysqli_fetch_assoc($ipconfurationSql);

                        $networkIP = $ipconfurationSqlResult['network_ip'];
                        $routerIP = $ipconfurationSqlResult['router_ip'];
                        $atmIP = $ipconfurationSqlResult['atm_ip'];

                        $created_by = $sql_result['created_by'];
                        $created_by = getUsername($created_by, false);


                        echo "<tr>
                                            <td>{$counter}</td>
                                            <td>{$atmid}</td>
                                            <td>{$serialNumber}</td>
                                            <td>{$networkIP}</td>
                                            <td>{$routerIP}</td>
                                            <td>{$atmIP}</td>
                                            <td>{$created_at}</td>
                                            <td>{$created_by}</td>
                                            <td><a href='#' data-bs-toggle='modal' data-bs-target='#unbindModal' data-id='{$id}'>Unbind</a></td>

                                        </tr>";

                        $counter++;
                    }

                    echo "    </tbody>
                            </table>";

                    $serial_no = $_REQUEST['serial_no'];
                    $atmid = $_REQUEST['atmid'];
                    echo '
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" style="margin: auto;"> 
                                <div class="dataTables_paginate paging_simple_numbers" id="example_paginate"><ul class="pagination">';

                    if ($start_window > 1) {

                        echo "<li class='paginate_button'><a href='?page=1&&serial_no=$serial_no&&atmid=$atmid'>First</a></li>";
                        echo '<li class="paginate_button"><a href="?page=' . ($start_window - 1) . '&&serial_no=' . $serial_no . '&&atmid=' . $atmid . '">Prev</a></li>';
                    }

                    for ($i = $start_window; $i <= $end_window; $i++) {
                        ?>
                        <li class="paginate_button <? if ($i == $current_page) {
                            echo 'active';
                        } ?>">
                            <a href="?page=<?= $i; ?>&&serial_no=<?= $serial_no; ?>&&atmid=<?= $atmid; ?>">
                                <?= $i; ?>
                            </a>
                        </li>

                    <? }

                    if ($end_window < $total_pages) {

                        echo '<li class="paginate_button"><a href="?page=' . ($end_window + 1) . '&&serial_no=' . $serial_no . '&&atmid=' . $atmid . '">Next</a></li>';
                        echo '<li class="paginate_button"><a href="?page=' . $total_pages . '&&serial_no=' . $serial_no . '&&atmid=' . $atmid . '">Last</a></li>';
                    }
                    echo '</ul></div>';


                } else {
                    echo '
                                    <div class="noRecordsContainer">
                                    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
                                    <dotlottie-player src="../json/nofound.json" background="transparent" speed="1" loop autoplay style="
                                    height: 400px;
                                    width: 100%;
                                "></dotlottie-player>
                                    
                                    </div>';
                }

                ?>
            </div>
        </div>

    </div>


</div>


<? include('../footer.php'); ?>