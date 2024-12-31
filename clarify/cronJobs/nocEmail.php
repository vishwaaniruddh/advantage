<?php
include('config.php');

$startTime = microtime(true);
ob_start();

date_default_timezone_set("Asia/Calcutta"); // India time (GMT+5:30)
error_reporting(E_ALL); // Enable error reporting for debugging
set_time_limit(0);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$username = 'noc2@advantagesb.com';
$password = 'AVav@@2024';
$emailServer = 'webmail.advantagesb.com';
$nodes = 'http://clarify.advantagesb.com/generateAutoCallFromEmailReceived.php';

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);

if ($inbox) {
    $unseenMessages = imap_search($inbox, 'UNSEEN');
    if ($unseenMessages) {
        $mycount = 1;
        foreach ($unseenMessages as $messageNumber) {
            if ($mycount < 10) {
                $header = imap_fetchheader($inbox, $messageNumber);
                $headerInfo = imap_headerinfo($inbox, $messageNumber);
                $subject = $headerInfo->subject;
                imap_setflag_full($inbox, $messageNumber, '\\Seen');

                $isReply = isset($headerInfo->in_reply_to) ? true : false;

                $message_id = getHeaderValue($header, 'Message-ID');
                $references = getHeaderValue($header, 'References');
                $in_reply_to = getHeaderValue($header, 'In-Reply-To');

                $emailContent = $emailBody = imap_fetchbody($inbox, $messageNumber, '1', FT_PEEK);
                $atmID = extractValue($emailBody, 'ATM ID');

                $sender = $headerInfo->from[0]->mailbox . "@" . $headerInfo->from[0]->host;

                $recipientsToArr = array();
                foreach ($headerInfo->to as $to) {
                    $recipientsToArr[] = $to->mailbox . "@" . $to->host;
                }
                $recipientsToArr[] = $sender;
                $recipientsToStr = implode(", ", $recipientsToArr);

                $recipientsCCArr = array();
                if (isset($headerInfo->cc)) {
                    foreach ($headerInfo->cc as $cc) {
                        $recipientsCCArr[] = $cc->mailbox . "@" . $cc->host;
                    }
                }
                $recipientsCCStr = implode(", ", $recipientsCCArr);

                $recipientsBCCArr = array();
                if (isset($headerInfo->bcc)) {
                    foreach ($headerInfo->bcc as $bcc) {
                        $recipientsBCCArr[] = $bcc->mailbox . "@" . $bcc->host;
                    }
                }
                $recipientsBCCStr = implode(", ", $recipientsBCCArr);

                $structure = imap_fetchstructure($inbox, $messageNumber);

                if (isset($atmID) && !empty($atmID)) {
                    echo 'Looking Fresh Call!';

                    $emailBody = strip_tags($emailBody);
                    $emailBody = quoted_printable_decode($emailBody);

                    $data = array(
                        'atmid' => $atmID,
                        'to' => $recipientsToArr,
                        'cc' => ($recipientsCCArr ? $recipientsCCArr : ''),
                        'message' => $emailBody,
                        'message_id' => $message_id,
                        'fromEmail' => $sender,
                        'toEmail' => $recipientsToStr,
                        'ccEmail' => $recipientsCCStr,
                        'subject' => $subject,
                        'references' => ($references ? $references : ''),
                        'in_reply_to' => ($in_reply_to ? $in_reply_to : '')
                    );

                    echo json_encode($data);

                    $options = array(
                        'http' => array(
                            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method' => 'POST',
                            'content' => http_build_query($data)
                        )
                    );

                    $context = stream_context_create($options);
                    $result = file_get_contents($nodes, false, $context);
                    print_r($result);
                    $data = json_decode($result, true);

                    $emailId = $data['emailId'];

                    $attachmentFolder = createDirectoryStructure($emailId);

                    if (isset($structure->parts) && is_array($structure->parts)) {
                        foreach ($structure->parts as $partNumber => $part) {
                            processAttachment($inbox, $messageNumber, $part, $emailId, $partNumber);
                        }
                    }
                } else {
                    $startPos = strpos($emailContent, "Content-Transfer-Encoding: quoted-printable");
                    if ($startPos !== false) {
                        $secondPos = strpos($emailContent, "Content-Transfer-Encoding: quoted-printable", $startPos + strlen("Content-Transfer-Encoding: quoted-printable"));

                        if ($secondPos !== false) {
                            $emailBody = substr($emailContent, $secondPos);
                        }
                    }

                    $emailBody = str_replace('Content-Transfer-Encoding: quoted-printable', '', quoted_printable_decode($emailBody));

                    echo 'Looking Thread';
                    $references = ($references ? $references : $message_id);
                    $check_sql = mysqli_query($con, "SELECT * FROM mis WHERE message_id='" . $references . "'");
                    $check_sql_result = mysqli_fetch_assoc($check_sql);
                    $message_id = $references;
                    $misid = $check_sql_result['id'];
                    $stroredSubject = $check_sql_result['subject'];

                    $isReply = 1;
                    $emailQuery = "INSERT INTO emails (subject, content_body, from_email, is_reply, message_id, `references`, created_at, mis_id) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($con, $emailQuery);

                    mysqli_stmt_bind_param($stmt, 'sssisssi', $subject, $emailBody, $sender, $isReply, $messageId, $references, $createdAt, $misId);
                    $isReply = 1;
                    $messageId = $message_id;
                    $createdAt = $datetime;
                    $misId = $misid;

                    if (mysqli_stmt_execute($stmt)) {
                        $emailId = mysqli_insert_id($con);
                        $attachmentFolder = createDirectoryStructure($emailId);

                        foreach ($headerInfo->to as $to) {
                            $recipientsTo = $to->mailbox . "@" . $to->host;
                            $recipientQuery = "INSERT INTO recipients (email_id, recipient_type, recipient_email) 
                                    VALUES ('$emailId', 'To', '" . $recipientsTo . "')";
                            mysqli_query($con, $recipientQuery);
                        }

                        if (isset($headerInfo->cc)) {
                            foreach ($headerInfo->cc as $cc) {
                                $recipientsCC = $cc->mailbox . "@" . $cc->host;
                                $ccRecipientQuery = "INSERT INTO recipients (email_id, recipient_type, recipient_email) 
                                                             VALUES ('$emailId', 'Cc', '" . $recipientsCC . "')";
                                mysqli_query($con, $ccRecipientQuery);
                            }
                        }

                        if (isset($structure->parts) && is_array($structure->parts)) {
                            foreach ($structure->parts as $partNumber => $part) {
                                processAttachment($inbox, $messageNumber, $part, $emailId, $partNumber);
                            }
                        }
                    } else {
                        echo mysqli_error($con);
                    }
                }
                $mycount++;
            }
        }
    }
}
imap_close($inbox);

function getHeaderValue($header, $headerName)
{
    preg_match('/' . $headerName . ':\s*<([^>]+)>/i', $header, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return null;
    }
}

function extractValue($emailBody, $label)
{
    if (preg_match("/$label\s+(.+)/i", $emailBody, $matches)) {
        return trim($matches[1]);
    } else {
        return '';
    }
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

            $attachmentFileName = $attachmentFolder . '/' . $attachmentFileName;
            file_put_contents($attachmentFileName, $attachmentContent);

            // Insert attachment information into the database
            global $con;
            $attachmentQuery = "INSERT INTO attachments (email_id, file_path, file_name) VALUES ('$emailId', '$attachmentFileName', '$attachmentFileName')";
            mysqli_query($con, $attachmentQuery);
        }
    }

    if (isset($part->parts) && is_array($part->parts)) {
        foreach ($part->parts as $subPartNumber => $subPart) {
            processAttachment($inbox, $email_number, $subPart, $emailId, $partNumber . '.' . ($subPartNumber + 1));
        }
    }
}

function createDirectoryStructure($emailId)
{
    $attachmentFolder = 'attachments/' . $emailId;
    if (!is_dir($attachmentFolder)) {
        mkdir($attachmentFolder, 0777, true);
    }
    return $attachmentFolder;
}
?>
