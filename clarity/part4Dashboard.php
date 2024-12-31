
<?
if ($assignedLho) {
    $query = "SELECT  COUNT(s.delegatedToVendorId) AS siteAllocated,  COALESCE(SUM(s.delegatedByVendor = 1), 0) AS assignEngineer, COALESCE(SUM(s.isFeasibiltyDone = 1), 0) AS feasibiltyDone FROM 
           sites s  where s.LHO like '" . $assignedLho . "'";
} else {
    $query = "SELECT COUNT(s.delegatedToVendorId) AS siteAllocated,  COALESCE(SUM(s.delegatedByVendor = 1), 0) AS assignEngineer, COALESCE(SUM(s.isFeasibiltyDone = 1), 0) AS feasibiltyDone FROM 
        sites s ";
}


// echo $query ; 
$result = mysqli_query($con, $query);
$data = array();
if ($row = mysqli_fetch_assoc($result)) {
    // $vendorId = $row['id'];
    $query4 = mysqli_query($con, "SELECT COUNT(DISTINCT siteid) AS count FROM material_requests WHERE status='pending' AND isProject=1");
    $query4_result = mysqli_fetch_assoc($query4);
    $materialRequest = $query4_result['count'];

    if ($assignedLho) {
        $query5 = mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a INNER JOIN sites s ON a.atmid=s.atmid where  s.LHO like '" . $assignedLho . "'");

        $query6 = mysqli_query($con, "SELECT COUNT(distinct a.atmid) AS count FROM projectInstallation a INNER JOIN sites s ON a.atmid=s.atmid where a.isDone=1 and s.LHO like '" . $assignedLho . "' and a.status=1");

    } else {
        $query5 = mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send");
        $query6 = mysqli_query($con, "SELECT COUNT(distinct atmid) AS count FROM projectInstallation where isDone=1 and status=1");

    }


    $query5_result = mysqli_fetch_assoc($query5);
    $materialSend = $query5_result['count'];


    $query6_result = mysqli_fetch_assoc($query6);
    $installationDone = $query6_result['count'];


    // $vendorName = $row['vendorName'];
    $siteAllocated = $row['siteAllocated'];
    $assignEngineer = $row['assignEngineer'];
    $feasibiltyDone = $row['feasibiltyDone'];

    $data[] = array(
        // "Vendor" => $vendorName,
        "siteAllocated" => $siteAllocated,
        "assignEngineer" => $assignEngineer,
        "feasibiltyDone" => $feasibiltyDone,
        "materialRequest" => $materialRequest,
        "materialSend" => $materialSend,
        "project" => $installationDone
    );
}
?>


<style>
    #chart-container-part4 {
        width: 100%;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div id="chart-container-part4" class="col-sm-12">
    <canvas id="myChartPart4" style="height: 300px; overflow: hidden; text-align: left;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

var data = <?php echo json_encode($data); ?>;
data.forEach(function (item) {
    item.siteAllocated = parseInt(item.siteAllocated);
    item.assignEngineer = parseInt(item.assignEngineer);
    item.feasibilityDone = parseInt(item.feasibilityDone);
    item.materialRequest = parseInt(item.materialRequest);
    item.materialSend = parseInt(item.materialSend);
    item.project = parseInt(item.project);
});

// Create Chart.js chart with a unique ID
var ctxPart4 = document.getElementById('myChartPart4').getContext('2d');
var myChartPart4 = new Chart(ctxPart4, {
    type: 'doughnut',
    data: {
        labels: [
            'Site Allocated',
            'Engineer Assign',
            'Feasibility Done',
            'Material Request',
            'Material Dispatch',
            'Live Sites'
        ],
        datasets: [{
            data: [
                data[0].siteAllocated,
                data[0].assignEngineer,
                data[0].feasibilityDone,
                data[0].materialRequest,
                data[0].materialSend,
                data[0].project
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    fontColor: '#333'
                }
            }
        }
    }
});

</script>

