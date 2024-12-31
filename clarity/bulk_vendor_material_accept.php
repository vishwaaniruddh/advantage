<?php include ('./config.php');

$statement = "SELECT challan_no,vendorId,count(1) as total FROM `material_send` where challan_no<>'' group by challan_no,vendorId";


?>

<table border="1">
    <thead>
        <tr>
            <th>Sr</th>
            <th>Challan</th>
            <th>VendorId</th>
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
            <td><?php echo $vendorId ; ?></td>
            <td><?php echo $total; ?></td>
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