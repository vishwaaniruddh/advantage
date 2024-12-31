<?
function removeLimitStatement($sqlQuery) {
    // Pattern to match LIMIT statement
    $pattern = '/\bLIMIT\s+\d+(,\s*\d+)?\b/i';
    
    // Remove LIMIT statement from the query
    $sqlQueryWithoutLimit = preg_replace($pattern, '', $sqlQuery);
    
    return $sqlQueryWithoutLimit;
}

?>

<br><br>
<div class="row">
    <div class="col-sm-12">


        <style>
             .dashboard_data td{
                white-space: inherit !important ;
            } 
            
            .pagination {
                margin-top: 20px;
                float: right;
            }

            .pagination a {
                color: #333;
                padding: 8px 12px;
                text-decoration: none;
                border: 1px solid #ccc;
                margin-right: 5px;
            }

            .pagination a.active {
                background-color: #007bff;
                color: #fff;
                border: 1px solid #007bff;
            }

            .pagination a:hover {
                background-color: #f0f0f0;
            }
        </style>

        <div class="card">

        
        <div class="flex" style="display:flex;justify-content:space-between;padding:15px;">

            <h3>Recent Open Calls</h3>


            <form action="./mis/exportMis.php" method="POST">
                <?php
                if ($assignedLho) {

                    $sql = "select a.lho, a.remarks,a.reference_code,a.id,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.id,b.mis_id,b.atmid,
                    b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,      
                    (SELECT name from vendorUsers WHERE id= a.created_by) AS createdBy
                    from mis a INNER JOIN mis_details b ON b.mis_id = a.id 
                    where 1 and 
                    b.mis_id = a.id and a.LHO like '<?php echo $assignedLho; ?>'
                    and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '2024-03-31' and 
                    b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') ";


                } elseif ($_SESSION['isVendor'] == 1) {
                    $sql = "select a.lho, a.remarks,a.reference_code,a.id,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.id,b.mis_id,b.atmid,
        b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,      
        (SELECT name from vendorUsers WHERE id= a.created_by) AS createdBy
        from mis a INNER JOIN mis_details b ON b.mis_id = a.id 
        where 1 and 
        b.mis_id = a.id and a.vendorId like '<?php echo $_GLOBAL_VENDOR_ID; ?>' 
        and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '2024-03-31' and 
        b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update')  ";

                } else {
                    $sql = "select a.lho, a.remarks,a.reference_code,a.id,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.id,b.mis_id,b.atmid,
        b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,      
        (SELECT name from vendorUsers WHERE id= a.created_by) AS createdBy
        from mis a INNER JOIN mis_details b ON b.mis_id = a.id 
        where 1 and 
        b.mis_id = a.id 
        and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '2024-03-31' and 
        b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update')  ";

                }


                if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO']!=='') {
                    $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
                    $sql .= " and a.lho like '" . $_GLOBAL_LHO . "'";
                }




                ?>

                <input type="hidden" name="exportSql" value="<?php echo $sql; ?>" />

                <input type="submit" name="exportMis" class="btn btn-primary" value="Export">
            </form>
        </div>

            <div class="card-block" style="overflow:auto;">
                <?php

                // Number of records per page
                $recordsPerPage = 20;

                // Determine current page
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $recordsPerPage;

                $agingCount = $offset + 1;



                if ($assignedLho) {

                    $sql = "SELECT a.atmid, MAX(c.created_at) as latest_created_at,a.reference_code,a.created_at, a.lho, a.state, a.city, a.location, b.ticket_id, b.id, a.isRead,b.status as dependecy,b.status as detailStatus
                        FROM mis a 
                        INNER JOIN mis_details b ON a.id = b.mis_id 
                        LEFT JOIN mis_history c ON c.mis_id = a.id
                        WHERE b.status <> 'close' and a.lho='" . $assignedLho . "'";

                } else if ($_SESSION['isVendor'] == 1) {
                    $sql = "SELECT a.atmid, MAX(c.created_at) as latest_created_at,a.reference_code,a.created_at, a.lho, a.state, a.city, a.location, b.ticket_id, b.id, a.isRead,b.status as dependecy,b.status as detailStatus
                        FROM mis a 
                        INNER JOIN mis_details b ON a.id = b.mis_id 
                        LEFT JOIN mis_history c ON c.mis_id = a.id
                        WHERE b.status <> 'close' and a.vendorId='" . $_GLOBAL_VENDOR_ID . "' ";


                } else {
                    $sql = "SELECT a.atmid, MAX(c.created_at) as latest_created_at,a.reference_code,a.created_at, a.lho, a.state, a.city, a.location, b.ticket_id, b.id, a.isRead ,b.status as dependecy,b.status as detailStatus
                        FROM mis a 
                        INNER JOIN mis_details b ON a.id = b.mis_id 
                        LEFT JOIN mis_history c ON c.mis_id = a.id
                        WHERE b.status <> 'close' ";

                }

                if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO']!=='') {
                    $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
                    $sql .= " and a.lho like '" . $_GLOBAL_LHO . "'";
                }



               $sql .= " GROUP BY a.atmid  ORDER BY latest_created_at DESC LIMIT $offset, $recordsPerPage";


                $highagingsql = mysqli_query($con, $sql);

                if (mysqli_num_rows($highagingsql) > 0) {

                    echo '
<table class="table table-hover table-styling table-xs dashboard_data" >
<thead>
<tr class="table-primary">

    <th>ATMID</th>
    <th>Ticket ID</th>
    <th>Created At</th>
    <th>Aging</th>
    <th>Dependency</th>
    <th>lastUpdate</th>
    <th>LHO</th>
    <th>City</th>
    <th>State</th>
    <th>Location</th>
</tr>
</thead>
<tbody>
';

                    while ($highagingsql_result = mysqli_fetch_assoc($highagingsql)) {

                        $createdAtTimestamp = strtotime($highagingsql_result['created_at']);
                        $currentTime = time();
                        $agingInSeconds = $currentTime - $createdAtTimestamp;

                        $dependecy = $highagingsql_result['dependecy'];
                        if ($dependecy == 'open') {
                            $dependecyName = 'Open';
                        } else if ($dependecy == 'reassign') {
                            $dependecyName = 'Bank Dependency';
                        } else if ($dependecy == 'material_requirement') {
                            $dependecyName = 'Material Requirement';
                        }



                        $days = floor($agingInSeconds / (60 * 60 * 24));
                        $hours = floor(($agingInSeconds % (60 * 60 * 24)) / (60 * 60));
                        $minutes = floor(($agingInSeconds % (60 * 60)) / 60);
                        $seconds = $agingInSeconds % 60;

                        $agingFormatted = sprintf("%dd %02dh %02dm %02ds", $days, $hours, $minutes, $seconds);

                        $ticket_id = $highagingsql_result['ticket_id'];
                        $detail_id = $highagingsql_result['id'];
                        $referece_code = $highagingsql_result['reference_code'];
                        $lastUpdate = $highagingsql_result['latest_created_at'];

                        if (strtolower($highagingsql_result['isRead']) == 'unread') {
                            $className = 'unread';
                        } else {
                            $className = 'read';
                        }

                        echo "<tr class='{$className}'>

                <td>{$highagingsql_result['atmid']}</td><td>";

                        if ($SERVICE_LEVEL != 5) {
                            echo "<a href=\"mis/viewticket.php?ticket={$referece_code}\" target=\"_blank\">{$ticket_id}</a>";
                        } else {
                            echo "<a href=\"#\" target=\"_blank\">{$ticket_id}</a>";
                        }


                        echo "</td><td>{$highagingsql_result['created_at']}</td>
                                <td>{$agingFormatted}</td>
                                <td>{$dependecyName}</td>


                                <td>{$lastUpdate}</td>

                                <td>{$highagingsql_result['lho']}</td>
                                <td>{$highagingsql_result['city']}</td>
                                <td>{$highagingsql_result['state']}</td>
                                <td>{$highagingsql_result['location']}</td>
                                </tr>";

                        $agingCount++;
                    }
                    echo '</tbody>
</table>';

                    // Pagination Links
                
                    // echo $sql ; 
                    $sqlQueryWithoutLimit = removeLimitStatement($sql);

                    $totalRecords = mysqli_num_rows(mysqli_query($con, $sqlQueryWithoutLimit));
                    

                    $totalPages = ceil($totalRecords / $recordsPerPage);

                    echo '<div class="pagination">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $page) {
                            echo "<a class='active' href='?page=$i'>$i</a> ";
                        } else {
                            echo "<a href='?page=$i'>$i</a> ";
                        }
                    }
                    echo '</div>';

                } else {

                    echo '
                                        
<div class="noRecordsContainer">
<img src="assets/no_records.jpg">
</div>';

                }
                ?>


            </div>
        </div>

        <style>
            /* Add styles for unread rows */
            .table-styling tbody tr.unread {
                background-color: #ffcccc;
                /* Light red background for unread rows */
                font-size: 14px;
            }

            .table-styling tbody tr {
                font-size: 13px;
                background-color: #f2f6fc;
            }

            /* Style for unread ticket IDs */
            .table-styling tbody tr.unread td:nth-child(3) a {
                font-weight: bold;
                /* Make unread ticket IDs bold */
                color: #ff0000;
                /* Red color for unread ticket IDs */
            }
        </style>












      





    </div>
</div>