<?php
$startTime = microtime(true);
ob_start();
echo 'Alarm Email';
date_default_timezone_set("Asia/Calcutta"); // India time (GMT+5:30)
// error_reporting(E_ALL); // Enable error reporting for debugging
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

phpinfo();


$username = 'alarms@advantagesb.com';
$password = 'Adv@1234#';
// $emailServer = 'webmail.advantagesb.com';
$emailServer = 'server1.advantagesb.com';

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);

$nodes = 'http://clarify.advantagesb.com/generateAutoCallFromEmailReceived21.php';

if ($inbox) {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {
            $header = imap_fetchheader($inbox, $email_number);
          
            var_dump($header);
        }
    }
}
imap_close($inbox);
function getMessageID($header){
    preg_match('/Message-ID:\s*<([^>]+)>/i', $header, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return null;
    }
}

$logFile = './AlarmLog.log';

$endTime = microtime(true);
$executionTime = $endTime - $startTime;
$output = ob_get_clean();
$currentDateTime = date('Y-m-d H:i:s');
// Construct the log message with date and time
$logMessage = "$currentDateTime: Script completed in $executionTime seconds. Output: $output";
// Append the log message to the log file
file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);