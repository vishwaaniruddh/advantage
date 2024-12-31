<? include ('../header.php');
?>



<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <h5> Delegate : To Contractor</h5>


                <hr>
                <form id="myForm2" action="processBulkdelegate.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="delegateTo" value="vendor" />

                    <label for="">Space Seprated ATMID's</label>
                    <input type="text" name="atmid" class="form-control" />

<br>

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Contractor</label>
                            <select name="vendor" class="form-control" required>
                                <option value="">SELECT</option>
                                <?php
                                $sql2 = mysqli_query($con, "select * from vendor where status=1");
                                while ($sql_result2 = mysqli_fetch_assoc($sql2)) {
                                    $vendorid = $sql_result2['id'];
                                    $vendorname = $sql_result2['vendorName'];
                                    ?>
                                    <option value="<?php echo $vendorid; ?>">
                                        <?php echo strtoupper($vendorname); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <br>
                            <input type="submit" class="btn btn-success" value="Delegate">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<? include ('../footer.php'); ?>