<?php 

/*
  to connect to database in php there are three ways 

  - mysqli => procedural 
  - mysqli => oop 
  - PDO  => PHP Data Object 

*/

$hostname ="localhost";
$username = "root";
$dbname = "dhoha";
$password = "";

try {

$connection = mysqli_connect($hostname, $username, $password , $dbname);

} catch (Exception $e) {

 echo "Error In Connecting to Database ". $e->getMessage();
} 