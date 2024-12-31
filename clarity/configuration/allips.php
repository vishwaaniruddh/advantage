<?php include ('../header.php'); ?>

<div class="row">
    <div class="col-sm-12 grid-margin">

        <div class="card">
            <div class="card-body">

                <?php


                // Define pagination variables
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                $records_per_page = isset($_GET['records']) && in_array($_GET['records'], [25, 50, 75, 100]) ? $_GET['records'] : 10;
                $offset = ($page - 1) * $records_per_page;

                // Fetch records from the database with pagination
                $statement = "SELECT * FROM ips WHERE 1 ";

                // Apply filters
                if (!empty($_REQUEST['network_ip'])) {
                    $network_ip = $_REQUEST['network_ip'];
                    $statement .= "AND network_ip LIKE '%$network_ip%' ";
                }
                if (!empty($_REQUEST['router_ip'])) {
                    $router_ip = $_REQUEST['router_ip'];
                    $statement .= "AND router_ip LIKE '%$router_ip%' ";
                }
                if (!empty($_REQUEST['atm_ip'])) {
                    $atm_ip = $_REQUEST['atm_ip'];
                    $statement .= "AND atm_ip LIKE '%$atm_ip%' ";
                }
                if (isset($_REQUEST['isAssign']) && $_REQUEST['isAssign'] !== '') {
                    $isAssign = $_REQUEST['isAssign'];
                    $statement .= "AND isAssign = $isAssign ";
                }




                // Count total records
                // $total_records_query = "SELECT COUNT(*) as total FROM ips WHERE 1";
                // $total_records_result = mysqli_query($con, $total_records_query);
                // $total_records = mysqli_fetch_assoc($total_records_result)['total'];
                
                $withoutLimitsql = $statement;
                $sqlCount = mysqli_query($con, $statement);
                $total_records = mysqli_num_rows($sqlCount);

                $statement .= "LIMIT $offset, $records_per_page";
                $sql = mysqli_query($con, $statement);
                ?>


                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">

                    <div class="row">
                        <div class="col-sm-3">
                            <label for="">Network IP</label>
                            <input type="text" name="network_ip" class="form-control"
                                value="<?php echo isset($_REQUEST['network_ip']) ? $_REQUEST['network_ip'] : (isset($_REQUEST['network_ip']) ? $_REQUEST['network_ip'] : ''); ?>"
                                placeholder="Network IP ...." />
                        </div>
                        <div class="col-sm-3">
                            <label for="">Router IP</label>
                            <input type="text" name="router_ip" class="form-control"
                                value="<?php echo isset($_REQUEST['router_ip']) ? $_REQUEST['router_ip'] : (isset($_REQUEST['router_ip']) ? $_REQUEST['router_ip'] : ''); ?>"
                                placeholder="Router IP ...." />
                        </div>
                        <div class="col-sm-3">
                            <label for="">ATM IP</label>
                            <input type="text" name="atm_ip" class="form-control"
                                value="<?php echo isset($_REQUEST['atm_ip']) ? $_REQUEST['atm_ip'] : (isset($_REQUEST['atm_ip']) ? $_REQUEST['atm_ip'] : ''); ?>"
                                placeholder="ATM IP ...." />
                        </div>
                        <div class="col-sm-3">
                            <label for="">Is Assigned</label>
                            <select name="isAssign" class="form-control">
                                <option value="">Select</option>
                                <option value="0" <?php echo (isset($_REQUEST['isAssign']) && $_REQUEST['isAssign'] == '0') ? 'selected' : (isset($_REQUEST['isAssign']) && $_REQUEST['isAssign'] == '0' ? 'selected' : ''); ?>>Not Assigned</option>
                                <option value="1" <?php echo (isset($_REQUEST['isAssign']) && $_REQUEST['isAssign'] == '1') ? 'selected' : (isset($_REQUEST['isAssign']) && $_REQUEST['isAssign'] == '1' ? 'selected' : ''); ?>>Assigned</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <input type="submit" name="submit" value="Search" class="btn btn-primary">
                </form>

                <hr />

                <div class="div" style="
    display: flex;
    justify-content: space-between;
">
                    <form action="./exportallips.php" method="POST">
                        <input type="hidden" name="exportSql" value="<?= $withoutLimitsql; ?>" />
                        <input type="submit" value="Export" class="btn btn-dark">
                    </form>

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

                </div>

                <div class="clearfix"></div>

                <p>Total Records: <?php echo $total_records; ?></p>

                <table class="table table-bordered table-striped table-hover dataTable js-exportable no-footer"
                    style="width:100%">
                    <thead>
                        <tr class="table-primary">
                            <th>Sr No</th>
                            <th>Network IP</th>
                            <th>Router IP</th>
                            <th>ATM IP</th>
                            <th>Is Assigned</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1 + $offset; // Adjust index based on current page and offset
                        while ($sqlResult = mysqli_fetch_assoc($sql)) {
                            $network_ip = $sqlResult['network_ip'];
                            $router_ip = $sqlResult['router_ip'];
                            $atm_ip = $sqlResult['atm_ip'];
                            $created_at = $sqlResult['created_at'];
                            $isAssign = $sqlResult['isAssign'];

                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $network_ip; ?></td>
                                <td><?php echo $router_ip; ?></td>
                                <td><?php echo $atm_ip; ?></td>
                                <td><?php echo ($isAssign == 1 ? 'Assigned' : 'Not Assigned'); ?></td>
                                <td><?php echo $created_at; ?></td>
                            </tr>
                            <?php
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


    </div>
</div>

<?php include ('../footer.php'); ?>