<?php

$date = date('Y-m-d');

// var_dump($_SESSION);

if ($assignedLho) {

  $query1 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') and a.LHO like '" . $assignedLho . "'";
  $query2 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.close_date AS DATE) >= '2023-01-01' and CAST(b.close_date AS DATE) <= '" . $date . "' and b.status in ('close') and a.LHO like '" . $assignedLho . "'";
  $query3 = "SELECT COUNT(distinct a.atmid) AS count FROM projectInstallation a inner join sites b on a.atmid = b.atmid where a.isDone=1 and a.LHO like '" . $assignedLho . "' and a.status=1";

  $query4 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and a.call_receive_from = 'Customer / Bank' and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'close', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') and a.LHO like '" . $assignedLho . "' ";

} else if ($_SESSION['isVendor'] == 1) {

  $query1 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') and a.vendorId like '" . $_GLOBAL_VENDOR_ID . "'";
  $query2 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.close_date AS DATE) >= '2023-01-01' and CAST(b.close_date AS DATE) <= '2024-03-30' and b.status in ('close') and a.vendorId like '" . $_GLOBAL_VENDOR_ID . "' ";

  $query3 = "select count(distinct a.atmid) as count from projectInstallation a INNER JOIN sites s ON a.atmid = s.atmid where a.isDone=1 and a.status=1 and s.delegatedToVendorId like '" . $_GLOBAL_VENDOR_ID . "'";
  $query4 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and a.call_receive_from = 'Customer / Bank' and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'close', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update') and a.vendorId like '" . $_GLOBAL_VENDOR_ID . "'";
} else {

  $query1 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update')";

  $query2 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and CAST(b.close_date AS DATE) >= '2023-01-01' and CAST(b.close_date AS DATE) <= '" . $date . "' and b.status in ('close')";




  $query3 = "SELECT COUNT(distinct atmid) AS count FROM projectInstallation where isDone=1 and status=1";

  $query4 = "select count(1) as count from mis a INNER JOIN mis_details b ON b.mis_id = a.id where 1 and b.mis_id = a.id and a.call_receive_from = 'Customer / Bank' and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '" . $date . "' and b.status in ('open', 'close', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update')";

  if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO'] !== '') {
    $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
    $query1 .= " and a.lho like '" . $_GLOBAL_LHO . "'";
    $query2 .= " and a.lho like '" . $_GLOBAL_LHO . "'";
    $query3 .= " and lho like '" . $_GLOBAL_LHO . "'";
    $query4 .= " and a.lho like '" . $_GLOBAL_LHO . "'";

  }

}

// echo $query2 ; 
$queries = [$query1, $query2, $query3, $query4];
$results = [];

foreach ($queries as $query) {
  $result = mysqli_query($con, $query);
  $count = mysqli_fetch_assoc($result)['count'];
  $results[] = $count;
}


?>

<link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/css/ionicons.min.css">

<div class="row">

  <div class="col-lg-3 " style="color: white;">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3 id="info_box_online">
          <?= $results[0]; ?>
        </h3>
        <p>Open Call </p>
      </div>
      <div class="icon">
        <i class="mdi mdi-chart-pie"></i>

      </div>
      <a href="./mis/view_mis.php?atmid=&fromdt=2023-01-01&todt="
        .$date."&call_receive_from=&status%5B%5D=open&status%5B%5D=schedule&status%5B%5D=material_requirement&status%5B%5D=material_dispatch&status%5B%5D=permission_require&status%5B%5D=material_delivered&status%5B%5D=MRS&status%5B%5D=cancelled&status%5B%5D=available&status%5B%5D=not_available&status%5B%5D=material_in_process&status%5B%5D=fund_required&status%5B%5D=reassign&status%5B%5D=Mail+Update&submit=Filter"
        class="small-box-footer">

        View
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div>





  <div class="col-lg-3 " style="color: white;">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3 id="info_box_online">
          <?= $results[1]; ?>
        </h3>
        <p>Total Close Call</p>
      </div>
      <div class="icon">
        <i class="mdi mdi-settings"></i>
      </div>
      <a href="./mis/view_mis.php?atmid=&fromdt=2023-01-01&todt=&call_receive_from=&status%5B%5D=close&submit=Filter&exportSql=select+a.remarks%2Ca.reference_code%2Ca.id%2Ca.bank%2Ca.customer%2Ca.location%2Ca.zone%2Ca.state%2Ca.city%2Ca.branch%2Ca.created_by%2Ca.bm%2Cb.id%2Cb.mis_id%2Cb.atmid%2C%0D%0A++++++++++++++++b.component%2Cb.subcomponent%2Cb.engineer%2Cb.docket_no%2Cb.status%2Cb.created_at%2Cb.ticket_id%2Cb.close_date%2Cb.call_type%2Cb.case_type+%2C++++++%0D%0A++++++++++++++++%28SELECT+name+from+vendorUsers+WHERE+id%3D+a.created_by%29+AS+createdBy%0D%0A++++++++++++++++from+mis+a+INNER+JOIN+mis_details+b+ON+b.mis_id+%3D+a.id+%0D%0A++++++++++++++++where+1+and+%0D%0A++++++++++++++++b.mis_id+%3D+a.id+%0D%0A+++++++++++++++++and+CAST%28b.created_at+AS+DATE%29+>%3D+%272023-01-01%27+and+CAST%28b.created_at+AS+DATE%29+<%3D+%272024-03-30%27+and+b.status+in+%28%27open%27%2C+%27schedule%27%2C+%27material_requirement%27%2C+%27material_dispatch%27%2C+%27permission_require%27%2C+%27material_delivered%27%2C+%27MRS%27%2C+%27cancelled%27%2C+%27available%27%2C+%27not_available%27%2C+%27fund_required%27%2C+%27reassign%27%2C+%27Mail+Update%27%29++order+by+b.id+desc"
        class="small-box-footer">
        View
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div>








  <div class="col-lg-3 " style="color: white;">
    <div class="small-box bg-green">
      <div class="inner">
        <h3 id="info_box_online">
          <?= $results[2]; ?>
        </h3>
        <p>Total Active Sites </p>
      </div>
      <div class="icon">
        <i class="mdi mdi-chart-line"></i>
      </div>
      <a href="./completedInstallation.php" class="small-box-footer">View
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div>





  <div class="col-lg-3 " style="color: white;">
    <div class="small-box bg-blue">
      <div class="inner">
        <h3 id="info_box_online">
          <?= $results[3]; ?>
        </h3>
        <p>Total Calls from Bank </p>
      </div>
      <div class="icon">
        <i class="mdi mdi-counter"></i>
      </div>
      <a href="./mis/view_mis.php?atmid=&fromdt=2023-01-01&todt=<?php echo $date; ?>&call_receive_from=Customer+%2F+Bank&status%5B%5D=open&status%5B%5D=close&status%5B%5D=schedule&status%5B%5D=material_requirement&status%5B%5D=material_dispatch&status%5B%5D=permission_require&status%5B%5D=material_delivered&status%5B%5D=MRS&status%5B%5D=cancelled&status%5B%5D=available&status%5B%5D=not_available&status%5B%5D=material_in_process&status%5B%5D=fund_required&status%5B%5D=reassign&status%5B%5D=Mail+Update&submit=Filter&exportSql=select+a.remarks%2Ca.reference_code%2Ca.id%2Ca.bank%2Ca.customer%2Ca.location%2Ca.zone%2Ca.state%2Ca.city%2Ca.branch%2Ca.created_by%2Ca.bm%2Cb.id%2Cb.mis_id%2Cb.atmid%2C%0D%0A++++++++++++++++b.component%2Cb.subcomponent%2Cb.engineer%2Cb.docket_no%2Cb.status%2Cb.created_at%2Cb.ticket_id%2Cb.close_date%2Cb.call_type%2Cb.case_type+%2C++++++%0D%0A++++++++++++++++%28SELECT+name+from+vendorUsers+WHERE+id%3D+a.created_by%29+AS+createdBy%0D%0A++++++++++++++++from+mis+a+INNER+JOIN+mis_details+b+ON+b.mis_id+%3D+a.id+%0D%0A++++++++++++++++where+1+and+%0D%0A++++++++++++++++b.mis_id+%3D+a.id+%0D%0A+++++++++++++++++and+a.call_receive_from+%3D+%27Customer+%2F+Bank%27+and+CAST%28b.created_at+AS+DATE%29+>%3D+%272023-01-01%27+and+CAST%28b.created_at+AS+DATE%29+<%3D+%272024-03-31%27+and+b.status+in+%28%27open%27%2C+%27schedule%27%2C+%27material_requirement%27%2C+%27material_dispatch%27%2C+%27permission_require%27%2C+%27material_delivered%27%2C+%27MRS%27%2C+%27cancelled%27%2C+%27available%27%2C+%27not_available%27%2C+%27material_in_process%27%2C+%27fund_required%27%2C+%27reassign%27%2C+%27Mail+Update%27%29++order+by+b.id+desc"
        class="small-box-footer">
        View
        <i class="fa fa-arrow-circle-right"></i>
        <ion-icon name="pie-chart-outline"></ion-icon>
      </a>
    </div>
  </div>
</div>



<div class="container-fluid">
  <div class="row">
    <div class="col-md-6" style="padding: 0;">


      <div class="card">
        <div class="card-body">

          <div class="chart-container">
            <canvas id="openChart" style="height:300px"></canvas>
          </div>
        </div>
      </div>

    </div>
    <div class="col-md-6" style="padding: 0;">

      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="closeChart" style="height:300px"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?
$query = "SELECT DATE_FORMAT(created_at, '%H:00:00') AS hour, COUNT(*) AS open_calls 
          FROM mis 
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 10 HOUR)";

if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO'] !== '') {
  $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
  $query .= " and lho like '" . $_GLOBAL_LHO . "'";
}
$query .= "GROUP BY hour order by id asc";


$result = mysqli_query($con, $query);

$hourlyData = array();

// Loop through the result set and store the data in $hourlyData
while ($row = mysqli_fetch_assoc($result)) {
  $hourlyData[] = $row;
}

$hourlyDataJSON = json_encode($hourlyData);

$closequery = "SELECT DATE_FORMAT(created_at, '%H:00:00') AS hour, COUNT(*) AS close_calls 
          FROM mis_history 
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 10 HOUR) and type='close'";


if (isset($_SESSION['_GLOBAL_LHO']) && $_SESSION['_GLOBAL_LHO'] !== '') {
  $_GLOBAL_LHO = $_SESSION['_GLOBAL_LHO'];
  $closequery .= " and lho like '" . $_GLOBAL_LHO . "'";
}
$closequery .= "GROUP BY hour order by id asc";



$closeresult = mysqli_query($con, $closequery);

$closehourlyData = array();
while ($row = mysqli_fetch_assoc($closeresult)) {
  $closehourlyData[] = $row;
}
$hourlyDataJSONClose = json_encode($closehourlyData);
?>




<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // JSON data for open calls
  var jsonDataOpen = <?= $hourlyDataJSON ?>;

  // Extracting labels and data
  var labelsOpen = [];
  var dataOpen = [];
  jsonDataOpen.forEach(function (item) {
    labelsOpen.push(item.hour);
    dataOpen.push(parseInt(item.open_calls));
  });

  // Creating Chart.js line chart for open calls
  var ctxOpen = document.getElementById('openChart').getContext('2d');
  var openChart = new Chart(ctxOpen, {
    type: 'line',
    data: {
      labels: labelsOpen,
      datasets: [{
        label: 'Open Calls',
        data: dataOpen,
        borderColor: 'rgba(54, 162, 235, 1)', // Blue color
        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue background color
        borderWidth: 2,
        pointRadius: 5, // Set point radius
        pointHoverRadius: 7, // Set point hover radius
        pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue point color
        pointBorderColor: 'rgba(54, 162, 235, 1)', // Blue point border color
        pointHoverBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue point hover color
        pointHoverBorderColor: 'rgba(54, 162, 235, 1)' // Blue point hover border color
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false, // Allows the chart to be full width
      scales: {
        xAxes: [{
          type: 'time',
          time: {
            displayFormats: {
              hour: 'h:mm A' // Format time with AM/PM
            }
          },
          scaleLabel: {
            display: true,
            labelString: 'Hour'
          },
          ticks: {
            autoSkip: true,
            maxTicksLimit: 10
          }
        }],
        yAxes: [{
          scaleLabel: {
            display: true,
            labelString: 'Number of Calls'
          }
        }]
      }
    }
  });

  // JSON data for close calls
  var jsonDataClose = <?= $hourlyDataJSONClose ?>;

  // Extracting labels and data
  var labelsClose = [];
  var dataClose = [];
  jsonDataClose.forEach(function (item) {
    labelsClose.push(item.hour);
    dataClose.push(parseInt(item.close_calls));
  });

  // Creating Chart.js line chart for close calls
  var ctxClose = document.getElementById('closeChart').getContext('2d');
  var closeChart = new Chart(ctxClose, {
    type: 'line',
    data: {
      labels: labelsClose,
      datasets: [{
        label: 'Close Calls',
        data: dataClose,
        borderColor: 'rgba(75, 192, 192, 1)', // Green color
        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light green background color
        borderWidth: 2,
        pointRadius: 5, // Set point radius
        pointHoverRadius: 7, // Set point hover radius
        pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Green point color
        pointBorderColor: 'rgba(75, 192, 192, 1)', // Green point border color
        pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)', // Green point hover color
        pointHoverBorderColor: 'rgba(75, 192, 192, 1)' // Green point hover border color
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false, // Allows the chart to be full width
      scales: {
        xAxes: [{
          type: 'time',
          time: {
            displayFormats: {
              hour: 'h:mm A' // Format time with AM/PM
            }
          },
          scaleLabel: {
            display: true,
            labelString: 'Hour'
          },
          ticks: {
            autoSkip: true,
            maxTicksLimit: 10
          }
        }],
        yAxes: [{
          scaleLabel: {
            display: true,
            labelString: 'Number of Calls'
          }
        }]
      }
    }
  });
</script>