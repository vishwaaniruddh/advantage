<?php
include ('../config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_REQUEST['type'];
$atmid = $_REQUEST['atmid'];
$feasibilityId = $_REQUEST['feasibilityId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_REQUEST['remarks'])) {
        $remarks = $_REQUEST['remarks'];
        
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
                                values('" . $feasibilityId . "','" . $atmid . "','" . $type . "','" . $remarks . "',1,1,'" . $datetime . "','" . $userid . "')
                                ");
                                echo 1 ; 
                                // echo "<script>alert('Record Approved successfully!'); window.location.href = 'feasibilityReport1.php?atmid=$atmid';</script>";
                            } else {
                                echo 2 ; 
                                // echo "Error updating record: " . mysqli_error($con);
                            }
                        } else {
                            $insertCheckpointssql = "INSERT INTO feasibilitycheck_checkpoints(feasibiltyID, atmid, isGeneralInfoApproved, GeneralInfoApprovedBy, isNetworkInfoApproved, NetworkInfoApprovedBy, isBackroomInfoApproved, BackroomInfoApprovedBy, isEmLockApproved, EmLockApprovedBy, isRouterInfoApproved, RouterInfoApprovedBy, isUPSInfoApproved, UPSInfoApprovedBy, isPowerInfoApproved, PowerInfoApprovedBy, isOtherInfoApproved, OtherInfoApprovedBy, status) 
                            VALUES ('$feasibilityId','$atmid','','','','','','','','','','','','','','','','',1)";
    
                            if (mysqli_query($con, $insertCheckpointssql)) {
    
                                $actionsql = "UPDATE feasibilitycheck_checkpoints SET $type = '0', $typeBy='$userid'";
    
                                if (mysqli_query($con, $actionsql)) {
                                    mysqli_query($con, "insert into feasibilitycheck_checkpoints_remarks(feasibiltyID,atmid,fieldName,remark,fieldStatus,status,created_at,created_by)
                                values('" . $feasibilityId . "','" . $atmid . "','" . $type . "','" . $remarks . "',1,1,'" . $datetime . "','" . $userid . "')
                                ");
                                echo 1 ; 
                                    // echo "<script>alert('Record Approved successfully!'); window.location.href = 'feasibilityReport1.php?atmid=$atmid';</script>";
                                } else {
                                    echo 2 ; 
                                    // echo "Error updating record: " . mysqli_error($con);
                                }
    
                            } else {
                                echo 2 ; 
                                // echo "Error inserting record: " . mysqli_error($con);
                            }
                        }
                    } else {
                        echo 2 ; 
                        // echo "Error: " . mysqli_error($con);
                    }
                } else {
                    echo "No records found for the given ID and ATMID";
                }
            } else {
                echo 2 ; 
                // echo "Error: " . mysqli_error($con);
            }
        } else {
            echo "Database connection error";
        }




    }
}
?>