<?php session_start();
date_default_timezone_set('Asia/Kolkata');
error_reporting(0);

if ($_SERVER["HTTPS"] == "on") {
    // Get the current URL without the protocol
    $urlWithoutProtocol = preg_replace("/^https:/i", "http:", $_SERVER["REQUEST_URI"]);

    // Redirect to the same URL with HTTP instead of HTTPS
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $urlWithoutProtocol);
    exit;
}



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$base_url = "http://clarity.advantagesb.com/";

$host = "10.63.21.6";
$user = "advantage";
$pass = "qwerty121";
// $dbname = "adv";
$dbname = "sarmicrosystems_advantage";


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
$con  = $conn = getConnectedDatabase();
