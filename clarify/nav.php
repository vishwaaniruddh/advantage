<?
if ($_SESSION['ADVANTAGE_username']) {


    $id = $_SESSION['ADVANTAGE_userid'];


    $user = "select * from user where userid=" . $id;
    $usersql = mysqli_query($con, $user);
    $usersql_result = mysqli_fetch_assoc($usersql);

    $level = $usersql_result['level'];
    $permission = $usersql_result['servicePermission'];
    $permission = explode(',', $permission);
    sort($permission);

    $cpermission = json_encode($permission);
    $cpermission = str_replace(array('[', ']', '"'), '', $cpermission);
    $cpermission = explode(',', $cpermission);
    $cpermission = "'" . implode("', '", $cpermission) . "'";
    $mainmenu = [];
    foreach ($permission as $key => $val) {
        $sub_menu_sql = mysqli_query($con, "select * from sub_menu where id='" . $val . "' and isService=1");

        if (mysqli_num_rows($sub_menu_sql) > 0) {
            $sub_menu_sql_result = mysqli_fetch_assoc($sub_menu_sql);
            $mainmenu[] = $sub_menu_sql_result['main_menu'];
        }
    }
    $mainmenu = array_unique($mainmenu);
    sort($mainmenu);






    ?>

    <nav class="sidebar sidebar-offcanvas" id="sidebar" >
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
            <a class="sidebar-brand brand-logo" href="<?php echo $base_url ; ?>" style="color:white;">
                <img src="http://clarify.advantagesb.com/assets/railtellogo.jpg" alt="logo" />


            </a>
            <a class="sidebar-brand brand-logo-mini" href="<?php echo $base_url ; ?>"><img
                    src="<?= $base_url ; ?>/assets/images/railtellogo.png" alt="logo"></a>
        </div>


        <ul class="nav">
            <li class="nav-item menu-items">
                <a class="nav-link" href="#">
                    <span class="menu-title" id="clock" class="clock"></span>
                </a>
            </li>
            <li class="nav-item nav-category">
                <span class="nav-link">Navigation</span>
            </li>


            <li class="nav-item menu-items ">
                <a class="nav-link" href="<?= $base_url; ?>">
                    <span class="menu-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>



            <li class="nav-item menu-items ">
                <a class="nav-link" href="<?= 'http://clarify.advantagesb.com/mis/add_mis.php' ; ?>">
                    <span class="menu-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </span>
                    <span class="menu-title">New Ticket</span>
                </a>
            </li>


            <li class="nav-item menu-items ">
                
                <a class="nav-link" href="<?= 'http://clarify.advantagesb.com/mis/view_mis.php?atmid=&fromdt=2023-01-01&todt=&call_receive_from=&status%5B%5D=open&status%5B%5D=close&status%5B%5D=schedule&status%5B%5D=material_requirement&status%5B%5D=reassign&status%5B%5D=Mail+Update&submit=Filter' ; ?>">

                
                    <span class="menu-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </span>
                    <span class="menu-title">View All Tickets</span>
                </a>
            </li>


            
            <li class="nav-item menu-items ">
                <a class="nav-link" href="<?= 'http://clarify.advantagesb.com/mis/view_mis.php?atmid=&fromdt=2023-01-01&todt=' ; ?>">
                    <span class="menu-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </span>
                    <span class="menu-title">Open Tickets</span>
                </a>
            </li>

            <li class="nav-item menu-items ">
                <a class="nav-link" href="<?= 'http://clarify.advantagesb.com/mis/view_mis.php?atmid=&fromdt=2023-01-01&todt=&call_receive_from=&status%5B%5D=close&submit=Filter&exportSql=select+a.remarks%2Ca.reference_code%2Ca.id%2Ca.bank%2Ca.customer%2Ca.location%2Ca.zone%2Ca.state%2Ca.city%2Ca.branch%2Ca.created_by%2Ca.bm%2Cb.id%2Cb.mis_id%2Cb.atmid%2C%0D%0A++++++++++++++++b.component%2Cb.subcomponent%2Cb.engineer%2Cb.docket_no%2Cb.status%2Cb.created_at%2Cb.ticket_id%2Cb.close_date%2Cb.call_type%2Cb.case_type+%2C++++++%0D%0A++++++++++++++++%28SELECT+name+from+vendorUsers+WHERE+id%3D+a.created_by%29+AS+createdBy%0D%0A++++++++++++++++from+mis+a+INNER+JOIN+mis_details+b+ON+b.mis_id+%3D+a.id+%0D%0A++++++++++++++++where+1+and+%0D%0A++++++++++++++++b.mis_id+%3D+a.id+%0D%0A+++++++++++++++++and+CAST%28b.created_at+AS+DATE%29+%3E%3D+%272023-01-01%27+and+CAST%28b.created_at+AS+DATE%29+%3C%3D+%272024-03-30%27+and+b.status+in+%28%27open%27%2C+%27schedule%27%2C+%27material_requirement%27%2C+%27material_dispatch%27%2C+%27permission_require%27%2C+%27material_delivered%27%2C+%27MRS%27%2C+%27cancelled%27%2C+%27available%27%2C+%27not_available%27%2C+%27fund_required%27%2C+%27reassign%27%2C+%27Mail+Update%27%29++order+by+b.id+desc' ; ?>">
                    <span class="menu-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </span>
                    <span class="menu-title">Close Tickets</span>
                </a>
            </li>


            <li class="nav-item menu-items ">
                <a class="nav-link" href="<?= 'http://clarify.advantagesb.com/mis/view_mis.php?atmid=&fromdt=2023-01-01&todt=&call_receive_from=Customer+%2F+Bank&status%5B%5D=open&status%5B%5D=schedule&status%5B%5D=material_requirement&status%5B%5D=material_dispatch&status%5B%5D=permission_require&status%5B%5D=material_delivered&status%5B%5D=MRS&status%5B%5D=cancelled&status%5B%5D=available&status%5B%5D=not_available&status%5B%5D=material_in_process&status%5B%5D=fund_required&status%5B%5D=reassign&status%5B%5D=Mail+Update&submit=Filter&exportSql=select+a.remarks%2Ca.reference_code%2Ca.id%2Ca.bank%2Ca.customer%2Ca.location%2Ca.zone%2Ca.state%2Ca.city%2Ca.branch%2Ca.created_by%2Ca.bm%2Cb.id%2Cb.mis_id%2Cb.atmid%2C%0D%0A++++++++++++++++b.component%2Cb.subcomponent%2Cb.engineer%2Cb.docket_no%2Cb.status%2Cb.created_at%2Cb.ticket_id%2Cb.close_date%2Cb.call_type%2Cb.case_type+%2C++++++%0D%0A++++++++++++++++%28SELECT+name+from+vendorUsers+WHERE+id%3D+a.created_by%29+AS+createdBy%0D%0A++++++++++++++++from+mis+a+INNER+JOIN+mis_details+b+ON+b.mis_id+%3D+a.id+%0D%0A++++++++++++++++where+1+and+%0D%0A++++++++++++++++b.mis_id+%3D+a.id+%0D%0A+++++++++++++++++and+a.call_receive_from+%3D+%27Customer+%2F+Bank%27+and+CAST%28b.created_at+AS+DATE%29+%3E%3D+%272023-01-01%27+and+CAST%28b.created_at+AS+DATE%29+%3C%3D+%272024-03-31%27+and+b.status+in+%28%27open%27%2C+%27schedule%27%2C+%27material_requirement%27%2C+%27material_dispatch%27%2C+%27permission_require%27%2C+%27material_delivered%27%2C+%27MRS%27%2C+%27cancelled%27%2C+%27available%27%2C+%27not_available%27%2C+%27material_in_process%27%2C+%27fund_required%27%2C+%27reassign%27%2C+%27Mail+Update%27%29++order+by+b.id+desc' ; ?>">
                    <span class="menu-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </span>
                    <span class="menu-title">Bank Dependency</span>
                </a>
            </li>








            <li class="nav-item menu-items">
                <a class="nav-link" href="<?= $base_url; ?>/logout.php">
                    <span class="menu-icon">
                        <i class="mdi mdi-playlist-play"></i>
                    </span>
                    <span class="menu-title">Logout</span>
                </a>
            </li>


        </ul>
    </nav>

<? } ?>

<script>
    function updateClock() {
        var now = new Date();
        var date = now.toDateString();
        var time = now.toLocaleTimeString();

        var clockElement = document.getElementById('clock');
        clockElement.textContent = date + ' ' + time;
    }

    // Update the clock every second
    setInterval(updateClock, 1000);

    // Initial call to display the clock immediately
    updateClock();
</script>