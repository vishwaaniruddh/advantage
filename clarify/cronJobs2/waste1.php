<?php
include ('config.php');
$startTime = microtime(true);
ob_start();

echo 'Noc Email';
// header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Calcutta"); // India time (GMT+5:30)
error_reporting(E_ALL); // Enable error reporting for debugging
set_time_limit(0);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$username = 'noc@advantagesb.com';
$password = '4mPZJcl^X@XB';
// $emailServer = 'webmail-b21.web-hosting.com';
$emailServer = 'server1.advantagesb.com';

$nodes = 'http://clarify.advantagesb.com/generateAutoCallFromEmailReceived.php';
$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);



function getHeaderValue($header, $headerName)
{
    preg_match('/' . $headerName . ':\s*<([^>]+)>/i', $header, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return null;
    }
}

function getMessageID($header)
{
    preg_match('/Message-ID:\s*<([^>]+)>/i', $header, $matches);
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

function getSenderEmail($sender)
{
    $matches = array();
    preg_match('/([^<]*)<([^>]*)>/', $sender, $matches);
    return isset($matches[2]) ? trim($matches[2]) : '';
}

function getRecipientsEmails($recipients)
{
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

function getCCRecipientsFromHeaders($emailHeaders)
{
    preg_match('/^Cc:\s*(.*)$/mi', $emailHeaders, $matches);
    return isset($matches[1]) ? trim($matches[1]) : '';
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


            echo 'encoding' . $encoding;
            if ($encoding == 3) { // Base64 encoding
                $attachmentContent = base64_decode($attachmentContent);
            } elseif ($encoding == 4) { // Quoted-printable encoding
                $attachmentContent = quoted_printable_decode($attachmentContent);
            }


            $attachmentFileName = $attachmentFolder . $attachmentFileName;

            if (file_put_contents($attachmentFileName, $attachmentContent) !== false) {
                global $con;
                echo $attachmentQuery = "INSERT INTO attachments (email_id, file_name, file_path) 
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

function createDirectoryStructure($emailId)
{
    $currentDate = date('Y/m/d');
    $directoryStructure = '../emailAttachments/' . $currentDate . '/' . $emailId . '/';
    if (!file_exists($directoryStructure)) {
        mkdir($directoryStructure, 0777, true);
    }
    return $directoryStructure;
}



if ($inbox) {
    $unseenMessages = imap_search($inbox, 'UNSEEN');
    if ($unseenMessages) {



        foreach ($unseenMessages as $messageNumber) {
            $header = imap_fetchheader($inbox, $messageNumber);
            $headerInfo = imap_headerinfo($inbox, $messageNumber);
            $subject = $headerInfo->subject;


            // Check if the email is a reply
            $isReply = '';
            if (isset($headerInfo->in_reply_to)) {
                $isReply = true;
            } else {
                $isReply = false;
            }


            $message_id = getHeaderValue($header, 'Message-ID');
            $references = getHeaderValue($header, 'References');
            $in_reply_to = getHeaderValue($header, 'In-Reply-To');

            $emailContent = $emailBody = imap_fetchbody($inbox, $messageNumber, 1);



            // Assuming the body is in MIME type 1 (text/plain)
            $atmID = extractValue($emailBody, 'ATM ID');

            // Get sender
            $sender = $headerInfo->from[0]->mailbox . "@" . $headerInfo->from[0]->host;
            // echo "Sender: " . $sender . "<br>";


            // echo '/n';
            // Get recipients (To)
            $recipientsToArr = array();
            foreach ($headerInfo->to as $to) {
                $recipientsToArr[] = $to->mailbox . "@" . $to->host;
            }
            $recipientsToArr[] = $sender;
            $recipientsToStr = implode(", ", $recipientsToArr);

            // Get recipients (CC)
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

            // Attachments
            $structure = imap_fetchstructure($inbox, $messageNumber);






            // return  ;
            if (isset($atmID) && !empty($atmID) && !$isReply) {

                echo 'Looking Fresh Call !';


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


                if ($structure->parts) {
                    foreach ($structure->parts as $partNumber => $part) {
                        processAttachment($inbox, $messageNumber, $part, $emailId, $partNumber);
                    }
                }







            } else {






                // $emailBody = strip_tags($emailBody);
                // $emailBody = quoted_printable_decode($emailBody);


                $startPos = strpos($emailContent, "Content-Transfer-Encoding: quoted-printable");

                if ($startPos !== false) {
                    // Find the position of the second occurrence
                    $secondPos = strpos($emailContent, "Content-Transfer-Encoding: quoted-printable", $startPos + strlen("Content-Transfer-Encoding: quoted-printable"));

                    if ($secondPos !== false) {
                        // Extract the email body from the position after the second occurrence
                        $emailBody = substr($emailContent, $secondPos);
                        $emailBody = $emailContent = str_replace('Content-Transfer-Encoding: quoted-printable', '', $emailBody);
                        // Output or process the email body
                        // echo $emailBody;
                    } else {
                        // If the second occurrence is not found, use the whole message
                        // echo $emailContent;
                    }
                } else {
                    // If the first occurrence is not found, use the whole message
                    // echo $emailContent;
                }


              




                echo 'Looking Thread';
                $check_sql = mysqli_query($con, "select * from mis where message_id='" . $references . "'");
                if ($check_sql_result = mysqli_fetch_assoc($check_sql)) {
                    $message_id = $references;
                    echo $misid = $check_sql_result['id'];
                    $prev_subject = 'RE: ' . $check_sql_result['subject'];
                }

                if ($prev_subject == $subject) {

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

                        if ($structure->parts) {
                            foreach ($structure->parts as $partNumber => $part) {
                                processAttachment($inbox, $messageNumber, $part, $emailId, $partNumber);
                            }
                        }
                    } else {
                        echo mysqli_error($con);
                    }


                }




            }









        }
    }
}

imap_close($inbox);




return;
$logFile = './NocLog.log';

// return ; 

$endTime = microtime(true);
$executionTime = $endTime - $startTime;
$output = ob_get_clean();
$currentDateTime = date('Y-m-d H:i:s');
// Construct the log message with date and time
$logMessage = "$currentDateTime: Script completed in $executionTime seconds. Output: $output";

// Append the log message to the log file
file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);