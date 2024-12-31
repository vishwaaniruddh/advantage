<?php 
header('Content-Type: application/json; charset=utf-8');

include('../config.php');

$lho = $_REQUEST['lho'];

$defaultCcLhoEmailsSql = mysqli_query($con, "SELECT DISTINCT(email) as email from lhoemails where emailType='cc' and lhoname='" . $lho . "' and status=1");
$html = '';
$emails = '';

while ($defaultCcLhoEmails_result = mysqli_fetch_assoc($defaultCcLhoEmailsSql)) {
    $email = $defaultCcLhoEmails_result['email'];
    $html .= "<span class='tag'><span>" . $email . '</span><a href="#" title="Removing tag" class="removeTag" onclick="removeTagCc(this)">x</a></span>';
    $emails .= $email . ',';
}

$emails = rtrim($emails, ',');
$data[] = ['html' => $html, 'emails' => $emails];

echo json_encode($data);
?>
