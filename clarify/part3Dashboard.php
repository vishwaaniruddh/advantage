<div class="row">
    <div class="col-sm-12">


        <?



        echo '<div class="card">
    <div class="card-header">
            <h5>LHO Wise Open Calls</h5>
            <hr />
        </div>
    
        <div class="card-block">';

        // LHO wise open calls
        $lhowiseSrno = 1;


        if ($assignedLho) {

            $sql = "select a.lho,count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id   where 1 and b.mis_id = a.id and  
    CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'reassign') and a.lho='" . $assignedLho . "'
    
    ";
        } else if ($_SESSION['isVendor'] == 1) {
            $sql = "select a.lho,count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id   where 1 and b.mis_id = a.id and  
    CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'reassign') and a.vendorId='" . $_GLOBAL_VENDOR_ID . "'
      ";
        } else {
            $sql = "select a.lho,count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id   where 1 and b.mis_id = a.id and   CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'reassign')
      ";
        }



        if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO'] !== '') {
            $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
            $sql .= " and a.lho like '" . $_GLOBAL_LHO . "'";
        }

        $sql .= " group by a.lho order by a.lho asc ";

        // echo $sql ; 
        $lhosql = mysqli_query($con, $sql);
        if (mysqli_num_rows($lhosql) > 0) {


            echo '
<table class="table table-hover table-styling table-xs">
    <thead>
        <tr class="table-primary">
            <th>Sr No</th>
            <th>LHO</th>
            <th>Total Calls</th>
            <th>Open</th>
            <th>Close</th>
        <tr>
    <thead>
    <tbody>
    ';

            $totalLhoOpenCalls = 0;
            $totalLhoCloseCalls = 0;
            $totalallcall = 0;

            while ($lhosql_result = mysqli_fetch_assoc($lhosql)) {

                $lho = $lhosql_result['lho'];
                if ($assignedLho) {

                    $allcallsql = "select count(1) as total from mis where lho like '" . $assignedLho . "' and lho like '" . $lho . "' ";
                    $opencallsql = "select count(1) as total from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') and a.LHO like '" . $assignedLho . "'";
                    $closecallsql = "select count(1) as total from mis a where 1 and a.status like 'close' and a.lho='" . $lho . "'";


                } else if ($_SESSION['isVendor'] == 1) {
                    $allcallsql = "select count(1) as total from mis where vendorId like '" . $_GLOBAL_VENDOR_ID . "'";
                    $opencallsql = "select count(1) as total from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') and a.vendorId like '" . $_GLOBAL_VENDOR_ID . "'";
                    $closecallsql = "select count(1) as total from mis a where 1 and a.status like 'close' and a.lho='" . $lho . "'";

                } else {
                    $allcallsql = "select count(1) as total from mis where lho like '" . $lho . "'";
                    $opencallsql = "select count(1) as total from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and 
                     b.mis_id = a.id and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and 
                     b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update')";
                    $closecallsql = "select count(1) as total from mis a where 1 and a.status like 'close' and a.lho='" . $lho . "'";

                }


                if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO'] !== '') {
                    $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
                    $allcallsql .= " and lho like '" . $_GLOBAL_LHO . "'";
                    $opencallsql .= " and lho like '" . $_GLOBAL_LHO . "'";
                    $closecallsql .= " and lho like '" . $_GLOBAL_LHO . "'";
                }
                // echo $closecallsql;
                // echo $allcallsql ; 
        
                $allcall = mysqli_fetch_assoc(mysqli_query($con, $allcallsql))['total'];
                $opencall = mysqli_fetch_assoc(mysqli_query($con, $opencallsql))['total'];
                $closecall = mysqli_fetch_assoc(mysqli_query($con, $closecallsql))['total'];


                echo "<tr>
            <td>{$lhowiseSrno}</td>
            <td>{$lho}</td>
            <td>{$allcall}</td>
            <td>{$lhosql_result['count']}</td>
            <td>{$closecall}</td>
        <tr>";

                $totalLhoOpenCalls = $totalLhoOpenCalls + $lhosql_result['count'];

                $totalLhoCloseCalls = $totalLhoCloseCalls + $closecall;
                $totalallcall = $totalallcall + $allcall;


                $lhowiseSrno++;
            }




            echo "

  <tr class='table-primary'>
            <th></th>
            <th>Total</th>
            <th>{$totalallcall}</th>
            <th>{$totalLhoOpenCalls}</th>
            <th>{$totalLhoCloseCalls}</th>
        <tr>
        
        
        
        
</tbody>
</table>";

        } else {

            echo '
                                                
    <div class="noRecordsContainer">
        <img src="assets/no_records.jpg">
    </div>';

        }


        echo '
        </div>
    </div>
    ';





        ?>
    </div>
</div>