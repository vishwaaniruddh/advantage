<? include ('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">


        <?


        $materialRequestStatement = "SELECT a.siteid FROM `material_requests` a INNER JOIN sites b ON a.siteid=b.id WHERE a.status='pending'";

        if (isset($_REQUEST['atmid']) && $_REQUEST['atmid'] != '') {
            $atmid = $_REQUEST['atmid'];

            $atmidArray = explode(' ', $atmid);

            $atmidArray = array_map(function ($value) {
                return "'" . addslashes($value) . "'";
            }, $atmidArray);

            $atmidList = implode(',', $atmidArray);

            $materialRequestStatement .= " AND b.atmid IN ($atmidList)";
        }

        $materialRequestStatement .= "  group by siteid ";


        // echo $materialRequestStatement ; 
        
        $siteidsql = mysqli_query($con, $materialRequestStatement);
        while ($siteidsql_result = mysqli_fetch_assoc($siteidsql)) {
            $siteids[] = $siteidsql_result['siteid'];
        }
        $siteids_ar = $siteids;
        $siteids = json_encode($siteids);
        $siteids = str_replace(array('[', ']', '"'), '', $siteids);
        $siteids = explode(',', $siteids);
        $siteids = "'" . implode("', '", $siteids) . "'";

        ?>



        <form id="sitesForm" action="<?php echo basename(__FILE__); ?>" method="POST">
            <div class="row">
                <div class="col-sm-12">
                    <input type="text" name="atmid" class="form-control"
                        value="<?= htmlspecialchars($_REQUEST['atmid'] ?? '', ENT_QUOTES); ?>"
                        placeholder="Enter ATM IDs separated by spaces (e.g., ABCD EFGH IJKL)" />

                </div>
                <div class="col-sm-4">
                    <br>
                    <input type="submit" name="submit" value="Filter" class="btn btn-primary">
                </div>

            </div>

        </form>



        <hr>


        <form id="submitForm" style="margin: auto 10px;" class="readonlyAccess">
                        <button style="background: red;color: white;" type="submit">Bulk Delegate</button>
                    </form>


        <div style="overflow:auto;">


            <?



            $materialRequestStatement = " SELECT a.siteid FROM `material_requests` a INNER JOIN sites b ON a.siteid=b.id where a.status='pending' ";
            if (isset($_REQUEST['atmid']) && $_REQUEST['atmid'] != '') {
                $atmid = $_REQUEST['atmid'];
                $materialRequestStatement .= " and b.atmid in ( $atmidList ) ";
            }
            $materialRequestStatement .= "  group by a.siteid order by a.id DESC ";


            // echo $materialRequestStatement ; 
            $siteids = array();
            $siteidsql = mysqli_query($con, $materialRequestStatement);
            while ($siteidsql_result = mysqli_fetch_assoc($siteidsql)) {
                $siteids[] = $siteidsql_result['siteid'];
            }
            $siteids_ar = $siteids;
            $siteids = json_encode($siteids);
            $siteids = str_replace(array('[', ']', '"'), '', $siteids);
            $siteids = explode(',', $siteids);
            $siteids = "'" . implode("', '", $siteids) . "'";



            if ($siteids_ar) { ?>
                <table class="table table-hover table-styling table-xs">
                    <thead>
                        <tr class="table-primary">
                            <th>Srno</th>
                            <th class="readonlyAccess" style="padding-right:5px;"><input type="checkbox" id="check_all">Check All</th>
                            <th>ATMID</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Vendor</th>
                            <th>IP Configuration</th>
                            <th>Router Configuration</th>
                            <th>CCID</th>
                            <th>Action</th>
                            <!-- <th>Current Status</th> -->
                            <th>Date</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        $sql = mysqli_query($con, "SELECT * FROM sites WHERE id IN ($siteids)");
                        while ($sql_result = mysqli_fetch_assoc($sql)) {

                            $atmid = $sql_result['atmid'];
                            $configsql = mysqli_query($con, "select * from routerConfiguration where 1 and atmid like '%".$atmid."%' and status=1 order by id");
                            if ($configsqlResult = mysqli_fetch_assoc($configsql)) {
                                $serialNumber = $configsqlResult['serialNumber'];
                                // echo "select * from ipconfuration where serial_no ='" . $serialNumber . "' and status=1 order by id desc" ; 
                                $ipsql = mysqli_query($con, "select * from ipconfuration where serial_no ='" . $serialNumber . "' and status=1 order by id desc");
                                if ($ipsqlResult = mysqli_fetch_assoc($ipsql)) {

                                    $networkIP = $ipsqlResult['network_ip'];
                                    $routerIP = $ipsqlResult['router_ip'];
                                    $atmIP = $ipsqlResult['atm_ip'];

                                }
                            } else {
                                $networkIP = '';
                                $routerIP = '';
                                $atmIP = '';

                            }

                            
                                $ipRemark = '';
                            $error = 0;
                            $configurationRemark = '';
                            $configurationError = 0;
                            $atmid = $sql_result['atmid'];
                            $siteid = $sql_result['id'];
                            $city = $sql_result['city'];
                            $state = $sql_result['state'];
                            $address = $sql_result['address'];
                            
                            
                            if ($networkIP) {
                                $ipRemark .= ' Network IP <i class="mdi mdi-checkbox-marked-outline" style="color:green;"></i>';
                            } else {
                                $ipRemark .= ' Network IP <i class="mdi mdi-close-box" style="color:red;"></i>';
                                $error++;
                            }
                            if ($routerIP) {
                                $ipRemark .= ' Router IP <i class="mdi mdi-checkbox-marked-outline" style="color:green;"></i>';
                            } else {
                                $ipRemark .= ' Router IP <i class="mdi mdi-close-box"  style="color:red;"></i>';
                                $error++;
                            }
                            if ($atmIP) {
                                $ipRemark .= ' ATM IP <i class="mdi mdi-checkbox-marked-outline" style="color:green;"></i>';
                            } else {
                                $error++;
                                $ipRemark .= ' ATM IP <i class="mdi mdi-close-box"  style="color:red;"></i>';
                            }



                            $routerConfiguration = mysqli_query($con, "select * from routerConfiguration where atmid='" . $atmid . "' and status=1");
                            $routerConfigurationResult = mysqli_fetch_assoc($routerConfiguration);

                            $serialNumber = $routerConfigurationResult['serialNumber'];

                            if ($serialNumber) {
                                $configurationRemark .= ' Serial Number <i class="mdi mdi-checkbox-marked-outline" style="color:green;"></i>';
                            } else {
                                $configurationRemark .= ' Serial Number <i class="mdi mdi-close-box"  style="color:red;"></i>';
                                $configurationError++;
                            }


                            $isConfigurationFound = false;
                            $operator = array();
                            $ccid = array();
                            $created_by_name = array();
                            
                            // Query to check existing configurations
                            $check_existing_conf = mysqli_query($con, "SELECT * FROM ccidconfiguration WHERE atmid='" . $atmid . "' AND status=1");
                            
                            // Fetch results and populate arrays
                            while ($conf_result = mysqli_fetch_assoc($check_existing_conf)) {
                                $isConfigurationFound = true;
                                $operator[] = $conf_result['operator'];
                                $ccid[] = $conf_result['ccid'];
                                $created_by_name[] = $conf_result['created_by_name'];
                            }
                            
                            // Handle case where no configuration is found
                            if (!$isConfigurationFound) {
                                $configurationError++;
                            }


                            ?>
                            <tr>
                                <td>
                                    <?= $counter; ?>
                                </td>
                                <td>
<?php if($configurationError +$error > 0){

}else{ ?>
    <input type="checkbox" class="single_site_delegate" value="<?= $siteid; ?>" />
<?php } ?>

                                </td>
                                <td>
                                    <?= $atmid; ?>
                                </td>

                                <td>
                                    <?= $city; ?>
                                </td>
                                <td>
                                    <?= $state; ?>
                                </td>
                                <td>
                                    <?= getMaterialRequestInitiatorName($siteid); ?>
                                </td>
                                <td>
                                    <?= $ipRemark; ?>
                                </td>
                                <td>
                                    <?= $configurationRemark; ?>
                                </td>
                               <td>
    <?php
    if ($isConfigurationFound) {
        foreach ($operator as $operatorkey => $operatorvalue) {
            // Output the operator and corresponding CCID
            echo '<b>' . $operatorvalue . ' : </b>' . $ccid[$operatorkey] . '<br />';
        }
    } else {
        // Display error if no configuration is found
        echo '<span style="color:red;">CCID Configuration Not Found ! </span>';
    }
    ?>
</td>
                                <td>
                                    <?php
                                    if ($configurationError + $error > 0) {
                                        echo '<label style="color:red;">Pending Details !</label>';
                                    } else { ?>
                                        <a href="sendMaterial.php?siteid=<?= $siteid; ?>">Send Material</a>
                                    <?php } ?>
                                </td>
                                <!-- <td> -->
                                <?
                                getMaterialRequestStatus($siteid);
                                ?>
                                <!-- </td> -->
                                <td>
                                    <?= getMaterial_requestData($siteid, 'created_at'); ?>
                                </td>
                                <td>
                                    <?= $address; ?>
                                </td>
                            </tr>
                            <?php
                            $counter++;
                        } ?>
                    </tbody>
                </table>



                <hr />


                <?php
            }
            ?>
        </div>

    </div>
</div>



<script>
      $("#check_all").change(function () {
        $(".single_site_delegate").prop('checked', $(this).prop("checked"));
    });

    $("#submitForm").submit(function (e) {
        e.preventDefault();
        var checkedIds = [];
        $(".single_site_delegate:checked").each(function () {
            checkedIds.push($(this).val());
        });
        var form = $('<form action="bulkSendMaterial.php" method="post"></form>');
        for (var i = 0; i < checkedIds.length; i++) {
            form.append('<input type="hidden" name="checkedIds[]" value="' + checkedIds[i] + '" />');
        }
        if (checkedIds.length > 0) {
            $('body').append(form);
            form.submit();
        } else {
            alert('No Site Selected !');
        }

    });
</script>

<? include ('../footer.php'); ?>