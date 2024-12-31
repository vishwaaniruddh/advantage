<?php include('../header.php');
$datetime = date('Y-m-d H:i:s'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_REQUEST['installationId']) && isset($_REQUEST['atmid']) && isset($_REQUEST['remarks']) && isset($_REQUEST['liveDate'])) {
        $installationId = $_REQUEST['installationId'];
        $atmid = $_REQUEST['atmid'];
        $remarks = $_REQUEST['remarks'];
        $actionDate = $_REQUEST['liveDate'];
        $atmstatus = $_REQUEST['atmstatus'] ?? 'live'; 
        $status = 1;
        $created_at = $datetime; 
        $created_by = $userid;

        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt1 = $con->prepare("UPDATE projectinstallation SET liveDate=? WHERE id=?");
        if ($stmt1 === false) {
            die("Prepare failed: " . $con->error);
        }
        $stmt1->bind_param('si', $actionDate, $installationId);
        $stmt1->execute();
        if ($stmt1->error) {
            die("Execute failed: " . $stmt1->error);
        }

        $stmt2 = $con->prepare("INSERT INTO sitelivehistory (installationid, atmid, atmstatus, actionDate, remark, status, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt2 === false) {
            die("Prepare failed: " . $con->error);
        }
        $stmt2->bind_param('ssssssss', $installationId, $atmid, $atmstatus, $actionDate, $remarks, $status, $created_at, $created_by);
        $stmt2->execute();
        if ($stmt2->error) {
            die("Execute failed: " . $stmt2->error);
        }

        if ($stmt2->affected_rows > 0) {
            echo "<script>alert('Data Added successfully!'); window.location.href = 'irReports.php';</script>";
        } else {
            echo "<script>alert('Error adding data.');</script>";
        }

        $stmt1->close();
        $stmt2->close();
    } else {
        echo "<script>alert('Required form fields are missing.');</script>";
    }
}

?>
<div class="row">
    <div class="col-sm-12 grid-margin">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="atmid" value="<?php echo htmlspecialchars($_REQUEST['atmid']); ?>">
            <input type="hidden" name="installationId" value="<?php echo htmlspecialchars($_REQUEST['id']); ?>">
            <input type="hidden" name="atmstatus" value="live">

            <div class="row">
                <div class="col-sm-6">
                    <label for="liveDate">Live Date</label>
                    <input type="date" id="liveDate" name="liveDate" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="remarks">Remarks</label>
                    <input type="text" id="remarks" name="remarks" class="form-control">
                </div>

                <div class="col-sm-12">
                    <br>
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include('../footer.php');
?>
