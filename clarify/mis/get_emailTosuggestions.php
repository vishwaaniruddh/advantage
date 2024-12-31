<?php include('../config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = array();

$userEmail = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL); // Sanitize user input
if ($userEmail) {
    $searchQuery = mysqli_query($con, "SELECT email, name FROM emailcontact WHERE email LIKE '%" . $userEmail . "%' and status=1 group by email");
    while ($results = mysqli_fetch_assoc($searchQuery)) {
        $email = $results['email'];
        $name = $results['name'];
        $data[] = ['name' => $name, 'email' => $email];
    }
    echo json_encode($data);

} else {
    echo json_encode($data);

}



?>