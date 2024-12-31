<?php include ('../header.php');



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function formatDowntime($seconds)
{
    $downtime = '';

    // Calculate days, hours, minutes
    $days = floor($seconds / (3600 * 24));
    $seconds -= $days * 3600 * 24;
    $hours = floor($seconds / 3600);
    $seconds -= $hours * 3600;
    $minutes = floor($seconds / 60);

    // Format the downtime string
    if ($days > 0) {
        $downtime .= $days . ' days, ';
    }
    if ($hours > 0) {
        $downtime .= $hours . ' hours, ';
    }
    $downtime .= $minutes . ' minutes';

    return $downtime;
}



?>


<style>
    .border-checkbox-section .border-checkbox-group .border-checkbox-label {
        width: 50%;
    }
</style>
<script src="<? $_SERVER["DOCUMENT_ROOT"]; ?>/clarify/assets/js/jquery-3.7.min.js"></script>

<link rel="stylesheet" type="text/css" href="../datatable/dataTables.bootstrap.css">

<div class="card">
    <div class="card-block">
        <h5>SITE INFORMATION</h5>
        <hr>
        <?
        $id = $_GET['id'];

        $sql = mysqli_query($con, "select * from mis where id='" . $id . "'");
        if ($sql_result = mysqli_fetch_assoc($sql)) {


            $bank = $sql_result['bank'];
            $location = $sql_result['location'];

            $city = $sql_result['city'];
            $state = $sql_result['state'];
            $zone = $sql_result['zone'];
            $ticketStatus = $status = $sql_result['status'];
            $created_at = $sql_result['created_at'];
            $serviceExecutive = $sql_result['serviceExecutive'];
            $remarks = $sql_result['remarks'];
            $noProblemOccurs = $sql_result['noProblemOccurs'];


            $detailsql = mysqli_query($con, "select * from mis_details where mis_id='" . $id . "'");
            if ($detailsql_result = mysqli_fetch_assoc($detailsql)) {


                $ticketid = $detailsql_result['ticket_id'];
                $atmid = $detailsql_result['atmid'];
                $component = $detailsql_result['component'];
                $subcomponent = $detailsql_result['subcomponent'];

                if ($ticketStatus == 'close') {
                    $historysql = mysqli_query($con, "select * from mis_history where type='close' and mis_id='" . $ide . "'");
                    $historysql_result = mysqli_fetch_assoc($historysql);

                    $ticketOpenDate = $created_at;
                    $historyDate = $historysql_result['created_at'];
                    $ticketOpenTimestamp = strtotime($ticketOpenDate);
                    $historyTimestamp = strtotime($historyDate);
                    $downtimeSeconds = $historyTimestamp - $ticketOpenTimestamp;
                    $downtime = formatDowntime($downtimeSeconds);

                }








            }

        }




























        mysqli_query($con, "update mis set isRead='read' where id='" . $mis_id . "'");
        ?>
        <div class="view-info">
            <div class="row">
                <div class="col-lg-12">
                    <div class="general-info">
                        <div class="row">
                            <div class="col-lg-12 col-xl-6">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Ticket ID </th>
                                                <td>
                                                    <? echo $ticketid; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">ATM ID</th>
                                                <td>
                                                    <span>
                                                        <?php echo $atmid; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Bank</th>
                                                <td>
                                                    <?php echo $bank; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Location</th>
                                                <td style="    white-space: normal;">
                                                    <?

                                                    $location = breakStringIntoLines($location);
                                                    echo $location;
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end of table col-lg-6 -->
                            <div class="col-lg-12 col-xl-6">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <tbody>
                                            <tr>
                                            <tr>
                                                <th scope="row">City</th>
                                                <td>
                                                    <? echo $city; ?>
                                                </td>
                                            </tr>

                                            <th scope="row">State</th>
                                            <td>
                                                <? echo $state; ?>
                                            </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Zone</th>
                                                <td>
                                                    <? echo $zone; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td>
                                                    <? echo $status; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>



<div class="card">
    <div class="card-block">


        <h5>CALL INFORMATION</h5>
        <hr>

        <div class="view-info">
            <div class="row">
                <div class="col-lg-12">
                    <div class="general-info">
                        <div class="row">
                            <div class="col-lg-12 col-xl-6">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Ticket ID </th>
                                                <td>
                                                    <? echo $ticketid; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Current Status</th>
                                                <td>
                                                    <?php echo $status; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Component</th>
                                                <td>
                                                    <? echo $component; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Sub Component</th>
                                                <td>
                                                    <? echo $subcomponent; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end of table col-lg-6 -->
                            <div class="col-lg-12 col-xl-6">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Created On</th>
                                                <td>
                                                    <span>
                                                        <? echo $created_at; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <th scope="row">Created By</th>
                                            <td>
                                                <? echo $serviceExecutive; ?>
                                            </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Down Time </th>
                                                <td>
                                                    <?php echo $downtime; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Remark</th>
                                                <td>
                                                    <? echo $remarks; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Hardware</th>
                                                <td>
                                                    <? echo $noProblemOccurs; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


    </div>
</div>




<div class="card">
    <div class="card-block" style="overflow:scroll;">
        <h5>CALL DISPATCH INFORMATION</h5>

        <hr>
        <table id="example" class="table table-bordered table-striped table-hover dataTable js-exportable no-footer"
            style="width:100%">
            <thead>
                <tr class="table-primary">
                    <th>Sn No</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Material Condition</th>

                    <th>Date</th>
                    <th>Schedule Date</th>
                    <th>Require Material Name </th>
                    <th>Engineer</th>
                    <th>POD</th>
                    <th>Action By</th>
                    <th>Attchement</th>
                    <th>Attchement 2</th>
                    <th>Material Delivered Date</th>
                    <th>Address (Material Requirement)</th>
                    <th>Serial Number</th>
                    <th>Dependency</th>
                </tr>

            </thead>
            <tbody>
                <?
                // echo "select * from mis_history  where mis_id ='" . $id . "' order by id desc" ;
                
                $his_sql = mysqli_query($con, "select * from mis_history  where mis_id ='" . $id . "' order by id desc");
                $i = 1;
                while ($his_sql_result = mysqli_fetch_assoc($his_sql)) {
                    $is_material_dept = $his_sql_result['is_material_dept'];
                    ?>
                    <tr <? if ($is_material_dept == 1) { ?> style="background-color: #404e67;color:white;" <? } ?>>
                        <td>
                            <? echo $i; ?>
                        </td>
                        <td>
                            <? echo $his_sql_result['type']; ?>
                        </td>
                        <td>

                            <?
                            if ($his_sql_result['type'] == 'Mail Update') {
                                echo 'Auto Update On ticket';
                            } else {
                                echo $his_sql_result['remark'];
                            }

                            ?>

                        </td>
                        <td>
                            <? echo $his_sql_result['material_condition']; ?>
                        </td>

                        <td>
                            <? echo $his_sql_result['created_at']; ?>
                        </td>
                        <td>
                            <? if ($his_sql_result['schedule_date'] != '0000-00-00') {
                                echo $his_sql_result['schedule_date'];
                            } ?>
                        </td>
                        <td>
                            <? echo $his_sql_result['material']; ?>
                        </td>
                        <td>
                            <? echo getUsername($his_sql_result['engineer'], true); ?>
                        </td>
                        <td>
                            <? echo $his_sql_result['pod']; ?>
                        </td>

                        <td>
                            <? echo getUsername($his_sql_result['created_by'], true); ?>
                        </td>
                        <td>
                            <? if ($his_sql_result['attachment']) { ?><a href="<? echo $his_sql_result['attachment']; ?>"
                                    >View
                                    Attchment</a>
                            <? } ?>
                        </td>
                        <td>
                            <? if ($his_sql_result['attachment2']) { ?><a href="<? echo $his_sql_result['attachment2']; ?>"
                                    >View
                                    Attchment</a>
                            <? } ?>
                        </td>

                        <td>
                            <? if ($his_sql_result['delivery_date'] != '0000-00-00') {
                                echo $his_sql_result['delivery_date'];
                            } ?>
                        </td>
                        <td>
                            <? echo $his_sql_result['delivery_address']; ?>
                        </td>
                        <td>
                            <? echo $his_sql_result['serial_number']; ?>
                        </td>
                        <td>
                            <? echo $his_sql_result['dependency']; ?>
                        </td>


                    </tr>
                    <? $i++;
                } ?>

            </tbody>
        </table>
    </div>
</div>

</div>
</div>


</div>
</div>
</div>

<script>
    console.clear();

    function handleCheckboxChange(checkbox, matLoopCount) {
        // Get the corresponding select and input elements based on the matLoopCount
        var selectElement = document.getElementById("select_" + matLoopCount);
        var inputElement = document.getElementById("input_" + matLoopCount);
        var input_qty = document.getElementById("input_qty_" + matLoopCount);



        // Check if the select and input elements exist before setting the 'required' attribute
        if (selectElement && inputElement) {
            if (checkbox.checked) {
                selectElement.required = true;
                inputElement.required = true;
                input_qty.required = true;

            } else {
                selectElement.required = false;
                inputElement.required = false;
                input_qty.required = false;

            }
        }
    }


    $(document).ready(function () {

        $(document).on('change', '#status', function () {

            console.log("Checkbox:", this);

            var status = $(this).val();
            $("#status_col").html('');

            if (status == 'close') {
                var html = `<input type="hidden" name="status" value="close">
                <div class="col-sm-12"><label>Snap</label><br /><input type="file" name="image" required></div>
            <div class="col-sm-12"><br><label>Remark</label><input type="text" name="remark" class="form-control"></div>
            <div class="col-sm-12"><br><label>Resolution</label>
                <select name="" class="form-control" required>
                <option value="Spares Replaced">Spares Replaced</option>
                <option value="Antena relocated">Antenna relocated</option>
                    <option value="Loose connection fixed">Loose connection fixed</option>
                    <option value="Power turned on">Power turned on</option>
                    <option value="Router rebooted">Router rebooted</option>
                    <option value="LAN cable replaced or label fixed (if damaged).">LAN cable replaced or label fixed (if damaged).</option>
                    <option value="Electrical wiring done">Electrical wiring done</option>
                    <option value="SIM replaced">SIM replaced</option>
                    <option value="SIM re-inserted">SIM re-inserted</option>
                    <option value="No issue found">No issue found</option>
                    <option value="VPN Restore">VPN Restore</option>
                    
                    <?
                    $boqSql = mysqli_query($con, "select * from boq where status=1");
                    while ($boqSqlResult = mysqli_fetch_assoc($boqSql)) {
                        $boqValue = $boqSqlResult['value'];
                        ?>
                    <option value="<?= $boqValue; ?> Replaced"><?= $boqValue; ?> Replaced</option>
<?


                    }
                    ?>



                </select>
                </div>


            <div class="col-sm-12 oldMaterialDetails" style="display:none;">
            <br />
                <label>Old Material Details</label>
                <select name="oldMaterialDetails" id="oldMaterialDetails" class="form-control">
                  <option>-- Select --</option>
                  <option value="Old Material with Engineer">Old Material with Engineer</option>
                  <option value="Old Material Missing">Old Material Missing</option>
                  <option value="Old Material Scrap">Old Material Scrap</option>
                  <option value="Old Material in Service Center">Old Material in Service Center</option>
                  <option value="Old Material in Branch Office">Old Material in Branch Office</option>
                  <option value="Old Material in Dispached to Mumbai">Old Material in Dispached to Mumbai</option>
                </select>  
                </div>

            <div class="col-sm-12"><br><br><input class="btn btn-primary" value="Close" type="submit" name="submit"></div>`;
            } else if (status == 'material_requirement') {
                var html = `
                <input type="hidden" name="status" value="material_requirement">
                <div class="col-sm-12">
                    <label>Please Select Material </label>
                    <br />
                    <div class="border-checkbox-section" style="margin: auto 40px;">
                    <?
                    $matLoopCount = 1;
                    $mat_sql = mysqli_query($con, "select * from boq where status=1");
                    while ($mat_sqlResult = mysqli_fetch_assoc($mat_sql)) {
                        $value = $mat_sqlResult['value']; ?>
                                                                                <div class="border-checkbox-group border-checkbox-group-primary">
                                                                                    <input class="border-checkbox" name="requiredMaterial[]" type="checkbox" id="checkbox<?= $matLoopCount; ?>" value="<?= trim($value); ?>">

                                                                                    <label class="border-checkbox-label" for="checkbox<?= $matLoopCount; ?>"><?= trim($value); ?></label>

                                                                                    <input id="input_qty_<?= $matLoopCount; ?>" type="text" name="material_quantity[]" style="width: 50px;" placeholder="QTY" />
                                                                                    <select id="select_<?= $matLoopCount; ?>" name="material_condition[]">
                                                                                        <option value="">Select</option>
                                                                                        <option value="Missing">Missing</option>
                                                                                        <option value="Faulty">Faulty</option>
                                                                                        <option value="Not Installed">Not Installed</option>
                                                                                        <option value="Power Fluctuation">Power Fluctuation</option>
                                                                                    </select>

                                                                                    <input id="input_<?= $matLoopCount; ?>" type="file" name="material_requirement_images[]" />
                                                                                </div>
                        
                                                                                <? $matLoopCount++;
                    } ?>
                    </div>

                    <div class="col-sm-12">
                        <label>Remarks</label>
                        <input type="text" name="remark" class="form-control" required />
                    </div>
                    <div class="col-sm-12">
                        <br />
                        <input type="submit" name="submit" class="btn btn-primary" />
                    </div>
                    
                </div>
            `;
            } else if (status == 'reassign') {
                var html = `<input type="hidden" name="status" value="reassign">


            <div class="border-checkbox-section highlight" style="width:75%">
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                                    id="checkbox1" value="Ups Wroking">
                                                <label class="border-checkbox-label" for="checkbox1">Ups Not Wroking</label>
                                            </div>
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                                    id="checkbox2" value="No Power Outage">
                                                <label class="border-checkbox-label" for="checkbox2">Power Outage</label>
                                            </div>
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                                    id="checkbox3" value="No Problen in Machine Hardware">
                                                <label class="border-checkbox-label" for="checkbox3">Machine Hardware Issue</label>
                                            </div>

                                        </div>


            <div class="col-sm-12">
                <label>Snap</label><br />
                <input type="file" name="image" required>
            </div>
            <div class="col-sm-12">
            <br />
                <label>Remark</label>
                <input type="text" name="remark" class="form-control">
            </div>

            <div class="col-sm-12">
                        <br />
                        <input type="submit" name="submit" class="btn btn-primary" />
                    </div>
                    
            `;
            } else if (status == 'schedule') {
                var html = `<input type="hidden" name="status" value="schedule">
            <div class="col-sm-4">
            <label>Engineer</label>
            <select name="engineer" class="form-control">
            <option value="">Select</option>
            <? $eng_sql = mysqli_query($con, "select * from vendorusers where level=3 order by name asc");
            while ($eng_sql_result = mysqli_fetch_assoc($eng_sql)) { ?> 
                                                                    <option value="<? echo $eng_sql_result['id']; ?>">
                                                                    <?= ucwords(strtolower($eng_sql_result['name'])); ?>
                                                                    </option> <? } ?>
            
            </select>
            </div>
            <div class="col-sm-4"><label>Remark</label><input type="text" name="remark" class="form-control"></div>
            <div class="col-sm-4"><label>Schedule Date</label><input type="date" name="schedule_date" class="form-control"></div>
            <div class="col-sm-4"><br><input class="btn btn-success" type="submit" name="submit"></div>`;
            } else if (status == 'material_dispatch') {
                var html = `
            <input type="hidden" name="status" value="material_dispatch">
            <div class="col-sm-3">
            <label>Courier Agency</label>
            <input type="text" name="courier" class="form-control">
            </div>
            <div class="col-sm-3">
            <label>POD</label>
            <input type="text" name="pod" class="form-control">
            </div>
            <div class="col-sm-3">
            <label>Dispatch Date</label>
            <input type="date" name="dispatch_date" class="form-control">
            </div>
            <div class="col-sm-3">
            <label>Remark</label>
            <input type="text" name="remark" class="form-control">
            </div>
            <div class="col-sm-4">
            <br>
            <input class="btn btn-primary" type="submit" value="Update" name="submit">
            </div>`;
            } else if (status == 'replace_with_available') {
                var html = `
            <input type="hidden" name="status" value="replace_with_available">
            <div class="col-sm-6">
            <label> <h5>Material</h5> </label>
            </div>

            <div class="col-sm-6">
            <label> <h5>Serial Number</h5> </label>
            </div>
            <?

            $matLoopCount = 1;
            $faultySql = mysqli_query($con, "select * from generatefaultymaterialrequest where mis_id='" . $mis_id . "' and materialRequestType='clarify' and materialRequestLevel=3");
            if ($faultySqlResult = mysqli_fetch_assoc($faultySql)) {
                $generatefaultymaterialrequestID = $faultySqlResult['id'];
                $mat_sql = mysqli_query($con, "select * from generatefaultymaterialrequestdetails where requestId='" . $generatefaultymaterialrequestID . "'");
                while ($mat_sqlResult = mysqli_fetch_assoc($mat_sql)) {
                    $value = $mat_sqlResult['MaterialName'];
                    ?>                     
                                                                                    <div class="col-sm-6">
                                                                                        <input type="checkbox" name="materialToReplace[]" value="<?= $value; ?>" required>  <?= $value; ?>
                                                                                    </div>  
                                                                                    <div class="col-sm-6">
                                                                                        <input class="form-control" type="text" name="serial_number[]" required>  
                                                                                    </div>
                                                                                    <br />
                    
                                                                                    <?
                }
            }
            ?>
            



            <div class="col-sm-4">
            <br>
            <input class="btn btn-primary" type="submit" value="Update" name="submit">
            </div>`;
            }


            $("#status_col").html(html);
        });

        $(document).on('change', '.border-checkbox', function () {
            // Get the matLoopCount from the checkbox's ID
            var matLoopCount = this.id.replace('checkbox', '');
            handleCheckboxChange(this, matLoopCount);
        });
    });

    function throttle(f, delay) {
        var timer = null;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = window.setTimeout(function () {
                f.apply(context, args);
            },
                delay || 1000);
        };
    }


    $(document).ready(function () {
        $(".js-example-basic-single").select2();
    });


    function setaddress() {
        var address_type = $('#address_type').val();
        if (address_type == 'Branch') {
            $('#address').val('Branch');
            $('#address').attr('readonly', true);
            $('#Contactperson_name').hide();
            $('#Contactperson_mob').hide();
            $('#Contactperson_name_text').attr('required', false);
            $('#Contactperson_mob_text').attr('required', false);
            $('#address').show();
        }
        if (address_type == 'Other') {
            $('#address').val('');
            $('#address').attr('readonly', false);
            $('#Contactperson_name').show();
            $('#Contactperson_mob').show();
            $('#Contactperson_name_text').attr('required', true);
            $('#Contactperson_mob_text').attr('required', true);
            //  $('#address').show();
        }
    }




    $(document).on('keyup', '#address', throttle(function () {
        $("#item_name").html('');
        add = $(this).val();
        $.ajax({
            type: "POST",
            url: 'suggested_address.php',
            data: 'address=' + add,
            success: function (msg) {

                $("#item_name").append(msg);


            }
        });
        //   alert(add);
    }));
</script>






<? include ('../footer.php'); ?>

<script src="..datatable/jquery.dataTables.js"></script>
<script src="../datatable/dataTables.bootstrap.js"></script>
<script src="../datatable/dataTables.buttons.min.js"></script>
<script src="../datatable/buttons.flash.min.js"></script>
<script src="../datatable/jszip.min.js"></script>

<script src="../datatable/pdfmake.min.js"></script>
<script src="../datatable/vfs_fonts.js"></script>
<script src="../datatable/buttons.html5.min.js"></script>
<script src="../datatable/buttons.print.min.js"></script>
<script src="../datatable/jquery-datatable.js"></script>