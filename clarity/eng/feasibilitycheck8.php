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
                <h2>Part 8: Finishing</h2>






                <div class="card second_card grid-margin">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Unwanted material in back room which bars access for working</h6>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control" name="backroomDisturbingMaterial"
                                    onchange="toggleFields2(this)">
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-sm-3" id="backroomRemarkContainer" style="display: none;">
                                <input type="text" name="backroomDisturbingMaterialRemark" class="form-control"
                                    placeholder="Remarks ... " />
                            </div>
                            <div class="col-sm-3" id="backroomSnapContainer" style="display: none;">
                                <input type="file" name="backroomDisturbingMaterialSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />

                            </div>
                        </div>
<br>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Nearest Hadware or Electric Shop</h6>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="nearestShopDistance"
                                    placeholder="Distance From ATM " required />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="nearestShopName" placeholder="Name ..." />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="nearestShopNumber"
                                    placeholder="Number ..." />
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Any Other Remark</h6>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="Remarks" class="form-control" placeholder="Remarks ... " />
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="remarksSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)" />

                            </div>
                        </div>
                        <br>


                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Feasibility Done</h6>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="feasibilityDone" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
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
</script>
<? include ('../footer.php'); ?>