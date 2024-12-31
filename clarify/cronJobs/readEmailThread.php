<?php
return ; 
$startTime = microtime(true);

ob_start();

echo 'Thread Email';

include ('config.php');

date_default_timezone_set("Asia/Calcutta");
error_reporting(E_ALL);
set_time_limit(0);

$username = 'noc@advantagesb.com';
$password = '4mPZJcl^X@XB';
$emailServer = 'server1.advantagesb.com';
// $emailServer = 'webmail-b21.web-hosting.com';

function getHeaderValue($header, $headerName){
    preg_match('/' . $headerName . ':\s*<([^>]+)>/i', $header, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return null;
    }
}



function getSenderEmail($sender){
    $matches = array();
    preg_match('/([^<]*)<([^>]*)>/', $sender, $matches);
    return isset($matches[2]) ? trim($matches[2]) : '';
}

function getRecipientsEmails($recipients){
    $emails = array();
    if (is_array($recipients) || is_object($recipients)) {
        foreach ($recipients as $recipient) {
            if (is_array($recipient) || is_object($recipient)) {
                $emails[] = $recipient->mailbox . '@' . $recipient->host;
            }
        }
    }
    return $emails;
}


function processAttachment($inbox, $messageNumber, $part, $emailId, $partNumber){
    global $attachmentFolder;
    $attachmentFileName = null;

    if ($part->ifdisposition && strtolower($part->disposition) == "attachment") {
        if ($part->ifdparameters && $part->dparameters[0]->attribute == 'filename') {
            $attachmentFileName = $part->dparameters[0]->value;
        } elseif ($part->ifparameters && $part->parameters[0]->attribute == 'name') {
            $attachmentFileName = $part->parameters[0]->value;
        }

        if ($attachmentFileName) {
            $attachmentContent = imap_fetchbody($inbox, $messageNumber, $partNumber + 1);
            $encoding = $part->encoding;

            if ($encoding == 3) { // Base64 encoding
                $attachmentContent = base64_decode($attachmentContent);
            } elseif ($encoding == 4) { // Quoted-printable encoding
                $attachmentContent = quoted_printable_decode($attachmentContent);
            }


            $attachmentFileName = $attachmentFolder . $attachmentFileName;

            if (file_put_contents($attachmentFileName, $attachmentContent) !== false) {
                global $con;
                $attachmentQuery = "INSERT INTO attachments (email_id, file_name, file_path) 
                                       VALUES ('$emailId', '" . addslashes($part->dparameters[0]->value) . "', '" . addslashes($attachmentFileName) . "')";
                if (mysqli_query($con, $attachmentQuery)) {
                    echo 'Debug: Attachment saved to database' . "\n";
                } else {
                    echo 'Debug: Error inserting attachment information into the database: ' . mysqli_error($con) . "\n";
                }
            } else {
                echo 'Debug: Error saving attachment to file: ' . $attachmentFileName . "\n";
                echo 'Debug: ' . error_get_last()['message'] . "\n";
            }
        } else {
            echo 'Debug: Attachment filename is empty' . "\n";
        }
    }
}

function createDirectoryStructure($emailId){
    $currentDate = date('Y/m/d');
    $directoryStructure = '../emailAttachments/' . $currentDate . '/' . $emailId . '/';
    if (!file_exists($directoryStructure)) {
        mkdir($directoryStructure, 0777, true);
    }
    return $directoryStructure;
}


$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);




if ($inbox) {
    $unseenMessages = imap_search($inbox, 'UNSEEN');
    if ($unseenMessages) {

        foreach ($unseenMessages as $messageNumber) {
            $header = imap_fetchheader($inbox, $messageNumber);

            $header2 = imap_headerinfo($inbox, $messageNumber);
            $subject = $header2->subject;
            
            $emailBody = imap_fetchbody($inbox, $messageNumber, 1);        


            $message_id = getHeaderValue($header, 'Message-ID');
            $references = getHeaderValue($header, 'References');
            $in_reply_to = getHeaderValue($header, 'In-Reply-To');





            $check_sql = mysqli_query($con, "select * from mis where message_id='" . $references . "'");
            if ($check_sql_result = mysqli_fetch_assoc($check_sql)) {
                $message_id = $references;
                echo $misid = $check_sql_result['id'];
            }

            $structure = imap_fetchstructure($inbox, $messageNumber);

            preg_match('/From:\s*([^<]+)<([^>]+)>/i', $header, $from_matches);
            $from_name = isset($from_matches[1]) ? trim($from_matches[1]) : '';
            $from_email = isset($from_matches[2]) ? trim($from_matches[2]) : '';

            preg_match('/^To:\s*(.*?)\r?\n/m', $header, $matches_to);
            preg_match('/^Cc:\s*(.*?)\r?\n/m', $header, $matches_cc);

            $to = isset($matches_to[1]) ? $matches_to[1] : '';
            $cc = isset($matches_cc[1]) ? $matches_cc[1] : '';

            $to_emails = preg_replace('/[^<\s]*<([^>]*)>/', '$1', $to);
            $cc_emails = preg_replace('/[^<\s]*<([^>]*)>/', '$1', $cc);

            $to_list = implode(', ', explode(',', $to_emails));
            $cc_list = implode(', ', explode(',', $cc_emails));

            $overview = imap_fetch_overview($inbox, $messageNumber);
            $emailHeaders = imap_fetchheader($inbox, $messageNumber);

            $headerInfo = imap_rfc822_parse_headers($emailHeaders);

            $toRecipients = isset($headerInfo->to) ? $headerInfo->to : [];
            $toEmails = getRecipientsEmails($toRecipients);

            $fromaddress = isset($headerInfo->fromaddress) ? $headerInfo->fromaddress : [];
            $ccRecipients = isset($headerInfo->cc) ? $headerInfo->cc : [];

            $ccEmails[] = '';
            if (is_array($ccRecipients) || is_object($ccRecipients)) {
                foreach ($ccRecipients as $ccValue) {
                    if (is_array($ccValue) || is_object($ccValue)) {
                        $ccEmails[] = $ccValue->mailbox . '@' . $ccValue->host;
                    }
                }
            }


            $sender = $overview[0]->from;
            $senderEmail = getSenderEmail($sender);

            $toEmails[] = $senderEmail;
            $emailToRemove = "noc@advantagesb.com";

            foreach ($toEmails as $key => $email) {
                if ($email === $emailToRemove) {
                    unset($toEmails[$key]);
                }
            }


            $to = $senderEmail;
            $data = array(
                'atmid' => $atmID,
                'to' => $toEmails,
                'cc' => ($ccEmails ? $ccEmails : ''),
                'message' => $emailBody,
                'message_id' => $message_id,
                'fromEmail' => $from_email,
                'toEmail' => $to_list,
                'ccEmail' => $cc_list,
                'subject' => $subject

            );

            $toEmails = implode(',', $toEmails);
            $isReply = 1;



$emailQuery = "INSERT INTO emails (subject, content_body, from_email, is_reply, message_id, `references`, created_at, mis_id) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($con, $emailQuery);

if (is_array($toEmails)) {
    $fromEmail = implode(',', $toEmails);
} else {
    $fromEmail = $toEmails; 
}


mysqli_stmt_bind_param($stmt, 'sssisssi', $subject, $emailBody, $fromEmail, $isReply, $messageId, $references, $createdAt, $misId);
$isReply = 1; 
$messageId = $message_id;
$createdAt = $datetime; 
$misId = $misid; 


            if (mysqli_stmt_execute($stmt)) {
                $emailId = mysqli_insert_id($con);
                $attachmentFolder = createDirectoryStructure($emailId);

                foreach ($header2->to as $recipient) {
                    $recipientQuery = "INSERT INTO recipients (email_id, recipient_type, recipient_email) 
                                       VALUES ('$emailId', 'To', '" . addslashes($recipient->mailbox . "@" . $recipient->host) . "')";
                    mysqli_query($con, $recipientQuery);
                }

                if (!empty($header->cc)) {
                    foreach ($header2->cc as $ccRecipient) {
                        $ccRecipientQuery = "INSERT INTO recipients (email_id, recipient_type, recipient_email) 
                                             VALUES ('$emailId', 'Cc', '" . addslashes($ccRecipient->mailbox . "@" . $ccRecipient->host) . "')";
                        mysqli_query($con, $ccRecipientQuery);
                    }
                }



                if ($structure->parts) {
                    foreach ($structure->parts as $partNumber => $part) {
                        processAttachment($inbox, $messageNumber, $part, $emailId, $partNumber);
                    }
                }
            }else{
                echo mysqli_error($con);
            }









        }


    }
}






imap_close($inbox);


$logFile = './EmailThreadLog.log';
$endTime = microtime(true);
$executionTime = $endTime - $startTime;
$output = ob_get_clean();
$currentDateTime = date('Y-m-d H:i:s');
$logMessage = "$currentDateTime: Script completed in $executionTime seconds. Output: $output";

file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
?>