<?                                
if($assignedLho){
$query = "SELECT v.id,v.vendorName, COUNT(s.delegatedToVendorId) AS siteAllocated,  COALESCE(SUM(s.delegatedByVendor = 1), 0) AS assignEngineer, COALESCE(SUM(s.isFeasibiltyDone = 1), 0) AS feasibiltyDone FROM vendor v
          INNER JOIN sites s ON v.id = s.delegatedToVendorId where v.status=1  and s.LHO like '".$assignedLho."' GROUP BY v.id";
}
else if ($_SESSION['isVendor'] == 1 && $_SESSION['PROJECT_level'] != 3) {


    
$query = "SELECT v.id,v.vendorName, COUNT(s.delegatedToVendorId) AS siteAllocated, 
COALESCE(SUM(s.delegatedByVendor = 1), 0) AS assignEngineer,
COALESCE(SUM(s.isFeasibiltyDone = 1), 0) AS feasibiltyDone
FROM vendor v
LEFT JOIN sites s ON v.id = s.delegatedToVendorId
where v.id = '".$RailTailVendorID."'
GROUP BY v.id";


}
else{
$query = "SELECT v.id,v.vendorName, COUNT(s.delegatedToVendorId) AS siteAllocated,  COALESCE(SUM(s.delegatedByVendor = 1), 0) AS assignEngineer, COALESCE(SUM(s.isFeasibiltyDone = 1), 0) AS feasibiltyDone FROM vendor v
      INNER JOIN sites s ON v.id = s.delegatedToVendorId where v.status=1 GROUP BY v.id";
}
          
$result = mysqli_query($con, $query);

$data = array();

while ($row = mysqli_fetch_assoc($result)) {

$vendorId = $row['id'];
$query4 = mysqli_query($con,"SELECT COUNT(DISTINCT siteid) AS count FROM material_requests WHERE status='pending' AND isProject=1 and vendorId='".$vendorId."'");
$query4_result = mysqli_fetch_assoc($query4);
$materialRequest = $query4_result['count'];

if($assignedLho){
    $query5 = mysqli_query($con,"SELECT COUNT(1) AS count FROM material_send a INNER JOIN sites s ON a.atmid=s.atmid where a.vendorId='".$vendorId."' and s.LHO like '".$assignedLho."'");    
    $query6 = mysqli_query($con,"SELECT COUNT(distinct a.atmid) AS count FROM projectInstallation a INNER JOIN sites s ON a.atmid=s.atmid where a.vendor='".$vendorId."' and a.isDone=1 and s.LHO like '".$assignedLho."' and a.status=1");
}else{
    $query5 = mysqli_query($con,"SELECT COUNT(1) AS count FROM material_send where vendorId='".$vendorId."'");    
    $query6 = mysqli_query($con,"SELECT COUNT(distinct atmid) AS count FROM projectInstallation where vendor='".$vendorId."' and isDone=1 and status=1");
    
}



$query5_result = mysqli_fetch_assoc($query5);
$materialSend = $query5_result['count']; 


$query6_result = mysqli_fetch_assoc($query6);
$installationDone = $query6_result['count']; 


    $vendorName = $row['vendorName'];
    $siteAllocated = $row['siteAllocated'];
    $assignEngineer = $row['assignEngineer'];
    $feasibiltyDone = $row['feasibiltyDone'];

    $data[] = array(
        "Vendor" => $vendorName,
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
    

    #chart-container-contractors {
        width: 100%;
        margin: auto;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div id="chart-container-contractors" class="row">
    <canvas id="myChartContractors" style="height: 300px; overflow: hidden; text-align: left;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

var data = <?php echo json_encode($data); ?>;
data.forEach(function(item) {


    item.siteAllocated = parseInt(item.siteAllocated);
    item.assignEngineer = parseInt(item.assignEngineer);
    item.feasibilityDone = parseInt(item.feasibiltyDone);
    item.materialRequest = parseInt(item.materialRequest);
    item.materialSend = parseInt(item.materialSend);
    item.project = parseInt(item.project);
});

// Create Chart.js chart with a unique ID
var ctxContractors = document.getElementById('myChartContractors').getContext('2d');
var myChartContractors = new Chart(ctxContractors, {
    type: 'bar',
    data: {
        labels: data.map(item => item.Vendor),
        datasets: [
            {
                label: 'Site Allocated',
                data: data.map(item => item.siteAllocated),
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Engineer Assign',
                data: data.map(item => item.assignEngineer),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Feasibility Done',
                data: data.map(item => item.feasibilityDone),
                backgroundColor: 'rgba(255, 205, 86, 0.8)',
                borderColor: 'rgba(255, 205, 86, 1)',
                borderWidth: 1
            },
            {
                label: 'Material Request',
                data: data.map(item => item.materialRequest),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Material Dispatch',
                data: data.map(item => item.materialSend),
                backgroundColor: 'rgba(153, 102, 255, 0.8)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            },
            {
                label: 'Live Sites',
                data: data.map(item => item.project),
                backgroundColor: 'rgba(255, 159, 64, 0.8)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                type: 'category',
                title: {
                    display: true,
                    text: 'Contractors',
                    color: '#333'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Counts',
                    color: '#333'
                }
            }
        },
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
