<?php include ('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">
        <div class="card">
            <div class="card-block">

                <?php

                $materials = $_POST['materials'];
                $vendorId = $_REQUEST['materials'][1]['vendorId'];

                $sql = mysqli_query($con, "SELECT * FROM vendor WHERE id='" . $vendorId . "'");
                $sql_result = mysqli_fetch_assoc($sql);
                $vendorName = $sql_result['vendorName'];
                ?>

                <h5>Vendor Details</h5>
                <hr>
                <p><strong>Vendor Name:</strong>
                    <?php echo $vendorName; ?>
                </p>
                <br/>
                <form id="vendorForm" method="POST" action="./bulkProcessConfirmSendMaterialRequest.php">
                    <input type="hidden" name="vendorId" value="<?php echo $vendorId; ?>">

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label>Contact Person Name</label>
                            <input type="text" name="contactPersonName" class="form-control" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label>Contact Person Number</label>
                            <input type="text" name="contactPersonNumber" class="form-control" required>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required></textarea>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label>POD</label>
                            <input type="text" name="POD" class="form-control" required />
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label>Courier</label>
                            <input type="text" name="courier" class="form-control" required />
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label>Any Other Remark</label>
                            <input type="text" name="remark" class="form-control" />
                        </div>
                    </div>
                    <?php
                    // Serialize the materials array for processing
                    echo '<input type="hidden" name="materials" value="' . htmlentities(serialize($materials)) . '">';
                    ?>
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <br>
                            <input type="submit" name="submit" class="btn btn-primary" id="submitButton" value="Submit">
                        </div>
                    </div>
                </form>
                <?php
                ?>
            </div>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>