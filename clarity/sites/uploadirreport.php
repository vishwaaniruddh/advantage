<?php 
include ('../header.php');
$atmid = $_REQUEST['atmid'];


if(!$atmid){

    echo 'Something Wrong ! Redirect Back ....' ; 

}else{


if (isset($_POST['submit'])) {

// File upload handling
$uploadDirectory = "irReports/" . date("Y/m/d") . "/" . $atmid . "/";
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

    // Insert data into the database
    $sql = "INSERT INTO installationreport (atmid, reportPath, status, created_at, created_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $atmid, $reportPath, $status, $createdAt, $createdBy);
    $stmt->execute();
    $stmt->close();


    mysqli_query($con,"update projectinstallation set isIrReportGiven=1, irReportPath='".$reportPath."' where atmid='".$atmid."' and status=1 and isDone=1");


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

<div class="row">
<div class="col-sm-12 grid-margin">
    <p>
        <a href="../excelformats/Installation_Report.pdf" download>Download And Print</a>
    </p>

    <div class="card">
        <div class="card-block">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?atmid=<?= $atmid ; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Upload Signed Installation Report Copy</label>
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
</div>
</div>

    <?
}


 include ('../footer.php'); ?>
