<?php
include ('../config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_REQUEST['type'];
$atmid = $_REQUEST['atmid'];
$thisinstallationId = $installationId = $_REQUEST['installationId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form variables are set
    if (isset($_REQUEST['remarks'])) {
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

                    $getcheckrecordsSql = mysqli_query($con, "SELECT * FROM installationCheckpoints WHERE installationID='$installationId' AND status=1");

                    if ($getcheckrecordsSql) {
                        $getcheckrecordsSql_result = mysqli_fetch_assoc($getcheckrecordsSql);

                        if ($getcheckrecordsSql_result) {
                          echo  $actionsql = "UPDATE installationCheckpoints SET $type = '2', $typeBy='$userid' WHERE installationID='$thisinstallationId'";

                            if (mysqli_query($con, $actionsql)) {
                                mysqli_query($con, "INSERT INTO installationcheck_checkpoints_remarks(installationID, atmid, fieldName, remark, fieldStatus, status, created_at, created_by)
                                    VALUES ('$installationId', '$atmid', '$type', '$remarks', 0, 1, '$datetime', '$userid')");
                                echo 1 ; 
                            } else {
                                
                                echo "Error updating record: " . mysqli_error($con);
                            }
                        } else {
                            $insertCheckpointssql = "INSERT INTO installationCheckpoints(installationID, atmid,     isApprovedrouterFixedSnaps, routerFixedSnapsApprovedBy, isApprovedrouterStatusSnaps, routerStatusSnapsApprovedBy, isApprovedadaptorSnaps, adaptorSnapsApprovedBy, isApprovedadaptorStatusSnaps, adaptorStatusSnapsApprovedBy, isApprovedantennaSnaps, antennaSnapsApprovedBy, isApprovedantennaStatusSnaps, antennaStatusSnapsApprovedBy, isApprovedgpsSnaps, gpsSnapsApprovedBy, isApprovedgpsStatusSnaps, gpsStatusSnapsApprovedBy, isApprovedwifiSnaps, wifiSnapsApprovedBy, isApprovedwifiStatusSnaps, wifiStatusSnapsApprovedBy, isApprovedairtelSimSnaps, airtelSimSnapsApprovedBy, isApprovedairtelSimStatusSnaps, airtelSimStatusSnapsApprovedBy, isApprovedvodafoneSimSnaps, vodafoneSimSnapsApprovedBy, isApprovedvodafoneSimStatusSnaps, vodafoneSimStatusSnapsApprovedBy, isApprovedjioSimSnaps, jioSimSnapsApprovedBy, isApprovedjioSimStatusSnaps, jioSimStatusSnapsApprovedBy , status) 
                            VALUES ('$installationId','$atmid',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '',    '', '', 1)";

                            if (mysqli_query($con, $insertCheckpointssql)) {
                                $actionsql = "UPDATE installationCheckpoints SET $type = '2', $typeBy='$userid' WHERE installationID='$thisinstallationId'";

                                if (mysqli_query($con, $actionsql)) {
                                    mysqli_query($con, "INSERT INTO installationcheck_checkpoints_remarks(installationID, atmid, fieldName, remark, fieldStatus, status, created_at, created_by)
                                        VALUES ('$installationId', '$atmid', '$type', '$remarks', 0, 1, '$datetime', '$userid')");
                                   echo 1 ;  // echo 'Record Approved successfully!';
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
}
?>
