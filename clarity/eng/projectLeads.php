<? include ('../header.php');

if ($ADVANTAGE_level == 3) {


    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <div class="row">
        <div class="page-body">
            <div class="card">
                <div class="card-body" style="overflow: auto;">
                    <table id="example"
                        class="table table-bordered table-striped table-hover dataTable js-exportable no-footer"
                        style="width:100%">
                        <thead>
                            <tr class="table-primary">
                                <th>Srno</th>
                                <th>Atmid</th>
                                <th>Action</th>
                                <th>IR Report</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            $i = 1;
                            // echo "select * from assignedInstallation where assignedToId='" . $userid . "' and status=1";
                            $sql = mysqli_query($con, "select * from assignedInstallation where assignedToId='" . $userid . "' and status=1");
                            while ($sql_result = mysqli_fetch_assoc($sql)) {
                                $atmid = $sql_result['atmid'];
                                $siteid = $sql_result['siteid'];
                                $isDone = $sql_result['isDone'];

                                $sitessql = mysqli_query($con, "select * from sites where id='" . $siteid . "'");
                                $sitessql_result = mysqli_fetch_assoc($sitessql);
                                $address = $sitessql_result['address'];


// echo "Select * from routerconfiguration where atmid='" . $atmid . "' and status=1" ;
                                $routerconfigsql = mysqli_query($con, "Select * from routerconfiguration where atmid='" . $atmid . "' and status=1");
                                if ($routerconfigsqlResult = mysqli_fetch_assoc($routerconfigsql)) {
                                    $serial_no = $routerconfigsqlResult['serialNumber'];
                                    echo "select * from enginventory where serial_no='" . $serial_no . "' and eng_userid='" . $userid . "'" ;
                                    $material_send_id = mysqli_fetch_assoc(mysqli_query($con, "select * from enginventory where serial_no='" . $serial_no . "' and eng_userid='" . $userid . "'"))['material_send_id'];

                                    if ($material_send_id && $material_send_id > 0) {
                                        $installationRemark = 1;

                                    } else {
                                        $installationRemark = '1 Material Not Received !';
                                    }


                                } else {
                                    $installationRemark = '2 Material Not Received !';
                                }

                                ?>
                                <tr>
                                    <td>
                                        <? echo $i; ?>
                                    </td>
                                    <td>
                                        <? echo $atmid; ?>
                                    </td>

                                    <td>

                                        <?
                                        if ($isDone == 1) {
                                            echo 'Installation Done | ';
                                            // installationInfo.php?siteid=16201&atmid=demoatmid2024
                                            echo '<a href="../installation/engInstallationInfo.php?siteid=' . $siteid . '&&atmid=' . $atmid . '" "> Edit </a>';

                                        } else {
                                            if ($installationRemark == 1) {
                                                echo '<a href="proceedInstallation.php?siteid=' . $siteid . '&&atmid=' . $atmid . '" ">Proceed With Installation</a>';
                                            } else {
                                                echo $installationRemark;
                                            }

                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?

                                        $irsql = mysqli_query($con, "select * from installationreport where atmid='" . $atmid . "' and status=1");
                                        if ($irsql_result = mysqli_fetch_assoc($irsql)) {
                                            $reportPath = $irsql_result['reportPath'];

                                            ?>
                                            <a href="<?= $reportPath; ?>">View</a>
                                        <?

                                        } else { ?>

                                            <a href="./uploadirreport.php?atmid=<?= $atmid; ?>">Upload IR Report</a>

                                        <? }

                                        ?>
                                    </td>
                                    <td>
                                        <? echo $address; ?>
                                    </td>

                                </tr>

                                <? $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <script src="../datatable/jquery.dataTables.js"></script>
    <script src="../datatable/dataTables.bootstrap.js"></script>
    <script src="../datatable/dataTables.buttons.min.js"></script>
    <script src="../datatable/buttons.flash.min.js"></script>
    <script src="../datatable/jszip.min.js"></script>

    <script src="../datatable/pdfmake.min.js"></script>
    <script src="../datatable/vfs_fonts.js"></script>
    <script src="../datatable/buttons.html5.min.js"></script>
    <script src="../datatable/buttons.print.min.js"></script>
    <script src="../datatable/jquery-datatable.js"></script>

<?
} else {
    echo "You Don't have permission to access this page !";
}
?>
<? include ('../footer.php'); ?>