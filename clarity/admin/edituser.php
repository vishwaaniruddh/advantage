<? include('../config.php');
$id = $_REQUEST['userid'];

$sql = mysqli_query($con,"select * from user where id='".$id."'");
$sql_result = mysqli_fetch_assoc($sql) ; 

$name = $sql_result['name'];
$uname = $sql_result['uname'];
$pwd = $sql_result['pwd'];
$user_permission = $sql_result['permission'];
$servicePermission = $sql_result['servicePermission'];

$level = $sql_result['level'];
$contact = $sql_result['contact'];
$vendorid = $sql_result['vendorid'];


$user_permission = explode (",", $user_permission);

$servicePermission = explode (",", $servicePermission);


?>

<form id="editUserForm" method="POST">
<input type="hidden" name="id" value="<?= $id; ?>" />
    <div class="row">
        <div class="col-sm-6">


        <div class="col-sm-12 form-group">
                                    <label>Is Contractor : *</label><br />
                                    <input type="radio" id="isVendorYes" name="isVendor" value="1"> : Yes
                                    <input type="radio" id="isVendorNo" name="isVendor" value="0"> : No
                                </div>

                                <div class="col-sm-12 form-group">
    <label>Is LHO : *</label>
    <br />
    <input type="radio" id="islhoYes" name="islho" value="1"> : Yes
    <input type="radio" id="islhoNo" name="islho" value="0"> : No
</div>

<div id="lhoDiv" class="col-sm-12 form-group" style="display: none;">
    <label>LHO</label>
    <select name="lho" class="form-control">
        <option value="">Select LHO</option>
        <?php
        $lhosql = mysqli_query($con,"select * from lho where status=1"); 
        while($lhosqlResult = mysqli_fetch_assoc($lhosql)){
            $lhoid = $lhosqlResult['id'];
            $lhoName = $lhosqlResult['lho'];
        ?>
        <option value="<?= $lhoid ; ?>"><?= $lhoName;  ?></option>
        <?php
        }
        ?>
    </select>
</div>




            <div class="row">
                <div class="col-sm-12 form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?= $name; ?>">
                </div>

                <div class="col-sm-12 form-group">
                    <label>Email / userid</label>
                    <input type="email" name="uname" class="form-control" value="<?= $uname?>" readonly>
                </div>

                <div class="col-sm-12 form-group">
                    <label>Password</label>
                    <input type="password" name="pwd" class="form-control" value="<?= $pwd; ?>">
                </div>

                <div class="col-sm-12 form-group">
                    <label>Contact</label>
                    <input type="number" id="contact" name="contact" class="form-control"
                        onkeypress="return validInput(event);" value="<?= $contact; ?>">
                </div>

                <div class="col-sm-12 form-group">
                    <label>Role</label>
                    <select class="form-control" name="role" required>
                        <option value="">Select</option>
                        <option value="1" <? if($level==1){ echo 'selected';} ?>>Admin</option>
                        <option value="2" <? if($level==2){ echo 'selected';} ?>>Project Executive</option>
                        <option value="5" <? if($level==5){ echo 'selected';} ?>>Bank Executive</option>
                        <option value="6" <? if($level==6){ echo 'selected';} ?>>LHO</option>

                    </select>
                </div>
               
                <div class="col-sm-12 form-group">
                                    <label for="">Vendor</label>
                                    <select class="form-control" name="vendorid" >
                                        <option value="">Select</option>
                                        <?php 
                                        $vendorsql = mysqli_query($con,"select * from vendor where status=1");
                                        while($vendorsql_result = mysqli_fetch_assoc($vendorsql)){
                                            $thisvendorid = $vendorsql_result['id'];
                                            $vendorName = $vendorsql_result['vendorName'];
                                            ?>
                                            <option value="<?= $thisvendorid?>" <? if($thisvendorid==$vendorid){ echo 'selected' ; }?>><?= $vendorName ; ?></option>
                                        <? } ?>
                                    </select>
                                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 grid-margin">
                    <br>
                    <input type="submit" name="submit" class="btn btn-primary">
                </div>
            </div>

        </div>

        <div class="col-sm-6">
            <h4>Permissions - Clarity</h4>
            <hr />
            <ul style="">
                <?php
                $statusColumn = 'status';
                $mainsql = mysqli_query($con, "select * from main_menu where $statusColumn=1");
                while ($mainsql_result = mysqli_fetch_assoc($mainsql)) {
                    $main_id = $mainsql_result['id'];
                    ?>
                    <li class="card-block">
                        <input type="checkbox" class="main_menu" value="<?php echo $main_id; ?>">
                        <span class="strong">
                            <?php echo $mainsql_result['name']; ?>
                        </span>
                        <ul class="showsubmenu">
                            <?php
                            $sub_sql = mysqli_query($con, "select * from sub_menu where main_menu='" . $main_id . "' and $statusColumn=1");
                            while ($sub_sql_result = mysqli_fetch_assoc($sub_sql)) {
                                $sub_id = $sub_sql_result['id'];
                                ?>
                                <li>
                                    <input class="submenu" type="checkbox" data-main_id="<?php echo $main_id ?>"
                                        name="sub_menu[]" value="<?php echo $sub_id; ?>"  <? if(in_array($sub_id,$user_permission)){ echo 'checked' ; } ?>>
                                    <?php echo $sub_sql_result['sub_menu']; ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <hr />
                    </li>
                <?php } ?>
            </ul>

<hr />



<h4>Permissions - Clarify</h4>
            <hr />
            <ul style="">
                <?php
                $statusColumn = 'isService';
                $mainsql = mysqli_query($con, "select * from main_menu where $statusColumn=1");
                while ($mainsql_result = mysqli_fetch_assoc($mainsql)) {
                    $main_id = $mainsql_result['id'];
                    ?>
                    <li class="card-block">
                        <input type="checkbox" class="main_menu" value="<?php echo $main_id; ?>">
                        <span class="strong">
                            <?php echo $mainsql_result['name']; ?>
                        </span>
                        <ul class="showsubmenu">
                            <?php
                            $sub_sql = mysqli_query($con, "select * from sub_menu where main_menu='" . $main_id . "' and $statusColumn=1");
                            while ($sub_sql_result = mysqli_fetch_assoc($sub_sql)) {
                                $sub_id = $sub_sql_result['id'];
                                ?>
                                <li>
                                    <input class="submenu" type="checkbox" data-main_id="<?php echo $main_id ?>"
                                        name="sub_menu_clarify[]" value="<?php echo $sub_id; ?>"  <? if(in_array($sub_id,$servicePermission)){ echo 'checked' ; } ?>>
                                    <?php echo $sub_sql_result['sub_menu']; ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <hr />
                    </li>
                <?php } ?>
            </ul>



        </div>
    </div>

</form>



<script>



// Get references to the radio buttons and the LHO div
var islhoYes = document.getElementById('islhoYes');
var islhoNo = document.getElementById('islhoNo');
var lhoDiv = document.getElementById('lhoDiv');

// Add event listeners to the radio buttons
islhoYes.addEventListener('change', toggleLhoDiv);
islhoNo.addEventListener('change', toggleLhoDiv);

// Function to toggle the visibility of the LHO div
function toggleLhoDiv() {
    // If "Yes" is selected, display the LHO div, otherwise hide it
    
    if (islhoYes.checked) {
        lhoDiv.style.display = 'block';
    } else {
        lhoDiv.style.display = 'none';
    }
}

// Initial toggle based on the default radio button state
toggleLhoDiv();



$(document).ready(function () {
        checkVendorRequirement();

        $('input[name="isVendor"]').change(function () {
            checkVendorRequirement();
        });

        function checkVendorRequirement() {
            var isVendorYes = $('#isVendorYes').is(':checked');
            var isVendorNo = $('#isVendorNo').is(':checked');

            if (isVendorYes) {
                $('#islhoNo').prop('checked', true);
                $('#islhoYes').prop('checked', false);
            } else if (isVendorNo) {
                $('#islhoYes').prop('checked', true);
                $('#islhoNo').prop('checked', false);
            }
        }
    });



     $(document).ready(function () {
        
        $('#editUserForm').submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'process_edit_user.php',
                data: formData,
                success: function (response) {
                    console.log(response)
                    if (response == 1) {
                        Swal.fire('Success','User updated successfully!','success').then(function () {
                            window.location.reload();
                             
                        });

                    } else {
                        Swal.fire('Error','Failed to Update user. Please try again !','error')
                        .then(function () {
                            // window.location.reload();
                                                    });

                    }
                },
                error: function () {
                    Swal.fire('Error','Failed to Update user. Please try again !','error')
                        .then(function () {
                            window.location.reload();
                            });
                }
            });
        });
    });
    </script>
