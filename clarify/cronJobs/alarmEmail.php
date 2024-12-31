<?php
$startTime = microtime(true);
ob_start();
echo 'Alarm Email';
date_default_timezone_set("Asia/Calcutta"); // India time (GMT+5:30)
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'alarms@advantagesb.com';
$password = 'Adv@1234#';
$emailServer = 'server1.advantagesb.com';

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);
$nodes = 'http://clarify.advantagesb.com/generateAutoCallFromEmailReceived21.php';

if ($inbox) {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        $emailCount = 0; // Initialize a counter to limit the number of emails processed

        foreach ($emails as $email_number) {
            if ($emailCount >= 20) {
                break; // Stop processing after 20 emails
            }

            $header = imap_fetchheader($inbox, $email_number);
            $message_id = getMessageID($header);
            $structure = imap_fetchstructure($inbox, $email_number);
            imap_setflag_full($inbox, $email_number, '\\Seen');

            preg_match('/From:\s*([^<]+)<([^>]+)>/i', $header, $from_matches);
            $from_name = isset($from_matches[1]) ? trim($from_matches[1]) : '';
            $from_email = isset($from_matches[2]) ? trim($from_matches[2]) : '';

            preg_match('/^To:\s*(.*?)\r?\n/m', $header, $matches_to);
            preg_match('/^Cc:\s*(.*?)\r?\n/m', $header, $matches_cc);

            $to = isset($matches_to[1]) ? $matches_to[1] : '';
            $cc = isset($matches_cc[1]) ? $matches_cc[1] : '';

            $to_emails = preg_replace('/[^<\s]*<([^>]*)>/', '$1', $to);
            $cc_emails = preg_replace('/[^<\s]*<([^>]*)>/', '$1', $cc);

            echo '$to_list = ' . $to_list = implode(', ', explode(',', $to_emails));
            echo '<br />';
            echo '$cc_list = ' . $cc_list = implode(', ', explode(',', $cc_emails));

            $message = imap_fetchbody($inbox, $email_number, 1);
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            echo $subject = $overview[0]->subject;

            if (strpos($subject, 'Device management platform alarm information') !== false) {
                $matches = [];

                if (preg_match('/The VPN of (\d+) devices in the platform is offline/i', $message, $matches)) {
                    $vpn = (int) $matches[1];
                } else {
                    $vpn = 0;
                }

                $dom = new DOMDocument;
                libxml_use_internal_errors(true);
                $dom->loadHTML(mb_convert_encoding($message, 'HTML-ENTITIES', 'UTF-8'));
                libxml_use_internal_errors(false);
                $tables = $dom->getElementsByTagName('table');

                echo '<pre>';
                print_r($tables);
                echo '</pre>';

                $table = $tables->item(0);
                if ($table) {
                    foreach ($table->getElementsByTagName('tr') as $row) {
                        $cells = $row->getElementsByTagName('td');

                        if ($cells->length >= 3) {
                            $sn = trim(html_entity_decode(strip_tags($cells->item(0)->nodeValue)));
                            $deviceID = trim(html_entity_decode(strip_tags($cells->item(1)->nodeValue)));
                            $description = trim(html_entity_decode(strip_tags($cells->item(2)->nodeValue)));

                            $description = explode("/", $description);
                            $description = trim($description[0]);

                            $data = array(
                                'atmid' => $description,
                                'message' => $message,
                                'vpn' => $vpn,
                                'message_id' => $message_id,
                                'fromEmail' => $from_email,
                                'toEmail' => $to_list,
                                'ccEmail' => $cc_list,
                                'subject' => $subject,
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

                            var_dump($result);

                        } else {
                            echo 'NO_API_CALLING';
                        }
                    }
                } else {
                    echo 'NO_TABLE';
                }
            }

            $emailCount++; // Increment the counter after processing each email
        }
    }
}

imap_close($inbox);

function getMessageID($header)
{
    preg_match('/Message-ID:\s*<([^>]+)>/i', $header, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}

$logFile = './AlarmLog.log';

$endTime = microtime(true);
$executionTime = $endTime - $startTime;
$output = ob_get_clean();
$currentDateTime = date('Y-m-d H:i:s');
$logMessage = "$currentDateTime: Script completed in $executionTime seconds. Output: $output";

file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
?>
