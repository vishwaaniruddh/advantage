<? include('../header.php'); ?>

     
            
                                <div class="card">
                                    <div class="card-block">
                                        
                                        <?

$atmid = $_REQUEST['atmid'];
$siteid = $_REQUEST['id'];
$engineer = $_REQUEST['engineer'];
$redelegate = $_REQUEST['action'];
$delegateTo = $_REQUEST['delegateTo'];
$vendor = $_REQUEST['vendor'];
$vendorName = getVendorName($vendor);


$reopen_redelegation = $_REQUEST['action'];

if(isset($reopen_redelegation) && !empty($reopen_redelegation)){
    
    
    loggingRecords('sites', $siteid,'log_before');
    if(mysqli_query($con,"update sites set delegatedToVendorId='".$vendor."',delegatedToVendorName='".$vendorName."',isFeasibiltyDone=0,verificationStatus='' where id='".$siteid."'")){
    loggingRecords('sites', $siteid,'log_after');
            reopenRedelegateToVendor($siteid,$atmid,'');
            
            ?>
            <script>
                Swal.fire("Success", "Call Reopen Succesfully !", "success");
                setTimeout(function () {
                    window.location.href = "sitestest.php?atmid=<? echo $atmid; ?>";
                }, 3000); // Redirect after 3 seconds
            </script>
            <?

        }
}
else{
        echo 'else' ; 
}


                                        
                                        ?>
                                        
                                        
                                        
                                    </div>
                                </div>
                           
                    
                    
    <? include('../footer.php'); ?>