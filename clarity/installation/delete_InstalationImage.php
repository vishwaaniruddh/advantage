<? include ('../config.php');

$installationID = $_REQUEST['installationID'];
$imagetype = $_REQUEST['imagetype'];

 
    $sql = "update installationdata set $imagetype='' where id='" . $installationID . "'";
    
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0 ;
    }

?>