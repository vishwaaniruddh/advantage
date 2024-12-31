<? include('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">


    
<?php
echo 
$statement = "SELECT challan_no,vendorId,count(1) as total FROM `material_send` 
where challan_no<>'' and vendorId='".$RailTailVendorID."' and isDelivered=0 group by challan_no,vendorId";


?>

<table border="1" class="table">
    <thead>
        <tr>
            <th>Sr</th>
            <th>Challan</th>
            <th>Material Count</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>


        <?
        $i = 1 ; 
        $sql = mysqli_query($con, $statement);
        while ($sql_result = mysqli_fetch_array($sql)) {

            $challan_no = $sql_result['challan_no'];
            $vendorId = $sql_result['vendorId'];
            $total = $sql_result['total'];


            ?>

            <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $challan_no ; ?></td>
            
            <td><?php echo 'For ' . $total . ' Site'; ?></td>
            <td>
            <a href="./vendor_bulkupdateMaterialSentTracking.php?challan=<?php echo $challan_no ; ?>&vendor=<?php echo $vendorId; ?>">Update Receive</a>
            
            </td>
            </tr>
        <?
        $i++ ; 
        }
        ?>
    </tbody>
</table>


    </div>
</div>


<? include('../footer.php'); ?>

