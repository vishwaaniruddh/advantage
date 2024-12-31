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

        <form id="wizardForm" action="./feasibilitycheck7.php" method="post" enctype="multipart/form-data">

            <div class="step">
                <h2>Part 6: UPS</h2>





                <div class="card second_card grid-margin">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6>UPS Available</h6>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="UPSAvailable" id="upsAvailableSelect" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>

                        <br>
                        <div id="upsOptionsContainer" class="extra_highlight">


                            <div class="row">
                                <div class="col-sm-3">
                                    <h6>UPS Snap</h6>
                                </div>
                                <div class="col-sm-3">
                                    <input type="file" name="UPSAvailableSnap[]" multiple
                                        accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />

                                </div>
                            </div>
<br>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6>No. of UPS</h6>
                                </div>
                                <div class="col-sm-6">
                                    <select class="form-control" name="NoOfUps" id="noOfUpsSelect">
                                        <option value="">Select</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="file" name="NoOfUpsSnap[]" multiple
                                        accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />

                                </div>
                            </div>
<br>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6>UPS Working</h6>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="UPSWorking1" id="upsWorking1Select">
                                        <option value="">Select</option>
                                        <option>Yes</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="UPSWorking2" id="upsWorking2Select">
                                        <option value="">Select</option>
                                        <option>Yes</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="UPSWorking3" id="upsWorking3Select">
                                        <option value="">Select</option>
                                        <option>Yes</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="file" name="upsWorkingSnap[]" multiple
                                        accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />

                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-sm-3">
                                    <h6>UPS Battery Backup</h6>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="UPSBateryBackup" class="form-control"
                                        placeholder="Hrs ..." />
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
</script>

<? include ('../footer.php'); ?>