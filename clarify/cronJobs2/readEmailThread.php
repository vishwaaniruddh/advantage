<?php
include('config.php');

date_default_timezone_set("Asia/Calcutta");
error_reporting(E_ALL);

$username = 'noc@advantagesb.com';
$password = '4mPZJcl^X@XB';
// $emailServer = 'webmail-b21.web-hosting.com';
$emailServer = 'server1.advantagesb.com';


$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);

if ($inbox) {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {

            $header = imap_headerinfo($inbox, $email_number);

            $header2 = imap_fetchheader($inbox, $email_number);

            $structure = imap_fetchstructure($inbox, $email_number);

            preg_match('/References:\s*(.*?)\r?\n/i', $header2, $matchesReferences);
            preg_match('/In-Reply-To:\s*(.*?)\r?\n/i', $header2, $matchesInReplyTo);

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

            preg_match('/From:\s*"(.*?)".*?<([^>]+)>/i', $header2, $matchesFrom);
            preg_match('/To:\s*<(.*?)>/i', $header2, $matchesTo);
            preg_match('/Cc:\s*<(.*?)>/i', $header2, $matchesCc);

            $isReply = 0;
            $type = 'Mail Update';
            $remark = filter_var($message, FILTER_SANITIZE_STRING);

            $emailQuery = "INSERT INTO emails (subject, content_body, from_email, is_reply,message_id,`references`) 
            VALUES ('" . $header->subject . "', '" . $remark . "', '" . $header->fromaddress . "', 0,'" . $message_id . "' ,'".$references."')";

            if (mysqli_query($con, $emailQuery)) {
                $emailId = $con->insert_id;
                $attachmentFolder = createDirectoryStructure($emailId);

                foreach ($header->to as $recipient) {
                    $recipientQuery = "INSERT INTO recipients (email_id, recipient_type, recipient_email) 
                                       VALUES ('$emailId', 'To', '" . addslashes($recipient->mailbox . "@" . $recipient->host) . "')";
                    mysqli_query($con, $recipientQuery);
                }

                if (!empty($header->cc)) {
                    foreach ($header->cc as $ccRecipient) {
                        $ccRecipientQuery = "INSERT INTO recipients (email_id, recipient_type, recipient_email) 
                                             VALUES ('$emailId', 'Cc', '" . addslashes($ccRecipient->mailbox . "@" . $ccRecipient->host) . "')";
                        mysqli_query($con, $ccRecipientQuery);
                    }
                }
                if ($structure->parts) {
                    foreach ($structure->parts as $partNumber => $part) {
                        processAttachment($inbox, $email_number, $part, $emailId, $partNumber);
                    }
                }
            }
        }
    }
}

imap_close($inbox);



function createDirectoryStructure($emailId)
{
    $currentDate = date('Y/m/d');
    $directoryStructure = '../emailAttachments/' . $currentDate . '/' . $emailId . '/';
    if (!file_exists($directoryStructure)) {
        mkdir($directoryStructure, 0777, true);
    }
    return $directoryStructure;
}

function processAttachment($inbox, $email_number, $part, $emailId, $partNumber)
{
    global $attachmentFolder;
    $attachmentFileName = null;

    if ($part->ifdisposition && strtolower($part->disposition) == "attachment") {
        if ($part->ifdparameters && $part->dparameters[0]->attribute == 'filename') {
            $attachmentFileName = $part->dparameters[0]->value;
        } elseif ($part->ifparameters && $part->parameters[0]->attribute == 'name') {
            $attachmentFileName = $part->parameters[0]->value;
        }

        if ($attachmentFileName) {
            $attachmentContent = imap_fetchbody($inbox, $email_number, $partNumber + 1);
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

?>