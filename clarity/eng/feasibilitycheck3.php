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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get POST data
            $siteid = $_REQUEST['siteid'];
            $feasibilityId = $_REQUEST['feasibilityId'];

            $operator = $_REQUEST['operator'];
            $signalStatus = $_REQUEST['signalStatus'];
            $backroomNetworkRemark = $_REQUEST['backroomNetworkRemark'];
            $operator2 = $_REQUEST['operator2'];
            $signalStatus2 = $_REQUEST['signalStatus2'];
            $backroomNetworkRemark2 = $_REQUEST['backroomNetworkRemark2'];

            // Handle file uploads
            $currentYear = date('Y');
            $currentMonth = date('m');
            $targetDir = "../feasibiltyData/$currentYear/$currentMonth/$feasibilityId/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $backroomNetworkSnap = $_FILES['backroomNetworkSnap'];
            $backroomNetworkSnap2 = $_FILES['backroomNetworkSnap2'];

            $uploadedFiles1 = handleUploads($backroomNetworkSnap, $targetDir);
            $uploadedFiles2 = handleUploads($backroomNetworkSnap2, $targetDir);

            $uploadedFilesStr1 = !empty($uploadedFiles1) ? implode(',', $uploadedFiles1) : '';
            $uploadedFilesStr2 = !empty($uploadedFiles2) ? implode(',', $uploadedFiles2) : '';

            // Update database with the uploaded files and other form data
            $update_sql = "UPDATE feasibilitycheck2 
        SET operator = '$operator', 
            signalStatus = '$signalStatus',
            backroomNetworkRemark = '$backroomNetworkRemark',
            operator2 = '$operator2',
            signalStatus2 = '$signalStatus2',
            backroomNetworkRemark2 = '$backroomNetworkRemark2',
            backroomNetworkSnap = '$uploadedFilesStr1',
            backroomNetworkSnap2 = '$uploadedFilesStr2'
        WHERE id = $feasibilityId";
            if (mysqli_query($con, $update_sql)) {
                ?>

                <script>
                    alert('Info Collected Successfully !');
                    window.location.href = "feasibilitycheck3.php?siteid=<?php echo $siteid; ?>&feasibilityId=<?php echo $insertid; ?>"; 
                </script>

                <?php

            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
        ?>



        <form id="wizardForm" action="./feasibilitycheck4.php" method="post" enctype="multipart/form-data">

            <div class="step">
                <h2>Part 3: Back Room</h2>


                <div class="card second_card grid-margin">
    <div class="card-body">

        <div class="row">
            <div class="col-sm-6">
                <label>Status</label>
                <select name="backroomKeyStatus" class="form-control" id="backroomKeyStatus" required>
                    <option value="">Select</option>
                    <option value="Available with LL">Available with LL</option>
                    <option value="Available with HK Person">Available with HK Person</option>
                    <option value="Available with MSP">Available with MSP</option>
                    <option value="Available with Bank">Available with Bank</option>
                    <option value="Not Available">Not Available</option>
                    <option value="Any Other">Any Other</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label>Backroom Contact Person Name</label>
                <input name="backroomKeyName" type="text" class="form-control" required />
            </div>
            <div class="col-sm-3">
                <label>Backroom Contact Person Number</label>
                <input name="backroomKeyNumber" type="text" class="form-control" required />
            </div>
        </div>

        <!-- Additional input field for "Any Other" -->
        <div class="row" id="anyOtherContainer" style="display: none;">
            <div class="col-sm-12">
                <label>Specify Status</label>
                <input name="backroomKeyStatusOther" type="text" class="form-control" />
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('backroomKeyStatus').addEventListener('change', function() {
        var value = this.value;
        var anyOtherContainer = document.getElementById('anyOtherContainer');

        if (value === 'Any Other') {
            anyOtherContainer.style.display = 'block';
        } else {
            anyOtherContainer.style.display = 'none';
        }
    });
</script>





                <br>
                <button onclick="nextStep()">Next</button>
            </div>

        </form>

    </div>
</div>


<? include('../footer.php'); ?>