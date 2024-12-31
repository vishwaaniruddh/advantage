<? include('config.php');

$atmid = $_REQUEST['atmid'];
$siteAddress = $_REQUEST['siteAddress'];
$city = $_REQUEST['city'];
$circle = $_REQUEST['circle'];
$linkVendor = $_REQUEST['linkVendor'];
$atmIP = $_REQUEST['atmIP'];
$message = $_REQUEST['message'];

$message = quoted_printable_decode($message);
$message = str_replace('<br>', '', $message);



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($atmid) {
    $sql = mysqli_query($con, "select * from sites where atmid='" . trim($atmid) . "'");
    if ($sql_result = mysqli_fetch_assoc($sql)) {
        $customer = strtoupper($sql_result['customer']);
        $bank = $sql_result['bank'];
        $location = $sql_result['address'];
        $state = $sql_result['state'];
        $region = $sql_result['zone'];
        $bm = $sql_result['bm_name'];
        $branch = $sql_result['branch'];
        $city = $sql_result['city'];
        $eng_user_id = $sql_result['engineer_user_id'];
        $lho = $sql_result['LHO'];
        $engname = mysqli_query($con, "select name from mis_loginusers where id = '" . $eng_user_id . "' ");
        $engname_result = mysqli_fetch_assoc($engname);
        $_engname = $engname_result['name'];
        $to = $_REQUEST['to'];
        $vpn = $_REQUEST['vpn'];

        if($vpn==0){
            $comp = 'Offline';
            $subcomp = 'Router Offline';
        }else{
            $comp = 'VPN-down';
            $subcomp = 'VPN-down';
        }



        $call_receive = 'Auto Email Call';
        $status = 'open';
        $remarks = 'Call Log';
        $created_by = '45'; // userid for system 

        $created_at = $datetime;


        $checkSql = mysqli_query($con, "select * from mis where atmid='" . $atmid . "' and status <> 'close' order by id desc");

        if ($checkSqlResult = mysqli_fetch_assoc($checkSql)) {

            $misId = $checkSqlResult['id'];
            $misDetailsSql = mysqli_query($con, "select * from mis_details where mis_id = '" . $misId . "'");
            $misDetailsSqlResult = mysqli_fetch_assoc($misDetailsSql);

            $ticket_id = $misDetailsSqlResult['ticket_id'];
            mysqli_query($con, "insert into mis_history(mis_id,type,remark,created_at,created_by) values('" . $misId . "','Mail Update','" . $message . "','" . $created_at . "','" . $created_by . "')");
            mysqli_query($con, "update mis set isRead='unread' where id='" . $misId . "'");
        // echo "update mis set isRead='unread' where id='" . $misId . "'";
        } else {
            $statement = "INSERT INTO mis(atmid, bank, customer, zone, city, state, location, call_receive_from, remarks, status, created_by, created_at, branch, bm, call_type, serviceExecutive,lho) 
            VALUES ('" . $atmid . "','" . $bank . "','" . $customer . "','" . $zone . "','" . $city . "','" . $state . "','" . $location . "','" . $call_receive . "','" . $remarks . "','open','" . $created_by . "','" . $created_at . "','" . $branch . "','" . $bm . "','Service','System','" . $lho . "')";

            if (mysqli_query($con, $statement)) {
                $mis_id = $con->insert_id;
                $misId = $mis_id ; 
                $last_sql = mysqli_query($con, "select id from mis_details order by id desc");
                $last_sql_result = mysqli_fetch_assoc($last_sql);
                $last = $last_sql_result['id'];
                if (!$last) {
                    $last = 0;
                }
                $ticket_id = mb_substr(date('M'), 0, 1) . date('Y') . date('m') . date('d') . sprintf('%04u', $last);

                $detai_statement = "insert into mis_details(mis_id,atmid,component,subcomponent,status,created_at,ticket_id,
                         mis_city,zone,call_type,case_type,branch) 
                         values('" . $mis_id . "','" . $atmid . "','" . $comp . "','" . $subcomp . "','" . $status . "','" . $created_at . "','" . $ticket_id . "',
                         '" . $city . "','" . $zone . "','Service','" . $call_receive . "','" . $branch . "')";
                if (mysqli_query($con, $detai_statement)) {

                    mysqli_query($con, "insert into mis_history(mis_id,type,remark,created_at,created_by) values('" . $misId . "','Mail Update','" . $message . "','" . $created_at . "','" . $created_by . "')");

                    mysqli_query($con, "update mis set isRead='unread' where id='" . $misId . "'");

                }
            }
        }
    }
}
