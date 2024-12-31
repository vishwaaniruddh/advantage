
    <div class="card">
        <div class="card-body">

            <h5>CALL INFORMATION</h5>
            <hr>
            <?php

            $sql = mysqli_query($con, "select * from mis_details  where mis_id= '" . $id . "'");
            $detailresult = mysqli_fetch_assoc($sql);

            $ticketid = $detailresult['ticket_id'];

            $mis_status = $detailresult['status'];
            $status_view = 0;
            if ($mis_status == 'material_in_process') {
                $status_view = 1;
            }


            $date = date('Y-m-d H:i:s');
            $date1 = date('Y-m-d');
            $date1 = date_create($date1);
            $date2 = date_create($sql_result['created_at']);
            $diff = date_diff($date1, $date2);



            ?>
            <div class="view-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="general-info">
                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Ticket ID </th>
                                                    <td>
                                                        <?php echo $ticketid; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">ATMID</th>
                                                    <td>
                                                        <?php echo $atmid; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Component</th>
                                                    <td>
                                                        <?php echo $detailresult['component']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Sub Component</th>
                                                    <td>
                                                        <?php echo $detailresult['subcomponent']; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Created On</th>
                                                    <td>
                                                        <span>
                                                            <?php echo $sql_result['created_at']; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <th scope="row">Created By</th>
                                                    <td>
                                                        <?php echo $sql_result['serviceExecutive']; ?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row">Down Time </th>
                                                    <td>
                                                        <?php echo $diff->format("%a days"); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Current Status</th>
                                                    <td>
                                                        <?php echo $sql_result['status']; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <br/>