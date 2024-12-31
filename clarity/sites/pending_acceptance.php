<?php include('../header.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.2/css/buttons.dataTables.min.css">

<div class="row">
    <?php
    $i = 1;

    $vendor_id = $_SESSION['vendor_id'];
    $isVendor = $_SESSION['isVendor'];

    if ($isVendor == 1 && $vendor_id > 0) {
        // Vendor-specific logic (if any)
    }

    if ($assignedLho) {
        if ($ADVANTAGE_level == 2 || $ADVANTAGE_level == 5) {
            $atm_sql .= "and a.LHO = '" . $assignedLho . "' ";
            $sqlappCount .= "and a.LHO= '" . $assignedLho . "' ";
        }
        $sql = mysqli_query($con, "select * from lhositesdelegation where isPending=1 and lhoName='" . $assignedLho . "' group by atmid order by id desc");
        $numRows = mysqli_num_rows($sql);
        $__LHO = true;
        $acceptance_type = 'lho';
    } else {
        $sql = mysqli_query($con, "select * from vendorsitesdelegation where isPending=1 and vendorid='" . $vendor_id . "' and siteid>0 group by amtid order by id desc");
        $numRows = mysqli_num_rows($sql);
        $__LHO = false;
        $acceptance_type = 'vendor';
    }
    ?>

    <?php if ($numRows > 0): ?>

        <br />
        <form id="submitForm">
            <button type="submit">Bulk Acceptance</button>
        </form>
        <br /><br />
        <hr />

        <table id="example" class="table dataTable js-exportable" style="width:100%">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th><input type="checkbox" id="check_all"> Check All</th>
                    <th>Atmid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($sql_result = mysqli_fetch_assoc($sql)) {
                    $id = $sql_result['id'];

                    if ($__LHO) {
                        $atmid = $sql_result['atmid'];
                    } else {
                        $atmid = $sql_result['amtid'];
                    }
                    ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><input type="checkbox" class="single_site_delegate" value="<?= $id; ?>" /></td>
                        <td><?= $atmid; ?></td>
                        <td></td>
                    </tr>
                    <?php $i++; ?>
                <?php } ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No data available</p>
    <?php endif; ?>

</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#example').DataTable({
            "pageLength": 100,  // Show 100 records by default
            "lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Handle "Check All" checkbox
        $("#check_all").change(function () {
            $(".single_site_delegate").prop('checked', $(this).prop("checked"));
        });

        // Handle form submission
        $("#submitForm").submit(function (e) {
            e.preventDefault();
            var checkedIds = [];
            $(".single_site_delegate:checked").each(function () {
                checkedIds.push($(this).val());
            });
            var form = $('<form action="accept_sites.php?acceptance_type=<?= $acceptance_type; ?>" method="post"></form>');
            for (var i = 0; i < checkedIds.length; i++) {
                form.append('<input type="hidden" name="checkedIds[]" value="' + checkedIds[i] + '" />');
            }
            $('body').append(form);
            form.submit();
        });
    });
</script>

<?php include('../footer.php'); ?>


<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
