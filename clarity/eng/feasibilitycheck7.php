<? include ('../header.php'); ?>
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

        <form id="wizardForm" action="./feasibilitycheck8.php" method="post" enctype="multipart/form-data">

            <div class="step">
                <h2>Part 7: Power</h2>






                <div class="card second_card grid-margin">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Power Socket Available for Router in DB</h6>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control" name="powerSocketAvailability" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="powerSocketAvailabilitySnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />

                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Power Socket Available for Router in UPS</h6>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control" name="powerSocketAvailabilityUPS" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="powerSocketAvailabilityUPSSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Earthing</h6>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control" name="earthing" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="earthingVltg" class="form-control"
                                    placeholder="EN Vtg ... " required />
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="earthingSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Power Fluctuation</h6>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="powerFluctuationPE" placeholder="PE vtg.."
                                    required />
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="powerFluctuationPN" placeholder="PN vtg.."
                                    required />
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="powerFluctuationEN" placeholder="EN vtg.."
                                    required />
                            </div>

                            <div class="col-sm-3">
                                <input type="file" name="powerFluctuationSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Frequent Power cut</h6>
                            </div>
                            <div class="col-sm-2">
                                <select name="frequentPowerCut" class="form-control" onchange="toggleFields(this)"
                                    required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-sm-2" id="powerCutFromContainer" style="display: none;">
                                <input type="text" class="form-control" name="frequentPowerCutFrom"
                                    placeholder="Frequent Power Cut From" />
                            </div>
                            <div class="col-sm-2" id="powerCutToContainer" style="display: none;">
                                <input type="text" class="form-control" name="frequentPowerCutTo" placeholder="To" />
                            </div>
                            <div class="col-sm-3" id="powerCutRemarkContainer" style="display: none;">
                                <input type="text" class="form-control" name="frequentPowerCutRemark"
                                    class="form-control" />
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


<script>
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

</script>

<? include ('../footer.php'); ?>