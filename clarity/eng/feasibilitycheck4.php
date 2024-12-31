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

        <form id="wizardForm" action="./feasibilitycheck5.php" method="post" enctype="multipart/form-data">

            <div class="step">
                <h2>Part 4: EM Lock</h2>



                <div class="card second_card grid-margin">
                    <div class="card-body">

                        <div class="row">
                            <h6 class="col-sm-3">EM lock Available</h6>
                            <div class="col-sm-9">
                                <select class="form-control" name="EMlockAvailable" id="emLockAvailableSelect"
                                    onchange="toggleEmLockAccess(this)" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>

                        <div id="emLockAccessSection" style="display: none;" class="extra_highlight">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6>EM lock Access</h6>
                                </div>
                                <div class="col-sm-3">
                                    <label>Password Received</label>
                                    <select class="form-control" name="PasswordReceived"
                                        onchange="togglePasswordField(this)">
                                        <option value="">Select</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                                <div class="col-sm-3" id="emLockPasswordField" style="display: none;">
                                    <label>EM Lock Password</label>
                                    <input type="text" name="EMLockPassword" class="form-control" />
                                </div>
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
</script>


<? include ('../footer.php'); ?>