<? include ('../header.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

?>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">


<?

      $response = array();

                $siteids = $_REQUEST['siteid'];
                $siteidar = explode(',', $siteids);
                $atmid = $_REQUEST['atmid'];
                $atmidar = explode(' ', $atmid);

                $delegateTo = $_REQUEST['delegateTo'];
                $vendor = $_REQUEST['vendor'];
                $vendorName = getVendorName($vendor);
                
                foreach ($atmidar as $atmidarkey => $atmidval) {
                    
                    
                    $checkIfInstalledSite = mysqli_query($con,"select * from projectInstallation where atmid='".$atmidval."'");
                    if($checkIfInstalledSiteResult = mysqli_fetch_assoc($checkIfInstalledSite)){
                        echo 'ATMID <span style="color:green">' . $atmidval . '</span> Cannot Delegate. Its a live active site ! <br />' ;
                    }else{
                        
                                            $getSites = mysqli_query($con,"Select * from sites where atmid='".$atmidval."'");
                    if($getSitesResult = mysqli_fetch_assoc($getSites)){
                    $siteid = $getSitesResult['id'];
                    $lho = $getSitesResult['LHO'];


                    $delegationStatus = array(
                        'siteid' => $siteid,
                        'atmid' => $atmidval,
                        'success' => false
                    );

                    mysqli_query($con, "update vendorsitesdelegation set status=0 where amtid='" . trim($atmidval) . "'");
                    
                    $updatesql = "insert into vendorSitesDelegation(vendorid,vendorName,siteid,amtid,status,created_at,created_by,portal) 
                    values('" . $vendor . "','" . $vendorName . "','" . $siteid . "','" . trim($atmidval) . "',1,'" . $datetime . "','" . $userid . "','Clarity')";

                    // if (mysqli_query($con, $updatesql)) {
                
                    if (mysqli_query($con, $updatesql)) {


                        $lhosql = mysqli_query($con, "select * from lho where lho='" . $lho . "'");
                        $lhosql_result = mysqli_fetch_assoc($lhosql);
                        $lhoid = $lhosql_result['id'];

                        mysqli_query($con, "update lhositesdelegation set status=0 where atmid='" . trim($atmidval) . "'");

                        $updatesql2 = "insert into lhositesdelegation(lhoid,lhoName,siteid,atmid,status,created_at,created_by,portal) 
    values('" . $lhoid . "','" . $lho . "','" . $siteid . "','" . $atmidval . "',1,'" . $datetime . "','" . $userid . "','Advantage')";
                        mysqli_query($con, $updatesql2);
                        loggingRecords('sites', $siteid, 'log_after');
                        delegateToVendor($siteid, $atmidval, '', $vendorName);
                        addNotification('Advantage', $userid, $vendor, ' 1 New Site Delegated ! ', $siteid, $atmidval);
                        $delegationStatus['success'] = true;

                        echo $atmidval . ' delegated Successfully to '. $vendorName .' ! <br />';
                        $q = "update sites set isDelegated=1 where atmid='" . trim($atmidval) . "'";
                        mysqli_query($con, $q);

                    }
                    // }
                
                    $response[] = $delegationStatus;

                    
                    }else{
                        echo 'ATMID : <span style="color:red;">'. $atmidval . '</span> Not Exists ! <br/>'   ;
                    }
                    }

                    
                    

                   
                }


?>




</div>
</div>
</div>
</div>




<? include ('../footer.php'); ?>