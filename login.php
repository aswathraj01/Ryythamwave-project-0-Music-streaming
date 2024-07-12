<?php
session_start;
//variable diff
$hostname="localhost";
$username="root";
$password="";

//connnection
$conn=mysqli_connect($hostname,$username,$password,"ryythwave");

//checking connection
if(!$conn){
    die("Connection failed:".mysqli_connect_error($conn));
}

echo "Connection succesfull";

//dif the variable with the post value
$username=$_POST['username'];
$password=$_POST['password'];


//query
$sql="select password from accounts where username=$username";

//resulting

$result=mysqli_query($conn,$sql)

//checking the account

if($result == $password){
    header("home.php");
    exit;
}else{
    echo "Error";
}

mysqli_close($conn);
?>