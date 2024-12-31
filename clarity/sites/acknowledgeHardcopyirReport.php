<? include ('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">

        <?


$atmid = $_REQUEST['atmid'];

      

if (isset($_POST['submit'])) {

    // File upload handling
    $uploadDirectory = "hardcopyIRReports/" . date("Y/m/d") . "/" . $atmid . "/";
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true); // Create directory if not exists
    }
    $targetFile = $uploadDirectory . basename($_FILES["file"]["name"]);
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        
        // Construct full URL with domain name
        $fullURL = $base_url . 'sites/' . $targetFile;
    
        // Save form data to database
        $reportPath = $fullURL;
        $status = "1"; // Assuming the status is pending initially
        $createdAt = $datetime; // Current timestamp
        $createdBy = $userid; // Assuming it's uploaded by an admin, you can change this as needed
    
    
        mysqli_query($con,"update projectinstallation set isHardopyIrReportGiven=1, hadrdcopyIRPath='".$reportPath."',hardcopyReceivedBy='".$userid."',hardcopyReceivedAt='".$datetime."' where atmid='".$atmid."' and status=1 and isDone=1");
    
    
        ?>
    
    <script>
    
    alert('<? echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded." ; ?>' );
    window.location='./irReports.php';
    </script>
        <?
    
    
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    
    
    }

    

        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?atmid=<?= $atmid; ?>" method="POST"
            enctype="multipart/form-data">

            <p>
                Do you really want to confirm the hardcopy of ATMID : <?= $atmid; ?>
            </p>
            <br />
            <div class="row">
                <div class="col-sm-12">
                    <label for="">Upload Hardcopy of Installation Report</label>
                    <br>
                    <input type="file" name="file" id="" class="form-control">
                </div>


                <div class="col-sm-12">
                    <br>
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
            </div>
        </form>




    </div>
</div>


<? include ('../footer.php'); ?>