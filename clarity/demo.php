<?php
include ('config.php');


$serialNumberAr = array();

$sql = mysqli_query($con,"SELECT * FROM `routerconfiguration` WHERE `status` = 1 ORDER BY `status` DESC");
while($sql_result = mysqli_fetch_assoc($sql)){

    $serialNumber = $sql_result['serialNumber'];
    $atmid = $sql_result['atmid'];

    if(!in_array($serialNumber,$serialNumberAr)){
        $checksql = mysqli_query($con,"select * from material_send_details where serialNumber='".$serialNumber."' order by id desc");
        if($checksql_result = mysqli_fetch_assoc($checksql)){

            $materialSendId = $checksql_result['materialSendId']; 
            // get sim based on materialSendId
            $simsql = mysqli_query($con,"select * from material_send_details where attribute like '%sim%' and materialSendId='".$materialSendId."'");
            while($simsql_result = mysqli_fetch_assoc($simsql)){

                $attributeName = $simsql_result['attribute'];
                $simSerialNumber = $simsql_result['serialNumber'];

                mysqli_query($con,"INSERT INTO ccidconfiguration(serialNumber,atmid,operator,ccid,status,created_at,created_by,created_by_name) 
                values('".$serialNumber."','".$atmid."','".$attributeName."','".$simSerialNumber."','1','".$datetime."','".$userid."','Aniruddh Vishwakarma') ");


            }
            
            $serialNumberAr[] = $serialNumber ; 
        }

    }


}


var_dump($serialNumberAr)
;










return ; 
$i = 1;


$notFoundAr = array();
$sql = mysqli_query($con, "select distinct(atmid) as atmid from projectinstallation where isDone=1 and status=1 order by id desc");
while ($sql_result = mysqli_fetch_assoc($sql)) {

    echo $atmid = $sql_result['atmid'];


    $mssql = mysqli_query($con, "SELECT ms.id,ms.atmid,ms.address,ms.pod,ms.courier,ms.lho,ms.siteid,ms.vendorId,ms.contactPersonNumber,ms.remark,ms.created_at ,ms.isDelivered , 
msd.serialNumber AS latest_serialNumber,ms.contactPersonName FROM material_send ms JOIN material_send_details msd 
ON ms.id = msd.materialSendId WHERE ms.status like 1 and msd.attribute like '%Router%' and ms.portal in('clarity','Clarity') AND 
msd.created_at = ( SELECT MAX(created_at) FROM material_send_details WHERE materialSendId = ms.id AND attribute like '%Router%' ) 
and ms.atmid like '%" . $atmid . "%' and ms.portal in('clarity','Clarity') group by latest_serialNumber");
    if ($mssql_result = mysqli_fetch_assoc($mssql)) {
        $msId = $mssql_result['id'];
        echo ' Found';
        // mysqli_query($con, "update material_send set status=1 where id='" . $msId . "'");
        // echo "update material_send set status=1 where id='" . $msId . "'" ; 
    } else {

        echo ' Not Found';

    }
    echo '<br />';
}

































return;

$uniqueLatestAr = array();

$sql = mysqli_query($con, "SELECT * from projectinstallation where isDone=1 and status=1 ORDER BY id DESC");
while ($sql_result = mysqli_fetch_assoc($sql)) {

    $id = $sql_result['id'];
    $atmid = $sql_result['atmid'];

    // store atmid in array if not in $uniqueLatestAr
    if (!in_array($atmid, $uniqueLatestAr)) {
        $uniqueLatestAr[] = $atmid;

        mysqli_query($con, "update projectinstallation set status=1 where id = '" . $id . "'");

    } else {
        mysqli_query($con, "update projectinstallation set status=0 where id = '" . $id . "'");

    }
}






return;


$uniqueLatestAr = array();

$sql = mysqli_query($con, "SELECT * from material_send ORDER BY id DESC");
while ($sql_result = mysqli_fetch_assoc($sql)) {

    $id = $sql_result['id'];
    $atmid = $sql_result['atmid'];

    // store atmid in array if not in $uniqueLatestAr
    if (!in_array($atmid, $uniqueLatestAr)) {
        $uniqueLatestAr[] = $atmid;

        mysqli_query($con, "update material_send set status=1 where id = '" . $id . "'");

    }
}

// Now $uniqueLatestAr array contains unique 'atmid' values


































return;

$sql = mysqli_query($con, "select * from projectInstallation a where a.isDone=1 and a.status=1 and atmid not in ( select atmid from mytemdata ) group by a.atmid");
while ($sql_result = mysqli_fetch_assoc($sql)) {
    echo
        $atmid = $sql_result['atmid'];
    $siteid = $sql_result['siteid'];
    $vendorId = $sql_result['vendor'];
    $lho = $sql_result['lho'];


    if ($vendorId == 1) {

        $contactPersonName = 'Vijay Gupta';
        $contactPersonNumber = '8850830243';
        $address = '14/B/4  Ground Floor Plot -14A/14B  New Sion CHS, Swami 
    Vallabhdas Marg Road NO 24 Sindhi Colony , Sion Mumbai 400022.
    ';
    } else if ($vendorId == 2) {

        $contactPersonName = 'Shubham Yadav';
        $contactPersonNumber = '7045268980';
        $address = 'TempForce Technology Pvt Ltd
    Shop No. 10, Shagun Apartment, Rani Sati Marg,
    Near Railway Crossing, Malad East, 
    Mumbai – 400097, Maharashtra
    ';
    } else if ($vendorId == 3) {

        $contactPersonName = 'Prasad Arjee';
        $contactPersonNumber = '9967259290';
        $address = 'ADVAIT TECHSERVE INDIA PVT. LTD.
    4/48 Dheeraj Heritage Business Centre, SV Road, 
    On Milan Subway Signal, Santacruz – W, Mumbai 400054 ';
    } else if ($vendorId == 4) {

    } else if ($vendorId == 5) {

    } else if ($vendorId == 6) {

    } else if ($vendorId == 7) {
        $contactPersonName = 'Kishan Gupta';
        $contactPersonNumber = '8879149164';
        $address = 'Servolutions Group
    C325, Eastern Business District,
    Neptune Magnet Mall, LBS Marg,
    Bhandup West, Mumbai – 400078
    ';
    } else if ($vendorId == 8) {
        $contactPersonName = 'Mr.Akshay A. Muluk';
        $contactPersonNumber = '7045997356';
        $address = 'Veritas Infratech Pvt Ltd,
    K. Raheja Prime, 8th Floor,
    Sag Baug Road, Marol,
    Andheri East, Mumbai-400059';
    }


    //ms = material_send
    $ms = mysqli_query($con, "select * from material_send where atmid='" . $atmid . "'");
    if ($ms_result = mysqli_fetch_assoc($ms)) {

        echo ' Found !';

    } else {



        $msInsert = "
INSERT INTO material_send(atmid,siteid,vendorId,contactPersonName,contactPersonNumber,address,pod,courier,remark,created_at,isDelivered,material_qty,portal,lho,created_by)
VALUES('" . $atmid . "','" . $siteid . "','" . $vendorId . "','" . $contactPersonName . "','" . $contactPersonNumber . "','" . $address . "','','','','" . $datetime . "',1,1,'clarity','" . $lho . "','" . $userid . "')
";
        if (mysqli_query($con, $msInsert)) {


            $msId = $con->insert_id;

            $boq = mysqli_query($con, "select * from boq where status=1");
            while ($boqResult = mysqli_fetch_assoc($boq)) {

                echo $product = $boqResult['value'];

                if ($product == 'Router') {

                    $serialNumber = mysqli_fetch_assoc(mysqli_query($con, "select serialNumber from routerConfiguration where status=1 and atmid='" . $atmid . "' order by id desc"))['serialNumber'];


                } else {
                    $serialNumber = '';
                }
                echo '<br />';


                $msDetailInsert = "INSERT INTO material_send_details(materialSendId,attribute,value,serialNumber,material_qty,created_at) 
values('" . $msId . "','" . $product . "','" . $product . "','" . $serialNumber . "',1,'" . $datetime . "')";

                mysqli_query($con, $msDetailInsert);


            }



        }





    }

    echo '<br />';










}






















return;

$sql = mysqli_query($con, "select * from material_send");
while ($sql_result = mysqli_fetch_assoc($sql)) {


    $id = $sql_result['id'];
    $created_at = $sql_result['created_at'];

    $update = "update material_send_details set created_at='" . $created_at . "' where materialSendId='" . $id . "'";
    mysqli_query($con, $update)
    ;
}



return;





$sql = mysqli_query($con, "select id from material_send a where a.isDelivered=0 and a.portal='clarity' and a.atmid in (select atmid from projectinstallation where status=1 and isDone=1) ORDER BY `id` DESC");










































































return;



$sql = mysqli_query($con, "select * from projectInstallation where isDone=1");
while ($sql_result = mysqli_fetch_assoc($sql)) {

    $atmid = $sql_result['atmid'];


    mysqli_query($con, "update sites set isDone=1 where atmid='" . $atmid . "'");



}




return;

$sql = mysqli_query($con, "select * from mis");
while ($sql_result = mysqli_fetch_assoc($sql)) {

    $id = $sql_result['id'];
    $atmid = $sql_result['atmid'];
    $vendorId = $sql_result['vendorId'];


    $sitesql = mysqli_query($con, "select * from sites where atmid='" . $atmid . "'");
    $sitesql_result = mysqli_fetch_assoc($sitesql);
    $delegatedToVendorName = $sitesql_result['delegatedToVendorName'];
    $delegatedToVendorId = $sitesql_result['delegatedToVendorId'];


    if ($vendorId == '') {

        mysqli_query($con, "update mis set vendorId='" . $delegatedToVendorId . "', vendorName='" . $delegatedToVendorName . "' where id='" . $id . "'");
    }


}



return;
$sql = mysqli_query($con, "select * from material_send_details where uploadType='Bulk' and value<>'0'");
while ($sqlResult = mysqli_fetch_assoc($sql)) {

    $id = $sqlResult['id'];
    echo $attribute = $sqlResult['attribute'];
    echo '<br />';

    // $invsql = mysqli_query($con, "select * from inventory where material='" . $attribute . "' and status=1");
    // if ($invsqlResult = mysqli_fetch_assoc($invsql)) {

    //     $invID = $invsqlResult['id'];

    //     mysqli_query($con,"update inventory set status=0 where id='".$invID."'");
    //     mysqli_query($con,"update material_send_details set invID='".$invID."' where id='".$id."'");


    // }





}








return;
$sql = mysqli_query($con, "select * from material_send_details where uploadType='Bulk' and value='0'");
while ($sqlResult = mysqli_fetch_assoc($sql)) {

    $id = $sqlResult['id'];
    echo $attribute = $sqlResult['attribute'];

    $invsql = mysqli_query($con, "select * from inventory where material='" . $attribute . "' and status=1");
    if ($invsqlResult = mysqli_fetch_assoc($invsql)) {

        $invID = $invsqlResult['id'];

        mysqli_query($con, "update inventory set status=0 where id='" . $invID . "'");
        mysqli_query($con, "update material_send_details set invID='" . $invID . "' where id='" . $id . "'");


    }





}





?>