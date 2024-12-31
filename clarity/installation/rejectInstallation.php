<?php
include ('../header.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_REQUEST['type'];
$atmid = $_REQUEST['atmid'];
$thisinstallationId =  $installationId = $_REQUEST['installationId'];

?>


<form
    action="<? $_SERVER['PHP_SELF']; ?>?type=<?= $type; ?>&atmid=<?= $atmid; ?>&installationId=<?= $installationId; ?>"
    method="POST">
    <div class="row">
        <div class="col-sm-12">
            <label for="">Remarks</label>
            <input type="text" name="remarks" class="form-control">
        </div>

        <div class="col-sm-12">
            <br>
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </div>

    </div>
</form>
<?


// Check if the form variables are set
if (isset($_REQUEST['type'], $_REQUEST['atmid'], $_REQUEST['installationId'], $_REQUEST['remarks'], $_REQUEST['submit'])) {
    $type = $_REQUEST['type'];
    $atmid = $_REQUEST['atmid'];
    $installationId = $_REQUEST['installationId'];
    $remarks = $_REQUEST['remarks'];
    // Check if the database connection is established
    if ($con) {
        $sql = mysqli_query($con, "SELECT * FROM installationData WHERE id='$installationId' AND atmid='$atmid'");

        // Check if the query executed successfully
        if ($sql) {
            $sql_result = mysqli_fetch_assoc($sql);

            if ($sql_result) {
                $typeBy = str_replace('is', '', $type);
                $typeBy = $typeBy . 'ApprovedBy';

                $type = 'isApproved' . $type;

                // echo "SELECT * FROM installationCheckpoints WHERE installationID='$installationId' AND status=1" ; 

                $getcheckrecordsSql = mysqli_query($con, "SELECT * FROM installationCheckpoints WHERE installationID='$installationId' AND status=1");

                if ($getcheckrecordsSql) {
                    $getcheckrecordsSql_result = mysqli_fetch_assoc($getcheckrecordsSql);

                    if ($getcheckrecordsSql_result) {

                        echo $actionsql = "UPDATE installationCheckpoints SET $type = '2', $typeBy='$userid' where installationID='".$thisinstallationId."'";

                        if (mysqli_query($con, $actionsql)) {
                            echo "insert into installationcheck_checkpoints_remarks(installationID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                            values('" . $installationId . "','" . $atmid . "','" . $type . "','" . $remarks . "',0,1,'" . $datetime . "','" . $userid . "')
                            ";
                            mysqli_query($con, "insert into installationcheck_checkpoints_remarks(installationID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                            values('" . $installationId . "','" . $atmid . "','" . $type . "','" . $remarks . "',0,1,'" . $datetime . "','" . $userid . "')
                            ");
                            echo "<script>alert('Record Reject successfully!'); window.location.href = 'installationInfo.php?atmid=$atmid';</script>";
                        } else {
                            echo "Error updating record: " . mysqli_error($con);
                        }
                    } else {

                        $insertCheckpointssql = "INSERT INTO installationCheckpoints(installationID, atmid,     isApprovedrouterFixedSnaps, routerFixedSnapsApprovedBy, isApprovedrouterStatusSnaps, routerStatusSnapsApprovedBy, isApprovedadaptorSnaps, adaptorSnapsApprovedBy, isApprovedadaptorStatusSnaps, adaptorStatusSnapsApprovedBy, isApprovedantennaSnaps, antennaSnapsApprovedBy, isApprovedantennaStatusSnaps, antennaStatusSnapsApprovedBy, isApprovedgpsSnaps, gpsSnapsApprovedBy, isApprovedgpsStatusSnaps, gpsStatusSnapsApprovedBy, isApprovedwifiSnaps, wifiSnapsApprovedBy, isApprovedwifiStatusSnaps, wifiStatusSnapsApprovedBy, isApprovedairtelSimSnaps, airtelSimSnapsApprovedBy, isApprovedairtelSimStatusSnaps, airtelSimStatusSnapsApprovedBy, isApprovedvodafoneSimSnaps, vodafoneSimSnapsApprovedBy, isApprovedvodafoneSimStatusSnaps, vodafoneSimStatusSnapsApprovedBy, isApprovedjioSimSnaps, jioSimSnapsApprovedBy, isApprovedjioSimStatusSnaps, jioSimStatusSnapsApprovedBy , status) 
                        VALUES ('$installationId','$atmid',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '', '', 1)";

                        if (mysqli_query($con, $insertCheckpointssql)) {

                            echo $actionsql = "UPDATE installationCheckpoints SET $type = '2', $typeBy='$userid' where installationID='".$thisinstallationId."'";

                            if (mysqli_query($con, $actionsql)) {

                                echo "insert into installationcheck_checkpoints_remarks(installationID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                                values('" . $installationId . "','" . $atmid . "','" . $type . "','" . $remarks . "',0,1,'" . $datetime . "','" . $userid . "')
                                " ;

                                mysqli_query($con, "insert into installationcheck_checkpoints_remarks(installationID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                            values('" . $installationId . "','" . $atmid . "','" . $type . "','" . $remarks . "',0,1,'" . $datetime . "','" . $userid . "')
                            ");
                                echo "<script>alert('Record Reject successfully!'); window.location.href = 'installationInfo.php?atmid=$atmid';</script>";
                            } else {
                                echo "Error updating record this: " . mysqli_error($con);
                            }

                        } else {
                            echo "Error inserting record: " . mysqli_error($con);
                        }
                    }
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                echo "No records found for the given ID and ATMID";
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Database connection error";
    }
} else {
    echo "Enter Remarks";
}

include ('../footer.php');
?>