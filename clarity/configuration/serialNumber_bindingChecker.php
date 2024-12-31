<? include('../config.php');



$serialNumber = $_REQUEST['serialNumber'];
$atm_sql = "select * from ipconfuration where serial_no='".$serialNumber."' and status=1";
$sql = mysqli_query($con,$atm_sql);

if($sql_result = mysqli_fetch_assoc($sql)){
    echo 1;

}else{
    echo 0; 
}
