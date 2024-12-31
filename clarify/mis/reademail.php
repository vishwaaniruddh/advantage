<?php
include('../config.php');

date_default_timezone_set("Asia/Calcutta");
error_reporting(E_ALL); 

$username = 'noc@advantagesb.com';
$password = '4mPZJcl^X@XB';
$emailServer = 'webmail-b21.web-hosting.com';

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);

if ($inbox) {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {
            // Fetch the full email header
            $header = imap_fetchheader($inbox, $email_number);

            // Extract References and In-Reply-To headers from the header
            preg_match('/References:\s*(.*?)\r?\n/i', $header, $matchesReferences);
            preg_match('/In-Reply-To:\s*(.*?)\r?\n/i', $header, $matchesInReplyTo);

            // Get the values of References and In-Reply-To headers
            $references = isset($matchesReferences[1]) ? trim($matchesReferences[1]) : '';
            $inReplyTo = isset($matchesInReplyTo[1]) ? trim($matchesInReplyTo[1]) : '';

            $check_sql = mysqli_query($con, "select * from mis where message_id='" . $references . "'");
            if ($check_sql_result = mysqli_fetch_assoc($check_sql)) {
                $message_id = $references;
            }
            if (empty($message_id) && $message_id == '') {
                $message_id = $inReplyTo;
            }

            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $subject = $overview[0]->subject;

            $message = imap_fetchbody($inbox, $email_number, 1);
          
            $type = 'Mail Update';
            $remark = filter_var($message, FILTER_SANITIZE_STRING);
            $sql = "insert into mis_history(message_id,type,remark,created_at) 
            values('" . $message_id . "','" . $type . "','" . $remark . "','" . $datetime . "')";
            mysqli_query($con, $sql);

        }
    }
}

imap_close($inbox);
?>