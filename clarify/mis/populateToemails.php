<?php 
header('Content-Type: application/json; charset=utf-8');

include('../config.php');

$lho = $_REQUEST['lho'];

$defaultToLhoEmailsSql = mysqli_query($con, "SELECT DISTINCT(email) as email from lhoemails where emailType='to' and lhoname='" . $lho . "' and status=1");
$html = '';
$emails = '';

while ($defaultToLhoEmailsSql_result = mysqli_fetch_assoc($defaultToLhoEmailsSql)) {
    $email = $defaultToLhoEmailsSql_result['email'];
    if($email){
        $html .= "<span class='tag'><span>" . $email . '</span><a href="#" title="Removing tag" class="removeTag" onclick="removeTag(this)">x</a></span>';
        $emails .= $email . ',';

    }
}

$emails = rtrim($emails, ',');
$data[] = ['html' => $html, 'emails' => $emails];

echo json_encode($data);
?>
