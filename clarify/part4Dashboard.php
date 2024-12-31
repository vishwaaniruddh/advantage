  <div class="col-sm-12" style="display:none;">
            <div class="card">
                <div class="card-header">
                    <h5>Call From Bank</h5>
                </div>

                <div class="card-block" style="overflow:auto;">

                    <?php

                    $agingCount = 1;
                    $highagingsql = mysqli_query($con, "select a.created_at,a.atmid,a.lho,a.state,a.city,a.location,b.ticket_id,b.id from mis a
        INNER JOIN mis_details b on a.id=b.mis_id
        where a.status='open' and a.call_receive_from='Customer / Bank' order by a.created_at asc limit 10 ");
                    if (mysqli_num_rows($highagingsql) > 0) {

                        echo '
<table class="table table-hover table-styling table-xs">
    <thead>
        <tr class="table-primary">
            <th>Sr No</th>
            <th>ATMID</th>
            <th>Ticket ID</th>
            <th>Created At</th>
            <th>Aging</th>
            <th>LHO</th>
            <th>City</th>
            <th>State</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
';

                        while ($highagingsql_result = mysqli_fetch_assoc($highagingsql)) {


                            $createdAtTimestamp = strtotime($highagingsql_result['created_at']);
                            $currentTime = time();
                            $agingInSeconds = $currentTime - $createdAtTimestamp;

                            $days = floor($agingInSeconds / (60 * 60 * 24));
                            $hours = floor(($agingInSeconds % (60 * 60 * 24)) / (60 * 60));
                            $minutes = floor(($agingInSeconds % (60 * 60)) / 60);
                            $seconds = $agingInSeconds % 60;

                            $agingFormatted = sprintf("%dd %02dh %02dm %02ds", $days, $hours, $minutes, $seconds);



                            $ticket_id = $highagingsql_result['ticket_id'];
                            $detail_id = $highagingsql_result['id'];


                            echo "<tr>
        <td>{$agingCount}</td>
        <td  class='strong'>{$highagingsql_result['atmid']}</td><td>";

                            if ($SERVICE_LEVEL != 5) {
                                echo "<a href=\"mis/viewticket.php?ticket={$referece_code}\">{$ticket_id}</a>";
                            } else {
                                echo "<a href=\"#\">{$ticket_id}</a>";
                            }


                            echo "</td><td>{$highagingsql_result['created_at']}</td>
        <td>{$agingFormatted}</td>
        <td>{$highagingsql_result['lho']}</td>
        <td>{$highagingsql_result['city']}</td>
        <td>{$highagingsql_result['state']}</td>
        <td>{$highagingsql_result['location']}</td>
    </tr>";


                            $agingCount++;
                        }
                        echo '</tbody>
    </table>';


                    } else {

                        echo '
                                                
    <div class="noRecordsContainer">
        <img src="assets/notFound.png">
    </div>';

                    }
                    ?>



                </div>
            </div>

        </div>