<? include ('../header.php');


function getFeasibilityRemarks($feasibilityID,$parameter)
{

    global $con;
    $checkpointsql = mysqli_query($con, "select $parameter from feasibilitycheck_checkpoints where feasibiltyID='" . $feasibilityID . "'");
    if($checkpointsql_result = mysqli_fetch_assoc($checkpointsql)){
        if($checkpointsql_result[$parameter] == 0 || $checkpointsql_result[$parameter] == 1){
         

            $getremarksql = mysqli_query($con,"select * from feasibilitycheck_checkpoints_remarks where feasibiltyID='".$feasibilityID."' and fieldName='".$parameter."' order by id desc");
            $getremarksql_result = mysqli_fetch_assoc($getremarksql);
    
            $remark = $getremarksql_result['remark'];
            
            if($checkpointsql_result[$parameter]==1){
                $approvalStatus = 'Approved';
            }else if($checkpointsql_result[$parameter]==0){
                $approvalStatus = 'Rejected';
            }
    
            echo '<h5>Approval Status : '.$approvalStatus.'</h5>';
            echo '<h6>Approval Status : '.$remark.'</h6>';
            
        }else{
            echo '<h5>Approval Status : Pending</h5>';
            
        }
    }else{
        echo '<h5>Approval Status : Pending</h5>';
    }
    


}

?>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">

    <style>
        .graybackground{
            background: gray;
            padding: 20px;
            color: white;
        }
        .imgbox {
            position: relative;
        }

        .deleteImage {
            width: 25px;
            position: absolute;
            top: 0;
            right: 0;
            background: red;
            color: white;
            cursor: pointer;
            border-radius: 50%;
            text-align: center;
            vertical-align: middle;
        }

        .feasibilityimage {
            width: 150px;
            height: 150px;
        }

        .highlight {
            border-color: red;
        }

        select:focus,
        input:focus {
            border-bottom: 1px solid red !important;
        }

        input.form-control,
        input {
            /* border: none; */
            margin: 10px auto;
        }

        .form-control:focus {
            color: #55595c;
            background-color: #fff;
            border-color: #66afe9;
            outline: none;
            box-shadow: none;
            border: none;
        }

        .highlight {
            border-color: red;
        }

        .swal-overlay {
            background-color: rgba(43, 165, 137, 0.45);
        }

        input.form-control,
        input {
            /* border: none; */
            margin: 10px auto;
        }

        select.form-control,
        select {
            /* border: none; */
            margin: 10px auto;
        }


        .second_card .row {
            margin: 30px auto;
            /*border-bottom: 0.1px solid #efefef;*/
        }
    </style>
    <link rel="stylesheet" type="text/css" href="files/assets/pages/j-pro/css/j-pro-modern.css" />


    <div class="page-body">
        <?
        $siteid = $_REQUEST['siteid'];
        if (isset($siteid) && !empty($siteid)) {

            $sql = mysqli_query($con, "select * from sites where id='" . $siteid . "' and status=1");
            if ($sql_result = mysqli_fetch_assoc($sql)) {
                $atmid = $sql_result['atmid'];
                $atmid2 = $sql_result['atmid2'];
                $atmid3 = $sql_result['atmid3'];
                $address = $sql_result['address'];
                $city = $sql_result['city'];
                $state = $sql_result['state'];
                $lho = $sql_result['LHO'];
            }
        }



        ?>

        <h3>Feasibility Check</h3>
        <form id="feasibilityForm" action="process_updateFeasibilitycheck.php" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" name="userid" value="<? echo $userid; ?>" />



            <?php
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
                    $routerPositionSnap = $row['routerPositionSnap'];
                    $getverificationStatus = $row['verificationStatus'];
                    $routerPosition = $row['routerPosition'];


                    $isVendor = $row['isVendor'];
                    $atm_id = $row['atmid'];

                    $baseurl = 'http://clarity.advantagesb.com/API/';

                }
            }






            ?>

            <input type="hidden" name="feasibiltyid" value="<? echo $id; ?>" />

            <div class="card grid-margin">
                <div class="card-body">
                    <div id="isGeneralInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isGeneralInfoApproved'); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Number of ATM Available</label>
                            <select class="form-control" name="noOfAtm" id="noOfAtm">
                                <option value="">Select</option>
                                <option selected>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </div>

                        <div class="col-sm-6">


                            <label id="atm1StatusLabel">ATMID 1 Working</label>
                            <select class="form-control" name="atm1Status">
                                <option value="">Select</option>
                                <option <? if ($atm1Status == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($atm1Status == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>ATMID 1</label>
                            <input type="text" id="ATMID1" name="ATMID1" class="form-control" value="<? echo $atmid; ?>" <? if ($atmid) {
                                   echo 'readonly';
                               } ?> />

                            ATMID1 Snap &nbsp;

                            <?

                            $imagePaths = explode(",", $ATMID1Snap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='ATMID1Snap'>x</span>
                                </div>
                                <?php
                            }
                            ?>




                            <input type="file" name="ATMID1Snap[]" multiple accept="image/jpeg, image/jpg, image/png"
                                onchange="checkFileCount(this)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <label>City</label>
                            <input type="text" id="city" name="city" class="form-control" value="<? echo $city; ?>" <? if ($atmid) {
                                   echo 'readonly';
                               } ?> />
                        </div>
                        <div class="col-sm-3">
                            <label>Location</label>
                            <input type="text" id="location" name="location" class="form-control"
                                value="<? echo $address; ?>" <? if ($atmid) {
                                       echo 'readonly';
                                   } ?> />
                        </div>
                        <div class="col-sm-3">
                            <label>LHO</label>
                            <input type="text" id="LHO" name="LHO" class="form-control" value="<? echo $lho; ?>" <? if ($atmid) {
                                   echo 'readonly';
                               } ?> />
                        </div>
                        <div class="col-sm-3">
                            <label>State</label>
                            <input type="text" id="state" name="state" class="form-control" value="<? echo $state; ?>" <? if ($atmid) {
                                   echo 'readonly';
                               } ?> />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Address</label>
                            <input type="text" id="address" name="address" class="form-control"
                                value="<? echo $address; ?>" <? if ($atmid) {
                                       echo 'readonly';
                                   } ?> />
                        </div>
                    </div>

                </div>
            </div>


            <div class="card second_card grid-margin">
                <div class="card-block">
                <div id="isNetworkInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isNetworkInfoApproved'); ?>
                    </div>
                    <div class="row">
                        <h6 class="col-sm-3">Network available in back room</h6>
                        <div class="col-sm-2">
                            <label>Operator 1</label>
                            <select name="operator" class="form-control">
                                <option value="">Select</option>
                                <?php $operatorSql = mysqli_query($con, "select * from operator where status=1");
                                while ($operatorSqlResult = mysqli_fetch_assoc($operatorSql)) { ?>
                                    <option value="<?php echo $operatorSqlResult['operator']; ?>" <?php if ($operator == $operatorSqlResult['operator']) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $operatorSqlResult['operator']; ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Status of Signal</label>
                            <input name="signalStatus" type="text" class="form-control"
                                value="<?php echo $signalStatus; ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label>Remark</label>
                            <input name="backroomNetworkRemark" type="text" class="form-control"
                                value="<?php echo $backroomNetworkRemark; ?>" />
                        </div>
                        <div class="col-sm-2">
                            <label>Snapshot</label>

                            <?

                            $imagePaths = explode(",", $backroomNetworkSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='backroomNetworkSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>


                            <input name="backroomNetworkSnap[]" type="file" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>

                        <h6 class="col-sm-3"></h6>
                        <div class="col-sm-2">
                            <label>Operator 2</label>
                            <select name="operator2" class="form-control">
                                <option value="">Select</option>
                                <?php $operatorSql = mysqli_query($con, "select * from operator where status=1");
                                while ($operatorSqlResult = mysqli_fetch_assoc($operatorSql)) { ?>
                                    <option value="<?php echo $operatorSqlResult['operator']; ?>" <?php if ($operator2 == $operatorSqlResult['operator']) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $operatorSqlResult['operator']; ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Status of Signal</label>
                            <input name="signalStatus2" type="text" class="form-control"
                                value="<?php echo $signalStatus2; ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label>Remark</label>
                            <input name="backroomNetworkRemark2" type="text" class="form-control"
                                value="<?php echo $backroomNetworkRemark2; ?>" />
                        </div>
                        <div class="col-sm-2">
                            <label>Snapshot</label>


                            <?

                            $imagePaths = explode(",", $backroomNetworkSnap2);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='backroomNetworkSnap2'>x</span>
                                </div>
                                <?php
                            }
                            ?>


                            <input name="backroomNetworkSnap2[]" type="file" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>
                </div>
            </div>


            <div class="card second_card grid-margin">
                <div class="card-block">
                <div id="isBackroomInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isBackroomInfoApproved'); ?>
                    </div>
                    <div class="row">
                        <h6 class="col-sm-3">Back Room Key</h6>
                        <div class="col-sm-3">
                            <label>Status</label>
                            <select name="backroomKeyStatus" class="form-control">
                                <option value="">Select</option>
                                <option <?php if ($backroomKeyStatus == 'Available with LL') {
                                    echo 'selected';
                                } ?>>
                                    Available with LL</option>
                                <option <?php if ($backroomKeyStatus == 'Available with HK Person') {
                                    echo 'selected';
                                } ?>>
                                    Available with HK Person</option>
                                <option <?php if ($backroomKeyStatus == 'Available with MSP') {
                                    echo 'selected';
                                } ?>>
                                    Available with MSP</option>
                                <option <?php if ($backroomKeyStatus == 'Available with Bank') {
                                    echo 'selected';
                                } ?>>
                                    Available with Bank</option>
                                <option <?php if ($backroomKeyStatus == 'Not Available') {
                                    echo 'selected';
                                } ?>>Not
                                    Available</option>
                                <option>Any Other</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Backroom Contact Person Name</label>
                            <input name="backroomKeyName" type="text" class="form-control"
                                value="<?php echo $backroomKeyName; ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label>Backroom Contact Person Number</label>
                            <input name="backroomKeyNumber" type="text" class="form-control"
                                value="<?php echo $backroomKeyNumber; ?>" />
                        </div>
                    </div>
                </div>
            </div>


            <div class="card second_card grid-margin">
                <div class="card-block">
                
                <div id="isEmLockApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isEmLockApproved'); ?>
                    </div>
                    <div class="row">
                        <h6 class="col-sm-3">EM lock Available</h6>
                        <div class="col-sm-9">
                            <select class="form-control" name="EMlockAvailable" id="emLockAvailableSelect"
                                onchange="toggleEmLockAccess(this)">
                                <option value="">Select</option>
                                <option <?php if ($EMlockAvailable == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <?php if ($EMlockAvailable == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div id="emLockAccessSection"
                        style="display: <?php echo ($EMlockAvailable == 'Yes') ? 'block' : 'none'; ?>;"
                        class="extra_highlight">

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>EM lock Access</h6>
                            </div>
                            <div class="col-sm-3">
                                <label>Password Received</label>
                                <select class="form-control" name="PasswordReceived"
                                    onchange="togglePasswordField(this)">
                                    <option value="">Select</option>
                                    <option <?php if ($PasswordReceived == 'Yes') {
                                        echo 'selected';
                                    } ?>>Yes</option>
                                    <option <?php if ($PasswordReceived == 'No') {
                                        echo 'selected';
                                    } ?>>No</option>
                                </select>
                            </div>
                            <div class="col-sm-3" id="emLockPasswordField" <?php if ($PasswordReceived != 'Yes') { ?>
                                    style="display: none;" <? } ?>>
                                <label>EM Lock Password</label>
                                <input type="text" name="EMLockPassword" value="<?php echo $EMLockPassword; ?>"
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="card second_card grid-margin">
                <div class="card-block">
                    
                <div id="isRouterInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isRouterInfoApproved'); ?>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Place to fixe router</h6>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="routerPosition">
                                <option value="">Select</option>
                                <option <? if ($routerPosition == 'Rack Available') {
                                    echo 'selected';
                                } ?>>Rack Available
                                </option>
                                <option <? if ($routerPosition == 'Fixed on wall') {
                                    echo 'selected';
                                } ?>>Fixed on wall
                                </option>
                                <option <? if ($routerPosition == 'Above Ceiling') {
                                    echo 'selected';
                                } ?>>Above Ceiling
                                </option>
                                <option <? if ($routerPosition == 'Any Other') {
                                    echo 'selected';
                                } ?>>Any Other</option>
                            </select>
                        </div>
                        <div class="col-sm-3">

                            <?

                            $imagePaths = explode(",", $routerPositionSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='routerPositionSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>


                            <input type="file" name="routerPositionSnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Place to fix Router Antenna</h6>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="routerAntenaPosition">
                                <option value="">Select</option>
                                <option <? if ($routerAntenaPosition == 'Above Ceiling') {
                                    echo 'selected';
                                } ?>>Above
                                    Ceiling</option>
                                <option <? if ($routerAntenaPosition == 'In ATM lobby') {
                                    echo 'selected';
                                } ?>>In ATM
                                    lobby</option>
                                <option <? if ($routerAntenaPosition == 'Out Side the lobby') {
                                    echo 'selected';
                                } ?>>Out
                                    Side the lobby</option>
                                <option>Any Other</option>
                            </select>
                        </div>
                        <div class="col-sm-3">


                            <?

                            $imagePaths = explode(",", $routerAntenaSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='routerAntenaSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>




                            <input type="file" name="routerAntenaSnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Antenna Wire Routing detail</h6>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="AntennaRoutingdetail" value="<?= $AntennaRoutingdetail; ?>"
                                class="form-control" />
                        </div>
                        <div class="col-sm-3">


                            <?

                            $imagePaths = explode(",", $AntennaRoutingSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='AntennaRoutingSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>
                            <input type="file" name="AntennaRoutingSnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>

                </div>
            </div>



            <div class="card second_card grid-margin">
                <div class="card-block">

                <div id="isUPSInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isUPSInfoApproved'); ?>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                            <h6>UPS Available</h6>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" name="UPSAvailable" id="upsAvailableSelect">
                                <option value="">Select</option>
                                <option <? if ($UPSAvailable == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($UPSAvailable == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div id="upsOptionsContainer" class="extra_highlight">


                        <div class="row">
                            <div class="col-sm-3">
                                <h6>UPS Snap</h6>
                            </div>
                            <div class="col-sm-3">


                                <?

                                $imagePaths = explode(",", $UPSAvailableSnap);
                                foreach ($imagePaths as $imagePath) {
                                    ?>
                                    <div class="imgbox">
                                        <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                        <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                            data-imagetype='UPSAvailableSnap'>x</span>
                                    </div>
                                    <?php
                                }
                                ?>


                                <input type="file" name="UPSAvailableSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>No. of UPS</h6>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control" name="NoOfUps" id="noOfUpsSelect">
                                    <option value="">Select</option>
                                    <option <? if ($NoOfUps == '1') {
                                        echo 'selected';
                                    } ?>>1</option>
                                    <option <? if ($NoOfUps == '2') {
                                        echo 'selected';
                                    } ?>>2</option>
                                    <option <? if ($NoOfUps == '3') {
                                        echo 'selected';
                                    } ?>>3</option>
                                </select>
                            </div>
                            <div class="col-sm-3">

                                <?

                                $imagePaths = explode(",", $NoOfUpsSnap);
                                foreach ($imagePaths as $imagePath) {
                                    ?>
                                    <div class="imgbox">
                                        <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                        <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                            data-imagetype='NoOfUpsSnap'>x</span>
                                    </div>
                                    <?php
                                }
                                ?>


                                <input type="file" name="NoOfUpsSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>UPS Working</h6>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="UPSWorking1" id="upsWorking1Select" <? if ($NoOfUps >= 1) { ?> style="display:block;" <? } else { ?> style="display:none;" <? } ?>>
                                    <option value="">Select</option>
                                    <option <? if ($UPSWorking1 == 'Yes') {
                                        echo 'selected';
                                    } ?>>Yes</option>
                                    <option <? if ($UPSWorking1 == 'No') {
                                        echo 'selected';
                                    } ?>>NO</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="UPSWorking2" id="upsWorking2Select" <? if ($NoOfUps > 1) { ?> style="display:block;" <? } else { ?> style="display:none;" <? } ?>>
                                    <option value="">Select</option>
                                    <option <? if ($UPSWorking2 == 'Yes') {
                                        echo 'selected';
                                    } ?>>Yes</option>
                                    <option <? if ($UPSWorking2 == 'No') {
                                        echo 'selected';
                                    } ?>>NO</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="UPSWorking3" id="upsWorking3Select" <? if ($NoOfUps > 2) { ?> style="display:block;" <? } else { ?> style="display:none;" <? } ?>>
                                    <option value="">Select</option>
                                    <option <? if ($UPSWorking3 == 'Yes') {
                                        echo 'selected';
                                    } ?>>Yes</option>
                                    <option <? if ($UPSWorking3 == 'No') {
                                        echo 'selected';
                                    } ?>>NO</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <?

                                $imagePaths = explode(",", $upsWorkingSnap);
                                foreach ($imagePaths as $imagePath) {
                                    ?>
                                    <div class="imgbox">
                                        <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                        <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                            data-imagetype='upsWorkingSnap'>x</span>
                                    </div>
                                    <?php
                                }
                                ?>

                                <input type="file" name="upsWorkingSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>UPS Battery Backup</h6>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="UPSBateryBackup" value="<?= $UPSBateryBackup; ?>"
                                    class="form-control" placeholder="Hrs ..." />
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleEmLockAccess(emLockAvailableSelect) {
                            const emLockAccessSection = document.getElementById('emLockAccessSection');
                            if (emLockAvailableSelect.value === 'Yes') {
                                emLockAccessSection.style.display = 'block';
                            } else {
                                emLockAccessSection.style.display = 'none';
                            }
                        }

                        function togglePasswordField(passwordReceivedSelect) {
                            const emLockPasswordField = document.getElementById('emLockPasswordField');
                            if (passwordReceivedSelect.value === 'Yes') {
                                emLockPasswordField.style.display = 'block';
                            } else {
                                emLockPasswordField.style.display = 'none';
                            }
                        }


                        const upsAvailableSelect = document.getElementById('upsAvailableSelect');

                        // Get the UPS options container
                        const upsOptionsContainer = document.getElementById('upsOptionsContainer');

                        // Function to toggle visibility based on UPS Available selection
                        function toggleUpsOptionsVisibility() {
                            const isUpsAvailable = upsAvailableSelect.value === 'Yes';
                            upsOptionsContainer.style.display = isUpsAvailable ? 'block' : 'none';
                        }

                        // Initial call to set the visibility based on the default value
                        toggleUpsOptionsVisibility();

                        // Attach an event listener to the UPS Available select element
                        upsAvailableSelect.addEventListener('change', toggleUpsOptionsVisibility);
                    </script>




                </div>
            </div>


            <div class="card second_card grid-margin">
                <div class="card-block">
                <div id="isPowerInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isPowerInfoApproved'); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Power Socket Available for Router in DB</h6>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="powerSocketAvailability">
                                <option value="">Select</option>
                                <option <? if ($powerSocketAvailability == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($powerSocketAvailability == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                        <div class="col-sm-3">



                            <?

                            $imagePaths = explode(",", $powerSocketAvailabilitySnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='powerSocketAvailabilitySnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>


                            <input type="file" name="powerSocketAvailabilitySnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Power Socket Available for Router in UPS</h6>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="powerSocketAvailabilityUPS">
                                <option value="">Select</option>
                                <option <? if ($powerSocketAvailabilityUPS == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($powerSocketAvailabilityUPS == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                        <div class="col-sm-3">

                            <?

                            $imagePaths = explode(",", $powerSocketAvailabilityUPSSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='powerSocketAvailabilityUPSSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>


                            <input type="file" name="powerSocketAvailabilityUPSSnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Earthing</h6>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="earthing">
                                <option value="">Select</option>
                                <option <? if ($earthing == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($earthing == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?= $earthingVltg; ?>" name="earthingVltg"
                                class="form-control" placeholder="EN Vtg ... " />
                        </div>
                        <div class="col-sm-3">


                            <?

                            $imagePaths = explode(",", $earthingSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='earthingSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>


                            <input type="file" name="earthingSnap[]" multiple accept="image/jpeg, image/jpg, image/png"
                                onchange="checkFileCount(this)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Power Fluctuation</h6>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" value="<?= $powerFluctuationPE; ?>"
                                name="powerFluctuationPE" placeholder="PE vtg.." />
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" value="<?= $powerFluctuationPN; ?>"
                                name="powerFluctuationPN" placeholder="PN vtg.." />
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" value="<?= $powerFluctuationEN; ?>"
                                name="powerFluctuationEN" placeholder="EN vtg.." />
                        </div>

                        <div class="col-sm-3">
                            <?

                            $imagePaths = explode(",", $powerFluctuationSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='powerFluctuationSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>




                            <input type="file" name="powerFluctuationSnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Frequent Power cut</h6>
                        </div>
                        <div class="col-sm-2">
                            <select name="frequentPowerCut" class="form-control" onchange="toggleFields(this)">
                                <option value="">Select</option>
                                <option <? if ($frequentPowerCut == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($frequentPowerCut == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                        <div class="col-sm-2" id="powerCutFromContainer">
                            <input type="text" class="form-control" value="<?= $frequentPowerCutFrom; ?>"
                                name="frequentPowerCutFrom" placeholder="Frequent Power Cut From" />
                        </div>
                        <div class="col-sm-2" id="powerCutToContainer">
                            <input type="text" class="form-control" value="<?= $frequentPowerCutTo; ?>"
                                name="frequentPowerCutTo" placeholder="To" />
                        </div>
                        <div class="col-sm-3" id="powerCutRemarkContainer">
                            <input type="text" class="form-control" value="<?= $frequentPowerCutRemark; ?>"
                                name="frequentPowerCutRemark" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>



            <div class="card second_card grid-margin">
                <div class="card-block">
                <div id="isOtherInfoApproved" class="graybackground">
                        <?php getFeasibilityRemarks($id,'isOtherInfoApproved'); ?>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Unwanted material in back room which bars access for working</h6>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" name="backroomDisturbingMaterial"
                                onchange="toggleFields2(this)">
                                <option value="">Select</option>
                                <option <? if ($backroomDisturbingMaterial == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($backroomDisturbingMaterial == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                        <div class="col-sm-3" id="backroomRemarkContainer">
                            <input type="text" value="<?= $backroomDisturbingMaterialRemark; ?>"
                                name="backroomDisturbingMaterialRemark" class="form-control"
                                placeholder="Remarks ... " />
                        </div>
                        <div class="col-sm-3" id="backroomSnapContainer">

                            <?

                            $imagePaths = explode(",", $backroomDisturbingMaterialSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='backroomDisturbingMaterialSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>





                            <input type="file" name="backroomDisturbingMaterialSnap[]" multiple
                                accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Nearest Hadware or Electric Shop</h6>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?= $nearestShopDistance; ?>"
                                name="nearestShopDistance" placeholder="Distance From ATM " />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?= $nearestShopName; ?>"
                                name="nearestShopName" placeholder="Name ..." />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?= $nearestShopNumber; ?>"
                                name="nearestShopNumber" placeholder="Number ..." />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Any Other Remark</h6>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="Remarks" value="<?= $Remarks ?>" class="form-control"
                                placeholder="Remarks ... " />
                        </div>
                        <div class="col-sm-3">
                            <?

                            $imagePaths = explode(",", $remarksSnap);
                            foreach ($imagePaths as $imagePath) {
                                ?>
                                <div class="imgbox">
                                    <img class="feasibilityimage" src="<?= $imagePath; ?>" />
                                    <span class="deleteImage" data-feasibilityid='<?= $id; ?>'
                                        data-imagetype='remarksSnap'>x</span>
                                </div>
                                <?php
                            }
                            ?>

                            <input type="file" name="remarksSnap[]" multiple accept="image/jpeg, image/jpg, image/png"
                                onchange="checkFileCount(this)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <h6>Feasibility Done</h6>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" name="feasibilityDone">
                                <option value="">Select</option>
                                <option <? if ($feasibilityDone == 'Yes') {
                                    echo 'selected';
                                } ?>>Yes</option>
                                <option <? if ($feasibilityDone == 'No') {
                                    echo 'selected';
                                } ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <br />
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" id="submitButton" class="btn btn-success">Update Info</button>
                    <div id="loadingIndicator" style="display: none;">Please Wait ...</div>
                    <!--<input type="submit" name="submit" class="btn btn-success" />-->
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {

            // Show/hide ATMID 2 and ATMID 3 based on the selected value





            // $(".atm-status").hide();
            $("#noOfAtm").on("change", function () {
                var noOfAtm = $(this).val();
                // $(".atm-status").hide();
                $(".atm-status").prev("label").hide();
                for (var i = 1; i <= noOfAtm; i++) {
                    $("select[name='atm" + i + "Status']").show();
                    $("label#atmId" + i + "Label").show();
                    $("label#atm" + i + "StatusLabel").show();
                }

                if (noOfAtm >= 1) {
                    $("input[name='ATMID1']").show();
                    $("label[for='ATMID1']").show();
                    $("input[name='ATMID1Snap']").show();

                } else {
                    $("input[name='ATMID1']").hide();
                    $("label[for='ATMID1']").hide();
                    $("input[name='ATMID1Snap']").hide();
                }

                if (noOfAtm >= 2) {
                    $(".atmid2Section").show();
                } else {
                    $(".atmid2Section").hide();
                }
                if (noOfAtm >= 3) {
                    $(".atmid3Section").show();
                } else {
                    $(".atmid3Section").hide();

                }


            });

            $("#noOfUpsSelect").change(function () {
                var noOfUps = $(this).val();
                // Show/hide UPSWorking fields based on selected NoOfUps
                $("#upsWorking1Select").toggle(noOfUps >= 1);
                $("#upsWorking2Select").toggle(noOfUps >= 2);
                $("#upsWorking3Select").toggle(noOfUps >= 3);

                $("#upsWorking1Select").prop("required", noOfUps >= 1);
                $("#upsWorking2Select").prop("required", noOfUps >= 2);
                $("#upsWorking3Select").prop("required", noOfUps >= 3);
            });
        });

        $(document).ready(function () {
            // Function to populate form fields

            function populateFormFields(data) {
                if (data) {
                    $("#address").prop("readonly", true);
                    $("#city").prop("readonly", true);
                    $("#location").prop("readonly", true);
                    $("#LHO").prop("readonly", true);
                    $("#state").prop("readonly", true);

                    $("#address").val(data.address);
                    $("#city").val(data.city);
                    $("#location").val(data.location);
                    $("#LHO").val(data.lho);
                    $("#state").val(data.state);
                } else {
                    $("#address").prop("readonly", false);
                    $("#city").prop("readonly", false);
                    $("#location").prop("readonly", false);
                    $("#LHO").prop("readonly", false);
                    $("#state").prop("readonly", false);

                    $("#address").val("");
                    $("#city").val("");
                    $("#location").val("");
                    $("#LHO").val("");
                    $("#state").val("");
                }
            }

            // Event listener for change in ATMID1
            $("#ATMID1").change(function () {
                var atmID = $(this).val();
                if (atmID !== "") {
                    // AJAX request to fetch ATM details
                    $.ajax({

                        url: "../admin/API/getATMIDInfo.php?ATMID1=" + atmID,
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            $("#address").val("");
                            $("#city").val("");
                            $("#location").val("");
                            $("#LHO").val("");
                            $("#state").val("");

                            if (response.code === 200) {
                                populateFormFields(response); // Populate form fields with response data
                            } else if (response.code === 300) {
                                populateFormFields(response);
                                swal("Error", "ATMID Not found !", "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                            alert("An error occurred while fetching ATM details. Please try again.");
                        },
                    });
                } else {
                    // Clear form fields if ATMID1 is empty
                    $("#address").val("");
                    $("#city").val("");
                    $("#location").val("");
                    $("#LHO").val("");
                    $("#state").val("");
                }
            });
        });

        function toggleFields2(selectElement) {
            var backroomRemarkContainer = document.getElementById("backroomRemarkContainer");
            var backroomSnapContainer = document.getElementById("backroomSnapContainer");

            if (selectElement.value === "Yes") {
                backroomRemarkContainer.style.display = "block";
                backroomSnapContainer.style.display = "block";
            } else {
                backroomRemarkContainer.style.display = "none";
                backroomSnapContainer.style.display = "none";
            }
        }

        function togglePasswordField(selectElement) {
            var emLockPasswordField = document.getElementById("emLockPasswordField");

            if (selectElement.value === "Yes") {
                emLockPasswordField.style.display = "block";
            } else {
                emLockPasswordField.style.display = "none";
            }
        }

        function toggleFields(selectElement) {
            var powerCutFromContainer = document.getElementById("powerCutFromContainer");
            var powerCutToContainer = document.getElementById("powerCutToContainer");
            var powerCutRemarkContainer = document.getElementById("powerCutRemarkContainer");

            if (selectElement.value === "Yes") {
                powerCutFromContainer.style.display = "block";
                powerCutToContainer.style.display = "block";
                powerCutRemarkContainer.style.display = "block";
            } else {
                powerCutFromContainer.style.display = "none";
                powerCutToContainer.style.display = "none";
                powerCutRemarkContainer.style.display = "none";
            }
        }

        function saveForm() {
            event.preventDefault();

            var submitButton = document.getElementById("submitButton");
            var loadingIndicator = document.getElementById("loadingIndicator");

            submitButton.disabled = true;
            loadingIndicator.style.display = "block";

            var formData = new FormData($("#feasibilityForm")[0]);
            var requiredFields = $("#feasibilityForm :required");
            var emptyFields = [];

            // Check for empty required fields
            requiredFields.each(function () {
                if ($(this).val() === "") {
                    emptyFields.push($(this).attr("name"));
                }
            });

            if (emptyFields.length > 0) {
                swal("Error", "Please fill in all the required fields!", "error");
                $("input, select").removeClass("highlight");
                $.each(emptyFields, function (index, fieldName) {
                    $('[name="' + fieldName + '"]').addClass("highlight");
                });

                return; // Exit the function if empty fields exist

            }

            $.ajax({
                url: "process_feasibilitycheck.php",
                type: "POST",
                data: formData,
                contentType: false, // Important: Set contentType to false for file uploads
                processData: false, // Important: Disable processing of the data
                headers: {
                    'Referrer-Policy': 'strict-origin-when-cross-origin'
                },

                success: function (response) {
                    console.log(response);
                    var jsonResponse = JSON.parse(response); // Parse the JSON response
                    console.log(jsonResponse.code);
                    if (jsonResponse.code == 200) {
                        submitButton.disabled = false;
                        loadingIndicator.style.display = "none";
                        swal("Success", "Response Saved Successfully!", "success");
                        setTimeout(function () {
                            window.location.href = "assignLeads.php";
                        }, 3000); // Redirect after 3 seconds
                    } else {
                        console.error(jsonResponse.error); // Assuming the error message is provided in the response
                        submitButton.disabled = false;
                        loadingIndicator.style.display = "none";
                        alert("An error occurred. Please check log for details.");
                    }
                },

                error: function (xhr, status, error) {
                    console.error(error);

                    alert("An error occurred: " + error); // Display a generic error message

                    if (xhr.responseText) {
                        try {
                            var errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.response) {
                                alert("Server Error: " + errorResponse.response);
                            }
                        } catch (e) {
                            console.error("Error parsing error response:", e);
                        }
                    }

                },
            });
        }
    </script>


</div>


<script>

    function checkFileCount(input) {
        if (input.files.length > 5) {
            alert("Maximum 5 images are allowed.");
            input.value = ''; // Clear the file input to remove the selected files
        }
    }
    $('.deleteImage').click(function (e) {
        feasibilityid = $(this).data('feasibilityid');
        imageType = $(this).data('imagetype');

        $('#feasibilityImagesShowModal').html('')

        $.ajax({
            url: 'deletefeasibilityImages.php',
            type: 'POST',
            data: {
                'feasibilityid': feasibilityid,
                'imagetype': imageType, // Specify the type as ASD

            },
            success: function (response) {
                if (response == 1) {
                    alert('Record modified successfully !');
                    window.location.reload();
                } else {
                    alert('Error !');

                }
            }
        });
    });


</script>

<? include ('../footer.php'); ?>