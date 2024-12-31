<? include ('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


<div class="row">

    <div class="col-sm-12 grid-margin">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <div class="row">
                <div class="col-sm-6">
                    <label for="">Router</label>
                    <input type="text" name="serial_no" class="form-control"
                        value="<?php echo isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : (isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : ''); ?>"
                        placeholder="Serial Number ...." />
                </div>
                <div class="col-sm-6">
                    <label for="">ATMID</label>
                    <input type="text" name="atmid" class="form-control"
                        value="<?php echo isset($_REQUEST['atmid']) ? $_REQUEST['atmid'] : (isset($_REQUEST['atmid']) ? $_REQUEST['atmid'] : ''); ?>"
                        placeholder="ATMID ...." />
                </div>

                <div class="col-sm-6">
                    <br>
                    <input type="submit" name="submit" value="Search" class="btn btn-primary">

                </div>

            </div>

        </form>
    </div>


    <div class="col-sm-12 grid-margin">

        <div style="float:right;">

            <div class="form-group" style="width:200px;">
                <label for="recordsPerPage">Records Per Page:</label>
                <select class="form-control" id="recordsPerPage" onchange="changeRecordsPerPage(this)">
                    <option value="25" <?php echo ($records_per_page == 25 ? 'selected' : ''); ?>>25</option>
                    <option value="50" <?php echo ($records_per_page == 50 ? 'selected' : ''); ?>>50</option>
                    <option value="75" <?php echo ($records_per_page == 75 ? 'selected' : ''); ?>>75</option>
                    <option value="100" <?php echo ($records_per_page == 100 ? 'selected' : ''); ?>>100
                    </option>
                </select>
            </div>
        </div>
        <?php



        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = isset($_GET['records']) && in_array($_GET['records'], [25, 50, 75, 100]) ? $_GET['records'] : 25;
        $offset = ($page - 1) * $records_per_page;




        $statement = "
                SELECT 
                    a.serial_no, 
                    b.atmid, 
                    CASE 
                        WHEN b.serialNumber IS NOT NULL THEN 1 
                        ELSE 0 
                    END AS configured,
                    c.network_ip,
                    c.atm_ip,
                    c.router_ip
                FROM 
                    inventory a
                LEFT JOIN 
                    routerConfiguration b 
                ON 
                    a.serial_no = b.serialNumber 
                    AND b.status = 1
                LEFT JOIN
                    ipconfuration  c
                ON
                    a.serial_no = c.serial_no
                    AND c.status = 1
                WHERE 
                    a.material = 'Router'
    ";


        if (!empty($_REQUEST['serial_no'])) {
            $serial_no = $_REQUEST['serial_no'];
            $statement .= "AND a.serial_no LIKE '%$serial_no%' ";
        }


        if (!empty($_REQUEST['atmid'])) {
            $atmid = $_REQUEST['atmid'];
            $statement .= "AND b.atmid LIKE '%$atmid%' ";
        }



        $withoutLimitsql = $statement;
        $sqlCount = mysqli_query($con, $statement);
        $total_records = mysqli_num_rows($sqlCount);

        $statement .= "LIMIT $offset, $records_per_page";
        $sql = mysqli_query($con, $statement);




        ?>
        <div class="clearfix"></div>

        <p>Total Records: <?php echo $total_records; ?></p>

        <table class="table table-bordered table-striped table-hover dataTable js-exportable no-footer"
            style="width:100%">
            <thead>
                <tr class="table-primary">
                    <th>Sr No</th>
                    <th>Router</th>
                    <th>ATMID</th>
                    <th>Network IP</th>
                    <th>Router IP</th>
                    <th>ATM IP</th>
                    <th>Operator </th>
                </tr>
            </thead>
            <tbody>

                <?
                $i = 1 + $offset; // Adjust index based on current page and offset
                
                while ($sql_result = mysqli_fetch_assoc($sql)) {

                    $serial_no = $sql_result['serial_no'];
                    $atmid = $sql_result['atmid'];

                    $network_ip = $sql_result['network_ip'];
                    $atm_ip = $sql_result['atm_ip'];
                    $router_ip = $sql_result['router_ip'];

                    // echo "SELECT * FROM ccidconfiguration WHERE serialNumber='" . $serial_no . "' AND status=1" ; 
                    $check_existing_conf = mysqli_query($con, "SELECT * FROM ccidconfiguration WHERE serialNumber='" . $serial_no . "' AND status=1");

                    $isConfigurationFound = false;

                    $operator = [];

                    while ($conf_result = mysqli_fetch_assoc($check_existing_conf)) {
                        $isConfigurationFound = true;
                        $operator[] = $conf_result['operator'];
                        $ccid[] = $conf_result['ccid'];
                        $created_by_name[] = $conf_result['created_by_name'];
                    }






                    ?>
                    <tr>
                        <td><b><?php echo $i; ?></b></td>
                        <td style="font-weight:700; color: #0090e7; "><?php echo $serial_no; ?></td>
                        <td><?php echo ($atmid ? $atmid : '<b style="color:red;">Not Configured</b>'); ?></td>
                        <td><?php echo $network_ip; ?></td>
                        <td><?php echo $router_ip; ?></td>
                        <td><?php echo $atm_ip; ?></td>
                        <td>

                            <?

                            if ($atmid && $serial_no) {
                                if ($isConfigurationFound) {

                                    $counter = 0;
                                    foreach ($operator as $operatorkey => $operatorvalue) {


                                        echo '<b>Operator : </b>' . $operatorvalue . '<br />';
                                        echo '<b>CCID : </b>' . $ccid[$counter] . '<br />';
                                        echo '<b>Configured By  : </b>' . $created_by_name[$counter] . '<br />';

                                        $counter++;

                                    }


                                } else { ?>
                                    <a
                                        href="./ccIDConfigure.php?serial_no=<?php echo $serial_no; ?>&atmid=<?php echo $atmid; ?>">Configure</a>

                                <?php }
                            } else {

                                echo '<b style="color:red;">Other Configurations Pending ! </b>';

                            }

                            ?>

                        </td>
                    </tr>
                    <?
                    $i++;

                }

                ?>

            </tbody>
        </table>


        <?php
        $total_pages = ceil($total_records / $records_per_page);


        $network_ip = $_REQUEST['network_ip'];
        $router_ip = $_REQUEST['router_ip'];
        $atm_ip = $_REQUEST['atm_ip'];
        $isAssign = $_REQUEST['isAssign'];


        // Get filter parameters
        $filters = http_build_query(['network_ip' => $network_ip, 'router_ip' => $router_ip, 'atm_ip' => $atm_ip, 'isAssign' => $isAssign]);

        if ($total_pages > 1) {
            echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

            // First page
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=1&records=' . $records_per_page . '&' . $filters . '">First</a></li>';
            }

            // Previous page
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '&records=' . $records_per_page . '&' . $filters . '">Previous</a></li>';
            }

            // Page links
            $start = max(1, $page - 2);
            $end = min($total_pages, $page + 2);
            for ($i = $start; $i <= $end; $i++) {
                echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '&records=' . $records_per_page . '&' . $filters . '">' . $i . '</a></li>';
            }

            // Next page
            if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '&records=' . $records_per_page . '&' . $filters . '">Next</a></li>';
            }

            // Last page
            if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&records=' . $records_per_page . '&' . $filters . '">Last</a></li>';
            }

            echo '</ul></nav>';
        }
        ?>
        <script>
            // Function to change records per page
            function changeRecordsPerPage(select) {
                var selectedRecords = select.value;
                var currentUrl = window.location.href;
                var url = new URL(currentUrl);

                // Get existing filter parameters
                var network_ip = url.searchParams.get("network_ip");
                var router_ip = url.searchParams.get("router_ip");
                var atm_ip = url.searchParams.get("atm_ip");
                var isAssign = url.searchParams.get("isAssign");

                // Construct new URL with updated records per page and existing filter parameters
                var newUrl = '?page=1&records=' + selectedRecords;
                if (network_ip !== null) newUrl += '&network_ip=' + network_ip;
                if (router_ip !== null) newUrl += '&router_ip=' + router_ip;
                if (atm_ip !== null) newUrl += '&atm_ip=' + atm_ip;
                if (isAssign !== null) newUrl += '&isAssign=' + isAssign;

                window.location.href = newUrl;
            }
        </script>

    </div>
</div>


<? include ('../footer.php'); ?>