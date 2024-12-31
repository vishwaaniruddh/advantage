<?php
require_once './PHPMailer/src/PHPMailer.php';
require_once './PHPMailer/src/SMTP.php';
require_once './PHPMailer/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();

try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'mail.advantagesb.com';              // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'noc@advantagesb.com';                  // SMTP username
    $mail->Password   = 'Adv$2435%';                            // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    $mail->SMTPDebug = 2;

    //Recipients
    $mail->setFrom('noc@advantagesb.com', 'Mailer');
    $mail->addAddress('vishwaaniruddh@gmail.com', 'Aniruddh');  // Add a recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer SSL 465 mail.advantagesb.com.';

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>




<?php
return; 




$username = 'noc@advantagesb.com';
$password = 'Adv$2435%';
$emailServer = 'webmail.advantagesb.com';

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);




if ($inbox) {
    $unseenMessages = imap_search($inbox, 'UNSEEN');
    if ($unseenMessages) {
        $mycount=1 ; 
        foreach ($unseenMessages as $messageNumber) {

            if($mycount < 10){
                $header = imap_fetchheader($inbox, $messageNumber);

var_dump($header)
;
            }
        }
    }
}








return ; 




















var_dump();
include('config.php');
return ; 
$sql = mysqli_query($con,"select * from mis_history");
while($sql_result = mysqli_fetch_assoc($sql)){
    $id = $sql_result['id'];
    $mis_id = $sql_result['mis_id'];

    $missql = mysqli_query($con,"Select * from mis where id='".$mis_id."'");
    $missqlresult = mysqli_fetch_assoc($missql) ; 
    $lho = $missqlresult['lho'];

    
    mysqli_query($con,"update mis_history set lho ='".$lho."' where id='".$id."'");

    echo "update mis_history set lho ='".$lho."' where id='".$id."'" ; 
    echo '<br />';
}



return ;
$sql = mysqli_query($con,"select * from projectinstallation where lho=''");
while($sql_result = mysqli_fetch_assoc($sql)){
$id = $sql_result['id'];
    $atmid = $sql_result['atmid'];

    $sitessql = mysqli_query($con,"select * from sites where atmid ='".$atmid."'");
    $sitessql_result = mysqli_fetch_assoc($sitessql);
    $lho = $sitessql_result['LHO'];

    mysqli_query($con,"update projectinstallation set lho ='".$lho."' where id='".$id."'");

    echo "update projectinstallation set lho ='".$lho."' where id='".$id."'" ; 
    echo '<br />';
}

?>