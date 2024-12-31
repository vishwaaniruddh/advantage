<? include('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">


<?php 

$installationID = $_REQUEST['installationID'];
if($installationID){

    $sql = "update installationData set verificationStatus='verify', verificationBy='".$userid."' where id='".$installationID."'" ; 
    mysqli_query($con,$sql);
    echo '<h1>Verification Done Succefully !</h1>'; 

    echo '<a href="completedInstallation.php">View Live Sites</a>';

}else{
    echo 'Issue in verification'; 
}

?>


    </div>
</div>


<? include('../footer.php'); ?>