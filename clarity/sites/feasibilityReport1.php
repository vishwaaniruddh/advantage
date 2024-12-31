<? include ('../header.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$atmid = $_REQUEST['atmid'];


$getsitesinfosql = mysqli_query($con, "select * from sites where atmid='" . $atmid . "'");
if ($getsitesinfosqlResult = mysqli_fetch_assoc($getsitesinfosql)) {
    $siteid = $getsitesinfosqlResult['id'];
}



function checkFeasibiltyStatus($feasibiltyId, $field)
{
    global $con;
    // echo "select $field from feasibilitycheck_checkpoints where feasibiltyID='".$feasibiltyId."' and status=1" ; 
    $sql = mysqli_query($con, "select $field from feasibilitycheck_checkpoints where feasibiltyID='" . $feasibiltyId . "' and status=1");
    if ($sql_result = mysqli_fetch_assoc($sql)) {
        $status = $sql_result[$field];

        $remark_sql = mysqli_query($con, "select remark from  feasibilitycheck_checkpoints_remarks where feasibiltyID='" . $feasibiltyId . "' and fieldName='" . $field . "' and status=1 order by id desc");
        if ($remark_sql_result = mysqli_fetch_assoc($remark_sql)) {
            $remarks = $remark_sql_result['remark'];
        } else {
            $remarks = '';
        }

        if ($status == 0) {
            return 'Rejected. ' . $remarks;
        } else if ($status == 1) {
            return 'Approved. ' . $remarks;
        }
    } else {
        return 'Pending';
    }
}

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>




<style>
    tr th:first-child {
        width: 40%;
    }
</style>

<div class="row">
    <div class="col-lg-12">



        <div class="card">
            <div class="card-header">
                <h5 class="card-header-text">Feasibility Report for ATMID : <span
                        style="color:red;display: inline-block;">
                        <? echo $atmid; ?>
                    </span></h5>
                <a href="../eng/editFeasibilitycheck.php?siteid=<?php echo $siteid; ?>" ">View Form </a>

            </div>
            <div class=" card-block accordion-block">

                    <div class="accordion accordion-filled" id="accordion-7" role="tablist">
                        <?php



                        $atm_id = $_REQUEST['atmid'];
                        $getsiteIdSql = mysqli_query($con, "select * from sites where atmid='" . $atm_id . "'");
                        $getsiteIdSql_result = mysqli_fetch_assoc($getsiteIdSql);
                        $siteid = $getsiteIdSql_result['id'];


                        if (isset($_POST['rejectsubmit'])) {
                            $status = 'Reject';

                            $feasibilityRemark = $_REQUEST['feasibilityRemark'];
                            $feasibiltyId = $_REQUEST['feasibiltyId'];

                            mysqli_query($con, "update sites set verificationStatus='" . $status . "' where id='" . $siteid . "'");
                            mysqli_query($con, "update feasibilityCheck set verificationStatus='" . $status . "' where id='" . $feasibiltyId . "'");

                            feasibilityApprovalReject($siteid, $atm_id, '', $feasibilityRemark);



                        } else if (isset($_POST['verifysubmit'])) {
                            $status = 'Verify';


                            $feasibiltyId = $_REQUEST['feasibiltyId'];

                            mysqli_query($con, "update sites set verificationStatus='" . $status . "' where id='" . $siteid . "'");
                            mysqli_query($con, "update feasibilityCheck set verificationStatus='" . $status . "', verificationBy='" . $userid . "',verificationByName='" . $username . "' where id='" . $feasibiltyId . "'");

                            // echo "update feasibilityCheck set verificationStatus='" . $status . "', verificationBy='" . $userid . "',verificationByName='" . $username . "' where id='" . $feasibiltyId . "'" ; 
                        


                            feasibilityApprovalVerify($siteid, $atm_id, '');
                            // Initiate Material Request here
                        
                            $materialQuantities = [];
                            $sql = "SELECT value, count FROM boq";
                            $result = $con->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $materialName = $row['value'];
                                    $quantity = $row['count'];
                                    $materialQuantities[$materialName] = $quantity;
                                }
                            }



                            $feasSql = mysqli_query($con, "select * from feasibilityCheck where id='" . $feasibiltyId . "'");
                            $feasSql_result = mysqli_fetch_assoc($feasSql);
                            $isVendor = $feasSql_result['isVendor'];

                            if ($isVendor == 0) {
                                $type = 'Internal';
                                $vendorId = 0;
                            } else if ($isVendor == 1) {
                                $type = 'External';
                                $feasibiltyCreatedBy = $feasSql_result['created_by'];
                                // echo "select * from user where id='" . $feasibiltyCreatedBy . "'" ;
                                $feasibiltyCreatedBy = $feasSql_result['created_by'];
                                $vendorsql = mysqli_query($con, "select * from user where userid='" . $feasibiltyCreatedBy . "'");
                                $vendorsql_result = mysqli_fetch_assoc($vendorsql);

                                $vendorId = $vendorsql_result['vendorid'];

                            }





                            // Generate material requests
                        

                            // foreach ($materialQuantities as $materialName => $quantity) {
                            //     // Insert the material request into the table
                        
                            //     $checkMaterialRequstSql = mysqli_query($con, "select * from material_requests where siteid='" . $siteid . "' and material_name='" . $materialName . "'");
                            //     if ($checkMaterialRequstSql_result = mysqli_fetch_assoc($checkMaterialRequstSql)) {

                            //         mysqli_query($con, "update material_requests set feasibility_id='" . $feasibiltyId . "' where 
                            //                         siteid='" . $siteid . "' and material_name='" . $materialName . "'");

                            //         $manualSendFound = 1;
                            //     } else {
                            //         $sql = "INSERT INTO material_requests (siteid, feasibility_id, material_name, quantity, status, created_by,created_at,type,vendorId)
                            //                                 VALUES ('$siteid', '$feasibiltyId', '$materialName', '$quantity', 'pending', '" . $userid . "','" . $datetime . "','" . $type . "','" . $vendorId . "')";
                            //         if ($con->query($sql) === false) {
                            //             // echo "Error: " . "<br>" . $con->error;
                            //             // echo "Error: " . $sql . "<br>" . $con->error;
                        
                            //         }
                            //         // echo '<br />';
                            //     }


                            // }

                            // if ($manualSendFound == 0) {
                            //     generatesAutoMaterialRequest($siteid, $atm_id, '');
                            // }






                            // End Material Request
                        


                        }


                        $query = "SELECT * FROM feasibilityCheck where ATMID1='" . $atmid . "' order by id desc";

                        $result = $con->query($query);

                        if ($result->num_rows > 0) {
                            $i = 1;
                            if ($row = $result->fetch_assoc()) {
                                $id = $row['id'];

                                $noOfAtm = $row['noOfAtm'];
                                $ATMID1 = $row['ATMID1'];
                                $ATMID2 = $row['ATMID2'];
                                $ATMID3 = $row['ATMID3'];
                                $address = $row['address'];
                                $city = $row['city'];
                                $location = $row['location'];
                                $LHO = $row['LHO'];
                                $state = $row['state'];
                                $atm1Status = $row['atm1Status'];
                                $atm2Status = $row['atm2Status'];
                                $atm3Status = $row['atm3Status'];
                                $operator = $row['operator'];
                                $signalStatus = $row['signalStatus'];
                                $backroomNetworkRemark = $row['backroomNetworkRemark'];
                                $backroomNetworkSnap = $row['backroomNetworkSnap'];
                                $AntennaRoutingdetail = $row['AntennaRoutingdetail'];
                                $EMLockPassword = $row['EMLockPassword'];
                                $EMlockAvailable = $row['EMlockAvailable'];
                                $NoOfUps = $row['NoOfUps'];
                                $PasswordReceived = $row['PasswordReceived'];
                                $Remarks = $row['Remarks'];
                                $UPSAvailable = $row['UPSAvailable'];
                                $UPSBateryBackup = $row['UPSBateryBackup'];
                                $UPSWorking1 = $row['UPSWorking1'];
                                $UPSWorking2 = $row['UPSWorking2'];
                                $UPSWorking3 = $row['UPSWorking3'];
                                $backroomDisturbingMaterial = $row['backroomDisturbingMaterial'];
                                $backroomDisturbingMaterialRemark = $row['backroomDisturbingMaterialRemark'];
                                $backroomKeyName = $row['backroomKeyName'];
                                $backroomKeyNumber = $row['backroomKeyNumber'];
                                $backroomKeyStatus = $row['backroomKeyStatus'];
                                $earthing = $row['earthing'];
                                $earthingVltg = $row['earthingVltg'];
                                $frequentPowerCut = $row['frequentPowerCut'];
                                $frequentPowerCutFrom = $row['frequentPowerCutFrom'];
                                $frequentPowerCutRemark = $row['frequentPowerCutRemark'];
                                $frequentPowerCutTo = $row['frequentPowerCutTo'];
                                $nearestShopDistance = $row['nearestShopDistance'];
                                $nearestShopName = $row['nearestShopName'];
                                $nearestShopNumber = $row['nearestShopNumber'];
                                $powerFluctuationEN = $row['powerFluctuationEN'];
                                $powerFluctuationPE = $row['powerFluctuationPE'];
                                $powerFluctuationPN = $row['powerFluctuationPN'];
                                $powerSocketAvailability = $row['powerSocketAvailability'];
                                $routerAntenaPosition = $row['routerAntenaPosition'];
                                $routerAntenaSnap = $row['routerAntenaSnap'];
                                $AntennaRoutingSnap = $row['AntennaRoutingSnap'];
                                $UPSAvailableSnap = $row['UPSAvailableSnap'];
                                $NoOfUpsSnap = $row['NoOfUpsSnap'];
                                $upsWorkingSnap = $row['upsWorkingSnap'];
                                $powerSocketAvailabilitySnap = $row['powerSocketAvailabilitySnap'];
                                $earthingSnap = $row['earthingSnap'];
                                $powerFluctuationSnap = $row['powerFluctuationSnap'];
                                $remarksSnap = $row['remarksSnap'];
                                $status = $row['status'];
                                $created_at = $row['created_at'];
                                $powerSocketAvailabilityUPS = $row['powerSocketAvailabilityUPS'];
                                $powerSocketAvailabilityUPSSnap = $row['powerSocketAvailabilityUPSSnap'];
                                $operator2 = $row['operator2'];
                                $signalStatus2 = $row['signalStatus2'];
                                $backroomNetworkRemark2 = $row['backroomNetworkRemark2'];
                                $backroomNetworkSnap2 = $row['backroomNetworkSnap2'];
                                $created_by = $row['created_by'];
                                $feasibilityDone = $row['feasibilityDone'];
                                $isVendor = $row['isVendor'];
                                $ticketid = $row['ticketid'];
                                $verificationStatus = $row['verificationStatus'];
                                $ATMID1Snap = $row['ATMID1Snap'];
                                $ATMID2Snap = $row['ATMID2Snap'];
                                $ATMID3Snap = $row['ATMID3Snap'];
                                $verificationByName = $row['verificationByName'];
                                $routerPosition = $row['routerPosition'];
                                $routerPositionSnap = $row['routerPositionSnap'];
                                $getverificationStatus = $row['verificationStatus'];


                                $isVendor = $row['isVendor'];
                                $atm_id = $row['atmid'];

                                $baseurl = 'http://clarity.advantagesb.com/API/';


                                // $baseurl = $baseurl . '' ;
                        
                                ?>

                                <div class="card">
                                    <div class="card-header" role="tab" id="heading-<?php echo $i; ?>">
                                        <h5 class=" mb-0">
                                            <? echo 'Feasibility Check - ' . ($verificationStatus ? $verificationStatus : 'Pending'); ?>

                                        </h5>
                                    </div>

                                    <form action="<? $_SERVER['PHP_SELF'] . '?atmid=' . $atmid ?> " method="POST">


                                        <div class="card">
                                            <table class="table" id="isGeneralInfoApproved">
                                                <tr>
                                                    <th> No of Atm Available</th>
                                                    <td>
                                                        <?php echo $noOfAtm; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>A T M I D1</th>
                                                    <td>
                                                        <?php echo $ATMID1; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td>
                                                        <?php echo $address; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>City</th>
                                                    <td>
                                                        <?php echo $city; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Location</th>
                                                    <td>
                                                        <?php echo $location; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>L H O</th>
                                                    <td>
                                                        <?php echo $LHO; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>State</th>
                                                    <td>
                                                        <?php echo $state; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>ATMID 1 Working</th>
                                                    <td>
                                                        <?php echo $atm1Status; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>ATMID1 Snap</th>
                                                    <td> <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                            data-value="<?php echo $id; ?>"
                                                            data-siteid=" <?php echo $siteid; ?>" data-imagetype="ATMID1Snap">
                                                            &nbsp; View Images
                                                        </button>
                                                        <?
                                                        // $ATMID1Snap_ar = explode(',', $ATMID1Snap);
                                                        // foreach ($ATMID1Snap_ar as $ATMID1Snap_ar_key => $ATMID1Snap_ar_val) {
                                                
                                                        //     $imageFileName = pathinfo($baseurl . $ATMID1Snap_ar_val, PATHINFO_BASENAME);
                                                        //     if (isImageFile($imageFileName)) {
                                                        //         echo '<a href="' . $baseurl . $ATMID1Snap_ar_val . '" ">View</a>';
                                                        //     } else {
                                                        //         echo 'No Image Found';
                                                        //     }
                                                
                                                        // }
                                                
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <a class="btn btn-primary approvalAjaxMethod" href="#"
                                                data-type="isGeneralInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                                data-feasibilityid=" <?php echo $id; ?>">Approve</a>
                                            <a class="btn btn-danger rejectionAjaxMethod" href="#"
                                                data-type="isGeneralInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                                data-feasibilityid=" <?php echo $id; ?>">Reject</a><!-- <a href="./approvalFeasibility.php?type=isGeneralInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isGeneralInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                            <?php echo checkFeasibiltyStatus($id, 'isGeneralInfoApproved'); ?>
                                        </div>


                                        <br>

                                        <div class="card">
                                            <table class="table" id="isNetworkInfoApproved">
                                                <tr>Network available in back room</tr>
                                                <tr>
                                                    <th>Operator 1</th>
                                                    <td>
                                                        <?php echo $operator; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Signal Status 1</th>
                                                    <td>
                                                        <?php echo $signalStatus; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Backroom Network Remark 1 </th>
                                                    <td>
                                                        <?php echo $backroomNetworkRemark; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Backroom Network Snap 1</th>
                                                    <td>
                                                        <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                            data-value="<?php echo $id; ?>"
                                                            data-siteid=" <?php echo $siteid; ?>"
                                                            data-imagetype="backroomNetworkSnap">
                                                            &nbsp; View Images
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Operator 2</th>
                                                    <td>
                                                        <?php echo $operator2; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Signal Status 2</th>
                                                    <td>
                                                        <?php echo $signalStatus2; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Backroom Network Remark 2</th>
                                                    <td>
                                                        <?php echo $backroomNetworkRemark2; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Backroom Network Snap 2</th>
                                                    <td>
                                                        <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                            data-value="<?php echo $id; ?>"
                                                            data-siteid=" <?php echo $siteid; ?>"
                                                            data-imagetype="backroomNetworkSnap2">
                                                            &nbsp; View Images
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                            <a class="btn btn-primary approvalAjaxMethod" href="#"
                                                data-type="isNetworkInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                                data-feasibilityid=" <?php echo $id; ?>">Approve</a>
                                            <a class="btn btn-danger rejectionAjaxMethod" href="#"
                                                data-type="isNetworkInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                                data-feasibilityid=" <?php echo $id; ?>">Reject</a>
                                            <!--                                     
                                    <a href="./approvalFeasibility.php?type=isNetworkInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isNetworkInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                            <?php echo checkFeasibiltyStatus($id, 'isNetworkInfoApproved'); ?>

                                        </div>

                                        <br>

                                        <table class="table" id="isBackroomInfoApproved">
                                            <tr>Back Room Key</tr>
                                            <tr>
                                                <th>Backroom Key Name</th>
                                                <td>
                                                    <?php echo $backroomKeyName; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Backroom Key Number</th>
                                                <td>
                                                    <?php echo $backroomKeyNumber; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Backroom Key Status</th>
                                                <td>
                                                    <?php echo $backroomKeyStatus; ?>
                                                </td>
                                            </tr>
                                        </table>


                                        <a class="btn btn-primary approvalAjaxMethod" href="#"
                                            data-type="isBackroomInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Approve</a>


                                        <a class="btn btn-danger rejectionAjaxMethod" href="#"
                                            data-type="isBackroomInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Reject</a>
                                        <!--                                     
                                    <a href="./approvalFeasibility.php?type=isBackroomInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isBackroomInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                        <?php echo checkFeasibiltyStatus($id, 'isBackroomInfoApproved'); ?>

                                        <br>
                                        EMlockAvailable<table class="table" id="isEmLockApproved">
                                            <tr>
                                                <th>EM lock Available</th>
                                                <td>
                                                    <?php echo $EMlockAvailable; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Password Received</th>
                                                <td>
                                                    <?php echo $PasswordReceived; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>E M Lock Password</th>
                                                <td>
                                                    <?php echo $EMLockPassword; ?>
                                                </td>
                                            </tr>
                                        </table>

                                        <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="isEmLockApproved"
                                            data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Approve</a>
                                        <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="isEmLockApproved"
                                            data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Reject</a>
                                        <!-- <a href="./approvalFeasibility.php?type=isEmLockApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isEmLockApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                        <?php echo checkFeasibiltyStatus($id, 'isEmLockApproved'); ?> <br>


                                        <table class="table" id="isRouterInfoApproved">
                                            <tr>router</tr>
                                            <tr>
                                                <th>Place to fix router</th>
                                                <td>
                                                    <?php echo $routerPosition; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Place to fixe router Snap</th>
                                                <td> <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid=" <?php echo $siteid; ?>"
                                                        data-imagetype="routerPositionSnap">
                                                        &nbsp; View Images
                                                    </button> </td>
                                            </tr>
                                            <tr>
                                                <th>Place to fix Router Antenna</th>
                                                <td>
                                                    <?php echo $routerAntenaPosition; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Router Antena Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid=" <?php echo $siteid; ?>"
                                                        data-imagetype="routerAntenaSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Antenna Routingdetail</th>
                                                <td>
                                                    <?php echo $AntennaRoutingdetail; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Antenna Routing Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid=" <?php echo $siteid; ?>"
                                                        data-imagetype="AntennaRoutingSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                        <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="isRouterInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Approve</a>
                                        <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="isRouterInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Reject</a>
                                        <!-- <a href="./approvalFeasibility.php?type=isRouterInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isRouterInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                        <?php echo checkFeasibiltyStatus($id, 'isRouterInfoApproved'); ?>

                                        <br>



                                        <table class="table" id="isUPSInfoApproved">
                                            <tr>UPS</tr>
                                            <tr>
                                                <th>U P S Available</th>
                                                <td>
                                                    <?php echo $UPSAvailable; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>U P S Available Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid=" <?php echo $siteid; ?>"
                                                        data-imagetype="UPSAvailableSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>No Of Ups</th>
                                                <td>
                                                    <?php echo $NoOfUps; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>No Of Ups Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid=" <?php echo $siteid; ?>"
                                                        data-imagetype="NoOfUpsSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>U P S Working1</th>
                                                <td>
                                                    <?php echo $UPSWorking1; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>U P S Working2</th>
                                                <td>
                                                    <?php echo $UPSWorking2; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>U P S Working3</th>
                                                <td>
                                                    <?php echo $UPSWorking3; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>UPS Working Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid=" <?php echo $siteid; ?>"
                                                        data-imagetype="upsWorkingSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>U P S Batery Backup</th>
                                                <td>
                                                    <?php echo $UPSBateryBackup; ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="isUPSInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid=" <?php echo $id; ?>">Approve</a>
                                        <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="isUPSInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>" data-feasibilityid="
                                                            <?php echo $id; ?>">Reject</a>
                                        <!-- <a href="./approvalFeasibility.php?type=isUPSInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isUPSInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                        <?php echo checkFeasibiltyStatus($id, 'isUPSInfoApproved'); ?>
                                        <br>
                                        <table class="table" id="isPowerInfoApproved">
                                            <tr>Power</tr>
                                            <tr>
                                                <th>Power Socket Available for Router in DB</th>
                                                <td>
                                                    <?php echo $powerSocketAvailability; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Socket Availability Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid="
                                                                            <?php echo $siteid; ?>"
                                                        data-imagetype="powerSocketAvailabilitySnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Socket Available for Router in UPS</th>
                                                <td>
                                                    <?php echo $powerSocketAvailabilityUPS; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Socket Available for Router in UPS Snap
                                                </th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid="
                                                                            <?php echo $siteid; ?>"
                                                        data-imagetype="powerSocketAvailabilityUPSSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Earthing</th>
                                                <td>
                                                    <?php echo $earthing; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Earthing Vltg</th>
                                                <td>
                                                    <?php echo $earthingVltg; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Earthing Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid="
                                                                            <?php echo $siteid; ?>"
                                                        data-imagetype="earthingSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Fluctuation PE</th>
                                                <td>
                                                    <?php echo $powerFluctuationPE; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Fluctuation PN</th>
                                                <td>
                                                    <?php echo $powerFluctuationPN; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Fluctuation EN</th>
                                                <td>
                                                    <?php echo $powerFluctuationEN; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Power Fluctuation Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid="
                                                                            <?php echo $siteid; ?>"
                                                        data-imagetype="powerFluctuationSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Frequent Power Cut</th>
                                                <td>
                                                    <?php echo $frequentPowerCut; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Frequent Power Cut From</th>
                                                <td>
                                                    <?php echo $frequentPowerCutFrom; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Frequent Power Cut To</th>
                                                <td>
                                                    <?php echo $frequentPowerCutTo; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Frequent Power Cut Remark</th>
                                                <td>
                                                    <?php echo $frequentPowerCutRemark; ?>
                                                </td>
                                            </tr>
                                        </table> <a class="btn btn-primary approvalAjaxMethod" href="#"
                                            data-type="isPowerInfoApproved" data-atmid="<?php echo $atmid; ?>"
                                            data-feasibilityid="
                                                                <?php echo $id; ?>">Approve</a>
                                        <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="isPowerInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>" data-feasibilityid="
                                                                    <?php echo $id; ?>">Reject</a>
                                        <!-- <a href="./approvalFeasibility.php?type=isPowerInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isPowerInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                        <?php echo checkFeasibiltyStatus($id, 'isPowerInfoApproved'); ?>
                                        <br>

                                        <table class="table" id="isOtherInfoApproved">
                                            <tr>Other </tr>
                                            <tr>
                                                <th>Backroom Disturbing Material</th>
                                                <td>
                                                    <?php echo $backroomDisturbingMaterial; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Backroom Disturbing Material Remark</th>
                                                <td>
                                                    <?php echo $backroomDisturbingMaterialRemark; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td>
                                                    <?php echo $Remarks; ?>
                                                </td>
                                            </tr>





                                            <tr>
                                                <th>Nearest Shop Distance</th>
                                                <td>
                                                    <?php echo $nearestShopDistance; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nearest Shop Name</th>
                                                <td>
                                                    <?php echo $nearestShopName; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nearest Shop Number</th>
                                                <td>
                                                    <?php echo $nearestShopNumber; ?>
                                                </td>
                                            </tr>







                                            <tr>
                                                <th>Remarks Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid="
                                                                                    <?php echo $siteid; ?>"
                                                        data-imagetype="remarksSnap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created Date</th>
                                                <td>
                                                    <?php echo $created_at; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created By</th>
                                                <td>
                                                    <?php echo getUsername($created_by, true); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Feasibility Done</th>
                                                <td>
                                                    <?php echo $feasibilityDone; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Is Vendor</th>
                                                <td>
                                                    <?php echo getVendorName($isVendor); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Ticketid</th>
                                                <td>
                                                    <?php echo $id; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>ATMID1 Snap</th>
                                                <td>
                                                    <button type="button" class="viewFeasibilityImage btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewFeasibilityImage-modal"
                                                        data-value="<?php echo $id; ?>" data-siteid="
                                                                                    <?php echo $siteid; ?>"
                                                        data-imagetype="ATMID1Snap">
                                                        &nbsp; View Images
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Verification Status</th>
                                                <td>
                                                    <?php echo $verificationStatus; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th> Action By </th>
                                                <td>
                                                    <?php echo $verificationByName; ?>
                                                </td>
                                            </tr>


                                        </table>
                                        <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="isOtherInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>" data-feasibilityid="
                                                                        <?php echo $id; ?>">Approve</a>
                                        <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="isOtherInfoApproved"
                                            data-atmid="<?php echo $atmid; ?>" data-feasibilityid="
                                                                            <?php echo $id; ?>">Reject</a>
                                        <!-- <a href="./approvalFeasibility.php?type=isOtherInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Approve</a>
                                    <a href="./rejectFeasibility.php?type=isOtherInfoApproved&atmid=<?php echo $atmid; ?>&feasibilityId=<?php echo $id; ?>">Reject</a> -->
                                        <?php echo checkFeasibiltyStatus($id, 'isOtherInfoApproved'); ?>
                                        <br>
                                        <br>
                                        <br>



                                        <?                                        // echo "select * from feasibilitycheck_checkpoints where atmid='".$atmid."' and isGeneralInfoApproved=1 and isNetworkInfoApproved=1 and isBackroomInfoApproved=1 and isEmLockApproved=1 and isRouterInfoApproved=1 and isUPSInfoApproved=1 and isPowerInfoApproved=1 and isOtherInfoApproved=1 and status=1";
// echo 
// "select * from feasibilitycheck_checkpoints where atmid='".$atmid."' and isGeneralInfoApproved=1 and isNetworkInfoApproved=1 and isBackroomInfoApproved=1 and isEmLockApproved=1 and isRouterInfoApproved=1 and isUPSInfoApproved=1 and isPowerInfoApproved=1 and isOtherInfoApproved=1 and status=1";
                                                $verifysql = mysqli_query($con, "select * from feasibilitycheck_checkpoints where atmid='" . $atmid . "' and isGeneralInfoApproved=1 and isNetworkInfoApproved=1 and isBackroomInfoApproved=1 and isEmLockApproved=1 and isRouterInfoApproved=1 and isUPSInfoApproved=1 and isPowerInfoApproved=1 and isOtherInfoApproved=1 and status=1");
                                                if ($verifysql_result = mysqli_fetch_assoc($verifysql)) {
                                                    if (isset($getverificationStatus) && !empty($getverificationStatus)) {
                                                        if ($getverificationStatus == 'Reject') {
                                                            echo '<h4>Rejected !</h4>';
                                                        } else if ($getverificationStatus == 'Verify') {
                                                            echo '<h4>Verified!</h4>';
                                                        }
                                                    } else {
                                                        $isVendor = $_SESSION['isVendor'];
                                                        $islho = $_SESSION['islho'];
                                                        $ADVANTAGE_level = $_SESSION['ADVANTAGE_level'];
                                                        if ($islho == 0 && $isVendor == 0) {
                                                            echo '<input type="text" name="feasibilityRemark" class="form-control" placeholder="Enter Remarks !" required />';
                                                            echo '<input type="hidden" name="atm_id" value="' . $atm_id . '" />';
                                                            echo '<input type="hidden" name="feasibiltyId" value="' . $id . '" />';
                                                            echo '<br />';
                                                            echo '<input type="submit" name="verifysubmit" value="Verify" class="btn btn-primary" onclick="return confirm(\'Are you sure you want to verify ?\');">';
                                                            echo '&nbsp;&nbsp;<input type="submit" name="rejectsubmit" value="Reject" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to reject ?\');">';
                                                        }
                                                    }
                                                } else {
                                                    echo '<h4> Approval Pending !</h4>';
                                                }
                                                ?>
                                    </form>


                                </div>



                                <?



                                $i++;
                            }
                        } else {
                            echo "No records found.";
                        }

                        $con->close();
                        ?>
                    </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="viewFeasibilityImage-modal" tabindex="-1" aria-labelledby="ModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-lg modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">View images </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="feasibilityImagesShowModal">



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Approval Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Remarks</label>
                        <input type="text" name="remarks" class="form-control noreadonly">
                    </div>

                    <div class="col-sm-12">
                        <br>
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">Rejection Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Remarks</label>
                        <input type="text" name="remarks" class="form-control noreadonly">
                    </div>

                    <div class="col-sm-12">
                        <br>
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<script>




    $(document).ready(function () {
        $('.approvalAjaxMethod').on('click', function (e) {
            e.preventDefault(); // Prevent default link behavior

            // Get data attributes from the link
            var type = $(this).data('type');
            var atmid = $(this).data('atmid');
            var feasibilityId = $(this).data('feasibilityid');
            var siteid = '<?php echo $_REQUEST['siteid']; ?>';

            // Show the modal
            $('#approvalModal').modal('show');

            // Handle the form submission inside the modal
            $('#approvalModal').on('click', '.btn-primary', function (e) {
                e.preventDefault();

                // Get the remark value
                var remarks = $('#approvalModal input[name="remarks"]').val();

                // AJAX request
                $.ajax({
                    url: './approvalAjaxFeasibility.php',
                    type: 'POST',
                    data: {
                        type: type,
                        atmid: atmid,
                        feasibilityId: feasibilityId,
                        remarks: remarks
                    },
                    success: function (response) {
                        // Handle success (e.g., show a success message, close the modal)
                        console.log(response);
                        // alert('Record Approved successfully!');
                        $('#approvalModal').modal('hide');

                        // Refresh the page and move to the specific section
                        window.location = './feasibilityReport1.php?siteid=' + siteid + '&atmid=' + atmid + '#' + type;
                        window.location.reload();
                    },
                    error: function () {
                        alert('An error occurred while approving the record.');
                    }
                });
            });
        });

        $('.rejectionAjaxMethod').on('click', function (e) {
            e.preventDefault(); // Prevent default link behavior

            // Get data attributes from the link
            var type = $(this).data('type');
            var atmid = $(this).data('atmid');
            var feasibilityId = $(this).data('feasibilityid');
            var siteid = '<?php echo $_REQUEST['siteid']; ?>';

            // Show the modal
            $('#rejectionModal').modal('show');

            // Handle the form submission inside the modal
            $('#rejectionModal').on('click', '.btn-primary', function (e) {
                e.preventDefault();

                // Get the remark value
                var remarks = $('#rejectionModal input[name="remarks"]').val();

                // AJAX request
                $.ajax({
                    url: './rejectionAjaxFeasibility.php',
                    type: 'POST',
                    data: {
                        type: type,
                        atmid: atmid,
                        feasibilityId: feasibilityId,
                        remarks: remarks
                    },
                    success: function (response) {
                        // Handle success (e.g., show a success message, close the modal)
                        console.log(response);
                        // alert('Record Approved successfully!');
                        $('#rejectionModal').modal('hide');

                        // Refresh the page and move to the specific section
                        window.location = './feasibilityReport1.php?siteid=' + siteid + '&atmid=' + atmid + '#' + type;
                        window.location.reload();
                    },
                    error: function () {
                        alert('An error occurred while approving the record.');
                    }
                });
            });
        });

    });


    // feasibilityImagesShowModal

    $('.viewFeasibilityImage').click(function (e) {
        siteId = $(this).data('siteid');
        id = $(this).data('value');
        imageType = $(this).data('imagetype');

        $('#feasibilityImagesShowModal').html('')

        $.ajax({
            url: 'viewFeasibilityImages.php',
            type: 'POST',
            data: {
                'siteId': siteId,
                'id': id,
                'imagetype': imageType, // Specify the type as ASD

            },
            success: function (response) {

                // $('#ModalLabel').html(response.label)

                $('#feasibilityImagesShowModal').html(response)
            }
        });
    });

</script>
<? include ('../footer.php'); ?>