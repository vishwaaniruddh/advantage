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




require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

$hostusername = 'noc@advantagesb.com';
$hostPassword = 'Adv@3254#';
// $hostPassword = '4mPZJcl^X@XB';

$mail = new PHPMailer\PHPMailer\PHPMailer();
$emailServer = 'server1.advantagesb.com';

// $mail->SMTPDebug = 1;                                // Enable verbose debug output
$mail->isSMTP(); // Set mailer to use SMTP
// $emailServer;
$mail->Host = $emailServer; // Specify main and backup SMTP servers
// $mail->Host = 'webmail-b21.web-hosting.com'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true;
$mail->Username = $hostusername; // SMTP username
$mail->Password = $hostPassword; // SMTP password
$mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465; // Use port 587 for TLS



$to = $_REQUEST['to'];
foreach ($to as $valto) {
    $mail->addAddress($valto);
}

$cc = $_REQUEST['cc'];
foreach ($cc as $valcc) {
    $mail->addCC($valcc);
}

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

        $comp = 'Offline';
        $subcomp = 'Router Offline';
        $call_receive = 'Auto Email Call';
        $status = 'open';
        $remarks = 'Call Log';
        $created_by = '45'; // userid for system 

        $created_at = $datetime;


        $checkSql = mysqli_query($con, "select * from mis where atmid='" . $atmid . "' and status='open' order by id desc");

        if ($checkSqlResult = mysqli_fetch_assoc($checkSql)) {

            $misId = $checkSqlResult['id'];
            $misDetailsSql = mysqli_query($con, "select * from mis_details where mis_id = '" . $misId . "'");
            $misDetailsSqlResult = mysqli_fetch_assoc($misDetailsSql);

            mysqli_query($con, "insert into mis_history(mis_id,type,remark,created_at,created_by,lho) values('" . $misId . "','Mail Update','" . $message . "','" . $created_at . "','" . $created_by . "','" . $lho . "')");
            mysqli_query($con, "update mis set isRead='unread' where id='" . $misId . "'");

            $ticket_id = $misDetailsSqlResult['ticket_id'];
            $from = 'noc@advantagesb.com';
            $fromname = 'NOC Advantaesb Team';
            $subject = 'Docket Number ' . $ticket_id . ' ATM ID : ' . $atmid;
            $message = '
                    <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Auto Response Template</title>
                        </head>
                        <body>                        
                        <div>
                        Dear Sir/Madam,
                        <br>
                        
                        <p>
                        Ticket is already open for the subject ATM site (Site ID: ' . $atmid . '). Please note the docket no. ' . $ticket_id . ' for future reference.
                        </p>
                        <br><br>
                        
                            <p style="margin: 0;">Best Regards,</p>
                            <p style="margin: 0;">Team Railtel</p>
                            <p style="margin: 0;">Toll free No : 1800 2102 004</p>
                        <br><br>    
                        </div>
                        
                        </body>
                        </html>

                    ';



            $mail->addReplyTo('noc@advantagesb.com');
            $mail->setFrom($from, $fromname);
            $mail->From = trim($hostusername);
            $mail->FromName = $fromname;

            $mail->addCC('vishwaaniruddh@gmail.com');
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject . "\r\n";
            $mail->Body = $message;

            if ($mail->send()) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            $statement = "INSERT INTO mis(atmid, bank, customer, zone, city, state, location, call_receive_from, remarks, status, created_by, created_at, branch, bm, call_type, serviceExecutive,lho) 
            VALUES ('" . $atmid . "','" . $bank . "','" . $customer . "','" . $zone . "','" . $city . "','" . $state . "','" . $location . "','" . $call_receive . "','" . $remarks . "','open','" . $created_by . "','" . $created_at . "','" . $branch . "','" . $bm . "','Service','System','" . $lho . "')";
            if (mysqli_query($con, $statement)) {
                $mis_id = $con->insert_id;
                $last_sql = mysqli_query($con, "select id from mis_details order by id desc");
                $last_sql_result = mysqli_fetch_assoc($last_sql);
                $last = $last_sql_result['id'];
                if (!$last) {
                    $last = 0;
                }
                $ticket_id = mb_substr(date('M'), 0, 1) . date('Y') . date('m') . date('d') . sprintf('%04u', $last);

                mysqli_query($con, "insert into mis_history(mis_id,type,remark,created_at,created_by,lho) values('" . $misId . "','Mail Update','" . $message . "','" . $created_at . "','" . $created_by . "','" . $lho . "')");
                mysqli_query($con, "update mis set isRead='unread' where id='" . $misId . "'");


                $detai_statement = "insert into mis_details(mis_id,atmid,component,subcomponent,status,created_at,ticket_id,
                         mis_city,zone,call_type,case_type,branch) 
                         values('" . $mis_id . "','" . $atmid . "','" . $comp . "','" . $subcomp . "','" . $status . "','" . $created_at . "','" . $ticket_id . "',
                         '" . $city . "','" . $zone . "','Service','" . $call_receive . "','" . $branch . "')";
                if (mysqli_query($con, $detai_statement)) {

                    $from = 'noc@advantagesb.com';
                    $fromname = 'NOC Advantaesb Team';
                    $subject = 'Docket Number ' . $ticket_id . ' ATM ID : ' . $atmid;
                    $message = '
                            <!DOCTYPE html>
                                <html>
                                <head>
                                    <title>Auto Response Template</title>
                                </head>
                                <body>
                                
                                <div>
                                Dear Sir/Madam,
                                <br>
                                    <p>Thanks for your mail. We have noted the concern and will get back to you shortly. Refer the docket number ' . $ticket_id . ' for your future reference. </p>
                                <br><br>
                                
                                    <p style="margin: 0;">Best Regards,</p>
                                    <p style="margin: 0;">Team Railtel</p>
                                    <p style="margin: 0;">Toll free No : 1800 2102 004</p>
                                <br><br>    
                                </div>
                                
                                </body>
                                </html>

                            ';

                    $mail->addReplyTo('noc@advantagesb.com');
                    $mail->setFrom($from, $fromname);
                    $mail->From = trim($hostusername);
                    $mail->FromName = $fromname;
                    $mail->addCC('vishwaaniruddh@gmail.com');
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = $subject . "\r\n";
                    $mail->Body = $message;

                    if ($mail->send()) {
                        echo 1;
                        // $randomName = rand(132, 344323);
                        // mysqli_query($con, "insert into test(name,created_at) values('" . $randomName . "','" . $datetime . "')");
                    } else {
                        echo 0;
                    }
                }
            }
        }
    }
}
