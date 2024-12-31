<?php

require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

$hostusername = 'noc@advantagesb.com';
$hostPassword = '4mPZJcl^X@XB';
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP(); // Set mailer to use SMTP
$emailServer = 'server1.advantagesb.com';

$mail->Host = $emailServer; // Specify main and backup SMTP servers
$mail->SMTPAuth = true;
$mail->Username = $hostusername; // SMTP username
$mail->Password = $hostPassword; // SMTP password
$mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465; // Use port 587 for TLS



$to = explode(',', $toEmail);
foreach ($to as $valto) {
    $mail->addAddress($valto);
}

$cc = explode(',', $ccEmail);
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
        $created_by = '45'; // userid for system 

        $created_at = $datetime;


// echo "select * from mis where id='".$mis_id."'" ; 
        $checkSql = mysqli_query($con, "select * from mis where id='".$mis_id."'");

        if ($checkSqlResult = mysqli_fetch_assoc($checkSql)) {

            $misId = $checkSqlResult['id'];
            $remarks = $sql_result['remarks'];
            
            $message_id = $sql_result['message_id'];
            $reference_code = $checkSqlResult['reference_code'];


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
                <html lang="en">
                
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>New Ticket Created</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }
                
                        .container {
                            max-width: 600px;
                            margin: 20px auto;
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                
                        h1 {
                            color: #333;
                        }
                
                        p {
                            color: #555;
                            line-height: 1.6;
                        }
                
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            background-color: #007bff;
                            color: #fff;
                            text-decoration: none;
                            border-radius: 5px;
                        }
                
                        .footer {
                            margin-top: 20px;
                            color: #888;
                            font-size: 12px;
                        }
                    </style>
                </head>
                
                <body>
            <div class="container">
        <h1>New Ticket Created</h1>
        <p>Dear Sir/Madam,</p>
        <p>Thanks for your mail. We have noted the concern and will get back to you shortly. Refer the docket number ' . $ticket_id . ' for your future reference</p>

     
        <div class="footer">
            <p>Thank you,</p>
            <p>Clarify Helpdesk</p>
        </div>
    </div>
</body>
</html>
                    ';




            $mail->addCustomHeader('In-Reply-To', $message_id);
            $mail->addReplyTo('noc@advantagesb.com');
            $mail->setFrom($from, $fromname);
            $mail->From = trim($hostusername);
            $mail->FromName = $fromname;

            $mail->addCC('vishwaaniruddh@gmail.com');
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject . "\r\n";
            $mail->Body = $message;

            if ($mail->send()) {
                
                $messageId = $mail->getLastMessageID();


                mysqli_query($con,"update mis set message_id= '".$messageId."',fromEmail='".$from."' where id ='".$misId."'");
                $response['email_status'] = '1';
                // $randomName = rand(132, 344323);
                // mysqli_query($con, "insert into test(name,created_at) values('" . $randomName . "','" . $datetime . "')");
            } else {
                $response['email_status'] = '0';
                
            }
        } 
        
     









    }
}







?>