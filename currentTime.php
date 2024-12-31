<?php
date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)

$host = "10.63.21.6";
$user = "advantage";
$pass = "qwerty121";
$dbname = "sarmicrosystems_advantage";

// Create a database connection
$con = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Query to retrieve server time and MySQL time
$query = "SELECT NOW() AS server_time, CURRENT_TIMESTAMP() AS mysql_time";
$result = $con->query($query);
$row = $result->fetch_assoc();
$serverTime = $row['server_time'];
$mysqlTime = $row['mysql_time'];

// Get system date and time
$systemTime = date('Y-m-d H:i:s');

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Server Time and MySQL Time</title>
</head>
<body>
    <h1>Server Time and MySQL Time</h1>
    <p>Server Time: <?php echo $serverTime; ?></p>
    <p>MySQL Time: <?php echo $mysqlTime; ?></p>
    <p>System Time: <?php echo $systemTime; ?></p>
</body>
</html>
