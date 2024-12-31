<? include('../header.php'); ?>


<div class="card">
    <div class="card-block">

        <?

        // Add pagination and filters
        $recordsPerPage = 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $vendorFilter = isset($_GET['vendor']) ? $_GET['vendor'] : '';
        $atmidFilter = isset($_GET['atmid']) ? $_GET['atmid'] : '';
        $ticketIdFilter = isset($_GET['ticketid']) ? $_GET['ticketid'] : '';

        $pendingInstallationSql = "SELECT * FROM projectInstallation WHERE isDone = 0";
        $pendingInstallationSqlCount = "SELECT count(1) as totalRecords FROM projectInstallation WHERE isDone = 0";
        
        
        
        
            if ($assignedLho) {
                $pendingSql = "select p.vendor,p.atmid,p.created_at,p.sbiTicketId,v.status from projectInstallation p
                                INNER JOIN sites s ON p.atmid = s.atmid LEFT JOIN vendor v ON p.vendor = v.id
                                where p.isDone=0 and v.status=1 and s.LHO like '" . $assignedLho . "' ";
                $pendingInstallationSqlCount = "select count(1) as totalRecords from projectInstallation p
                                INNER JOIN sites s ON p.atmid = s.atmid LEFT JOIN vendor v ON p.vendor = v.id
                                where p.isDone=0 and v.status=1 and s.LHO like '" . $assignedLho . "' ";


            } else if ($_SESSION['isVendor'] == 1) {
                $pendingSql = "select p.vendor,p.atmid,p.created_at,p.sbiTicketId,v.status
                                from projectInstallation p LEFT JOIN vendor v ON p.vendor = v.id
                                where isDone=0 and v.status=1 and p.vendor='".$_GLOBAL_VENDOR_ID."' ";
                $pendingInstallationSqlCount = "select count(1) as totalRecords from projectInstallation p LEFT JOIN vendor v ON p.vendor = v.id
                                where isDone=0 and v.status=1 and p.vendor='".$_GLOBAL_VENDOR_ID."' ";
                                
            }

            else {
                $pendingSql = "select p.vendor,p.atmid,p.created_at,p.sbiTicketId,v.status
                                from projectInstallation p LEFT JOIN vendor v ON p.vendor = v.id
                                where isDone=0 and v.status=1 ";
                                

                $pendingInstallationSqlCount = "select count(1) as totalRecords from projectInstallation p LEFT JOIN vendor v ON p.vendor = v.id
                                where isDone=0 and v.status=1 ";
                                
                                
            }
        
        
        
        
        
        if (!empty($vendorFilter)) {
            $pendingSql .= " AND p.vendor = '$vendorFilter'";
            $pendingInstallationSqlCount .= " AND p.vendor = '$vendorFilter'";
        }
        if (!empty($atmidFilter)) {
            $pendingSql .= " AND p.atmid = '$atmidFilter'";
            $pendingInstallationSqlCount .= " AND p.atmid = '$atmidFilter'";
        }
        if (!empty($ticketIdFilter)) {
            $pendingSql .= " AND p.sbiTicketId = '$ticketIdFilter'";
            $pendingInstallationSqlCount .= " AND p.sbiTicketId = '$ticketIdFilter'";
        }
        $pendingSql .= " ORDER BY p.created_at ASC ";

        $sqlStatement = $pendingSql;

        $pendingSql = "$sqlStatement LIMIT $offset, $recordsPerPage";
        $pendingInstallationSql = mysqli_query($con, $pendingSql);

        $totalRecordsResult = mysqli_query($con, $pendingInstallationSqlCount);
        $totalRecordsData = mysqli_fetch_assoc($totalRecordsResult);
        $totalRecords = $totalRecordsData['totalRecords'];
        $totalPages = ceil($totalRecords / $recordsPerPage);
        ?>


        <!-- Add filter form above the table -->
        <form method="GET" action="">
            <!--<input type="text" name="vendor" placeholder="Filter by Vendor">-->
            <div class="row">
                <div class="col-sm-3">
                    <label>Vendor</label>
                    <select name="vendor" class="form-control">
                        <option value="">Select</option>
                        <?
                        $vendorSql = mysqli_query($con, "select distinct(vendor) as vendor from projectInstallation where status=1");
                        while ($vendorSql_result = mysqli_fetch_assoc($vendorSql)) { ?>
                            <option value="<? echo $vendorSql_result['vendor']; ?>" <? if ($vendorSql_result['vendor'] == $_REQUEST['vendor']) {
                                   echo 'selected';
                               } ?>>
                                <? echo getVendorName($vendorSql_result['vendor']); ?>
                            </option>
                        <? } ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>ATMID</label>
                    <input type="text" name="atmid" placeholder="Filter by Atmid" value="<? echo $_REQUEST['atmid']; ?>"
                        class="form-control">
                </div>
                <div class="col-sm-3">
                    <label>SBIN TICKET ID</label>
                    <input type="text" name="ticketid" value="<? echo $_REQUEST['ticketid']; ?>"
                        placeholder="Filter by SBIN Ticket ID" class="form-control">
                </div>
                <div class="col-sm-3"></div>

                <div class="col-sm-3">
                    <br />
                    <input type="submit" value="Filter" class="btn btn-primary">
                </div>

            </div>





        </form>



        <hr />
        <h3 class="strong">Intallation Calls with Most Pending time </h3>
        <form action="exportPendingSites.php" method="POST">

            <input type="hidden" name="exportSql" value="<? echo $sqlStatement; ?>">
            <input type="submit" name="exportsites" class="btn btn-primary" value="Export">
        </form>
        <hr />
        <div class="pendingInstallationDashboard overflow_auto">


            <table class="table table-bordered table-striped table-hover dataTable js-exportable no-footer table-xs">
                <thead>
                    <tr class="table-primary">
                        <th> Vendor </th>
                        <th> Atmid </th>
                        <th> Pending From </th>
                        <th> Duration </th> <!-- New column -->
                        <th> SBIN Ticket ID </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($pendingInstallationSqlResult = mysqli_fetch_assoc($pendingInstallationSql)) {
                        $vendorId = $pendingInstallationSqlResult['vendor'];
                        $vendorName = getVendorName($vendorId);
                        $atmid = $pendingInstallationSqlResult['atmid'];
                        $created_at = $pendingInstallationSqlResult['created_at'];
                        $sbiTicketId = $pendingInstallationSqlResult['sbiTicketId'];

                        // Calculate the duration using PHP's DateTime class
                        $createdAtDateTime = new DateTime($created_at);
                        $currentDateTime = new DateTime();
                        $durationInterval = $createdAtDateTime->diff($currentDateTime);
                        $duration = $durationInterval->format('%d days, %h hours, %i minutes');
                        ?>

                        <tr>
                            <td class="strong">
                                <?= $vendorName; ?>
                            </td>
                            <td class="strong">
                                <?= $atmid; ?>
                            </td>
                            <td>
                                <?= $created_at; ?>
                            </td>
                            <td>
                                <?= $duration; ?>
                            </td> <!-- Display the calculated duration -->
                            <td>
                                <?= $sbiTicketId; ?>
                            </td>
                        </tr>

                        <?php
                        $i++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <br />




        <?
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $queryParams = $_GET;
            $queryParams['page'] = $i;
            $queryString = http_build_query($queryParams);
            echo '<li><a href="?' . $queryString . '">' . $i . '</a></li>';
        }
        echo '</ul>';
        ?>



    </div>
</div>
</div>
</div>


</div>
</div>
</div>


<? include('../footer.php'); ?>