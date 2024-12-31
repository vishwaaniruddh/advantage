<? include ('../header.php'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<div class="row">
    <div class="col-sm-12 grid-margin">

    <div class="card">
        <div class="card-block" style="overflow: overlay;">
            
        <form method="POST" action="./process_lhoemails.php">
            <label for="lhoSelect">Select LHO:</label>
            <select id="lhoSelect" name="lho" class="form-control" required>
                <?
                $lhosql = mysqli_query($con, "select * from lho where status=1");
                while ($lhosql_result = mysqli_fetch_assoc($lhosql)) {
                    $lhoid = $lhosql_result['id'];
                    $lhoName = $lhosql_result['lho'];

                    ?>
                    <option value="<?= $lhoid; ?>"><?= $lhoName; ?></option>
                <? } ?>

            </select>
            <br><br>

            <label for="emailType">Type of Email:</label>
            <select id="emailType" name="emailType" class="form-control" required>
                <option value="to">To</option>
                <option value="cc">CC</option>
            </select>
            <br>

            <label for="emailId">Name:</label>
            <input type="text" id="contactPersonName" name="contactPersonName" class="form-control" required>
            <br>

            <label for="emailId"> Contact </label>
            <input type="text" id="contactPersonmob" name="contactPersonmob" class="form-control" required>
            <br>

            <label for="emailId">Email ID:</label>
            <input type="email" id="emailId" name="emailId" class="form-control" required>
            <br>

            <input type="submit" value="Submit" class="btn btn-submit">
        </form>





        </div>
    </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">

  
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>LHO</th>
                    <th>Email Type</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email Id</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?
                $i = 1;
                $lhoemails = mysqli_query($con, "select * from  lhoemails where status=1 order by id desc");
                while ($lhoemails_result = mysqli_fetch_assoc($lhoemails)) {
                    $id = $lhoemails_result['id'];
                    $lhoName = $lhoemails_result['lhoname'];
                    $emailType = $lhoemails_result['emailType'];
                    $email = $lhoemails_result['email'];
                    $created_at = $lhoemails_result['created_at'];
                    $created_by = $lhoemails_result['created_by'];
                    $contactPersonName = $lhoemails_result['contactPersonName'];
                    $contactPersonMob = $lhoemails_result['contactPersonMob'];


                    ?>

                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= $lhoName; ?></td>
                        <td><?= ucwords($emailType); ?></td>
                        <td><?= $contactPersonName; ?></td>
                        <td><?= $contactPersonMob; ?></td>

                        <td><?= $email; ?></td>
                        <td><?= ucwords(getUsername($created_by)); ?></td>
                        <td><?= $created_at; ?></td>
                        <td>
                            <a href="./deletelhoemail.php?id=<?= $id; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>

                    <?
                    $i++;
                }


                ?>
            </tbody>
        </table>


    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<!-- Include DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<!-- Include JSZip for DataTables Buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- Include PDFMake for DataTables Buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Include HTML5 export buttons for DataTables -->
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<!-- Include print button for DataTables -->
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

<script>

    new DataTable('#example', {
    layout: {
        topStart: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        },
        bottomEnd: {
                paging: {
                    boundaryNumbers: false
                }
            }
    }
});
</script>

<? include ('../footer.php'); ?>