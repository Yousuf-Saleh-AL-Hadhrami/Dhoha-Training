<?php 

$hostname ="localhost";
$username = "root";
$dbname = "dhoha";
$password = "";

try {

$connection = mysqli_connect($hostname, $username, $password , $dbname);

} catch (Exception $e) {

 echo "Error In Connecting to Database ". $e->getMessage();
} 