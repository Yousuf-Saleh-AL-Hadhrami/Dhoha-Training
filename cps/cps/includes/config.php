<?php 

// Way One  uses mysqli_procedural way 

/*
   - mysqli procedural 
   - mysqli oop 
   - PDO => PHP DATA OBJECT [mysql , sqlserver , oracle ,postgress , etc ]
*/

$hostname = "localhost"; 
$username = "root";
$database = "computer_parts_stock";
$password = "";

$connection = mysqli_connect($hostname, $username , $password , $database);

if(!$connection)
{              
   echo "Faild to Connect to database";
}          


define('BASE_URL', '/mini-project/public/assets/');

