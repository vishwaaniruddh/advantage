<? include ('../config.php');

$feasibilityid = $_REQUEST['feasibilityid'];
$imagetype = $_REQUEST['imagetype'];

    $sql = "update feasibilityCheck set $imagetype='' where id='" . $feasibilityid . "'";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0 ;
    }

?>