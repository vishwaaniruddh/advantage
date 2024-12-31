<? include ('../header.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$baseurl = 'http://clarity.advantagesb.com/eng/';


function checkInstallationStatus($feasibilityID,$parameter)
{

    global $con;
    $checkpointsql = mysqli_query($con, "select $parameter from installationcheckpoints where installationID='" . $feasibilityID . "'");
    if($checkpointsql_result = mysqli_fetch_assoc($checkpointsql)){
        if($checkpointsql_result[$parameter] == 0 || $checkpointsql_result[$parameter] == 1){
         

            $getremarksql = mysqli_query($con,"select * from installationcheck_checkpoints_remarks where installationID='".$feasibilityID."' and fieldName='".$parameter."' order by id desc");
            $getremarksql_result = mysqli_fetch_assoc($getremarksql);
    
            $remark = $getremarksql_result['remark'];
            
            if($checkpointsql_result[$parameter]==1){
                $approvalStatus = 'Approved';
            }else if($checkpointsql_result[$parameter]==2){
                $approvalStatus = 'Rejected';
            }else if($checkpointsql_result[$parameter]==0){
                $approvalStatus = 'Pending';
            }
    
            echo '<h5>Approval Status : '.$approvalStatus.'</h5>';
            echo '<h6>Remark : '.$remark.'</h6>';
            
        }else{
            echo '<h5>Approval Status : Pending</h5>';
            
        }
    }else{
        echo '<h5>Approval Status : Pending</h5>';
    }
    


}




?>

<style>
     .graybackground{
            background: gray;
            padding: 20px;
            color: white;
        }
        .rejectionAjaxMethod,.approvalAjaxMethod{
            display:none;
        }
    /* Style for disabled radio buttons */
    input[type="radio"]:disabled {
        /* Change color to dark red */
        color: darkred;
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

    .imgbox {
        position: relative;
    }
</style>

<div class="card">
    <div class="card-block">

        <style>
            .installationImage {
                width: 300px;
                height: 300px;
            }
        </style>

        <form id="feasibilityForm" action="process_updateInstallationData.php" method="POST"
            enctype="multipart/form-data">

            <?


            $siteid = $_REQUEST['siteid'];
            $atmid = $_REQUEST['atmid'];

            //   echo "SELECT * FROM `installationData` where atmid='".$atmid."' order by id desc" ; 
            
            $sql = mysqli_query($con, "SELECT * FROM `installationData` where atmid='" . $atmid . "' order by id desc");
            if ($sql_result = mysqli_fetch_assoc($sql)) {


                $installationId = $sql_result['id'];

                $atmId = $sql_result['atmId'];
                $atmId2 = $sql_result['atmId2'];
                $atmId3 = $sql_result['atmId3'];
                $address = $sql_result['address'];
                $city = $sql_result['city'];
                $location = $sql_result['location'];
                $lho = $sql_result['lho'];
                $state = $sql_result['state'];
                $atmWorking1 = $sql_result['atmWorking1'];
                $atmWorking2 = $sql_result['atmWorking2'];
                $atmWorking3 = $sql_result['atmWorking3'];
                $vendorName = $sql_result['vendorName'];
                $engineerName = $sql_result['engineerName'];
                $engineerNumber = $sql_result['engineerNumber'];
                $routerSerial = $sql_result['routerSerial'];
                $routerMake = $sql_result['routerMake'];
                $routerModel = $sql_result['routerModel'];
                $routerFixed = $sql_result['routerFixed'];
                $routerFixedRemarks = $sql_result['routerFixedRemarks'];
                $routerFixedSnaps = $sql_result['routerFixedSnaps'];
                $routerStatus = $sql_result['routerStatus'];
                $routerStatusRemarks = $sql_result['routerStatusRemarks'];
                $routerStatusSnaps = $sql_result['routerStatusSnaps'];
                $adaptorSnaps = $sql_result['adaptorSnaps'];
                $adaptorStatusRemarks = $sql_result['adaptorStatusRemarks'];
                $adaptorStatusSnaps = $sql_result['adaptorStatusSnaps'];
                $lanCableInstallRemark = $sql_result['lanCableInstallRemark'];
                $lanCableInstallSnap = $sql_result['lanCableInstallSnap'];
                $lanCableStatusNotWorkingReasons = $sql_result['lanCableStatusNotWorkingReasons'];
                $lanCableStatusRemark = $sql_result['lanCableStatusRemark'];
                $lanCableStatusSnap = $sql_result['lanCableStatusSnap'];
                $antennaRemarks = $sql_result['antennaRemarks'];
                $antennaSnaps = $sql_result['antennaSnaps'];
                $antennaStatus = $sql_result['antennaStatus'];
                $antennaStatusRemarks = $sql_result['antennaStatusRemarks'];
                $antennaStatusSnaps = $sql_result['antennaStatusSnaps'];
                $gpsRemarks = $sql_result['gpsRemarks'];
                $gpsSnaps = $sql_result['gpsSnaps'];
                $gpsStatusRemarks = $sql_result['gpsStatusRemarks'];
                $gpsStatusSnaps = $sql_result['gpsStatusSnaps'];
                $wifiRemarks = $sql_result['wifiRemarks'];
                $wifiSnaps = $sql_result['wifiSnaps'];
                $wifiStatusRemarks = $sql_result['wifiStatusRemarks'];
                $wifiStatusSnaps = $sql_result['wifiStatusSnaps'];
                $airtelSimRemarks = $sql_result['airtelSimRemarks'];
                $airtelSimSnaps = $sql_result['airtelSimSnaps'];
                $airtelSimStatusRemarks = $sql_result['airtelSimStatusRemarks'];
                $airtelSimStatusSnaps = $sql_result['airtelSimStatusSnaps'];
                $vodafoneSimRemarks = $sql_result['vodafoneSimRemarks'];
                $vodafoneSimSnaps = $sql_result['vodafoneSimSnaps'];
                $vodafoneSimStatusRemarks = $sql_result['vodafoneSimStatusRemarks'];
                $vodafoneSimStatusSnaps = $sql_result['vodafoneSimStatusSnaps'];
                $jioSimRemarks = $sql_result['jioSimRemarks'];
                $jioSimSnaps = $sql_result['jioSimSnaps'];
                $jioSimStatusRemarks = $sql_result['jioSimStatusRemarks'];
                $jioSimStatusSnaps = $sql_result['jioSimStatusSnaps'];
                $vendorStamp = $sql_result['vendorStamp'];
                $signatureImage = $sql_result['signatureImage'];


                $adaptorInstalled = $sql_result['adaptorInstalled'];
                $adaptorStatus = $sql_result['adaptorStatus'];
                $lanCableInstalled = $sql_result['lanCableInstalled'];
                $lanCableStatus = $sql_result['lanCableStatus'];
                $antennaInstalled = $sql_result['antennaInstalled'];
                $gpsInstalled = $sql_result['gpsInstalled'];
                $gpsStatus = $sql_result['gpsStatus'];
                $wifiInstalled = $sql_result['wifiInstalled'];
                $wifiStatus = $sql_result['wifiStatus'];
                $airtelSimInstalled = $sql_result['airtelSimInstalled'];
                $airtelSimStatus = $sql_result['airtelSimStatus'];
                $vodafoneSimInstalled = $sql_result['vodafoneSimInstalled'];
                $vodafoneSimStatus = $sql_result['vodafoneSimStatus'];
                $jioSimInstalled = $sql_result['jioSimInstalled'];
                $jioSimStatus = $sql_result['jioSimStatus'];



                ?>
                <input type="hidden" name="siteid" value="<? echo $siteid; ?>">
                <input type="hidden" name="atmid" value="<? echo $atmid; ?>">

                <p>Step 1</p>
                <table>
                    <tr>
                  
                    </tr>
                    <tr>
                        <td><label for="atmId">ATM ID:</label></td>
                        <td><input type="text" id="atmId" name="atmId" value="<? echo $atmid; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td><label for="address">Address:</label></td>
                        <td><input type="text" id="address" name="address" value="<? echo $address; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="city">City:</label></td>
                        <td><input type="text" id="city" name="city" value="<? echo $city; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td><label for="location">Location:</label></td>
                        <td><input type="text" id="location" name="location" value="<? echo $address; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="lho">LHO:</label></td>
                        <td><input type="text" id="lho" name="lho" value="<? echo $lho; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="state">State:</label></td>
                        <td><input type="text" id="state" name="state" value="<? echo $state; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td><label for="atmWorking1">Atm 1 Working </label></td>
                        <td>
                            <input type="text" name="atmWorking1" value="<?= $atmWorking1; ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td><label for="vendorName">Installation Vendor Name:</label></td>
                        <td><input type="text" id="vendorName" name="vendorName" value="<?= $vendorName ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td><label for="engineerName">Installation Engineer Name & Number:</label></td>
                        <td><input type="text" id="engineerName" name="engineerName" value="<?= $engineerName; ?>" /><input
                                type="text" id="engineerNumber" name="engineerNumber" value="<?= $engineerNumber; ?>" />
                        </td>
                    </tr>
                </table>
                <br>


                <br>

                <p>Step 2</p>

                <table>

                
                    <tr>
                        <td>
                            <h5>Router</h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label for="routerSerial">Serial No:</label></td>
                        <td><input type="text" id="routerSerial" name="routerSerial" value="<?= $routerSerial; ?>"
                                 />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="routerMake">Make:</label></td>
                        <td><input type="text" id="routerMake" name="routerMake" value="<?= $routerMake; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="routerModel">Model:</label></td>
                        <td><input type="text" id="routerModel" name="routerModel" value="<?= $routerModel; ?>" /></td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Router Fixed</h5>
                        </td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td><label for="routerFixedYes">Yes:</label></td>
                        <td><input type="radio" id="routerFixedYes" name="routerFixed" value="yes" <? if ($routerFixed == 'Yes' || $routerFixed == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="routerFixedNo">No:</label></td>
                        <td><input type="radio" id="routerFixedNo" name="routerFixed" value="no" <? if ($routerFixed == 'No') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="routerFixedRemarks">Remarks:</label></td>
                        <td><input type="text" id="routerFixedRemarks" name="routerFixedRemarks"
                                value="<?= $routerFixedRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="routerFixedSnaps">Snaps:</label></td>
                        <td>
                            <div class="imgbox">


                                <?
                                $routerFixedSnapsAR = explode(',', $routerFixedSnaps);

                                foreach ($routerFixedSnapsAR as $routerFixedSnapsARKEY => $routerFixedSnapsARVAL) {

                                    ?>
                                    <a href="<?= $baseurl . $routerFixedSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                        src="<?= $baseurl . $routerFixedSnapsARVAL; ?>" />
                                    </a>
                                <?
                                }
                                ?>

                                <span class="deleteImage" data-installation-id="<?php echo $installationId; ?>"
                                    data-imagetype="routerFixedSnaps">x</span>
                            </div>

                            <h5>
                                <?
                                $routerFixedSnapsResponse = getInstallationStatusWithRemark('routerFixedSnaps', $installationId);

                                if ($routerFixedSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($routerFixedSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($routerFixedSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($routerFixedSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>
                            <!-- isset($routerFixedSnapsResponse['status']) &&  -->
                            <?php if ($routerFixedSnapsResponse['status'] != 1) { ?>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="routerFixedSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>


                                <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="routerFixedSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>

                                <hr>

                                <input id="INSTALLATION_routerFixedSnaps" class="ajaxImageUpload" name="routerFixedSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>

                            <!-- UPLOAD NEW FILE -->
                            <!-- <input type="file" id="routerFixedSnaps" name="routerFixedSnaps" /> -->


                        </td>
                    </tr>
                    <tr>
                    
                        <td>
                            <h5>Router Status</h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label for="routerStatusWorking">Working:</label></td>
                        <td><input type="radio" id="routerStatusWorking" name="routerStatus" value="working" <? if ($routerStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="routerStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="routerStatusNotWorking" name="routerStatus" value="notWorking" <? if ($routerStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="routerStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="routerStatusRemarks" name="routerStatusRemarks"
                                value="<?= $routerStatusRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="routerStatusSnaps">Snaps:</label></td>
                        <td>
                            <div class="imgbox">

                                <?
                                $routerStatusSnapsAR = explode(',', $routerStatusSnaps);

                                foreach ($routerStatusSnapsAR as $routerStatusSnapsARKEY => $routerStatusSnapsARVAL) {

                                    ?>
                                    <a href="<?= $baseurl . $routerStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                        src="<?= $baseurl . $routerStatusSnapsARVAL; ?>" />
                                    </a>
                                <?
                                }
                                ?>

                                <br>
                            </div>

                            <h5>
                                <?
                                $routerStatusSnapsResponse = getInstallationStatusWithRemark('routerStatusSnaps', $installationId);

                                if ($routerStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($routerStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($routerStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($routerStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($routerStatusSnapsResponse['status'] != 1) { ?>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="routerStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>

                                <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="routerStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>

                                <hr>
                                <input id="INSTALLATION_routerStatusSnaps" class="ajaxImageUpload" name="routerStatusSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>


                        </td>
                    </tr>
                </table>


                <br>

                <p>Step 3</p>
                <table>

                    <tr>
                        <td>
                            <h5>Adaptor Installed</h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label for="adaptorInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="adaptorInstalledYes" name="adaptorInstalled" value="yes" <? if ($adaptorInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="adaptorInstalledNo">No:</label></td>
                        <td><input type="radio" id="adaptorInstalledNo" name="adaptorInstalled" value="no" <? if ($adaptorInstalled == 'no') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="adaptorSnaps">Snaps:</label></td>
                        <td>

                            <?
                            $adaptorSnapsAR = explode(',', $adaptorSnaps);

                            foreach ($adaptorSnapsAR as $adaptorSnapsARKEY => $adaptorSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $adaptorSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $adaptorSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>



                            <br>

                            <h5>
                                <?
                                $adaptorSnapsResponse = getInstallationStatusWithRemark('adaptorSnaps', $installationId);

                                if ($adaptorSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($adaptorSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($adaptorSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($adaptorSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($adaptorSnapsResponse['status'] != 1) { ?>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="adaptorSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>

                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="adaptorSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                              

                                <hr>
                                <input id="INSTALLATION_adaptorSnaps" class="ajaxImageUpload" name="adaptorSnaps[]" type="file"
                                    multiple accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            <? } ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Adaptor Status</h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label for="adaptorStatusWorking">Working:</label></td>
                        <td><input type="radio" id="adaptorStatusWorking" name="adaptorStatus" value="working" <? if ($adaptorStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="adaptorStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="adaptorStatusNotWorking" name="adaptorStatus" value="notWorking" <? if ($adaptorStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="adaptorStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="adaptorStatusRemarks" name="adaptorStatusRemarks"
                                value="<?= $adaptorStatusRemarks; ?>" /></td>
                    </tr>
                    <tr id="adaptorStatusSnaps">
                        <td><label for="adaptorStatusSnaps">Snaps:</label></td>

                        <td>

                            <?
                            $adaptorStatusSnapsAR = explode(',', $adaptorStatusSnaps);

                            foreach ($adaptorStatusSnapsAR as $adaptorStatusSnapsARKEY => $adaptorStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $adaptorStatusSnapsARVAL; ?>" ">
                                         <img class=" installationImage" src="<?= $baseurl . $adaptorStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>
                            <h5>
                                <?
                                $adaptorStatusSnapsResponse = getInstallationStatusWithRemark('adaptorStatusSnaps', $installationId);

                                // var_dump($adaptorStatusSnapsResponse);
                                if ($adaptorStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($adaptorStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($adaptorStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($adaptorStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($adaptorStatusSnapsResponse['status'] != 1) { ?>


                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="adaptorStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                             
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="adaptorStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                              
                                <hr>
                                <input id="INSTALLATION_adaptorStatusSnaps" class="ajaxImageUpload" name="adaptorStatusSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />

                            <? } ?>

                        </td>
                    </tr>
                </table>

                <br>
                <p>Step 4</p>
                <table>
                    <tr>
                        <td>
                            <h5>LAN Cable Installed</h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstalledYes">Yes</label></td>
                        <td><input type="radio" id="lanCableInstalledYes" name="lanCableInstalled" value="yes" <? if ($lanCableInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstalledNo">No</label></td>
                        <td><input type="radio" id="lanCableInstalledNo" name="lanCableInstalled" value="no" <? if ($lanCableInstalled == 'no') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstallRemark">Remarks:</label></td>
                        <td><input type="text" id="lanCableInstallRemark" name="lanCableInstallRemark"
                                value="<?= $lanCableInstallRemark; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstallSnap">Snaps:</label></td>
                        <td>
                            <?
                            $lanCableInstallSnapAR = explode(',', $lanCableInstallSnap);

                            foreach ($lanCableInstallSnapAR as $lanCableInstallSnapARKEY => $lanCableInstallSnapARVAL) {

                                ?>
                                <a href="<?= $baseurl . $lanCableInstallSnapARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $lanCableInstallSnapARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>
                            <h5>
                                <?

                                $lanCableInstallSnapResponse = getInstallationStatusWithRemark('lanCableInstallSnap', $installationId);

                                // var_dump($lanCableInstallSnapResponse);
                                if ($lanCableInstallSnapResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($lanCableInstallSnapResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($lanCableInstallSnapResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($lanCableInstallSnapResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($lanCableInstallSnapResponse['status'] != 1) { ?>


                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="lanCableInstallSnap"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>

                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="lanCableInstallSnap"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                              
                               
                                    <hr>


                                <input id="INSTALLATION_lanCableInstallSnap" class="ajaxImageUpload"
                                    name="lanCableInstallSnap[]" type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>

                        </td>
                    </tr>

                    <tr>
                        <td>
                            <h5>LAN Cable Status</h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label>Yes</label></td>
                        <td><input type="radio" id="lanCableStatusYes" name="lanCableStatus" value="yes" <? if ($lanCableStatus == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstalledNo">No</label></td>
                        <td>
                            <input type="radio" id="lanCableStatusNo" name="lanCableStatus" value="no" <? if ($lanCableStatus == 'no') {
                                echo 'checked';
                            } ?> />
                            &nbsp;&nbsp;&nbsp;&nbsp;

                            <input type="text" value="<?= $lanCableStatusNotWorkingReasons; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstallRemark">Remarks:</label></td>
                        <td><input type="text" id="lanCableStatusRemark" name="lanCableStatusRemark"
                                value="<?= $lanCableStatusRemark; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="lanCableInstallSnap">Snaps:</label></td>
                        <td>
                            <a href="<?= $baseurl . $lanCableStatusSnap; ?>" ">
                
                                                                            <img class=" installationImage"
                                src="<?= $baseurl . $lanCableStatusSnap; ?>" />
                            </a>
                            <?
                            $lanCableStatusSnapAR = explode(',', $lanCableStatusSnap);

                            foreach ($lanCableStatusSnapAR as $lanCableStatusSnapARKEY => $lanCableStatusSnapARVAL) {

                                ?>
                                <a href="<?= $baseurl . $lanCableStatusSnapARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $lanCableStatusSnapARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>

                            <h5>
                                <?
                                $lanCableStatusSnapResponse = getInstallationStatusWithRemark('lanCableStatusSnap', $installationId);

                                if ($lanCableStatusSnapResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($lanCableStatusSnapResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($lanCableStatusSnapResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($lanCableStatusSnapResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($lanCableStatusSnapResponse['status'] != 1) { ?>



                                <br>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="lanCableStatusSnap"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                               
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="lanCableStatusSnap"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                            
                                <hr>

                                <input id="INSTALLATION_lanCableStatusSnap" class="ajaxImageUpload" name="lanCableStatusSnap[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>
                </table>

                <br>

                <p>Step 5</p>
                <table>
                    <tr>
                        <h5>4G Antenna Installed</h5>
                    </tr>
                    <tr>
                        <td><label for="antennaInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="antennaInstalledYes" name="antennaInstalled" value="yes" <? if ($antennaInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="antennaInstalledNo">No:</label></td>
                        <td><input type="radio" id="antennaInstalledNo" name="antennaInstalled" value="no" <? if ($antennaInstalled == 'no') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="antennaRemarks">Remarks:</label></td>
                        <td><input type="text" id="antennaRemarks" name="antennaRemarks" value="<?= $antennaRemarks; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="antennaSnaps">Snaps:</label></td>
                        <td>

                            <?
                            $antennaSnapsAR = explode(',', $antennaSnaps);

                            foreach ($antennaSnapsAR as $antennaSnapsARKEY => $antennaSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $antennaSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $antennaSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $antennaSnapsResponse = getInstallationStatusWithRemark('antennaSnaps', $installationId);

                                if ($antennaSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($antennaSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($antennaSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($antennaSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($antennaSnapsResponse['status'] != 1) { ?>


                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="antennaSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                                
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="antennaSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                            
                                <hr>

                                <input id="INSTALLATION_antennaSnaps" class="ajaxImageUpload" name="antennaSnaps[]" type="file"
                                    multiple accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <h5>4G Antenna Status</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="antennaStatusWorking">Working:</label></td>
                        <td><input type="radio" id="antennaStatusWorking" name="antennaStatus" value="working" <? if ($antennaStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>

                    <tr>
                        <td><label for="antennaStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="antennaStatusNotWorking" name="antennaStatus" value="notWorking" <? if ($antennaStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="antennaStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="antennaStatusRemarks" name="antennaStatusRemarks"
                                value="<?= $antennaStatusRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="antennaStatusSnaps">Snaps:</label></td>
                        <td>
                            <?
                            $antennaStatusSnapsAR = explode(',', $antennaStatusSnaps);

                            foreach ($antennaStatusSnapsAR as $antennaStatusSnapsARKEY => $antennaStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $antennaStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $antennaStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>

                            <h5>
                                <?
                                $antennaStatusSnapsResponse = getInstallationStatusWithRemark('antennaStatusSnaps', $installationId);

                                if ($antennaStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($antennaStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($antennaStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($antennaStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($antennaStatusSnapsResponse['status'] != 1) { ?>


                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="antennaStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                        
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="antennaStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     
                                   <hr>

                                <input id="INSTALLATION_antennaStatusSnaps" class="ajaxImageUpload" name="antennaStatusSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>

                        </td>
                    </tr>

                </table>

                <br>
                <p>Step 6</p>
                <table>


                    <tr>
                        <td colspan="2">
                            <h5>GPS Antenna Installed</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="gpsInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="gpsInstalledYes" name="gpsInstalled" value="yes" <? if ($gpsInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="gpsInstalledNo">No:</label></td>
                        <td><input type="radio" id="gpsInstalledNo" name="gpsInstalled" value="no" <? if ($gpsInstalled == 'no') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="gpsRemarks">Remarks:</label></td>
                        <td><input type="text" id="gpsRemarks" name="gpsRemarks" value="<?= $gpsRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="gpsSnaps">Snaps:</label></td>
                        <td>
                            <?
                            $gpsSnapsAR = explode(',', $gpsSnaps);

                            foreach ($gpsSnapsAR as $gpsSnapsARKEY => $gpsSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $gpsSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $gpsSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>



                            <h5>
                                <?
                                $gpsSnapsResponse = getInstallationStatusWithRemark('gpsSnaps', $installationId);

                                if ($gpsSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($gpsSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($gpsSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($gpsSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($gpsSnapsResponse['status'] != 1) { ?>


                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="gpsSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>

                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="gpsSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     
                                <hr>


                                <input id="INSTALLATION_gpsSnaps" class="ajaxImageUpload" name="gpsSnaps[]" type="file" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>

                    </tr>

                    <tr>
                        <td colspan="2">
                            <h5>GPS Antenna Status</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="gpsStatusWorking">Working:</label></td>
                        <td><input type="radio" id="gpsStatusWorking" name="gpsStatus" value="working" <? if ($gpsStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="gpsStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="gpsStatusNotWorking" name="gpsStatus" value="notWorking" <? if ($gpsStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="gpsStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="gpsStatusRemarks" name="gpsStatusRemarks"
                                value="<?= $gpsStatusRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="gpsStatusSnaps">Snaps:</label></td>
                        <td>

                            <?
                            $gpsStatusSnapsAR = explode(',', $gpsStatusSnaps);

                            foreach ($gpsStatusSnapsAR as $gpsStatusSnapsARKEY => $gpsStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $gpsStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $gpsStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $gpsStatusSnapsResponse = getInstallationStatusWithRemark('gpsStatusSnaps', $installationId);

                                if ($gpsStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($gpsStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($gpsStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($gpsStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($gpsStatusSnapsResponse['status'] != 1) { ?>


                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="gpsStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                             
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="gpsStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     
                                <hr>


                                <input id="INSTALLATION_gpsStatusSnaps" class="ajaxImageUpload" name="gpsStatusSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>

                </table>

                <br>
                <p>Step 7</p>
                <table>

                    <tr>
                        <td colspan="2">
                            <h5>Wifi Antenna Installed</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wifiInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="wifiInstalledYes" name="wifiInstalled" value="yes" <? if ($wifiInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="wifiInstalledNo">No:</label></td>
                        <td><input type="radio" id="wifiInstalledNo" name="wifiInstalled" value="no" <? if ($wifiInstalled == 'no') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="wifiRemarks">Remarks:</label></td>
                        <td><input type="text" id="wifiRemarks" name="wifiRemarks" value="<?= $wifiRemarks; ?>" /></td>
                    </tr>
                    <tr id="wifiSnaps">
                        <td><label for="wifiSnaps">Snaps:</label></td>
                        <td>

                            <?
                            $wifiSnapsAR = explode(',', $wifiSnaps);

                            foreach ($wifiSnapsAR as $wifiSnapsARKEY => $wifiSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $wifiSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $wifiSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>




                            <h5>
                                <?
                                $wifiSnapsResponse = getInstallationStatusWithRemark('wifiSnaps', $installationId);

                                if ($wifiSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($wifiSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($wifiSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($wifiSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($wifiSnapsResponse['status'] != 1) { ?>

                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="wifiSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                             
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="wifiSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

                                <hr>


                                <input id="INSTALLATION_wifiSnaps" class="ajaxImageUpload" name="wifiSnaps[]" type="file"
                                    multiple accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            <? } ?>

                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <h5>Wifi Antenna Status</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wifiStatusWorking">Working:</label></td>
                        <td><input type="radio" id="wifiStatusWorking" name="wifiStatus" value="working" <? if ($wifiStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="wifiStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="wifiStatusNotWorking" name="wifiStatus" value="notWorking" <? if ($wifiStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="wifiStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="wifiStatusRemarks" name="wifiStatusRemarks"
                                value="<?= $wifiStatusRemarks; ?>" /></td>
                    </tr>
                    <tr id="wifiStatusSnaps">
                        <td><label for="wifiStatusSnaps">Snaps:</label></td>
                        <td>

                            <?
                            $wifiStatusSnapsAR = explode(',', $wifiStatusSnaps);

                            foreach ($wifiStatusSnapsAR as $wifiStatusSnapsARKEY => $wifiStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $wifiStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $wifiStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $wifiStatusSnapsResponse = getInstallationStatusWithRemark('wifiStatusSnaps', $installationId);

                                if ($wifiStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($wifiStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($wifiStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($wifiStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($wifiStatusSnapsResponse['status'] != 1) { ?>

                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="wifiStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="wifiStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

                                <hr>

                                <input id="INSTALLATION_wifiStatusSnaps" class="ajaxImageUpload" name="wifiStatusSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>

                </table>

                <br>

                <p>Step 8</p>
                <table>

                    <tr>
                        <td colspan="2">
                            <h5>Airtel SIM Installed</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="airtelSimInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="airtelSimInstalledYes" name="airtelSimInstalled" value="yes" <? if ($airtelSimInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="airtelSimInstalledNo">No:</label></td>
                        <td><input type="radio" id="airtelSimInstalledNo" name="airtelSimInstalled" value="no" <? if ($airtelSimInstalled == 'no') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="airtelSimRemarks">Remarks:</label></td>
                        <td><input type="text" id="airtelSimRemarks" name="airtelSimRemarks"
                                value="<?= $airtelSimRemarks; ?>" /></td>
                    </tr>
                    <tr id="airtelSimSnaps">
                        <td><label for="airtelSimSnaps">Snaps:</label></td>
                        <td>
                            <a href="<?= $baseurl . $airtelSimSnaps; ?>" ">
                                                                        <img class=" installationImage"
                                src="<?= $baseurl . $airtelSimSnaps; ?>" />
                            </a>


                            <?
                            $airtelSimSnapsAR = explode(',', $airtelSimSnaps);

                            foreach ($airtelSimSnapsAR as $airtelSimSnapsARKEY => $airtelSimSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $airtelSimSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $airtelSimSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $airtelSimSnapsResponse = getInstallationStatusWithRemark('airtelSimSnaps', $installationId);

                                if ($airtelSimSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($airtelSimSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($airtelSimSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($airtelSimSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($airtelSimSnapsResponse['status'] != 1) { ?>

                                <br>

                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="airtelSimSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                              
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="airtelSimSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

           <hr>

                                <input id="INSTALLATION_airtelSimSnaps" class="ajaxImageUpload" name="airtelSimSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>

                    <!-- airtel SIM Status -->
                    <tr>
                        <td colspan="2">
                            <h5>Airtel SIM Status</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="airtelSimStatusWorking">Working:</label></td>
                        <td><input type="radio" id="airtelSimStatusWorking" name="airtelSimStatus" value="working" <? if ($airtelSimStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="airtelSimStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="airtelSimStatusNotWorking" name="airtelSimStatus" value="notWorking" <? if ($airtelSimStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="airtelSimStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="airtelSimStatusRemarks" name="airtelSimStatusRemarks"
                                value="<?= $airtelSimStatusRemarks; ?>" /></td>
                    </tr>
                    <tr id="airtelSimStatusSnaps">
                        <td><label for="airtelSimStatusSnaps">Snaps:</label></td>
                        <td>
                            <?
                            $airtelSimStatusSnapsAR = explode(',', $airtelSimStatusSnaps);

                            foreach ($airtelSimStatusSnapsAR as $airtelSimStatusSnapsARKEY => $airtelSimStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $airtelSimStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $airtelSimStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $airtelSimStatusSnapsResponse = getInstallationStatusWithRemark('airtelSimStatusSnaps', $installationId);

                                if ($airtelSimStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($airtelSimStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($airtelSimStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($airtelSimStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($airtelSimStatusSnapsResponse['status'] != 1) { ?>

                                <br>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="airtelSimStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>

                                                                  
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="airtelSimStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     


                                <hr>


                                <input id="INSTALLATION_airtelSimStatusSnaps" class="ajaxImageUpload"
                                    name="airtelSimStatusSnaps[]" type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>

                        </td>
                    </tr>

                    <!-- Vodafone SIM Installed -->
                    <tr>
                        <td colspan="2">
                            <h5>Vodafone SIM Installed</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="vodafoneSimInstalledYes" name="vodafoneSimInstalled" value="yes" <? if ($vodafoneSimInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimInstalledNo">No:</label></td>
                        <td><input type="radio" id="vodafoneSimInstalledNo" name="vodafoneSimInstalled" value="no" <? if ($vodafoneSimInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimRemarks">Remarks:</label></td>
                        <td><input type="text" id="vodafoneSimRemarks" name="vodafoneSimRemarks"
                                value="<?= $vodafoneSimRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimSnaps">Snaps:</label></td>
                        <td>
                            <?
                            $vodafoneSimSnapsAR = explode(',', $vodafoneSimSnaps);

                            foreach ($vodafoneSimSnapsAR as $vodafoneSimSnapsARKEY => $vodafoneSimSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $vodafoneSimSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $vodafoneSimSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $vodafoneSimSnapsResponse = getInstallationStatusWithRemark('vodafoneSimSnaps', $installationId);

                                if ($vodafoneSimSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($vodafoneSimSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($vodafoneSimSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($vodafoneSimSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($vodafoneSimSnapsResponse['status'] != 1) { ?>

                                <br>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="vodafoneSimSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                                                                    
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="vodafoneSimSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     
                                <hr>


                                <input id="INSTALLATION_vodafoneSimSnaps" class="ajaxImageUpload" name="vodafoneSimSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />

                            <? } ?>
                        </td>
                    </tr>

                    <!-- Vodafone SIM Status -->
                    <tr>
                        <td colspan="2">
                            <h5>Vodafone SIM Status</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimStatusWorking">Working:</label></td>
                        <td><input type="radio" id="vodafoneSimStatusWorking" name="vodafoneSimStatus" value="working" <? if ($vodafoneSimStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="vodafoneSimStatusNotWorking" name="vodafoneSimStatus" value="notWorking"
                                <? if ($vodafoneSimStatus == 'notWorking') {
                                    echo 'checked';
                                } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="vodafoneSimStatusRemarks" name="vodafoneSimStatusRemarks"
                                value="<?= $vodafoneSimStatusRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="vodafoneSimStatusSnaps">Snaps:</label></td>
                        <td>
                            <?
                            $vodafoneSimStatusSnapsAR = explode(',', $vodafoneSimStatusSnaps);

                            foreach ($vodafoneSimStatusSnapsAR as $vodafoneSimStatusSnapsARKEY => $vodafoneSimStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $vodafoneSimStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $vodafoneSimStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $vodafoneSimStatusSnapsResponse = getInstallationStatusWithRemark('vodafoneSimStatusSnaps', $installationId);

                                if ($vodafoneSimStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($vodafoneSimStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($vodafoneSimStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($vodafoneSimStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($vodafoneSimStatusSnapsResponse['status'] != 1) { ?>

                                <br>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="vodafoneSimStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                                    
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="vodafoneSimStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

                                 <hr>

                                <input id="INSTALLATION_vodafoneSimStatusSnaps" class="ajaxImageUpload"
                                    name="vodafoneSimStatusSnaps[]" type="file" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>

                    <!-- JIO SIM Installed -->
                    <tr>
                        <td colspan="2">
                            <h5>JIO SIM Installed</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="jioSimInstalledYes">Yes:</label></td>
                        <td><input type="radio" id="jioSimInstalledYes" name="jioSimInstalled" value="yes" <? if ($jioSimInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="jioSimInstalledNo">No:</label></td>
                        <td><input type="radio" id="jioSimInstalledNo" name="jioSimInstalled" value="no" <? if ($jioSimInstalled == 'yes') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="jioSimRemarks">Remarks:</label></td>
                        <td><input type="text" id="jioSimRemarks" name="jioSimRemarks" value="<?= $jioSimRemarks; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="jioSimSnaps">Snaps:</label></td>
                        <td>
                            <?
                            $jioSimSnapsAR = explode(',', $jioSimSnaps);

                            foreach ($jioSimSnapsAR as $jioSimSnapsARKEY => $jioSimSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $jioSimSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $jioSimSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>


                            <h5>
                                <?
                                $jioSimSnapsResponse = getInstallationStatusWithRemark('jioSimSnaps', $installationId);

                                if ($jioSimSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($jioSimSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($jioSimSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($jioSimSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($jioSimSnapsResponse['status'] != 1) { ?>


                                <br>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="jioSimSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>
                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="jioSimSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

                                <hr>


                                <input id="INSTALLATION_jioSimSnaps" class="ajaxImageUpload" name="jioSimSnaps[]" type="file"
                                    multiple accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>

                    <!-- JIO SIM Status -->
                    <tr>
                        <td colspan="2">
                            <h5>JIO SIM Status</h5>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="jioSimStatusWorking">Working:</label></td>
                        <td><input type="radio" id="jioSimStatusWorking" name="jioSimStatus" value="working" <? if ($jioSimStatus == 'working') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="jioSimStatusNotWorking">Not Working:</label></td>
                        <td><input type="radio" id="jioSimStatusNotWorking" name="jioSimStatus" value="notWorking" <? if ($jioSimStatus == 'notWorking') {
                            echo 'checked';
                        } ?> /></td>
                    </tr>
                    <tr>
                        <td><label for="jioSimStatusRemarks">Remarks:</label></td>
                        <td><input type="text" id="jioSimStatusRemarks" name="jioSimStatusRemarks"
                                value="<?= $jioSimStatusRemarks; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="jioSimStatusSnaps">Snaps:</label></td>
                        <td>

                            <?
                            $jioSimStatusSnapsAR = explode(',', $jioSimStatusSnaps);

                            foreach ($jioSimStatusSnapsAR as $jioSimStatusSnapsARKEY => $jioSimStatusSnapsARVAL) {

                                ?>
                                <a href="<?= $baseurl . $jioSimStatusSnapsARVAL; ?>" ">
                                                                                            <img class=" installationImage"
                                    src="<?= $baseurl . $jioSimStatusSnapsARVAL; ?>" />
                                </a>
                            <?
                            }
                            ?>



                            <h5>
                                <?
                                $jioSimStatusSnapsResponse = getInstallationStatusWithRemark('jioSimStatusSnaps', $installationId);

                                if ($jioSimStatusSnapsResponse['response'] == 200) {

                                    echo 'Verfication Status : ' . ($jioSimStatusSnapsResponse['status'] == 1 ? 'Approved' : 'Reject') . '<br />';
                                    echo 'Action By : ' . getUsername($jioSimStatusSnapsResponse['verifyby']) . '<br />';
                                    echo 'Remark : ' . ($jioSimStatusSnapsResponse['remark']) . '<br />';

                                } else {
                                    echo 'Action Pending';
                                }


                                ?>

                            </h5>

                            <?php if ($jioSimStatusSnapsResponse['status'] != 1) { ?>


                                <br>
                                <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="jioSimStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>

                                    <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="jioSimStatusSnaps"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

                                <hr>


                                <input id="INSTALLATION_jioSimStatusSnaps" class="ajaxImageUpload" name="jioSimStatusSnaps[]"
                                    type="file" multiple accept="image/jpeg, image/jpg, image/png"
                                    onchange="checkFileCount(this)" />
                            <? } ?>
                        </td>
                    </tr>
                </table>
                <br>
                <p>Step 9</p>
                <table>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <h5>Signature of Engineer Installed</h5>

                            <div>
                                <label for="signatureCanvas">Digital Signature:</label><br />

                                <img class="installationImage" src="<?= $signatureImage; ?>" />

                            </div>

                            <br />
                            <label for="vendorStamp">Stamp of Vendor:</label>

                            <img class="installationImage" src="<?= $baseurl . $vendorStamp; ?>" />
                            <input type="file" id="vendorStamp" name="vendorStamp" />
                            <br>
                            <a class="btn btn-primary approvalAjaxMethod" href="#" data-type="isvendorStamp_infoApproved"
                                data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Approve</a>


                                <a class="btn btn-danger rejectionAjaxMethod" href="#" data-type="isvendorStamp_infoApproved"
                                    data-atmid="<?= $atmid; ?>" data-installationid="<?= $installationId; ?>">Reject</a>
                     

                    
                        </td>
                    </tr>
                </table>




            <? } ?>

            <input type="hidden" name="installationId" value="<?php echo $installationId; ?>">
            <!-- <input type="submit" name="submit" class="btn btn-primary approvalAjaxMethod" value="Submit" /> -->

        </form>


        <hr>

        <?php

        $icpsql = "select  isApprovedrouterFixedSnaps,
        isApprovedrouterStatusSnaps,
        isApprovedadaptorSnaps,
        isApprovedadaptorStatusSnaps,
        isApprovedantennaSnaps,
        isApprovedantennaStatusSnaps,
        isApprovedgpsSnaps,
        isApprovedgpsStatusSnaps,
        isApprovedwifiSnaps,
        isApprovedwifiStatusSnaps,
        isApprovedairtelSimSnaps,
        isApprovedairtelSimStatusSnaps,
        isApprovedvodafoneSimSnaps,
        isApprovedvodafoneSimStatusSnaps,
        isApprovedjioSimSnaps,
        isApprovedjioSimStatusSnaps,
        isApprovedlanCableInstallSnap,
        isApprovedlanCableStatusSnap from installationcheckpoints where installationID = '" . $installationId . "'";
        // $icpsql_result = mysqli_fetch_assoc($icpsql);
        
        $icpsqlResult = $con->query($icpsql);

        if ($icpsqlResult->num_rows > 0) {
            $row = $icpsqlResult->fetch_assoc();
            $allApproved = true;
            foreach ($row as $key => $value) {
                if ($value != 1) {
                    $allApproved = false;
                    break;
                }
            }

            // Conditionally show the form
            if ($allApproved) {

                // echo '<form action="submitinstallationVerification.php" method="post">';
                ?>
                <!-- <input type="hidden" name="installationID" value="<?php echo $installationId; ?>" /> -->
                <?php
                // echo '
                // <input type="submit" value="Verify This Installation">
                //       </form>';
            } else {
                echo '<h1>Not all fields are approved.<h1>';

echo '<hr />';

?>

<!-- <form action="./installationApproveAllAtOnce.php" method="POST" >
    <input type="hidden" name="installationid" value="<?php echo $installationId ; ?>">
    <input type="hidden" name="atmid" value="<?php echo $atmid ; ?>">
    
    <input type="submit" value="Approve All" class="btn btn-primary">

</form> -->
<?


            }
        } else {
            echo "0 results";
        }





        ?>




    </div>
</div>


<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 1px 4px;
        border: 1px solid #000;
    }

    #signatureCanvas {
        border: 1px solid #000;

    }

    ul li a[disabled] {
        pointer-events: none;
        /* Disable click events */
        opacity: 0.5;
        /* Make the tab appear less visible */
        cursor: not-allowed;
        /* Show a "not allowed" cursor */
    }
</style>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


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
            var installationId = $(this).data('installationid');
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
                    url: './approvalAjax.php',
                    type: 'POST',
                    data: {
                        type: type,
                        atmid: atmid,
                        installationId: installationId,
                        remarks: remarks
                    },
                    success: function (response) {
                        // Handle success (e.g., show a success message, close the modal)
                        console.log(response);
                        // alert('Record Approved successfully!');
                        $('#approvalModal').modal('hide');

                        // Refresh the page and move to the specific section
                        window.location = './installationInfo.php?siteid=' + siteid + '&atmid=' + atmid + '#' + type;
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
            var installationId = $(this).data('installationid');
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
                    url: './rejectionAjax.php',
                    type: 'POST',
                    data: {
                        type: type,
                        atmid: atmid,
                        installationId: installationId,
                        remarks: remarks
                    },
                    success: function (response) {
                        // Handle success (e.g., show a success message, close the modal)
                        console.log(response);
                        // alert('Record Approved successfully!');
                        $('#rejectionModal').modal('hide');

                        // Refresh the page and move to the specific section
                        window.location = './installationInfo.php?siteid=' + siteid + '&atmid=' + atmid + '#' + type;
                        window.location.reload();
                    },
                    error: function () {
                        alert('An error occurred while approving the record.');
                    }
                });
            });
        });

    });


    $(document).on('change', '.ajaxImageUpload', function () {
        var image_name = $(this).attr('name');
        var atmid = '<?php echo $atmid; ?>';
        var installationId = '<?php echo $installationId; ?>';

        var files = $(this)[0].files;
        var formData = new FormData();

        // Append each file to the formData object
        $.each(files, function (i, file) {
            formData.append('files[]', file);
        });

        formData.append('atmid', atmid);
        formData.append('image_name', image_name);
        formData.append('installationId', installationId);



        $.ajax({
            url: 'uploadInstallationImage.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Handle the response from the server
                alert('Upload successful!');
                console.log(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle any errors
                alert('Upload failed!');
                console.log(textStatus, errorThrown);
            }
        });
    });


    const installationId = '<?php echo $installationId; ?>';
    function checkFileCount(input) {
        if (input.files.length > 5) {
            alert("Maximum 5 images are allowed.");
            input.value = ''; // Clear the file input to remove the selected files
        } else {
            const files = input.files;
            if (files.length > 0) {
                const name = input.name.split('[]')[0]; // Extract the base name from the input name
                uploadFiles(files, name);
            }
        }
    }

    function uploadFiles(files, snapName) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        formData.append('imageName', snapName); // Add the imageName to the formData

        $.ajax({
            url: 'instalation_image_upload.php', // Replace with your server upload URL
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log('Upload successful!', response);
                // Handle the response from the server
            },
            error: function (xhr, status, error) {
                console.error('Upload failed!', error);
                // Handle any errors
            }
        });
    }

    $(document).ready(function () {
        // $('input[type="text"]').prop('readonly', true);
        // $('input[type="radio"]').prop('disabled', true);

        $('input[type="text"].noreadonly').prop('readonly', false);

        $('input[type="text"]').addClass("form-control");
        $("select").addClass("form-control");
    });


    $('.deleteImage').click(function (e) {
        let installationID = $(this).data('installation-id');
        // installationID = $(this).data('installationID');
        let imageType = $(this).data('imagetype');

        $('#feasibilityImagesShowModal').html('')

        $.ajax({
            url: 'delete_InstalationImage.php',
            type: 'POST',
            data: {
                'installationID': installationID,
                'imagetype': imageType, // Specify the type as ASD

            },
            success: function (response) {
                console.log(response)
                if (response == 1) {

                    alert('Record modified successfully !');
                    // window.location.reload();
                } else {
                    alert('Error !');

                }
            }
        });
    });



</script>
<? include ('../footer.php'); ?>