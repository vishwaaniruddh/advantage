<?php session_start();
date_default_timezone_set('Asia/Kolkata');
error_reporting(0);


$host = "10.63.21.6";
$user = "advantage";
$pass = "qwerty121";
$dbname = "adv";


function connectToDatabase()
{
    global $host, $user, $pass, $dbname;

    $con = new mysqli($host, $user, $pass, $dbname);

    if ($con->connect_error) {
        die; // You might want to handle the connection error appropriately
    } else {
        return $con;
    }
}

function getConnectedDatabase()
{
    global $con;

    if (!$con || !$con->ping()) {
        $con = connectToDatabase();
    }

    return $con;
}

// Usage example:
$con = getConnectedDatabase();


$datetime = $created_at = date('Y-m-d H:i:s');
$date = date('Y-m-d');

$userid = $_SESSION['ADVANTAGE_userid'];


?>