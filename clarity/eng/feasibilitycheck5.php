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

        <form id="wizardForm" action="./feasibilitycheck6.php" method="post" enctype="multipart/form-data">

            <div class="step">
                <h2>Part 5: Positioning</h2>



                <div class="card second_card grid-margin">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Place to fix router</h6>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control" name="routerPosition" required>
                                    <option value="">Select</option>
                                    <option>Rack Available</option>
                                    <option>Fixed on wall</option>
                                    <option>Above Ceiling</option>
                                    <option>Any Other</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="routerPositionSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>
                        </div>

<br>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Place to fix Router Antenna</h6>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control" name="routerAntenaPosition" required>
                                    <option value="">Select</option>
                                    <option>Above Ceiling</option>
                                    <option>In ATM lobby</option>
                                    <option>Out Side the lobby</option>
                                    <option>Any Other</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="routerAntenaSnap[]" multiple
                                    accept="image/jpeg, image/jpg, image/png" onchange="checkFileCount(this)"
                                    required />

                            </div>
                        </div>

<br>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6>Antenna Wire Routing detail</h6>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="AntennaRoutingdetail" class="form-control" required />
                            </div>
                            <div class="col-sm-3">
                                <input type="file" name="AntennaRoutingSnap[]" multiple
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


<? include ('../footer.php'); ?>