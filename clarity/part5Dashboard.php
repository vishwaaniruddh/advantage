<div class="row">
    <div class="">


        <?

        $data = array();


        if ($assignedLho) {
            $sql = mysqli_query($con, "
                            SELECT
                            DATE(ESD) AS ESD_Date,
                            COUNT(1) AS ESD_Count,
                            DATE(ASD) AS ASD_Date,
                            COUNT(1) AS ASD_Count
                            FROM
                            sites
                            WHERE
                            (ESD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY)
                            OR (ASD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY)
                            and LHO like '" . $assignedLho . "'
                            GROUP BY
                            ESD_Date, ASD_Date
                            ORDER BY
                            ESD_Date, ASD_Date");

        } else if ($_SESSION['PROJECT_level'] == 3) {


            $sql = mysqli_query(
                $con,
                "SELECT
    DATE(a.ESD) AS ESD_Date,
    COUNT(1) AS ESD_Count,
    DATE(a.ASD) AS ASD_Date,
    COUNT(1) AS ASD_Count
    FROM
    sites a INNER JOIN delegation b 
    ON a.id = b.siteid
    WHERE
    ((a.ESD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY)
    OR (a.ASD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY))
    and b.engineerId='" . $userid . "'
    GROUP BY
    ESD_Date, ASD_Date
    ORDER BY
    ESD_Date, ASD_Date
    "
            );
        } else if ($_SESSION['isVendor'] == 1 && $_SESSION['PROJECT_level'] != 3) {


            $sql = mysqli_query(
                $con,
                "SELECT
            DATE(a.ESD) AS ESD_Date,
            COUNT(1) AS ESD_Count,
            DATE(a.ASD) AS ASD_Date,
            COUNT(1) AS ASD_Count
            FROM
            sites a 
            WHERE
            ((a.ESD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY)
            OR (a.ASD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY))
            and a.delegatedToVendorId='" . $_GLOBAL_VENDOR_ID . "'
            GROUP BY
            ESD_Date, ASD_Date
            ORDER BY
            ESD_Date, ASD_Date
            "
            );


        } else {
            $sql = mysqli_query($con, "
                            SELECT
                            DATE(ESD) AS ESD_Date,
                            COUNT(1) AS ESD_Count,
                            DATE(ASD) AS ASD_Date,
                            COUNT(1) AS ASD_Count
                            FROM
                            sites
                            WHERE
                            (ESD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY)
                            OR (ASD BETWEEN NOW() - INTERVAL 60 DAY AND NOW() + INTERVAL 60 DAY)
                            GROUP BY
                            ESD_Date, ASD_Date
                            ORDER BY
                            ESD_Date, ASD_Date");

        }

        while ($sql_result = mysqli_fetch_assoc($sql)) {
            $data[] = array(
                "ESD_Date" => $sql_result['ESD_Date'],
                "ESD_Count" => intval($sql_result['ESD_Count']),
                "ASD_Date" => $sql_result['ASD_Date'],
                "ASD_Count" => intval($sql_result['ASD_Count'])
            );
        }
        ?>















        <style>
            #chart-container {
                width: 100%;
                margin: auto;
                background-color: #ffffff;
                /* Set the background color of the chart container */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                /* Add a subtle box shadow */
            }
        </style>

        <div id="chart-container">
            <canvas id="myChart" height="400"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>

            // Extract data from PHP and format it for Chart.js
            var chartData = <?php echo json_encode($data); ?>;

            // Prepare data for Chart.js
            var dates = chartData.map(function (item) {
                return item.ESD_Date;
            });

            var esdData = chartData.map(function (item) {
                return item.ESD_Count;
            });

            var asdData = chartData.map(function (item) {
                return item.ASD_Count;
            });

            // Create Chart.js chart
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'ESD',
                        data: esdData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 3, // Increase the line thickness
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointRadius: 5, // Increase the point size
                        fill: false
                    }, {
                        label: 'ASD',
                        data: asdData,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointRadius: 5,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'category',
                            title: {
                                display: true,
                                text: 'Date',
                                color: '#333', // Set the color of the axis title
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Count',
                                color: '#333',
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontColor: '#333', // Set the color of the legend text
                            }
                        }
                    }
                }
            });

        </script>




        <?
        $data = array();

        ?>
        <br />


























































        <div id="chart-container" class="col-md-12 grid-margin stretch-card" style="height: 500px;">
            <canvas id="lhomyChart"></canvas>
        </div>



















        <div class="col-md-12 grid-margin stretch-card">

            <div class="table-responsive" style="width: 100%;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>LHO</th>
                            <th>Allocated Sites</th>
                            <th>Material Dispatch</th>
                            <th>Delivered</th>
                            <th>In-transit</th>
                            <th colspan="2" style="text-align:center;">Todays</th>
                        </tr>
                        <tr>
                            <th></th> <!-- Empty cell for Sr No -->
                            <th></th> <!-- Empty cell for LHO -->
                            <th></th> <!-- Empty cell for Allocated Sites -->
                            <th></th> <!-- Empty cell for Send Material -->
                            <th></th> <!-- Empty cell for Delivered -->
                            <th></th> <!-- Empty cell for In-transit -->
                            <th>Feasibility</th>
                            <th>Installation</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?
                        $i = 1;
                        if ($assignedLho) {
                            $sql = mysqli_query($con, "select * from lho where status=1 and lho like '" . $assignedLho . "'");
                        }  else {
                            $sql = mysqli_query($con, "select * from lho where status=1");
                        }

                        $grandallocatedCount = 0 ;
                        while ($sql_result = mysqli_fetch_assoc($sql)) {

                            $lho = $sql_result['lho'];

if ($_SESSION['isVendor'] == 1) {
    
                            $allocatedCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS allocatedCount FROM sites WHERE LOWER(LHO) LIKE LOWER('%" . $lho . "%') AND status = 1 and delegatedToVendorId='".$_GLOBAL_VENDOR_ID."'"))['allocatedCount'];
                            
                            $sendMaterialCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a where  a.lho like '" . $lho . "' and a.portal='clarity' and a.vendorId='".$_GLOBAL_VENDOR_ID."'"))['count'];
                            $deliveredsendMaterialCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a where  a.lho like '" . $lho . "' and portal='clarity' and isDelivered=1 and a.vendorId='".$_GLOBAL_VENDOR_ID."'"))['count'];
                            $intrasitsendMaterialCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a where  a.lho like '" . $lho . "' and portal='clarity' and isDelivered=0 and a.vendorId='".$_GLOBAL_VENDOR_ID."'"))['count'];


                            $asdesdCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(CASE WHEN ESD = CURDATE() THEN 1 END) AS ESD_Count,
                COUNT(CASE WHEN ASD = CURDATE() THEN 1 END) AS ASD_Count FROM
                sites WHERE (ESD = CURDATE() OR ASD = CURDATE()) AND status=1 and LHO like '" . $lho . "' and delegatedToVendorId='".$_GLOBAL_VENDOR_ID."'"));


                            $ESD_Count = $asdesdCount['ESD_Count'];
                            $ASD_Count = $asdesdCount['ASD_Count'];
    
    
    
}else{
                            $allocatedCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS allocatedCount FROM sites WHERE LOWER(LHO) LIKE LOWER('%" . $lho . "%') AND status = 1"))['allocatedCount'];
                            
                            $sendMaterialCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a where  a.lho like '" . $lho . "' and portal='clarity'"))['count'];
                            $deliveredsendMaterialCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a where  a.lho like '" . $lho . "' and portal='clarity' and isDelivered=1"))['count'];
                            $intrasitsendMaterialCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(1) AS count FROM material_send a where  a.lho like '" . $lho . "' and portal='clarity' and isDelivered=0"))['count'];


                            $asdesdCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(CASE WHEN ESD = CURDATE() THEN 1 END) AS ESD_Count,
                COUNT(CASE WHEN ASD = CURDATE() THEN 1 END) AS ASD_Count FROM
                sites WHERE (ESD = CURDATE() OR ASD = CURDATE()) AND status=1 and LHO like '" . $lho . "'"));


                            $ESD_Count = $asdesdCount['ESD_Count'];
                            $ASD_Count = $asdesdCount['ASD_Count'];
                            
    
}
                            
                            
                            
                            
                            ?>
                            <tr>
                                <td>
                                    <?= $i; ?>
                                </td>
                                <td>
                                    <?= $lho; ?>
                                </td>
                                <td class="text-right">
                                    <?= $allocatedCount; ?>
                                </td>
                                <td class="text-right font-weight-medium">
                                    <?= $sendMaterialCount; ?>
                                </td>

                                <td class="text-right font-weight-medium">
                                    <?= $deliveredsendMaterialCount; ?>
                                </td>

                                <td class="text-right font-weight-medium">
                                    <?= $intrasitsendMaterialCount; ?>
                                </td>
                                <td>
                                    <?= $ESD_Count; ?>
                                </td>
                                <td>
                                    <?= $ASD_Count; ?>
                                </td>

                            </tr>
                            <?


$grandallocatedCount= $grandallocatedCount+$allocatedCount;

                            $data[] = array(
                                "LHO" => $lho,
                                "TotalAllocatedSites" => intval($allocatedCount),
                                "SendMaterial" => intval($sendMaterialCount),
                                "Delivered" => intval($deliveredsendMaterialCount),
                                "InTransit" => intval($intrasitsendMaterialCount),
                            );

                            $i++;
                        }
                        ?>
                         <!-- <tr>
                            <th colspan="2">Total</th> 
                            <th><?= $grandallocatedCount ; ?></th> 
                            <th></th> 
                            <th></th> 
                            <th></th> 
                            <th>Feasibility</th>
                            <th>Installation</th>
                        </tr> -->


                    </tbody>
                </table>
            </div>

        </div>



        <script>

            // Extract data from PHP and format it for Chart.js
            var chartData = <?php echo json_encode($data); ?>;

            // Extract LHO names and counts for chart datasets
            var lhoNames = chartData.map(function (item) {
                return item.LHO;
            });

            var allocatedSites = chartData.map(function (item) {
                return item.TotalAllocatedSites;
            });

            var sendMaterial = chartData.map(function (item) {
                return item.SendMaterial;
            });

            var delivered = chartData.map(function (item) {
                return item.Delivered;
            });

            var inTransit = chartData.map(function (item) {
                return item.InTransit;
            });

            // Create Chart.js chart
            var ctx = document.getElementById('lhomyChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: lhoNames,
                    datasets: [
                        {
                            label: 'Allocated Sites',
                            data: allocatedSites,
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Material Dispatch',
                            data: sendMaterial,
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Delivered',
                            data: delivered,
                            backgroundColor: 'rgba(255, 205, 86, 0.8)',
                            borderColor: 'rgba(255, 205, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'In Transit',
                            data: inTransit,
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
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
                                text: 'LHO',
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

    </div>
</div>