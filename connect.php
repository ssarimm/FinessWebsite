<?php

$host="localhost";
$user="root";
$pass="";
$port="3307";
$db="fitnesswebsite";
$conn=new mysqli($host,$user,$pass,$db,$port);
if($conn->connect_error){
    echo "Failed to connect DB".$conn->connect_error;
}
?>