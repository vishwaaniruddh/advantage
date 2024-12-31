<? include('header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <?


    if ($assignedLho) {

        $query3 = "SELECT  a.*,b.address  FROM projectInstallation a 
    INNER JOIN sites b ON a.siteid = b.id
    WHERE DATE(a.scheduleDate) = CURDATE() and b.LHO like '" . $assignedLho . "'";



    } else if ($_SESSION['PROJECT_level'] == 3) {

        $query3 = "SELECT COUNT(DISTINCT atmid) AS today_record_count FROM projectInstallation 
    WHERE DATE(scheduleDate) = CURDATE()";

    } else if ($_SESSION['isVendor'] == 1 && $_SESSION['PROJECT_level'] != 3) {


        $query3 = "SELECT a.*,b.address FROM projectInstallation a 
                INNER JOIN sites b on a.siteid = b.id WHERE DATE(a.scheduleDate) = CURDATE() and a.vendor='".$_GLOBAL_VENDOR_ID."'";

    } else if ($_SESSION['PROJECT_level'] == 3) {

        $query3 = "SELECT COUNT(DISTINCT atmid) AS today_record_count FROM projectInstallation 
    WHERE DATE(scheduleDate) = CURDATE()";

    } else {

        $query3 = "SELECT a.*,b.address FROM projectInstallation a INNER JOIN sites b ON a.siteid = b.id
    WHERE DATE(a.scheduleDate) = CURDATE()";
        
    }
   

    ?>


    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                    <h4 class="card-title mb-1">Todays Scheduled Installations</h4>
                    <p class="text-muted mb-1"> Status</p>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr>
                        <div class="preview-list">


                        <?php
                        
                        
                        $sql = mysqli_query($con, $query3);
                        while($sql_result = mysqli_fetch_assoc($sql)){

                            $atmid = $sql_result['atmid'];
                            $address = $sql_result['address'];
                            $isPending = $sql_result['isPending'];

                            

                            ?>


<div class="preview-item border-bottom">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-primary" style="color:white;">
                                        <i class="mdi mdi-file-document"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-sm-flex flex-grow">
                                    <div class="flex-grow">
                                        <h4 class="preview-subject"><?php echo $atmid ; ?></h4>
                                        <p class="mb-0"><i class="mdi mdi-google-maps"></i><?= $address ; ?></p>
                                    </div>
                                    <div class="me-auto text-sm-right pt-2 pt-sm-0">
                                        <?php 
                                        
                                        if($isPending==1){
                                            
                                            echo '<p style="color:green;">Installation Done !</p>';
                                        }else{
                                            echo '<p style="color:red;">Installation Pending !</p>';

                                        }


                                        ?>
                                    </div>
                                </div>
                            </div>

<?php


                        }

                        

                        
                        ?>

                        
                        


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>


<? include('footer.php'); ?>