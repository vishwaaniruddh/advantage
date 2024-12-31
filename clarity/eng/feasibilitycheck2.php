<? include('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<?php

include('./feasibilityParts.php');
?>


<div class="row">
    <div class="col-sm-12 grid-margin">

        <style>
            .hidden {
                display: none;
            }
        </style>
        <?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            var_dump($_REQUEST);

            $ATMID1 = $_REQUEST['ATMID1'];

            // Check if ATMID exists and is active
            echo $check_sql = "SELECT * FROM sites WHERE atmid = '$ATMID1' AND status = 1";
            $check_sql_result = mysqli_query($con, $check_sql);

            if ($check_sql_result && mysqli_num_rows($check_sql_result) > 0) {
                $siteid = $_REQUEST['siteid'];
                $noOfAtm = $_REQUEST['noOfAtm'];
                $address = $_REQUEST['address'];
                $state = $_REQUEST['state'];
                $city = $_REQUEST['city'];
                $location = $_REQUEST['location'];
                $LHO = $_REQUEST['LHO'];
                $atm1Status = $_REQUEST['atm1Status'];
                echo '<br />';
                if (isset($siteid) && isset($ATMID1)) {
                    echo $insert_sql = "
                INSERT INTO feasibilitycheck2 
                (siteid, noOfAtm, ATMID1, address, state, city, location, LHO, atm1Status) 
                VALUES ('$siteid', '$noOfAtm', '$ATMID1', '$address', '$state', '$city', '$location', '$LHO', '$atm1Status')
            ";
                    mysqli_query($con, $insert_sql);

                    $insertid = mysqli_insert_id($con);

                    $currentYear = date('Y');
                    $currentMonth = date('m');
                    $targetDir = "../feasibiltyData/$currentYear/$currentMonth/$insertid/";

                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    $ATMID1Snap = $_FILES['ATMID1Snap'];
                    $uploadedFiles = handleUploads($ATMID1Snap, $targetDir);

                    if (!empty($uploadedFiles)) {
                        $uploadedFilesStr = implode(',', $uploadedFiles);

                        // Update the table with the comma-separated image paths
                        $update_sql = "
                    UPDATE feasibilitycheck2 
                    SET ATMID1Snap = '$uploadedFilesStr' 
                    WHERE id = $insertid
                ";
                        mysqli_query($con, $update_sql);
                    }

                    ?>

                    <script>
                        alert('Info Collected Successfully !');
                        window.location.href = "feasibilitycheck2.php?siteid=<?php echo $siteid; ?>&feasibilityId=<?php echo $insertid; ?>"; 
                    </script>

                    <?php

                }
            } else {
                echo 'ATMID NOT FOUND';
            }
        }
        $siteid = $_REQUEST['siteid'];
        $insertid = $_REQUEST['feasibilityId'];
        ?>

        <form id="wizardForm"
            action="./feasibilitycheck3.php?siteid=<?php echo $siteid; ?>&feasibilityId=<?php echo $insertid; ?>"
            method="post" enctype="multipart/form-data">
            <input type="hidden" name="feasibilityId" value="<?php echo $insertid; ?>">
            <div class="step">
                <h2>Part 2: Network available in back room</h2>


                <div class="card second_card grid-margin">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Operator 1</label>
                                <select name="operator" class="form-control" required>
                                    <option value="">Select</option>
                                    <option>Airtel</option>
                                    <option>Vodafone</option>
                                    <option>Jio</option>
                                    <option>Any Other</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Status of Signal</label>
                                <input name="signalStatus" type="text" class="form-control" required />
                            </div>
                            <div class="col-sm-6">
                                <label>Remark</label>
                                <input name="backroomNetworkRemark" type="text" class="form-control" required />
                            </div>
                            <div class="col-sm-2">
                                <label>Snapshot</label>
                                <input type="file" name="backroomNetworkSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>

                            <div class="col-sm-2">
                                <label>Operator 2</label>
                                <select name="operator2" class="form-control">
                                    <option value="">Select</option>
                                    <option>Airtel</option>
                                    <option>Vodafone</option>
                                    <option>Jio</option>
                                    <option>Any Other</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Status of Signal</label>
                                <input name="signalStatus2" type="text" class="form-control" />
                            </div>
                            <div class="col-sm-6">
                                <label>Remark</label>
                                <input name="backroomNetworkRemark2" type="text" class="form-control" />
                            </div>
                            <div class="col-sm-2">
                                <label>Snapshot</label>
                                <input type="file" name="backroomNetworkSnap2[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>
                        </div>
                    </div>
                </div>



                <br>
                <button onclick="nextStep()">Next</button>
            </div>

        </form>

    </div>
</div>


<? include('../footer.php'); ?>