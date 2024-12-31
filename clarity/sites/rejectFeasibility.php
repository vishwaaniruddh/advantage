<?php
include ('../header.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_REQUEST['type'];
$atmid = $_REQUEST['atmid'];
$feasibilityId = $_REQUEST['feasibilityId'];

?>


<form action="<? $_SERVER['PHP_SELF']; ?>?type=<?= $type; ?>&atmid=<?= $atmid; ?>&feasibilityId=<?= $feasibilityId; ?>"
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
if (isset($_REQUEST['type'], $_REQUEST['atmid'], $_REQUEST['feasibilityId'], $_REQUEST['remarks'], $_REQUEST['submit'])) {
    $type = $_REQUEST['type'];
    $atmid = $_REQUEST['atmid'];
    $feasibilityId = $_REQUEST['feasibilityId'];
    $remarks = $_REQUEST['remarks'];
    // Check if the database connection is established
    if ($con) {
        $sql = mysqli_query($con, "SELECT * FROM feasibilityCheck WHERE id='$feasibilityId' AND ATMID1='$atmid'");

        // Check if the query executed successfully
        if ($sql) {
            $sql_result = mysqli_fetch_assoc($sql);

            if ($sql_result) {
                $typeBy = str_replace('is', '', $type);
                $typeBy = $typeBy . 'By';

                $getcheckrecordsSql = mysqli_query($con, "SELECT * FROM feasibilitycheck_checkpoints WHERE feasibiltyID='$feasibilityId' AND status=1");

                if ($getcheckrecordsSql) {
                    $getcheckrecordsSql_result = mysqli_fetch_assoc($getcheckrecordsSql);

                    if ($getcheckrecordsSql_result) {

                        $actionsql = "UPDATE feasibilitycheck_checkpoints SET $type = '0', $typeBy='$userid'";

                        if (mysqli_query($con, $actionsql)) {
                            mysqli_query($con, "insert into feasibilitycheck_checkpoints_remarks(feasibiltyID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                            values('" . $feasibilityId . "','" . $atmid . "','" . $type . "','" . $remarks . "',0,1,'" . $datetime . "','" . $userid . "')
                            ");
                            echo "<script>alert('Record Rejected successfully!'); window.location.href = 'feasibilityReport1.php?atmid=$atmid';</script>";
                        } else {
                            echo "Error updating record: " . mysqli_error($con);
                        }
                    } else {
                        $insertCheckpointssql = "INSERT INTO feasibilitycheck_checkpoints(feasibiltyID, atmid, isGeneralInfoApproved, GeneralInfoApprovedBy, isNetworkInfoApproved, NetworkInfoApprovedBy, isBackroomInfoApproved, BackroomInfoApprovedBy, isEmLockApproved, EmLockApprovedBy, isRouterInfoApproved, RouterInfoApprovedBy, isUPSInfoApproved, UPSInfoApprovedBy, isPowerInfoApproved, PowerInfoApprovedBy, isOtherInfoApproved, OtherInfoApprovedBy, status) 
                        VALUES ('$feasibilityId','$atmid','','','','','','','','','','','','','','','','',1)";

                        if (mysqli_query($con, $insertCheckpointssql)) {

                            $actionsql = "UPDATE feasibilitycheck_checkpoints SET $type = '0', $typeBy='$userid'";

                            if (mysqli_query($con, $actionsql)) {
                                mysqli_query($con, "insert into feasibilitycheck_checkpoints_remarks(feasibiltyID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                            values('" . $feasibilityId . "','" . $atmid . "','" . $type . "','" . $remarks . "',0,1,'" . $datetime . "','" . $userid . "')
                            ");
                                echo "<script>alert('Record Rejected successfully!'); window.location.href = 'feasibilityReport1.php?atmid=$atmid';</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($con);
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