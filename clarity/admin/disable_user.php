<? include('../config.php');

$id = $_POST['userid'];

if($id>0){
    // echo "select user_status from user where userid ='".$id."'" ; 
    $user_sql = mysqli_query($con,"select user_status from user where userid ='".$id."'"); 
    $user_data = mysqli_fetch_assoc($user_sql);
    $current_user_status = $user_data['user_status'];
    if($current_user_status==0){
        $status = 1;
    }else{
       $status = 0; 
    }
    $sql = "update user set user_status='".$status."' where userid ='".$id."'";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}else{
    echo 0;
}

?>