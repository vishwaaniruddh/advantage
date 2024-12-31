<?php include('../../config.php') ; 
require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;


$full_name = $_REQUEST['full_name'];
$contact = $_REQUEST['contact'];

$uname = $_REQUEST['username'];
$password = $_REQUEST['password'];



if($uname && $password){
    // clarify_token

    
    if(mysqli_query($con,"insert into user(name,uname,pwd,contact,servicePermission) 
    values('".$full_name."','".$uname."','".$password."','".$contact."','5,6')
    ")){


        
    $insertid = $con->insert_id ; 
    $userid = 110000 + $insertid ; 
    mysqli_query($con,"update user set userid ='".$userid."' where id='".$insertid."'");


    $sql = mysqli_query($con, "SELECT * FROM user WHERE (uname = '$uname' OR contact = '$uname') AND pwd = '$password' AND user_status = 1");
    $result = mysqli_num_rows($sql);

        $sql_result = mysqli_fetch_assoc($sql);
        if($sql_result['user_status']==1){
                $_SESSION['ADVANTAGE_auth']=1;
                $_SESSION['ADVANTAGE_username']=$sql_result['name'];
                $_SESSION['ADVANTAGE_designation']=$sql_result['designation'];
                $_SESSION['ADVANTAGE_userid'] = $sql_result['userid'];
                $_SESSION['ADVANTAGE_level'] = $sql_result['level'];
                $_SESSION['ADVANTAGE_uname'] = $sql_result['uname'];
                
                $_SESSION['ADVANTAGE_branch'] = $sql_result['branch'];
                $_SESSION['ADVANTAGE_zone'] = $sql_result['zone'];
                $_SESSION['ADVANTAGE_cust_id'] = $sql_result['cust_id'];
                $_SESSION['vendor_id'] = $sql_result['vendorid'];

                $_SESSION['isVendor'] = $sql_result['isVendor'];
                $_SESSION['islho'] = $sql_result['islho'];
                
                $_SESSION['contact'] = $sql_result['contact'];

                
                $userid = $sql_result['id'];
                
                if($uname == 'admin@gmail.com'){
                    $_SESSION['ADVANTAGE_access']=1 ;
                }
                
                
                
                
                
                $secret_key = "Advantage";
        		$issuedat_claim = time(); // issued at
        		$notbefore_claim = $issuedat_claim + 10; //not before in seconds
        		$expire_claim = $issuedat_claim + 60; // expire time in seconds
        		
                $clarify_token = array(
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                        "id" => $userid,
                        "fullname" => $fname,
                        "email" => $email,
                ));
                $jwt = JWT::encode($clarify_token, $secret_key,"HS256");
                $clarify_token_sql = "update user set clarify_token='".$jwt."' , updated_at = '".$datetime."' where id='".$userid."'";
                    mysqli_query($con,$clarify_token_sql) ;                
                    
                
                $_SESSION['ADVANTAGE_advantagetoken'] = $jwt ;
                $response['success'] = true;
                $response['redirect'] = 'index.php'; // Change this to your actual redirect URL

                

    header('Content-Type: application/json');
    echo json_encode($response);               
               
               }else{
                    $response['success'] = false;
                    $response['message'] = 'Invalid username or password';
                    $response['redirect'] = 'login.php'; // Change this to your actual redirect URL
                    header('Content-Type: application/json');
                    echo json_encode($response);
               } 
             }else{ 
                 $response['success'] = false;
                    $response['message'] = 'Invalid username or password';
                    $response['redirect'] = 'register.php'; // Change this to your actual redirect URL
                    header('Content-Type: application/json');
                    echo json_encode($response);
                 
             }
}
else{ 
    $response['success'] = false;
                    $response['message'] = 'Please provide both username and password';
                    $response['redirect'] = 'register.php'; // Change this to your actual redirect URL
                    header('Content-Type: application/json');
                    echo json_encode($response);
    }
