<?php

include ('../header.php');


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



function get_mis_history($parameter, $type, $id)
{
  global $con;
  $sql = mysqli_query($con, "select $parameter from mis_history where type='" . $type . "' and mis_id='" . $id . "'");
  $sql_result = mysqli_fetch_assoc($sql);
  return $sql_result[$parameter];
}

$username = $_SESSION['SERVICE_username'];

?>

<script src="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/js/jquery-3.7.min.js"></script>


<style>
 
 .form-control {
    font-size: 10px !important;
  }

  .closeBox .callinfo,
  .closeBox .latestUpdate {
    background: none;
  }

  .closeBox {
    /* background: linear-gradient(109.6deg, rgb(243, 67, 67) 11.2%, rgb(2, 38, 208) 100.2%); */
    /* background: linear-gradient(209.3deg, rgb(202, 73, 118) 43.2%, rgb(255, 84, 84) -67.9%) */
    background-image: linear-gradient(to right, #36744b 0%, #38f9d7 100%);
  }

  .callinfo {
    background-color: #0093E9;
    background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);

    padding: 15px 20px;
    color: white;
  }

  .callinfo a {
    color: #b3ff00;
  }

  .latestUpdate {
    background-color: white;
    padding: 15px;
    color: black;
  }

  .pagination {
    display: flex;
    margin: 10px 0;
    padding: 0;
    justify-content: center;
  }

  .pagination li {
    display: inline-block;
    margin: 0 5px;
    padding: 5px 10px;
    border: 1px solid #ccc;
    background-color: #fff;
    color: #555;
    text-decoration: none;
  }

  .pagination li.active {
    border: 1px solid #007bff;
    background-color: #007bff;
    color: #fff;
  }

  .pagination li.active a {
    color: #fff;
  }

  .pagination li:hover:not(.active) {
    background-color: #f5f5f5;
    border-color: #007bff;
    color: #007bff;
  }

  td a {
    color: #01a9ac;
    text-decoration: none;
  }

  td a:focus,
  td a:hover {
    text-decoration: none;
    color: chartreuse;
  }

  a:not([href]) {
    padding: 5px;
  }

  .btn-group {
    border: 1px solid #cccccc;
  }

  ul.dropdown-menu {
    transform: translate3d(0px, 2%, 0px) !important;
    overflow: scroll !important;
    max-height: 250px;
  }

  label {
    font-weight: 900;

  }

  .indication {
    display: flex;
    background: #404e67;
  }

  .indication span {
    width: 15px;
    height: 15px;
    border: 1px solid white;
    border-radius: 25px;
    margin: 10px;
  }

  .open {
    background: white;
  }

  .close {
    background: #e29a9a;
  }

  .schedule {
    background: #d09f45;
  }

  th.address,
  td.address {
    white-space: inherit;
  }

  .box {
    max-height: 200px;
    overflow: hidden;
    border: 1px solid #ccc;
    /* padding: 10px; */
    /* width: 300px; */
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    margin: 15px auto;
  }

  .title {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
  }

  .content {
    font-size: 0.9em;
    margin-bottom: 10px;
  }

  .details {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
  }

  .attribute {
    font-weight: bold;
  }

  .value {
    margin-left: 10px;
  }

  .low {
    color: #6c757d;
  }

  .status {
    font-size: 0.8em;
    font-weight: bold;
    color: white;
    padding: 5px;
    text-align: center;
    border-radius: 5px;
  }

  .ticket_status {
    background: red;
    color: white;
    padding: 15px;
    border-radius: 10px;
  }


</style>

<?php

$userid = $_SESSION['userid'];
$call_type = $_REQUEST['call_type'];
$call_receive = $_REQUEST['call_receive'];
$sql = mysqli_query($con, "select * from user where userid ='" . $userid . "'");
$sql_result = mysqli_fetch_assoc($sql);









$statement = "select a.lho, a.remarks,a.reference_code,a.id,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.mis_id,b.atmid,
                b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,      
                (SELECT name from user WHERE userid = a.created_by) AS createdBy,b.id as detailId,b.status as detailsStatus
                from mis a INNER JOIN mis_details b ON b.mis_id = a.id 
                where 1 and 
                b.mis_id = a.id 
                ";
$sqlappCount = "select count(1) as total from mis a
                    INNER JOIN mis_details b ON b.mis_id = a.id 
                    where 1 and b.mis_id = a.id 
                ";


if (isset($_REQUEST['atmid']) && $_REQUEST['atmid'] != '') {
  $statement .= " and b.atmid like '%" . $_REQUEST['atmid'] . "%'";
  $sqlappCount .= " and b.atmid like '%" . $_REQUEST['atmid'] . "%'";
}

if (isset($_REQUEST['call_receive_from']) && $_REQUEST['call_receive_from'] != '') {
  $statement .= " and a.call_receive_from = '" . $_REQUEST['call_receive_from'] . "'";
  $sqlappCount .= " and a.call_receive_from = '" . $_REQUEST['call_receive_from'] . "'";
}


if (isset($_REQUEST['fromdt']) && $_REQUEST['fromdt'] != '' && isset($_REQUEST['todt']) && $_REQUEST['todt'] != '') {

  $date1 = $_REQUEST['fromdt'];
  $date2 = $_REQUEST['todt'];

  if (isset($_REQUEST['status']) && is_array($_REQUEST['status']) && count($_REQUEST['status']) > 0) {
    if ($_REQUEST['status'][0] == 'close' && count($_REQUEST['status']) == 1) {
      $statement .= " and CAST(b.close_date AS DATE) >= '" . $date1 . "' and CAST(b.close_date AS DATE) <= '" . $date2 . "'";
      $sqlappCount .= " and CAST(b.close_date AS DATE) >= '" . $date1 . "' and CAST(b.close_date AS DATE) <= '" . $date2 . "'";
    } else {
      $statement .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
      $sqlappCount .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
    }
  } else {
    $statement .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
    $sqlappCount .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
  }
}




if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {

  $statusArray = $_REQUEST['status'];
  $statusValues = array_values($statusArray);
  // var_dump($statusValues);
  // Convert the values to a string in the format "('close', 'schedule', ...)"
  $statusString = "('" . implode("', '", $statusValues) . "')";

  $statement .= " and b.status in $statusString ";
  $sqlappCount .= " and b.status in $statusString ";
} else {
  $statement .= " and b.status in('open','permission_require','dispatch','material_requirement','material_in_process','schedule','material_available_i','material_dispatch','cancelled','not_available','available','MRS','fund_required','service_center','reassign')";
  $sqlappCount .= " and b.status in('open','permission_require','dispatch','material_requirement','material_in_process','schedule','material_available_i','material_dispatch','cancelled','not_available','available','MRS','fund_required','service_center','reassign')";
}



if (isset($_REQUEST['call_receive']) && $_REQUEST['call_receive'] != '') {
  $statement .= " and b.case_type = '" . $call_receive . "'";
  $sqlappCount .= " and b.case_type = '" . $call_receive . "'";
}






if ($assignedLho) {

  $statement .= " and a.LHO like '" . $assignedLho . "'";
  $sqlappCount .= " and a.LHO like '" . $assignedLho . "'";
} else if ($_SESSION['isVendor'] == 1) {

  $statement .= " and a.vendorId like '" . $_GLOBAL_VENDOR_ID . "'";
  $sqlappCount .= " and a.vendorId like '" . $_GLOBAL_VENDOR_ID . "'";

}







if (isset($_SESSION['_GLOBAL_LHO'])) {
  $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
  $statement .= " and a.LHO like '" . $_GLOBAL_LHO . "'";
  $sqlappCount .= " and a.LHO like '" . $_GLOBAL_LHO . "'";

}



$statement .= " order by b.id desc";


$result = mysqli_query($con, $sqlappCount);
$row = mysqli_fetch_assoc($result);

$total_records = $row['total'];
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$page_size = 10;

$offset = ($current_page - 1) * $page_size;


$total_pages = ceil($total_records / $page_size);

$window_size = 10;

$start_window = max(1, $current_page - floor($window_size / 2));
$end_window = min($start_window + $window_size - 1, $total_pages);




// Query to retrieve the records for the current page
$sql_query = "$statement LIMIT $offset, $page_size";

// echo $sql_query ; 

// return ; 

?>


<div class="row">
  <div class="col-sm-12 grid-margin">
    <div class="card" id="filter">
      <div class="card-body">
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

          <div class="row">
            <div class="grid-margin col-md-3">
              <label>ATMID</label>
              <input type="text" name="atmid" class="form-control" value="<? echo $_REQUEST['atmid']; ?>" placeholder="Enter ATMID ...">
            </div>

            <div class="grid-margin col-md-3">
              <label>From Call Login Date</label>
              <input type="date" name="fromdt" class="form-control" value="<? if ($_REQUEST['fromdt']) {
                echo $_REQUEST['fromdt'];
              } else {
                echo '2023-01-01';
              } ?>">
            </div>
            <div class="grid-margin col-md-3">
              <label>To Call Login Date</label>
              <input type="date" name="todt" class="form-control" value="<? if ($_REQUEST['todt']) {
                echo $_REQUEST['todt'];
              } else {
                echo date('Y-m-d');
              } ?>">
            </div>

            <div class="grid-margin col-md-3">
              <label>Call Receive From </label>
              <select name="call_receive_from" id="call_receive_from" class="form-control">
                <option value="">Select</option>
                <option value="Customer / Bank" <? if ($_REQUEST['call_receive_from'] == 'Customer / Bank') {
                  echo 'selected';
                } ?>>Customer / Bank</option>
                <option value="Internal" <? if ($_REQUEST['call_receive_from'] == 'Internal') {
                  echo 'selected';
                } ?>>Internal</option>
                <option value="Auto Email Call" <? if ($_REQUEST['call_receive_from'] == 'Auto Email Call') {
                  echo 'selected';
                } ?>>Auto Email Call</option>
              </select>
            </div>

            <div class="col-md-12 ">
              <label>Status</label>

              <?


              $status_sql = mysqli_query($con, "select status_code,status_name from mis_status where status='1'");
              while ($status_sql_result = mysqli_fetch_assoc($status_sql)) {
                if ($status_sql_result['status_code'] == "material_pending") {
                  $status_sql_result['status_code'] = "MRS";
                }
                ?>
                <input type="checkbox" name="status[]" value="<? echo $status_sql_result['status_code']; ?>" <? if (isset($_REQUEST['status'])) {

                     if (in_array($status_sql_result['status_code'], $_REQUEST['status'])) {
                       echo 'checked';

                     }
                   } else {
                     if ($status_sql_result['status_name'] != 'Closed') {
                       echo 'checked';
                     }
                   } ?> > 
      <? echo $status_sql_result['status_name']; ?> &nbsp;&nbsp;
              <?
              }
              ?>





            </div>




          </div>
          <br><br>
          <div class="col" style="display:flex;justify-content:center;">
            <input type="submit" name="submit" value="Filter" class="btn btn-primary">
          </div>
        </form>




      </div>
    </div>
  </div>

  <div class="col-sm-12 grid-margin">


    <h5>Total Records: <strong class="record-count">
        <?= $total_records; ?>
      </strong></h5>
    <form action="./exportMis.php" method="POST">
      <input type="hidden" name="exportSql" value="<? echo $statement; ?>">
      <input type="submit" name="exportMis" class="btn btn-primary" value="Export">
    </form>
    <hr>

    <?php
    $date = date('Y-m-d');
    $date1 = date_create($date);

    $i = 0;

    $counter = ($current_page - 1) * $page_size + 1;
    $sql_app = mysqli_query($con, $sql_query);

    while ($sql_result = mysqli_fetch_assoc($sql_app)) {
      $reference_code = $sql_result['reference_code'];
      $id = $sql_result['id'];
      $createdBy = $sql_result['createdBy'];
      $site_eng_contact = $sql_result['eng_name_contact'];
      $detailsStatus = $sql_result['detailsStatus'];

      $detailId = $sql_result['detailId'];
      if ($site_eng_contact == '') {
        $site_engineer = "";
        $site_engineer_contact = "";
      } else {
        $site_engcontact = explode("_", $site_eng_contact);
        $site_engineer = $site_engcontact[0];
        $site_engineer_contact = $site_engcontact[1];
      }

      $mis_id = $sql_result['mis_id'];
      // echo $mis_id;
    
      $historydate = mysqli_query($con, "select created_at from mis_history where mis_id='" . $id . "' order by id desc limit 1");
      $created_date_result = mysqli_fetch_row($historydate);
      $created_date = $created_date_result[0];

      $customer = $sql_result['customer'];
      $closed_date = $sql_result['close_date'];

      if ($closed_date != '0000-00-00') {
        $date1 = date_create($closed_date);
      }

      $date2 = $sql_result['created_at'];
      $cust_date2 = date('Y-m-d', strtotime($date2));

      $cust_date2 = date_create($cust_date2);
      $diff = date_diff($date1, $cust_date2);
      $atmid = $sql_result['atmid'];


      $status = $sql_result['status'];
      $created_by = $sql_result['created_by'];
      $aging_day = $diff->format("%a");

      $mis_his_key = 0;
      $lastactionsql_result[] = array();
      // echo "select type,created_by,remark,schedule_date,material,material_condition,courier_agency,pod,serial_number,dispatch_date,(SELECT name FROM user WHERE userid = mis_history.created_by) AS last_action_by,created_at from mis_history where mis_id='" . $detailId . "' and type<>'Mail Update' order by id desc" ; 
      $lastactionsql = mysqli_query($con, "select ProblemOccurs,type,created_by,remark,schedule_date,material,material_condition,courier_agency,pod,serial_number,dispatch_date,(SELECT name FROM user WHERE userid = mis_history.created_by) AS last_action_by,created_at from mis_history where mis_id='" . $id . "' and type<>'Mail Update' order by id desc");

      if ($lastactionsql_result = mysqli_fetch_assoc($lastactionsql)) {
        // echo '<pre>';print_r($lastactionsql_result);echo '</pre>';die;
        $his_type = $lastactionsql_result['type'];
        $lastActionDate = $lastactionsql_result['created_at'];

        $lastactionuserid = $lastactionsql_result['created_by'];
        $status_remark = $lastactionsql_result['remark'];

        if ($mis_his_key == 0) {
          $last_action_by = $lastactionsql_result['last_action_by'];
        }
        $mis_his_key = $mis_his_key + 1;
        $schedule_date = "";
        if ($his_type == 'schedule') {
          $schedule_date = $lastactionsql_result['schedule_date'];
        }

        $material = "";
        $material_req_remark = "";
        if ($his_type == 'material_requirement') {
          $material = $lastactionsql_result['material'];
          $material_req_remark = $lastactionsql_result['remark'];
          $material_condition = $lastactionsql_result['material_condition'];
        }
        $courier_agency = "";
        $pod = "";
        $serial_number = "";
        $dispatch_date = "";
        $material_dispatch_remark = "";
        $ProblemOccurs = '';
        // if($his_type=='material_dispatch'){
        $courier_agency = $lastactionsql_result['courier_agency'];
        $pod = $lastactionsql_result['pod'];
        $serial_number = $lastactionsql_result['serial_number'];
        $dispatch_date = $lastactionsql_result['dispatch_date'];
        $material_dispatch_remark = $lastactionsql_result['remark'];
        // }
        $close_type = "";
        $close_remark = "";
        $close_created_at = "";
        $attachment = "";
        if ($his_type == 'close') {
          $close_type = $lastactionsql_result['close_type'];
          $close_remark = $lastactionsql_result['remark'];
          $close_created_at = $lastactionsql_result['created_at'];
          $attachment = $lastactionsql_result['attachment'];
        } else if ($his_type == 'reassign') {

          $close_type = $lastactionsql_result['close_type'];
          $close_remark = $lastactionsql_result['remark'];
          $close_created_at = $lastactionsql_result['created_at'];
          $attachment = $lastactionsql_result['attachment'];
          $ProblemOccurs = $lastactionsql_result['ProblemOccurs'];
        }
      }

      $dependency = ''; // Initialize the $dependency variable
      $type = array();
      $timeDifference = '';
      $dependencySql = mysqli_query($con, "SELECT * FROM mis_history WHERE mis_id='" . $id . "'");
      while ($dependencySqlResult = mysqli_fetch_assoc($dependencySql)) {

        $closeType = $dependencySqlResult['type'];

        if ($closeType == 'close') {
          $closureTime = $dependencySqlResult['created_at'];
          $closureTime = new DateTime($closureTime);
          $date2_2 = new DateTime($date2);
          $difference = $closureTime->diff($date2_2);

          $timeDifference = "";
          $timeDifference .= $difference->d > 0 ? $difference->d . " days " : "";
          $timeDifference .= $difference->h > 0 ? $difference->h . " hours " : "";
          $timeDifference .= $difference->i > 0 ? $difference->i . " minutes " : "";
          $timeDifference .= $difference->s > 0 ? $difference->s . " seconds" : "";
        }
        $type[] = $dependencySqlResult['type'];
      }


      $dependencySql2 = mysqli_query($con, "SELECT * FROM mis_history WHERE mis_id='" . $id . "' order by id desc");
      if ($dependencySqlResult2 = mysqli_fetch_assoc($dependencySql2)) {

        $misType = $dependencySqlResult2['type'];

        $closureTime2 = $datetime;
        $closureTime2 = new DateTime($closureTime2);

        $date22_2 = new DateTime($sql_result['created_at']);
        $difference2 = $closureTime2->diff($date22_2);

        $timeDifference2 = "";
        $timeDifference2 .= $difference2->d > 0 ? $difference2->d . " days " : "";
        $timeDifference2 .= $difference2->h > 0 ? $difference2->h . " hours " : "";
        $timeDifference2 .= $difference2->i > 0 ? $difference2->i . " minutes " : "";
        $timeDifference2 .= $difference2->s > 0 ? $difference2->s . " seconds" : "";

        if ($misType == 'close') {
          $timeDifference2 = $timeDifference;
        }

      }

      if (count($type) > 0) {
        if (in_array('reassign', $type)) {
          $dependency = 'Bank';
        } else {
          $dependency = 'Advantage';
        }
      } else {
        $dependency = 'Advantage';
      }

      // echo 'detailsStatus'.$detailsStatus;
    

      ?>



      <div class="box <?php if ($detailsStatus == 'close') {
        echo 'closeBox';
      } ?>">
        <div class="row">
          <div class="col-sm-7 callinfo">
            <div class="title">
              <a href="viewticket.php?ticket=<? echo $reference_code; ?>">
                <?php echo $atmid; ?>
              </a>

              <?php
              if ($detailsStatus == 'close') {
                echo 'Close';
              } else if ($detailsStatus == 'reassign') {
                echo '<p>Bank Dependency </p>'

                ;
              } else if ($detailsStatus == 'material_requirement') {
                echo 'Open <br />(Material Requirement)';
              } else {
                echo 'Open';

              }


              ?>

            </div>
            <? echo '<p style="text-align:right;"><small>' . $ProblemOccurs . '</small></p>'; ?>


            <div class="content">
              <?php echo $sql_result['component'] . ' - ' . $sql_result['subcomponent']; ?>
            </div>

            <p>
              <? echo $sql_result['city'] . ', ' . $sql_result['state']; ?> <br>
              <?php
              echo $sql_result['location'];

              ?>
            </p>
            <div class="details">
              <div class="attribute"><b>Created:</b>
                <?php echo $sql_result['created_at']; ?>
              </div>
              <div class="attribute"><b>Ticket ID:</b>
                <?php echo $sql_result['ticket_id']; ?>
              </div>


            </div>
          </div>
          <div class="col-sm-5 latestUpdate">

            <?
            if ($close_remark) {
              echo 'Action : <small><u>' . $close_remark . '</u></small>';
              echo '<br />';
              echo 'Last Action By : <small>' . ($last_action_by ? $last_action_by : 'System') . '</small>';
              echo '<br />';
              echo 'Last Action Date <small>: ' . $lastActionDate . '</small>';
            }






            ?>

          </div>
        </div>
      </div>


      <?
      $ProblemOccurs = '';
      $close_remark = '';
      $counter++;
    } ?>



  </div>

</div>








<?
// if (isset($_REQUEST['submit']) || isset($_GET['page'])) { 
?>










<?
$customer = $_REQUEST['customer'];
$customer = http_build_query(array('customer' => $customer));

$status = $_REQUEST['status'];
$statusQuery = http_build_query(array('status' => $status), '', '&', PHP_QUERY_RFC3986);

$call_receive_from = $_REQUEST['call_receive_from'];
$atmid = $_REQUEST['atmid'];
$fromdt = $_REQUEST['fromdt'];
$todt = $_REQUEST['todt'];




echo '<div class="pagination"><ul>';
if ($start_window > 1) {

  echo "<li><a href='?page=1&&atmid=$atmid&&$customer&&fromdt=$fromdt&&todt=$todt&&call_type=$call_type&&$statusQuery&&call_receive_from=$call_receive_from'>First</a></li>";
  echo '<li><a href="?page=' . ($start_window - 1) . '&&atmid=' . $atmid . '&&' . $customer . '&&fromdt=' . $fromdt . '&&todt=' . $todt . '&&call_type=' . $call_type . '&' . $statusQuery . '&&call_receive_from=' . $call_receive_from . '">Prev</a></li>';
}

for ($i = $start_window; $i <= $end_window; $i++) {
  ?>
  <li class="<? if ($i == $current_page) {
    echo 'active';
  } ?>">
    <a
      href="?page=<? echo $i; ?>&&atmid=<? echo $atmid; ?>&&<? echo $customer; ?>&&fromdt=<? echo $fromdt; ?>&&todt=<? echo $todt; ?>&&call_type=<? echo $call_type; ?>&&<? echo $statusQuery; ?>&&call_receive_from=<?= $call_receive_from; ?>">
      <? echo $i; ?>
    </a>
  </li>

<? }

if ($end_window < $total_pages) {

  echo '<li><a href="?page=' . ($end_window + 1) . '&&atmid=' . $atmid . '&&' . $customer . '&&fromdt=' . $fromdt . '&&todt=' . $todt . '&&call_type=' . $call_type . '&&' . $statusQuery . '&&call_receive_from=' . $call_receive_from . '">Next</a></li>';
  echo '<li><a href="?page=' . $total_pages . '&&atmid=' . $atmid . '&&' . $customer . '&&fromdt=' . $fromdt . '&&todt=' . $todt . '&&call_type=' . $call_type . '&&' . $statusQuery . '&&call_receive_from=' . $call_receive_from . '">Last</a></li>';

}
echo '</ul></div>';


?>









</div>
</div>

<?
//  } 

?>


<script>
  $('.update_remark').on('submit', function (e) {
    e.preventDefault();
    var remark = $(this).find("[name='update_remark']").val();
    var misid = $(this).find("[name='misid']").val();
    $.ajax({
      type: 'post',
      url: 'updatemisremark.php',
      data: 'remark=' + remark + '&&misid=' + misid,
      success: function (msg) {
        if (msg == 1) {
          swal('Updated !');
          setTimeout(function () {
            window.location.reload();
          }, 3000);


        } else if (msg == 0) {
          swal('Error in updated !');
        } else if (msg == 2) {
          swal('Remark should not be empty !');
        }
      }
    });


  });

  $(document).ready(function () {

    $('#multiselect_status').multiselect({
      buttonWidth: '100%',
      includeSelectAllOption: true,
      nonSelectedText: 'Select an Option'
    });




  });


  $("#show_filter").css('display', 'none');

  $("#hide_filter").on('click', function () {
    $("#filter").css('display', 'none');
    $("#show_filter").css('display', 'block');
  });
  $("#show_filter").on('click', function () {
    $("#filter").css('display', 'block');
    $("#show_filter").css('display', 'none');
  });



</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js">
</script>
<? include ('../footer.php'); ?>
