<? include('../config.php');


$id = $_REQUEST['id'];

$sql = "update lhoemails set status=0 where id = '".$id."'";

if(mysqli_query($con,$sql)){
    echo "<script>alert('Record Deleted successfully!'); window.location.href = 'lhomails.php';</script>";

}else{
    echo "<script>alert('An Error Occured !'); window.location.href = 'lhomails.php';</script>";

}

?>