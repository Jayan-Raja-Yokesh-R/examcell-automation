<?php 

//Configure username,password,host,database
$user="root";
$password="";
$host="localhost";
$db="examination";

$con=new mysqli($host,$user,$password,$db);

if($con->connect_error) {
	 die("Connection failed: " . $conn->connect_error);
}




?>