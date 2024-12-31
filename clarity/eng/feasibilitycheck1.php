<?php include('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<?php include('./feasibilityParts.php'); ?>

<div class="row">
    <div class="col-sm-12 grid-margin">
        <style>
            .hidden {
                display: none;
            }
        </style>



        <form id="wizardForm" action="./feasibilitycheck2.php" method="post" enctype="multipart/form-data">
            <div class="step">
                <h2>Part 1: Basic Info</h2>

                <?php
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

                <input type="hidden" name="siteid" value="<?php echo $siteid; ?>">

                <div class="row" style="display:none;">
                    <div class="col-sm-6">
                        <label>Number of ATM Available</label>
                        <select class="form-control" name="noOfAtm" id="noOfAtm" required="">
                            <option value="">Select</option>
                            <option selected>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                    </div>
                </div>

                <div class="card grid-margin">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <label>ATMID 1</label>
                                <input type="text" id="ATMID1" name="ATMID1" class="form-control"
                                    value="<?php echo $atmid; ?>" <?php if ($atmid) {
                                           echo 'readonly';
                                       } ?> />
                            </div>
                            <div class="col-sm-3">
                                <label id="atm1StatusLabel">ATMID 1 Working</label>
                                <select class="form-control" name="atm1Status" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>ATMID1 Snap</label>
                                <input type="file" name="ATMID1Snap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label>City</label>
                                <input type="text" id="city" name="city" class="form-control"
                                    value="<?php echo $city; ?>" <?php if ($atmid) {
                                           echo 'readonly';
                                       } ?> />
                            </div>
                            <div class="col-sm-3">
                                <label>Location</label>
                                <input type="text" id="location" name="location" class="form-control"
                                    value="<?php echo $address; ?>" <?php if ($atmid) {
                                           echo 'readonly';
                                       } ?> />
                            </div>
                            <div class="col-sm-3">
                                <label>LHO</label>
                                <input type="text" id="LHO" name="LHO" class="form-control" value="<?php echo $lho; ?>" <?php if ($atmid) {
                                       echo 'readonly';
                                   } ?> />
                            </div>
                            <div class="col-sm-3">
                                <label>State</label>
                                <input type="text" id="state" name="state" class="form-control"
                                    value="<?php echo $state; ?>" <?php if ($atmid) {
                                           echo 'readonly';
                                       } ?> />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label>Address</label>
                                <input type="text" id="address" name="address" class="form-control"
                                    value="<?php echo $address; ?>" <?php if ($atmid) {
                                           echo 'readonly';
                                       } ?> />
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <input type="submit" value="Next">
            </div>
        </form>
    </div>
</div>

<script>
    function checkFileCount(input) {
        if (input.files.length > 5) {
            alert("Maximum 5 images are allowed.");
            input.value = ''; // Clear the file input to remove the selected files
        }
    }
</script>

<?php include('../footer.php'); ?>