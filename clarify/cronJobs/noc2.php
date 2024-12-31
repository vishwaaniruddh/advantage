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
$password = 'Adv@3254#';
$emailServer = 'webmail.advantagesb.com';
// $emailServer = 'server1.advantagesb.com';

$nodes = 'http://clarify.advantagesb.com/generateAutoCallFromEmailReceived.php';
$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);




if ($inbox) {
    $unseenMessages = imap_search($inbox, 'UNSEEN');
    if ($unseenMessages) {
        $mycount=1 ; 
        foreach ($unseenMessages as $messageNumber) {

            var_dump($messageNumber) ;
           
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





// return;
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

