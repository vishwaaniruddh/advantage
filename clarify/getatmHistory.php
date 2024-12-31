<? include ('../config.php'); 

$base_url = "http://clarify.advantagesb.com/";

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (!function_exists('getUsername')) {

    function getUsername($id, $vendor = FALSE)
    {
        global $con;

        $sql = mysqli_query($con, "select * from user where userid ='" . $id . "'");
        $sql_result = mysqli_fetch_assoc($sql);
        return ucwords($sql_result['name']);
    }
}
error_reporting(0);
$atmid = $_REQUEST['atmid'];
$atmid = trim(strtoupper($atmid));

function cust_date($date)
{
    if ($date) {
        return date('d M, Y', strtotime($date));
    } else {
        return;
    }

}



function cust_datetime($date)
{
    if ($date) {
        return date('d M, Y H:i:s', strtotime($date));
    } else {
        return;
    }
}



$sql1 = mysqli_query($con, "select * from mis_details where atmid like '%" . $atmid . "%' order by id desc");
$i = 1;

if (mysqli_fetch_assoc($sql1)) {




    ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">



    <div>

        <table id="historyTableContent" class="table dataTable js-exportable" style="width:100%">

            <thead>
                <tr class="table-primary">
                    <th>Sn</th>
                    <th>Ticket ID</th>
                    <th>Component</th>
                    <th>Sub Component</th>
                    <th>Call Receive From</th>
                    <th>Initial Remarks</th>
                    <th>Current Status</th>
                    <th>Record Created By</th>
                    <th>Record Created Date</th>

                    <th>Close Remarks</th>
                    <th>Closed By</th>
                    <th>Closing Date</th>

                    <th>Down time</th>

                    <th>Attachment 1</th>
                </tr>
            </thead>
            <tbody>

                <? $sql = mysqli_query($con, "select * from mis_details where atmid = '" . $atmid . "' order by id desc");
                $i = 1;
                while ($sql_result = mysqli_fetch_assoc($sql)) {

                    $mis_id = $sql_result['mis_id'];
                    $sql1 = mysqli_query($con, "select * from mis where id='" . $sql_result['mis_id'] . "'");
                    $sql1_result = mysqli_fetch_assoc($sql1);
                    $created_by = $sql1_result['created_by'];

                    $created_at = $sql1_result['created_at'];


                    // $created_at = cust_date($created_at);
                    $created_at = cust_datetime($created_at);

                    $user_sql = mysqli_query($con, "select * from user where userid='" . $created_by . "'");
                    $user_sql_result = mysqli_fetch_assoc($user_sql);
                    $created_by = $user_sql_result['name'];




                    $his_sql = mysqli_query($con, "select * from mis_history where mis_id='" . $sql_result['mis_id'] . "' and type='close' order by id desc");
                    if ($his_sql_result = mysqli_fetch_assoc($his_sql)) {

                        $close_time = $his_sql_result['created_at'];
                        $attachment = $his_sql_result['attachment'];
                        $attachment2 = $his_sql_result['attachment2'];
                        $closeRemarks = $his_sql_result['remark2'] . '<br />' . $his_sql_result['remark'];
                        $closeBy = getUsername($his_sql_result['created_by']);

                        $date2 = date_create($sql_result['created_at']);

                        $date1_real = $his_sql_result['created_at'];
                        $date1 = date_create($date1_real);

                    } else {
                        $date1_real = date('Y-m-d H:i:s');
                        $date1 = date_create($date1_real);
                    }


                    $date2 = date_create($sql1_result['created_at']);

                    $diff = date_diff($date1, $date2);


                    ?>

                    <tr>
                        <td><?= $i; ?></td>
                        <td>
                            <a href="mis_details.php?id=<?= $mis_id; ?>">
                                <?= $sql_result['ticket_id']; ?>
                            </a>
                        </td>
                        <td><?= $sql_result['component']; ?></td>
                        <td><?= $sql_result['subcomponent']; ?></td>
                        <td><?= $sql1_result['call_receive_from']; ?></td>

                        <td><?= $sql1_result['remarks']; ?></td>
                        <td><?= $sql_result['status']; ?></td>
                        <td><?= $created_by; ?></td>
                        <td><?= $created_at; ?></td>

                        <td><?= $closeRemarks; ?></td>
                        <td><?= $closeBy; ?></td>

                        <td><?= cust_datetime($close_time); ?></td>

                        <td>
                            <?php


                            $formattedDiff = $diff->format("%a days, %h hours, %i minutes, %s seconds");
                            echo $formattedDiff;
                            //echo $diff->format("%a days"); 
                            ?>

                        </td>
                        <td>
                            <?
                            if ($attachment) {

                                if (strpos($attachment, 'mis/mis_images/') !== 0) {
                                    $attachment = $base_url . 'mis/' . $attachment;
                                }
                                ?>
                            <a style="white-space:nowrap;" href="<?= $attachment; ?>" class="btn btn-success">View Attachment</a>
                            <? } ?>
                        </td>




                    </tr>

                    <? $i++;
                } ?>

            </tbody>
        </table>
    </div>


<? } else {

    echo 'No History Found !';
}
?>

<script>
   $(document).ready(function() {
        $('#historyTableContent').DataTable({
            dom: 'lBfrtip', // Add the necessary buttons
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>


<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
