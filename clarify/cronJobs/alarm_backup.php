<?php
$startTime = microtime(true);
ob_start();
echo 'Alarm Email';
date_default_timezone_set("Asia/Calcutta");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'alarms@advantagesb.com';
$password = 'Adv@1234#';
$emailServer = 'server1.advantagesb.com';
// $emailServer = 'webmail.advantagesb.com';
$nodes = '../generateAutoCallFromEmailReceived21.php';  // Use relative path

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);

if ($inbox) {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {
            processEmail($inbox, $email_number, $nodes);
        }
    }
}
imap_close($inbox);

function processEmail($inbox, $email_number, $nodes)
{
    $header = imap_fetchheader($inbox, $email_number);
    $message_id = getMessageID($header);
    $structure = imap_fetchstructure($inbox, $email_number);
    imap_setflag_full($inbox, $email_number, '\\Seen');

    preg_match('/From:\s*([^<]+)<([^>]+)>/i', $header, $from_matches);
    $from_name = isset($from_matches[1]) ? trim($from_matches[1]) : '';
    $from_email = isset($from_matches[2]) ? trim($from_matches[2]) : '';

    preg_match('/^To:\s*(.*?)\r?\n/m', $header, $matches_to);
    preg_match('/^Cc:\s*(.*?)\r?\n/m', $header, $matches_cc);

    $to_emails = isset($matches_to[1]) ? cleanEmailList($matches_to[1]) : '';
    $cc_emails = isset($matches_cc[1]) ? cleanEmailList($matches_cc[1]) : '';

    $message = imap_fetchbody($inbox, $email_number, 1);
    $message = quoted_printable_decode($message); // Decode quoted-printable encoding
    $overview = imap_fetch_overview($inbox, $email_number, 0);
    $subject = $overview[0]->subject;

    echo '<pre>';
    echo 'Email Body: ' . htmlspecialchars($message);
    echo '</pre>';

    if (strpos($subject, 'Device management platform alarm information') !== false) {
        $vpn = extractVPNCount($message);
        processHTMLContent($message, $nodes, $message_id, $from_email, $to_emails, $cc_emails, $subject, $vpn);
    }
}

function getMessageID($header)
{
    preg_match('/Message-ID:\s*<([^>]+)>/i', $header, $matches);
    return $matches[1] ?? null;
}

function cleanEmailList($email_list)
{
    $emails = preg_replace('/[^<\s]*<([^>]*)>/', '$1', $email_list);
    return implode(', ', explode(',', $emails));
}

function extractVPNCount($message)
{
    if (preg_match('/The VPN of (\d+) devices in the platform is offline/i', $message, $matches)) {
        return (int)$matches[1];
    }
    return 0;
}

function processHTMLContent($message, $nodes, $message_id, $from_email, $to_emails, $cc_emails, $subject, $vpn)
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($message, 'HTML-ENTITIES', 'UTF-8'));
    libxml_use_internal_errors(false);

    echo '<pre>';
    echo 'HTML Content: ' . htmlspecialchars($dom->saveHTML());
    echo '</pre>';

    $tables = $dom->getElementsByTagName('table');
    echo 'Number of tables: ' . $tables->length;

    if ($tables->length > 0) {
        $table = $tables->item(0);
        foreach ($table->getElementsByTagName('tr') as $row) {
            $cells = $row->getElementsByTagName('td');
            if ($cells->length >= 3) {
               $description = trim(html_entity_decode(strip_tags($cells->item(2)->nodeValue)));
              
              
              echo 'atmid = ' . $description = explode("/", $description)[0];
              echo '<br />';

              $data = [
                    'atmid' => $description,
                    'message' => $message,
                    'vpn' => $vpn,
                    'message_id' => $message_id,
                    'fromEmail' => $from_email,
                    'toEmail' => $to_emails,
                    'ccEmail' => $cc_emails,
                    'subject' => $subject,
                ];
                include($nodes); // Directly include the local file
            }
        }
    } else {
        echo 'NO_TABLE';
    }
}

$logFile = './AlarmLog.log';
$endTime = microtime(true);
$executionTime = $endTime - $startTime;
$output = ob_get_clean();
$currentDateTime = date('Y-m-d H:i:s');
$logMessage = "$currentDateTime: Script completed in $executionTime seconds. Output: $output";

file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
?>
