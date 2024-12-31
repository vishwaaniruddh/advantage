<?php
date_default_timezone_set("Asia/Calcutta"); // India time (GMT+5:30)
// error_reporting(E_ALL); // Enable error reporting for debugging
set_time_limit(0);

$username = 'alarms@advantagesb.com';
$password = 'Adv@1234#';
// $emailServer = 'webmail-b21.web-hosting.com';
$emailServer = 'server1.advantagesb.com';

$inbox = imap_open("{{$emailServer}:993/imap/ssl}INBOX", $username, $password);

// $nodes = 'http://clarify.advantagesb.com/generateAutoCallFromEmailReceived21.php';

if ($inbox) {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {
            $message = imap_fetchbody($inbox, $email_number, 1);

            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $subject = $overview[0]->subject;

            if (strpos($subject, 'Device management platform alarm information') !== false) {


                $matches = [];

                // Use regex to extract the number of devices
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
                $table = $tables->item(0);

                if ($table) {
                    $snValues = [];
                    $deviceIDValues = [];
                    $descriptionValues = [];

                    
                    foreach ($table->getElementsByTagName('tr') as $row) {
                        $cells = $row->getElementsByTagName('td');

                        if ($cells->length >= 3) {
                            $sn = trim(html_entity_decode(strip_tags($cells->item(0)->nodeValue)));
                            $deviceID = trim(html_entity_decode(strip_tags($cells->item(1)->nodeValue)));
                            $description = trim(html_entity_decode(strip_tags($cells->item(2)->nodeValue)));

                            // echo "$sn\t$deviceID\t$description<br>";

                            $data = array(
                                'atmid' => $description,
                                'message' => $message,
                                'vpn' => $vpn
                            );

                            $options = array(
                                'http' => array(
                                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                                    'method' => 'POST',
                                    'content' => http_build_query($data)
                                )
                            );

                            $context = stream_context_create($options);
                            // $result = file_get_contents($nodes, false, $context);


                            $snValues[] = $sn;
                            $deviceIDValues[] = $deviceID;
                            $descriptionValues[] = $description;
                        }
                    }

                    // var_dump($snValues, $deviceIDValues, $descriptionValues);

                    // echo '<br/>';

                }
            }
        }
    }
}
imap_close($inbox);
