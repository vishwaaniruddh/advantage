<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


if ($assignedLho) {

  $query1 = "SELECT COUNT(1) AS count FROM sites a INNER JOIN lhositesdelegation b ON a.id=b.siteid where a.status=1 and a.LHO like '" . $assignedLho . "' and b.isPending=0";
  //  $query1 = "SELECT COUNT(1) AS count FROM sites WHERE status = 1 and LHO like '" . $assignedLho . "'";
  $query2 = "SELECT COUNT(distinct a.atmid) AS count FROM projectInstallation a inner join sites b on a.atmid = b.atmid where a.isDone=1 and b.LHO like '" . $assignedLho . "' and a.status=1";

} else if ($_SESSION['PROJECT_level'] == 3) {

  $query1 = "select COUNT(1) as count from delegation where engineerId='" . $userid . "' and status=1 and isFeasibilityDone = 1";
  $query2 = "select count(1) as count from assignedInstallation where assignedToId='" . $userid . "' and status=1 and isDone=1";

} else if ($_SESSION['isVendor'] == 1) {

  $query1 = "SELECT COUNT(1) AS count FROM sites WHERE status = 1 and delegatedToVendorId='" . $_GLOBAL_VENDOR_ID . "'";
  $query2 = "SELECT COUNT(distinct atmid) AS count FROM projectInstallation where isDone=1 and status=1 and vendor='" . $_GLOBAL_VENDOR_ID . "'";


} else {
  $query1 = "SELECT COUNT(1) AS count FROM sites WHERE status = 1";
  $query2 = "SELECT COUNT(distinct atmid) AS count FROM projectInstallation where isDone=1 and status=1";
}


// echo $query1 ;
// echo $query2 ; 
$queries = [$query1, $query2];
$results = [];
foreach ($queries as $query) {
  $result = mysqli_query($con, $query);
  $count = mysqli_fetch_assoc($result)['count'];
  $results[] = $count;
}


?>

<link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/css/ionicons.min.css">

<div class="row">

  <div class="col-lg-4 col-xs-6" style="color: white;">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3 id="info_box_online">
          <?= $results[1] . ' / ' . $results[0]; ?>
        </h3>
        <p>Active / All </p>
      </div>
      <div class="icon">
        <i class="mdi mdi-chart-pie"></i>

      </div>
      <a href="./sites/sitestest.php" class="small-box-footer">Sites
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div>





  <?

  if ($assignedLho) {

    $query3 = "SELECT COUNT(DISTINCT a.atmid) AS today_record_count FROM projectInstallation a 
    INNER JOIN sites b ON a.siteid = b.id
    WHERE DATE(a.scheduleDate) = CURDATE() and b.LHO like '" . $assignedLho . "'";
    $query33 = "SELECT COUNT(DISTINCT atmid) as today_record_count FROM projectInstallation WHERE DATE(scheduleDate) = CURDATE() and isDone=1";

  } else if ($_SESSION['PROJECT_level'] == 3) {

    $query3 = "SELECT COUNT(DISTINCT atmid) AS today_record_count FROM projectInstallation 
    WHERE DATE(scheduleDate) = CURDATE()";
    $query33 = "SELECT COUNT(DISTINCT atmid) as today_record_count FROM projectInstallation WHERE DATE(scheduleDate) = CURDATE() and isDone=1";

  } else if ($_SESSION['isVendor'] == 1 && $_SESSION['PROJECT_level'] != 3) {

    $query3 = "SELECT COUNT(DISTINCT atmid) AS today_record_count FROM projectInstallation 
    WHERE DATE(scheduleDate) = CURDATE() and vendor='" . $_GLOBAL_VENDOR_ID . "'";

    $query33 = "SELECT COUNT(DISTINCT atmid) as today_record_count FROM projectInstallation WHERE 
     isDone=1 and vendor='" . $_GLOBAL_VENDOR_ID . "' and isDoneDate like '" . $date . "'";

  } else if ($_SESSION['PROJECT_level'] == 3) {

    $query3 = "SELECT COUNT(DISTINCT atmid) AS today_record_count FROM projectInstallation 
    WHERE DATE(scheduleDate) = CURDATE()";

    $query33 = "SELECT COUNT(DISTINCT atmid) as today_record_count FROM projectInstallation WHERE DATE(scheduleDate) = CURDATE() and isDone=1";

  } else {

    $query3 = "SELECT COUNT(DISTINCT atmid) AS today_record_count FROM projectInstallation 
    WHERE DATE(scheduleDate) = CURDATE()";
    $query33 = "SELECT COUNT(DISTINCT atmid) as today_record_count FROM projectInstallation WHERE DATE(scheduleDate) = CURDATE() and isDone=1";

  }
  // echo $query3 ; 
  $sql = mysqli_query($con, $query3);
  $sql_result = mysqli_fetch_assoc($sql);


  // echo $query33 ; 
  $sql2 = mysqli_query($con, $query33);
  $sql_result2 = mysqli_fetch_assoc($sql2);


  ?>


  <div class="col-lg-4 col-xs-6" style="color: white;">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3 id="info_box_online">
          <?= $sql_result['today_record_count'] . ' / ' . $sql_result2['today_record_count']; ?>
        </h3>
        <p> Est. Plan / Actual Live Sites </p>
      </div>
      <div class="icon">
        <i class="mdi mdi-settings"></i>
      </div>
      <a href="./scheduledInstallation.php" class="small-box-footer">Todays Installation
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div>







  <?


  if ($assignedLho) {
    $inv_sql = mysqli_query($con, "SELECT isDelivered,count(1) as total FROM `material_send` where lho='" . $assignedLho . "' group by isDelivered");
  } else if ($_SESSION['PROJECT_level'] == 3) {

    $inv_sql = mysqli_query($con, "SELECT isDelivered,count(1) as total FROM `vendormaterialsend` where contactPersonName='" . $userid . "' group by isConfirm");

  } else if ($_SESSION['isVendor'] == 1 && $_SESSION['PROJECT_level'] != 3) {
    $inv_sql = mysqli_query($con, "SELECT isDelivered,count(1) as total FROM `vendormaterialsend` where vendorId='" . $_GLOBAL_VENDOR_ID . "' group by isDelivered");

  } else {
    $inv_sql = mysqli_query($con, "SELECT isDelivered,count(1) as total FROM `material_send` where 1 and portal='clarity' group by isDelivered");


    $inv_pending_sql = mysqli_query($con,"SELECT ms.id,ms.atmid, msd.serialNumber AS latest_serialNumber,ms.contactPersonName, ms.address,ms.pod,ms.courier,ms.lho,ms.siteid,ms.vendorId,ms.contactPersonNumber,ms.remark,ms.created_at FROM material_send ms JOIN material_send_details msd ON ms.id = msd.materialSendId WHERE ms.status=1 and msd.attribute = 'Router' and ms.isDelivered like '%0%' and ms.portal in ('clarity','Clarity') AND msd.created_at = ( SELECT MAX(created_at) FROM material_send_details WHERE materialSendId = ms.id AND attribute = 'Router' ) and ms.portal in ('clarity','Clarity') group by latest_serialNumber");
    $totalTransit = mysqli_num_rows($inv_pending_sql) ;

    // echo "SELECT ms.id,ms.atmid, msd.serialNumber AS latest_serialNumber,ms.contactPersonName, ms.address,ms.pod,ms.courier,ms.lho,ms.siteid,ms.vendorId,ms.contactPersonNumber,ms.remark,ms.created_at FROM material_send ms JOIN material_send_details msd ON ms.id = msd.materialSendId WHERE ms.status=1 and  msd.attribute = 'Router' and ms.isDelivered like '%1%' and ms.portal in ('clarity','Clarity') AND msd.created_at = ( SELECT MAX(created_at) FROM material_send_details WHERE materialSendId = ms.id AND attribute = 'Router' ) and ms.portal  in ('clarity','Clarity') group by latest_serialNumber" ;
    $inv_delivered_sql = mysqli_query($con,"SELECT ms.id,ms.atmid, msd.serialNumber AS latest_serialNumber,ms.contactPersonName, ms.address,ms.pod,ms.courier,ms.lho,ms.siteid,ms.vendorId,ms.contactPersonNumber,ms.remark,ms.created_at FROM material_send ms JOIN material_send_details msd ON ms.id = msd.materialSendId WHERE ms.status=1 and  msd.attribute = 'Router' and ms.isDelivered like '%1%' and ms.portal in ('clarity','Clarity') AND msd.created_at = ( SELECT MAX(created_at) FROM material_send_details WHERE materialSendId = ms.id AND attribute = 'Router' ) and ms.portal  in ('clarity','Clarity') group by latest_serialNumber");
    $totalDelivered = mysqli_num_rows($inv_delivered_sql) ;
 

  }

  while ($inv_sql_result = mysqli_fetch_assoc($inv_sql)) {
    $isDelivered = $inv_sql_result['isDelivered'];
    $statusCount[] = $inv_sql_result['total'];
  
    // var_dump($inv_sql_result);
  }


  if( !isset($totalTransit)){

    $totalTransit =  $statusCount[1] ; 
    $totalDelivered = $statusCount[0] ;
  }
  ?>



  
  <div class="col-lg-4 col-xs-6" style="color: white;">
   <div class="small-box bg-green">
     <div class="inner">
       <h3 id="info_box_online">
       <? echo $totalTransit . ' / ' . $totalDelivered ; ?>
       </h3>
       <p>In-transit / Delivered </p>
     </div>
     <div class="icon">
       <i class="mdi mdi-chart-line"></i>
     </div>
     <a href="./inventory/materialSent.php" class="small-box-footer">Material
       <i class="fa fa-arrow-circle-right"></i>
       <ion-icon name="pie-chart-outline"></ion-icon>
     </a>
   </div>
  </div> 



  <? if($userid == '10041'){ ?>

  <div class="col-lg-3 col-xs-6" style="color: white; display:none;">
    <div class="small-box bg-blue">
      <div class="inner">
        <h3 id="info_box_online">
          <?= ($statusCount[0] ? $statusCount[0] : '0') . ' / ' . ($statusCount[1] ? $statusCount[1] : '0'); ?>
        </h3>
        <p>In-transit / Delivered </p>
      </div>
      <div class="icon">
        <i class="mdi mdi-counter"></i>
      </div>
      <a href="./inventory/materialSent.php" class="small-box-footer">Material
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div> 





  <? } ?>




</div>