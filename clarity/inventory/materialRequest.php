<? include ('../header.php');


$isVendor = $_SESSION['isVendor'];
$islho = $_SESSION['islho'];
$ADVANTAGE_level = $_SESSION['ADVANTAGE_level'];


if ($isVendor == 1) {
    ?>
    <script>
        window.location.href = "/inventory/vendor_materialRequest.php";
    </script>
<?
}



?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="row">
    <style>
        .pagination .active a {
            background-color: #007bff;
            color: white;
        }

        .swal2-popup {
            background: white !important;
        }

        .error {
            border: 1px solid red;
        }

        .nav-tabs .slide {
            width: calc(100% / 2);
        }


        #materialRequest .nav-item {
            width: calc(100% / 2) !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <?php


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

            // Debugging output
// echo $materialRequestStatement;
            

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
            $recordsPerPage = 10; // Number of records per page
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($currentPage - 1) * $recordsPerPage;
            ?>

            <ul class="nav nav-tabs md-tabs" id="materialRequest" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#fresh-requests" role="tab"
                        aria-selected="true">Installation Material Requests</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#service-requests" role="tab"
                        aria-selected="false">Service Material Requests</a>
                    <div class="slide"></div>
                </li>
            </ul>

            <div class="">
                <!-- Fresh Requests Tab -->
                <div class="tab-pane fade show active" id="fresh-requests" role="tabpanel"
                    aria-labelledby="fresh-requests-tab">


                    <a href="./dispatchBulkMaterial.php">Dispatch In Bulk</a>
                    <br>
                    <hr>

                    <form id="sitesForm" action="<?php echo basename(__FILE__); ?>" method="POST">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" name="atmid" id="searchatmid" class="form-control" value="<?= $_REQUEST['atmid']; ?>"
                                    placeholder="Enter ATM ID ..." />
                            </div>
                            <div class="col-sm-4">
                                <br>
                                <input type="submit" name="submit" value="Filter" class="btn btn-primary">
                            </div>

                        </div>

                    </form>


                    <br>
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

                        $recordsPerPage = 10; // Number of records per page
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($currentPage - 1) * $recordsPerPage;

                        if ($siteids_ar) { ?>
                            <table class="table table-hover table-styling table-xs">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Srno</th>
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
                                    $counter = ($currentPage - 1) * $recordsPerPage + 1;
                                    $sql = mysqli_query($con, "SELECT * FROM sites WHERE id IN ($siteids) LIMIT $offset, $recordsPerPage");
                                    while ($sql_result = mysqli_fetch_assoc($sql)) {

                                        $atmid = $sql_result['atmid'];


                                        // echo "select * from routerConfiguration where 1 and atmid like '%".$atmid."%' and status=1 order by id" ; 
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
                                        // echo "SELECT * FROM ccidconfiguration WHERE atmid='" . $atmid . "' AND status=1" ; 
                                        $check_existing_conf = mysqli_query($con, "SELECT * FROM ccidconfiguration WHERE serialNumber='" . $serialNumber . "' AND status=1");
                                        
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

                                                <?
                                                if ($isConfigurationFound) {

                                                    $ccidCounter = 0;
                                                    foreach ($operator as $operatorkey => $operatorvalue) {


                                                        echo '<b>' . $operatorvalue . ' : </b>' . $ccid[$ccidCounter] . '<br />';

                                                        $ccidCounter++;

                                                    }


                                                } else {
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

                            <?php
                            $totalRecords = count($siteids_ar);
                            $recordsPerPage = 10; // Set this to your desired number of records per page
                            $totalPages = ceil($totalRecords / $recordsPerPage);
                            $range = 2; // Number of page links to show around the current page
                        
                            $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                            if ($currentPage < 1) {
                                $currentPage = 1;
                            } elseif ($currentPage > $totalPages) {
                                $currentPage = $totalPages;
                            }

                            $start = max(1, $currentPage - $range);
                            $end = min($totalPages, $currentPage + $range);
                            ?>

                            <hr />
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" style="margin: auto;">
                                <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                                    <ul class="pagination">

                                        <?php if ($currentPage > 1): ?>
                                            <li class="paginate_button">
                                                <a href="?page=1">&laquo; First</a>
                                            </li>
                                            <li class="paginate_button">
                                                <a href="?page=<?= $currentPage - 1; ?>">&lsaquo; Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($page = $start; $page <= $end; $page++): ?>
                                            <li class="paginate_button <?= ($page == $currentPage) ? 'active' : ''; ?>">
                                                <a href="?page=<?= $page; ?>">
                                                    <?= $page; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($currentPage < $totalPages): ?>
                                            <li class="paginate_button">
                                                <a href="?page=<?= $currentPage + 1; ?>">Next &rsaquo;</a>
                                            </li>
                                            <li class="paginate_button">
                                                <a href="?page=<?= $totalPages; ?>">Last &raquo;</a>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                            </div>


                            <?php
                        } else {
                            echo '<div class="noRecordsContainer">
                            <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
                            <dotlottie-player src="../json/nofound.json" background="transparent" speed="1" loop autoplay style="
                            height: 400px;
                            width: 100%;
                        "></dotlottie-player>
                            
                                    </div>';
                        }
                        ?>
                    </div>

                </div>

                <div class="tab-pane fade" id="service-requests" role="tabpanel" aria-labelledby="service-requests-tab">

                    <div class="card-body" style="overflow:auto;">

                        <?

                        $serviceRequest = mysqli_query($con, "select * from vendormaterialrequest where status=0 and requestToInventory=1 and sentFromInventory=0");
                        if (mysqli_num_rows($serviceRequest) > 0) {

                            echo "<table class='table table-hover table-styling table-xs'>
                                        <thead>
                                            <tr class='table-primary'>
                                            <th>Sr No</th>
                                            <th>Action</th>
                                            <th>Vendor</th>
                                            <th>ATMID</th>
                                            <th>Material</th>
                                            <th>Quantity</th>
                                            <th>Condition</th>
                                            <th>Requested At</th>
                                            <th>Availabilty</th>
                                        </tr>
                                        </thead>
                                        <tbody> ";

                            $srno = 1;
                            while ($serviceRequestResult = mysqli_fetch_assoc($serviceRequest)) {

                                $id = $serviceRequestResult['id'];
                                $siteid = $serviceRequestResult['siteid'];
                                $vendorId = $serviceRequestResult['vendorId'];
                                $vendorName = $serviceRequestResult['vendorName'];
                                $atmid = $serviceRequestResult['atmid'];
                                $materialName = $serviceRequestResult['materialName'];
                                $materialCondition = $serviceRequestResult['materialCondition'];
                                $requestToInventoryDate = $serviceRequestResult['requestToInventoryDate'];
                                $material_qty = $serviceRequestResult['material_qty'];
                                $checkInventory = mysqli_query($con, "select material,count(1) as materialCount from inventory where material like '" . trim($materialName) . "' and status=1 group by material having count(1) > 0");
                                if ($checkInventoryResult = mysqli_fetch_assoc($checkInventory)) {
                                    $matName = $checkInventoryResult['material'];
                                    $matCount = $checkInventoryResult['materialCount'];
                                    $availability = $matCount . ' In Stock ';
                                    $availabilityStatus = 1;
                                } else {
                                    $availability = 'Not Available';
                                    $availabilityStatus = 0;
                                }
                                echo "<tr>
                                                    <td>$srno</td>
                                                    <td>
                                                    
                                                    <button class='send-material btn btn-primary' data-materialName='$materialName'
                                                    data-id='$id' data-siteid='$siteid' data-atmid='$atmid' data-vendorid='$vendorId' data-material_qty='$material_qty'>Send Material</button> 
                                                |   
                                                
                                                <button class='reject-request btn btn-danger' data-materialName='$materialName'
                                                    data-id='$id' data-siteid='$siteid' data-atmid='$atmid' data-vendorid='$vendorId' data-material_qty='$material_qty'>Request Reject</button> 
                                                
                                                    </td>
                                                    <td>$vendorName</td>
                                                    <td>$atmid</td>
                                                    <td>$materialName</td>
                                                    <td>$material_qty</td>
                                                    <td>$materialCondition</td>
                                                    <td>$requestToInventoryDate</td>
                                                    <td>$availability</td>
                                                </tr>";

                                $srno++;
                            }



                            echo "</tbody>
                                        </table>";
                        } else {
                            echo '
                                            <div class="noRecordsContainer">
                                                    
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
    <dotlottie-player src="../json/nofound.json" background="transparent" speed="1"  loop autoplay style="
    height: 400px;
    width: 100%;
"></dotlottie-player>

                                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>



    </div>








    <div class="modal fade" id="sendFromStockModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send From Stock</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <form id="vendorForm">
                        <input type="hidden" name="id">
                        <input type="hidden" name="atmid" value="<?php echo $atmid; ?>">
                        <input type="hidden" name="siteid" value="<?php echo $siteid; ?>">
                        <input type="hidden" name="vendorId" value="<?php echo $vendorId; ?>">
                        <input type="hidden" name="attribute"
                            value="<?php echo htmlentities(serialize($attributes)); ?>">
                        <input type="hidden" name="values" value="<?php echo htmlentities(serialize($values)); ?>">
                        <input type="hidden" name="serialNumbers"
                            value="<?php echo htmlentities(serialize($serialNumbers)); ?>">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="">Material</label>
                                <input type="text" name="attribute" id="material" class="form-control" value=""
                                    readonly>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Serial Number</label>
                                <input type="text" name="serialNumbers" id="serialNumbers" class="form-control" value=""
                                    required>
                            </div>

                            <div class="col-sm-3">
                                <label for="">Quantity</label>
                                <input type="text" name="material_qty" id="material_qty" class="form-control" value=""
                                    required readonly>
                            </div>

                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-sm-6">
                                <label>Contact Person Name</label>
                                <input type="text" name="contactPersonName" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label>Contact Person Number</label>
                                <input type="text" name="contactPersonNumber" class="form-control" required>
                            </div>
                            <div class="col-sm-12">
                                <label>Address</label>
                                <textarea name="address" class="form-control" required></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label>POD</label>
                                <input type="text" name="POD" class="form-control" required />
                            </div>
                            <div class="col-sm-6">
                                <label>Courier</label>
                                <input type="text" name="courier" class="form-control" required />
                            </div>
                            <div class="col-sm-12">
                                <label>Any Other Remark</label>
                                <input type="text" name="remark" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <br>
                                <input type="submit" name="submit" class="btn btn-primary" onclick="submitForm(event);"
                                    id="submitButton" value="Submit">
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {

            $(".reject-request").on('click', function () {
                if (confirm('Are you sure to delete this request ?')) {

                    var id = $(this).data('id');
                    var siteid = $(this).data('siteid');
                    var atmid = $(this).data('atmid');
                    var materialName = $(this).data('materialname');
                    var vendorId = $(this).data('vendorid');
                    var material_qty = $(this).data('material_qty');

                    $.ajax({

                        type: "POST",
                        url: 'reject-materialRequest.php',
                        data: 'id=' + id,
                        success: function (msg) {

                            if (msg == '1') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Request Rejected !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = './materialRequest.php';
                                    }
                                });

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Cancelled !',
                                });
                            }



                        }
                    });
                } else {
                    alert('Canceled');
                }
            });



            $('.send-material').click(function () {
                var id = $(this).data('id');
                var siteid = $(this).data('siteid');
                var atmid = $(this).data('atmid');
                var materialName = $(this).data('materialname');
                var vendorId = $(this).data('vendorid');
                var material_qty = $(this).data('material_qty');


                $('#sendFromStockModal').find('[name="id"]').val(id);
                $('#sendFromStockModal').find('[name="atmid"]').val(atmid);
                $('#sendFromStockModal').find('[name="siteid"]').val(siteid);
                $('#sendFromStockModal').find('[name="vendorId"]').val(vendorId);
                $('#sendFromStockModal').find('[name="attribute"]').val(materialName); //attribute = material_name
                $('#sendFromStockModal').find('[name="material_qty"]').val(material_qty); //attribute = material_name
                $('#sendFromStockModal').modal('show');
            });



        });

        function submitForm(event) {
            event.preventDefault();
            $('#vendorForm [required]').removeClass('error');
            if (validateForm()) {
                var formData = $('#vendorForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: 'process_IndividualMaterialSend.php',
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        var responseData = JSON.parse(response);
                        if (responseData.status == '200') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: responseData.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = './materialRequest.php';
                                }
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: responseData.message,
                            });
                        }
                    },
                    error: function (error) {
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error submitting the form. Please try again.',
                        });
                    }
                });
            } else {
                alert('Please fill in all required fields before submitting.');
            }
        }

        function validateForm() {
            var isValid = true;
            $('#vendorForm [required]').each(function () {
                if ($(this).val().trim() == '') {
                    isValid = false;
                    $(this).addClass('error');
                }
            });

            return isValid;
        }
    </script>

</div>


<? include ('../footer.php'); ?>